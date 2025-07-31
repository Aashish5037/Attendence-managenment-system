<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\DataTables\PayrollDataTable;

class PayrollController extends Controller
{
    public function index(PayrollDataTable $dataTable)
    {
        // Calculate payroll amounts before showing table
        $payrolls = Payroll::with('employee')->get();
        foreach ($payrolls as $payroll) {
            $this->calculatePayments($payroll);
            $payroll->save();
        }

        return $dataTable->render('payrolls.index');
    }

    protected function calculatePayments(Payroll $payroll): void
    {
        $employee = $payroll->employee;
        if (!$employee) return;

        $attendance = $employee->attendances()
            ->whereDate('date', $payroll->period_date)
            ->first();

        $hourlyRate = $employee->employee_Hourly_pay ?? 0;
        $totalHours = $attendance?->total_hours ?? 0;
        $overtimeMinutes = $attendance?->overtime_minutes ?? 0;

        // Regular Pay
        $regularHours = min(8, $totalHours);
        $regularPay = $hourlyRate * $regularHours;

        // Overtime Pay (1.5x hourly rate)
        $overtimePay = ($overtimeMinutes / 60) * $hourlyRate * 1.5;

        $payroll->overtime_pay = $overtimePay;
        $payroll->net_pay = $regularPay + $overtimePay;
    }
}
