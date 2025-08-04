<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index(\App\DataTables\EmployeeDataTable $dataTable)
    {
        return $dataTable->render('employees.index');
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_name' => 'required|string|max:255',
            'employee_email' => 'required|email|unique:employees,employee_email',
            'employee_position' => 'required|string|max:255',
            'employee_Hourly_pay' => 'required|numeric',
            'employee_overtime_pay' => 'required|numeric',
            'employee_biometric_id' => 'required|string|unique:employees,employee_biometric_id',
        ]);

        Employee::create($validated);

        return redirect()->route('employees.index')
            ->with('success', 'Employee created successfully.');
    }

    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'employee_position' => 'required|string|max:255',
            'employee_Hourly_pay' => 'required|numeric',
            'employee_overtime_pay' => 'required|numeric',
        ]);

        $employee->update($validated);

        return redirect()->route('employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    public function destroy(Request $request, Employee $employee)
    {
        if (!Auth::check() || !Hash::check($request->password, Auth::user()->password)) {
            return back()->with('error', 'Password incorrect.');
        }

        $employee->delete();

        return redirect()->route('employees.index')
            ->with('success', 'Employee deleted successfully.');
    }

    public function attendance(Employee $employee)
    {
        $attendances = $employee->attendances()->get();

        return view('employees.attendance', compact('employee', 'attendances'));
    }
}
