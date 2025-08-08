<?php

namespace App\DataTables;

use App\Models\Attendance;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class AttendanceDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return (new EloquentDataTable($query))
            ->addColumn('employee_name', function ($attendance) {
                return $attendance->employee?->employee_name ?? '-';
            })
            ->filterColumn('employee_name', function ($query, $keyword) {
                $query->whereHas('employee', function ($q) use ($keyword) {
                    $q->where('employee_name', 'like', "%{$keyword}%");
                });
            })
            ->addColumn('action', function ($attendance) {
                return '<a href="' . route('attendances.edit', $attendance->id) . '" class="btn btn-sm btn-primary">Edit</a>';
            })
            ->rawColumns(['action']);
    }



    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Attendance $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Attendance $model)
    {
        return $model->newQuery()->with('employee');
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
            ->addAction(['width' => '120px', 'printable' => false])
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
            'employee_name' => ['title' => 'Employee Name', 'searchable' => true],
            'device_id' => ['title' => 'Device ID'],
            'date' => ['title' => 'Date'],
            'check_in' => ['title' => 'Check-in Time'],
            'check_out' => ['title' => 'Check-out Time'],
            'total_hours' => ['title' => 'Total Hours'],
            'overtime_minutes' => ['title' => 'Overtime (min)'],
        ];
    }


    /**
     * Get filename for export.
     *
     * @return string
     */
    public function filename(): string
    {
        return 'attendances_datatable_' . time();
    }
}
