<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;

class AttendanceFactory extends Factory
{
    protected $model = Attendance::class;

    public function definition(): array
    {
        // Random check-in between 08:00 and 10:00
        $checkInHour = $this->faker->numberBetween(8, 10);
        $checkIn = sprintf('%02d:%02d:00', $checkInHour, $this->faker->numberBetween(0, 59));

        // Check-out 8 to 10 hours later
        $checkOutHour = $checkInHour + $this->faker->numberBetween(8, 10);
        $checkOut = sprintf('%02d:%02d:00', $checkOutHour, $this->faker->numberBetween(0, 59));

        // Calculate total hours as decimal
        $in = Carbon::createFromTimeString($checkIn);
        $out = Carbon::createFromTimeString($checkOut);
        $totalHours = $in->diffInMinutes($out) / 60;

        return [
            'employee_id' => Employee::inRandomOrder()->value('id') ?? 1,
            'device_id' => 'DEVICE-001',
            'date' => now()->toDateString(), // override in seeder for specific date range
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'total_hours' => round($totalHours, 2),
            'overtime_minutes' => 0, // will update in seeder if needed
        ];
    }
}
