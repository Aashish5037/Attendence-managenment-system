<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Payroll;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\DataTables\AttendanceDataTable;

class AttendanceController extends Controller
{
    // Show the attendance list with DataTable
    public function index(AttendanceDataTable $dataTable)
    {
        return $dataTable->render('attendances.index');
    }

    // Show edit form for a single attendance record
    public function edit(Attendance $attendance)
    {
        $attendance->load('employee');

        return view('attendances.edit', compact('attendance'));
    }

    // Update attendance record after edit
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
            $attendance->total_hours = null;
            $attendance->overtime_minutes = 0;
        } else {
            $date = Carbon::parse($attendance->date)->format('Y-m-d');

            $checkInDateTime = Carbon::createFromFormat('Y-m-d H:i', $date . ' ' . $request->check_in);
            $checkOutDateTime = Carbon::createFromFormat('Y-m-d H:i', $date . ' ' . $request->check_out);

            $attendance->check_in = $checkInDateTime->format('H:i:s');
            $attendance->check_out = $checkOutDateTime->format('H:i:s');

            // Recalculate total hours and overtime after editing
            $this->calculateWorkingHours($attendance);
        }

        $attendance->save();

        // Recalculate payroll for the edited attendance
        $this->updatePayrollFromAttendance($attendance);

        return redirect()->route('attendances.index')->with('success', 'Attendance updated and payroll recalculated.');
    }

    // Calculate total hours and overtime for attendance
    protected function calculateWorkingHours(Attendance $attendance): void
    {
        $checkIn = $this->parseTime($attendance->check_in);
        $checkOut = $this->parseTime($attendance->check_out);

        $totalMinutes = 0;

        if ($checkIn && $checkOut && $checkOut->greaterThanOrEqualTo($checkIn)) {
            $totalMinutes = $checkOut->diffInMinutes($checkIn);
        }

        $totalHours = max(0, round($totalMinutes / 60, 2));
        $attendance->total_hours = $totalHours;

        // Simple overtime: above 8 hours
        if ($totalHours > 8) {
            $attendance->overtime_minutes = (int)(($totalHours - 8) * 60);
        } else {
            $attendance->overtime_minutes = 0;
        }
    }

    // Helper to parse time string to Carbon instance
    protected function parseTime(?string $time): ?Carbon
    {
        return $time ? Carbon::createFromFormat('H:i:s', $time) : null;
    }

    // Update payroll record based on attendance
    protected function updatePayrollFromAttendance(Attendance $attendance): void
    {
        if (!$attendance->employee) {
            $attendance->load('employee');
        }

        $employee = $attendance->employee;
        $date = Carbon::parse($attendance->date)->format('Y-m-d');

        if (!$employee) {
            return; // No employee found, skip payroll update
        }

        // Find payroll for the same employee and date
        $payroll = Payroll::firstOrNew([
            'employee_id' => $employee->id,
            'period_date' => $date,
        ]);

        $payroll->total_hours = $attendance->total_hours ?? 0;
        $payroll->overtime_minutes = $attendance->overtime_minutes ?? 0;

        $hourlyRate = $employee->salary ? ($employee->salary / 160) : 0; // 160 hours/month assumed
        $overtimeRate = $hourlyRate * 1.5;

        $payroll->regular_pay = ($payroll->total_hours > 8 ? 8 : $payroll->total_hours) * $hourlyRate;
        $payroll->overtime_pay = ($payroll->overtime_minutes / 60) * $overtimeRate;
        $payroll->net_pay = $payroll->regular_pay + $payroll->overtime_pay;

        $payroll->save();
    }
}
