<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\DataTables\PayrollDataTable;
use Illuminate\Support\Carbon;

class PayrollController extends Controller
{
    public function index(PayrollDataTable $dataTable)
    {
        // Just calculate today's net pay total — no recalculation for all rows
        $today = Carbon::today()->toDateString();

        $payrollToday = Payroll::whereDate('period_date', $today)->sum('net_pay');

        return $dataTable->render('payrolls.index', [
            'payrollToday' => $payrollToday,
        ]);
    }

    // Still available to call manually when needed (e.g., cron job, command, or observer)
    protected function calculatePayments(Payroll $payroll): void
    {
        $employee = $payroll->employee;
        if (!$employee) return;

        $attendance = $employee->attendances()
            ->whereDate('date', $payroll->period_date)
            ->first();

        if (!$attendance || $attendance->attendance_status === 'absent') {
            $payroll->overtime_pay = 0;
            $payroll->net_pay = 0;
            return;
        }

        $hourlyRate = $employee->employee_Hourly_pay ?? 0;
        $totalHours = $attendance->total_hours ?? 0;
        $overtimeMinutes = $attendance->overtime_minutes ?? 0;

        $regularHours = min(8, $totalHours);
        $regularPay = $hourlyRate * $regularHours;
        $overtimePay = ($overtimeMinutes / 60) * $hourlyRate * 1.5;

        $payroll->overtime_pay = $overtimePay;
        $payroll->net_pay = $regularPay + $overtimePay;
    }
}
