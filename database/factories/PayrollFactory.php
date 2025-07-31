<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Payroll;
use App\Models\Employee;
use App\Models\Attendance;

class PayrollFactory extends Factory
{
    protected $model = Payroll::class;

    public function definition(): array
    {
        $employee = Employee::inRandomOrder()->first();
        $attendance = Attendance::where('employee_id', $employee?->id)->inRandomOrder()->first();

        return [
            'employee_id' => $employee?->id ?? Employee::factory(),
            // 'attendance_id' => $attendance?->id ?? null, // removed, not in table
            'period_date' => $attendance?->date ?? $this->faker->date(),
            'overtime_pay' => null,
            'net_pay' => null,
        ];
    }
    
}
