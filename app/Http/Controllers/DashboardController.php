<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Payroll;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $today = Carbon::today();
            $totalEmployees = Employee::count();

            // Present employees today (where date = today and attendance_status = present)
            $presentCount = Attendance::whereDate('date', $today)
                ->where('attendance_status', 'present')
                ->distinct('employee_id')
                ->count('employee_id');

            // Employees marked absent today
            $absentFromAttendance = Attendance::whereDate('date', $today)
                ->where('attendance_status', 'absent')
                ->distinct('employee_id')
                ->count('employee_id');

            // Employees with any attendance record today (present or absent)
            $employeesWithAttendance = Attendance::whereDate('date', $today)
                ->distinct('employee_id')
                ->count('employee_id');

            // Employees not marked today
            $employeesNotMarked = max(0, $totalEmployees - $employeesWithAttendance);

            // Total absent = marked absent + not marked
            $absentCount = $absentFromAttendance + $employeesNotMarked;

            // Payroll total for today
           $employeeIdsToday = Attendance::whereDate('date', $today)
    ->distinct('employee_id')
    ->pluck('employee_id');

$payrollToday = Payroll::whereIn('employee_id', $employeeIdsToday)->sum('net_pay') ?? 0;


            // Weekly data arrays
            $dates = [];
            $presentCounts = [];
            $absentCounts = [];

            for ($i = 6; $i >= 0; $i--) {
                $date = $today->copy()->subDays($i);
                $dateLabel = $date->format('M j');

                $dailyPresent = Attendance::whereDate('date', $date)
                    ->where('attendance_status', 'present')
                    ->distinct('employee_id')
                    ->count('employee_id');

                $dailyAbsentMarked = Attendance::whereDate('date', $date)
                    ->where('attendance_status', 'absent')
                    ->distinct('employee_id')
                    ->count('employee_id');

                $dailyEmployeesWithAttendance = Attendance::whereDate('date', $date)
                    ->distinct('employee_id')
                    ->count('employee_id');

                $dailyNotMarked = max(0, $totalEmployees - $dailyEmployeesWithAttendance);
                $dailyAbsent = $dailyAbsentMarked + $dailyNotMarked;

                $dates[] = $dateLabel;
                $presentCounts[] = $dailyPresent;
                $absentCounts[] = $dailyAbsent;
            }

            return view('dashboard.index', [
                'totalEmployees' => $totalEmployees,
                'presentCount' => $presentCount,
                'absentCount' => $absentCount,
                'payrollToday' => round($payrollToday, 2),
                'dates' => $dates,
                'presentCounts' => $presentCounts,
                'absentCounts' => $absentCounts,
                'hasData' => $totalEmployees > 0,
                'attendanceRate' => $totalEmployees > 0 ? round(($presentCount / $totalEmployees) * 100, 1) : 0,
                'absenceRate' => $totalEmployees > 0 ? round(($absentCount / $totalEmployees) * 100, 1) : 0,
                
            ]);
        } catch (\Exception $e) {
            Log::error('Dashboard error: ' . $e->getMessage());

            return view('dashboard.index', [
                'totalEmployees' => 0,
                'presentCount' => 0,
                'absentCount' => 0,
                'payrollToday' => 0,
                'dates' => ['No Data'],
                'presentCounts' => [0],
                'absentCounts' => [0],
                'hasData' => false,
                'attendanceRate' => 0,
                'absenceRate' => 0,
                'error' => 'Unable to load dashboard data.',
            ]);
        }
    }
}
