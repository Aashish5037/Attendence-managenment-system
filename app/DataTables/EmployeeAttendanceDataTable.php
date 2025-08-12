<?php

namespace App\DataTables;

use App\Models\Attendance;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class EmployeeAttendanceDataTable extends DataTable
{
       protected $employeeId;

    public function __construct($employeeId)
    {
        $this->employeeId = $employeeId;
    }

    public function dataTable($query)
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', 'attendances.datatables_actions');
    }

    public function query()
    {
        return Attendance::where('employee_id', $this->employeeId)->newQuery();
    }


    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()  // uses ajax call automatically
            ->parameters([
                'dom' => 'Bfrtip',
                'buttons' => [
                    'excel', 'csv', 'pdf', 'print', 'reload'
                ],
                'order' => [[0, 'desc']],
            ]);
    }

    protected function getColumns()
    {
        return [
            'id' => ['searchable' => false],
            'date' => ['title' => 'Date'],
            'check_in' => ['title' => 'Check-in', 'searchable' => false],
            'check_out' => ['title' => 'Check-out', 'searchable' => false],
            'total_hours' => ['title' => 'Total Hours', 'searchable' => false],
            'overtime_minutes' => ['title' => 'Overtime (min)', 'searchable' => false],
        ];
    }

    public function filename(): string
    {
        return 'employee_attendance_' . $this->employeeId . '_' . time();
    }
}
