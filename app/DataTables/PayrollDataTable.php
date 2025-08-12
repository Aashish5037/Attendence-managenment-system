<?php

namespace App\DataTables;

use App\Models\Payroll;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class PayrollDataTable extends DataTable
{
    public function dataTable($query)
    {
        return (new EloquentDataTable($query))
            ->addColumn('employee_name', fn($payroll) => $payroll->employee->employee_name ?? '-')
            ->filterColumn('employee_name', function ($query, $keyword) {
                $query->whereHas('employee', fn($q) => $q->where('employee_name', 'like', "%{$keyword}%"));
            })
            ->editColumn('employee_Hourly_pay', function ($payroll) {
                $employee = $payroll->employee;
                if ($employee?->employee_Hourly_pay !== null) {
                    return 'Rs ' . number_format($employee->employee_Hourly_pay, 2);
                } elseif ($employee?->employee_monthly_salary !== null) {
                    return 'Rs ' . number_format($employee->employee_monthly_salary / 200, 2);
                }
                return '-';
            })
            ->editColumn('overtime_pay', fn($payroll) => $payroll->overtime_pay !== null ? 'Rs ' . number_format($payroll->overtime_pay, 2) : '-')
            ->editColumn('net_pay', fn($payroll) => $payroll->net_pay !== null ? 'Rs ' . number_format($payroll->net_pay, 2) : '-')
            ->filterColumn('period_date', fn($query, $keyword) => $query->where('period_date', 'like', "%{$keyword}%"));
    }

    public function query(Payroll $model)
    {
        return $model->newQuery()->with('employee');
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('payrolls-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->parameters([
                'dom' => 'Bfrtip',
                'order' => [[0, 'desc']],
                'language' => [
                    'searchPlaceholder' => 'Search payrolls...',
                    'search' => '',
                ],
                'buttons' => [
                    ['extend' => 'excel', 'className' => 'btn btn-success btn-sm', 'text' => 'Export Excel'],
                    ['extend' => 'csv', 'className' => 'btn btn-primary btn-sm', 'text' => 'Export CSV'],
                    ['extend' => 'pdf', 'className' => 'btn btn-danger btn-sm', 'text' => 'Export PDF'],
                    ['extend' => 'print', 'className' => 'btn btn-info btn-sm', 'text' => 'Print'],
                    [
                        'text' => 'Reload',
                        'className' => 'btn btn-warning btn-sm',
                        'action' => 'function ( e, dt, node, config ) { dt.ajax.reload(); }'
                    ],
                ],
            ]);
    }

    protected function getColumns()
    {
        return [
            [
                'data' => 'id',
                'name' => 'id',
                'title' => '#',
                'searchable' => false,
                'orderable' => true,
            ],
            [
                'data' => 'employee_name',
                'name' => 'employee_name',
                'title' => 'Employee Name',
                'searchable' => true,
                'orderable' => false,
            ],
            [
                'data' => 'period_date',
                'name' => 'period_date',
                'title' => 'Period Date',
                'searchable' => true,
                'orderable' => true,
            ],
            [
                'data' => 'employee_Hourly_pay',
                'name' => 'employee_Hourly_pay',
                'title' => 'Hourly Salary',
                'searchable' => false,
                'orderable' => false,
            ],
            [
                'data' => 'overtime_pay',
                'name' => 'overtime_pay',
                'title' => 'Overtime Pay',
                'searchable' => false,
                'orderable' => false,
            ],
            [
                'data' => 'net_pay',
                'name' => 'net_pay',
                'title' => "Net Pay",
                'searchable' => false,
                'orderable' => false,
            ],
        ];
    }

    protected  function filename(): string
    {
        return 'payrolls_' . date('Ymd_His');
    }
}
