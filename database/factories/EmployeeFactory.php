<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Employee;

class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition(): array
    {
        $Hourly = $this->faker->randomFloat(2, 300, 1200);


        //   $gmailPrefix = 'emailexample'; 

        return [
            'employee_biometric_id' => $this->faker->unique()->numerify('EMP####'),
            'employee_name' => $this->faker->name(),
            'employee_email' =>  $this->faker->unique()->userName() . '@gmail.com',
            'employee_position' => $this->faker->jobTitle(),
            'employee_Hourly_pay' => $Hourly,
            'employee_overtime_pay' => $Hourly * 1.5,
        ];
    }
}
