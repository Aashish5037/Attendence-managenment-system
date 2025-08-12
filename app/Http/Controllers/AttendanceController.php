<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
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

            $this->calculateWorkingHours($attendance);
        }

        $attendance->save();

        return redirect()->route('attendances.index')->with('success', 'Attendance updated successfully.');
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
}
