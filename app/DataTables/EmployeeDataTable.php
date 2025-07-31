<?php

namespace App\DataTables;

use App\Models\Employee;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;


class EmployeeDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
  public function dataTable($query)
{
    return datatables()
        ->eloquent($query)
        ->addColumn('action', function ($employee) {
            return view('employees.actions', compact('employee'))->render();
        })
        ->rawColumns(['action']);
}



    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Employee $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Employee $model)
    {
        // Select all columns you want
        return $model->newQuery()->select(
            'id',
            'employee_biometric_id',
            'employee_name',
            'employee_email',
            'employee_position',
            'employee_Hourly_pay',
            'employee_overtime_pay'
        );
    }

    /**
     * Optional method if you want to use HTML builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addAction(['width' => '120px', 'printable' => false, 'title' => 'Actions'])
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
                        'action' => 'function ( e, dt, node, config ) {
                            dt.ajax.reload();
                        }'
                    ],
                ],
                'order' => [[0, 'desc']],
                'language' => [
                    'searchPlaceholder' => 'Search employees...'
                ]
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'id' => ['title' => '#', 'searchable' => false],
            'employee_biometric_id' => ['title' => 'Biometric ID'],
            'employee_name' => ['title' => 'Name'],
            'employee_email' => ['title' => 'Email'],
            'employee_position' => ['title' => 'Position'],
            Column::make('employee_Hourly_pay')->title('Hourly Rate'),
            Column::make('employee_overtime_pay')->title('Overtime Rate'),



        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    public function filename(): string
    {
        return 'employees_datatable_' . time();
    }
}
