<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\DataTables\PayrollDataTable;
use Illuminate\Support\Carbon;

class PayrollController extends Controller
{
    public function index(PayrollDataTable $dataTable)
    {
        $payrolls = Payroll::with('employee')->get();

        foreach ($payrolls as $payroll) {
            $this->calculatePayments($payroll);
            $payroll->save();
        }

        // Get today's date
        $today = Carbon::today()->toDateString();

        // Sum of net_pay where period_date = today
        $payrollToday = Payroll::whereDate('period_date', $today)->sum('net_pay');

        // Pass value to the view
        return $dataTable->render('payrolls.index', [
            'payrollToday' => $payrollToday,
        ]);
    }

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
