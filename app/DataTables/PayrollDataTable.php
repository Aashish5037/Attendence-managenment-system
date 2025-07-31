<?php

namespace App\DataTables;

use App\Models\Payroll;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class PayrollDataTable extends DataTable
{
    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);

        return $dataTable->editColumn('employee_name', function ($payroll) {
            return $payroll->employee?->employee_name ?? '-';
        })
        ->editColumn('employee_Hourly_pay', function ($payroll) {
            return $payroll->employee?->employee_Hourly_pay 
                ? 'Rs ' . number_format($payroll->employee->employee_Hourly_pay, 2)
                : '-';
        })
        ->editColumn('overtime_pay', function ($payroll) {
            return $payroll->overtime_pay !== null 
                ? 'Rs ' . number_format($payroll->overtime_pay, 2)
                : '-';
        })
        ->editColumn('net_pay', function ($payroll) {
            return $payroll->net_pay !== null 
                ? 'Rs ' . number_format($payroll->net_pay, 2)
                : '-';
        });
    }

    public function query(Payroll $model)
    {
        return $model->newQuery()->with('employee');
    }

    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->parameters([
                'dom' => 'Bfrtip',
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
                'order' => [[0, 'desc']],
                'language' => ['searchPlaceholder' => 'Search payrolls...'],
            ]);
    }

    protected function getColumns()
    {
        return [
            'id' => ['title' => '#', 'searchable' => false],
            [
                'name' => 'employee_name',
                'title' => 'Employee Name',
                'data' => 'employee_name',
                'searchable' => true,
                'orderable' => true,
            ],
            [
                'name' => 'period_date',
                'title' => 'Period Date',
                'data' => 'period_date',
                'searchable' => true,
                'orderable' => true,
            ],
            [
                'name' => 'employee_Hourly_pay',
                'title' => 'Hourly Salary',
                'data' => 'employee_Hourly_pay',
                'searchable' => false,
                'orderable' => false,
            ],
            [
                'name' => 'overtime_pay',
                'title' => 'Overtime Pay',
                'data' => 'overtime_pay',
                'searchable' => false,
                'orderable' => false,
            ],
            [
                'name' => 'net_pay',
                'title' => "Today's Pay",
                'data' => 'net_pay',
                'searchable' => false,
                'orderable' => false,
            ],
        ];
    }

    public function filename(): string
    {
        return 'payrolls_datatable_' . time();
    }
}
