<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Payroll;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Repositories\DashboardRepository;

class DashboardController extends Controller
{
    /** @var  DashboardRepository */
    private $dashboardRepository;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct(DashboardRepository $dashboardRepo)
    // {
    //     $this->dashboardRepository = $dashboardRepo;
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $today = \Carbon\Carbon::today();

        $totalEmployees = \App\Models\Employee::count();

        $presentCount = \App\Models\Attendance::whereDate('check_in', $today)
            ->distinct('employee_id')
            ->count('employee_id');

        $absentCount = $totalEmployees - $presentCount;

        $payrollToday = \App\Models\Payroll::whereDate('created_at', $today)->sum('net_pay');

        return view('dashboard.index', compact(
            'totalEmployees',
            'presentCount',
            'absentCount',
            'payrollToday'
        ));
    }
}
