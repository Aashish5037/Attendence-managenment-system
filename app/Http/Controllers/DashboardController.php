<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Payroll;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $today = Carbon::today();
            
            // Get total employees with error handling
            $totalEmployees = Employee::count();
            
            // Get today's attendance with proper error handling
            $presentCount = 0;
            if ($totalEmployees > 0) {
                $presentCount = Attendance::whereDate('check_in', $today)
                    ->distinct('employee_id')
                    ->count('employee_id');
            }
            
            $absentCount = $totalEmployees - $presentCount;
            
            // Get today's payroll with null coalescing
            $payrollToday = Payroll::whereDate('created_at', $today)
                ->sum('net_pay') ?? 0;
            
            // Initialize arrays for 7 days data
            $dates = [];
            $presentCounts = [];
            $absentCounts = [];
            
            // Generate past 7 days attendance data
            for ($i = 6; $i >= 0; $i--) {
                $date = $today->copy()->subDays($i);
                $dateString = $date->format('M j'); // Format: "Jan 15"
                
                $dailyPresent = 0;
                if ($totalEmployees > 0) {
                    $dailyPresent = Attendance::whereDate('check_in', $date)
                        ->distinct('employee_id')
                        ->count('employee_id');
                }
                
                $dailyAbsent = $totalEmployees - $dailyPresent;
                
                $dates[] = $dateString;
                $presentCounts[] = $dailyPresent;
                $absentCounts[] = $dailyAbsent;
            }
            
            // Ensure all variables are properly set
            $data = [
                'totalEmployees' => $totalEmployees,
                'presentCount' => $presentCount,
                'absentCount' => $absentCount,
                'payrollToday' => $payrollToday,
                'dates' => $dates,
                'presentCounts' => $presentCounts,
                'absentCounts' => $absentCounts,
                'hasData' => $totalEmployees > 0
            ];
            
            return view('dashboard.index', $data);
            
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Dashboard Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            // Return safe default values
            $data = [
                'totalEmployees' => 0,
                'presentCount' => 0,
                'absentCount' => 0,
                'payrollToday' => 0,
                'dates' => [],
                'presentCounts' => [],
                'absentCounts' => [],
                'hasData' => false,
                'error' => 'Unable to load dashboard data. Please try again.'
            ];
            
            return view('dashboard.index', $data);
        }
    }
    
    /**
     * Get attendance statistics for a specific date range
     */
    public function getAttendanceStats($startDate = null, $endDate = null)
    {
        try {
            $startDate = $startDate ? Carbon::parse($startDate) : Carbon::today()->subDays(7);
            $endDate = $endDate ? Carbon::parse($endDate) : Carbon::today();
            
            $stats = [];
            $currentDate = $startDate->copy();
            
            while ($currentDate <= $endDate) {
                $present = Attendance::whereDate('check_in', $currentDate)
                    ->distinct('employee_id')
                    ->count('employee_id');
                
                $total = Employee::count();
                $absent = $total - $present;
                
                $stats[] = [
                    'date' => $currentDate->format('Y-m-d'),
                    'present' => $present,
                    'absent' => $absent,
                    'total' => $total
                ];
                
                $currentDate->addDay();
            }
            
            return response()->json(['stats' => $stats]);
            
        } catch (\Exception $e) {
            Log::error('Attendance Stats Error: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to fetch attendance statistics'], 500);
        }
    }
}