<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Payroll;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\DataTables\AttendanceDataTable;

class AttendanceController extends Controller
{
    // List attendances
    public function index(AttendanceDataTable $dataTable)
    {
        return $dataTable->render('attendances.index');
    }

    // Edit attendance
    public function edit(Attendance $attendance)
    {
        $attendance->load('employee');
        return view('attendances.edit', compact('attendance'));
    }

    // Update attendance
    public function update(Request $request, Attendance $attendance)
    {
        $request->validate([
            'status' => 'required|in:present,absent',
            'check_in' => 'required_if:status,present|date_format:H:i',
            'check_out' => 'required_if:status,present|date_format:H:i|after:check_in',
        ]);

        $attendance->attendance_status = $request->status;

        if ($request->status === 'absent') {
            $attendance->check_in = null;
            $attendance->check_out = null;
            $attendance->total_hours = 0;
            $attendance->overtime_minutes = 0;
        } else {
            $date = Carbon::parse($attendance->date)->format('Y-m-d');

            $checkIn = Carbon::createFromFormat('Y-m-d H:i', $date . ' ' . $request->check_in);
            $checkOut = Carbon::createFromFormat('Y-m-d H:i', $date . ' ' . $request->check_out);

            if ($checkOut->lessThanOrEqualTo($checkIn)) {
                $checkOut->addDay(); // handle overnight
            }

            $minutes = $checkOut->diffInMinutes($checkIn);
            $attendance->check_in = $checkIn->format('H:i');
            $attendance->check_out = $checkOut->format('H:i');
            $attendance->total_hours = round($minutes / 60, 2);
            $attendance->overtime_minutes = $attendance->total_hours > 8 ? (int)(($attendance->total_hours - 8) * 60) : 0;
        }

        $attendance->save();

        // Update payroll
        $this->updatePayrollFromAttendance($attendance);

        return redirect()->route('attendances.index')->with('success', 'Attendance updated and payroll recalculated.');
    }

    // Payroll recalculation
    protected function updatePayrollFromAttendance(Attendance $attendance): void
    {
        $employee = $attendance->employee;
        if (!$employee) return;

        $date = Carbon::parse($attendance->date)->format('Y-m-d');

        $payroll = Payroll::firstOrNew([
            'employee_id' => $employee->id,
            'period_date' => $date,
        ]);

        $payroll->total_hours = $attendance->total_hours ?? 0;
        $payroll->overtime_minutes = $attendance->overtime_minutes ?? 0;

        $hourlyRate = $employee->salary ? ($employee->salary / 160) : 0; // 160 hrs/month
        $overtimeRate = $hourlyRate * 1.5;

        $payroll->regular_pay = ($payroll->total_hours > 8 ? 8 : $payroll->total_hours) * $hourlyRate;
        $payroll->overtime_pay = ($payroll->overtime_minutes / 60) * $overtimeRate;
        $payroll->net_pay = $payroll->regular_pay + $payroll->overtime_pay;

        $payroll->save();
    }
}
