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
        // Randomly decide if the attendance is present or absent
        $isPresent = $this->faker->boolean(80); // 80% chance present, 20% absent

        if ($isPresent) {
            // Random check-in between 08:00 and 10:00
            $checkInHour = $this->faker->numberBetween(8, 10);
            $checkInMinute = $this->faker->numberBetween(0, 59);
            $checkIn = sprintf('%02d:%02d:00', $checkInHour, $checkInMinute);

            // Check-out 8 to 10 hours later
            $checkOutHour = $checkInHour + $this->faker->numberBetween(8, 10);
            $checkOutMinute = $this->faker->numberBetween(0, 59);
            $checkOut = sprintf('%02d:%02d:00', $checkOutHour, $checkOutMinute);

            // Calculate total hours as decimal
            $in = Carbon::createFromTimeString($checkIn);
            $out = Carbon::createFromTimeString($checkOut);
            $totalHours = $in->diffInMinutes($out) / 60;

            return [
                'employee_id' => Employee::inRandomOrder()->value('id') ?? 1,
                'device_id' => 'DEVICE-001',
                'date' => now()->toDateString(),
                'check_in' => $checkIn,
                'check_out' => $checkOut,
                'total_hours' => round($totalHours, 2),
                'overtime_minutes' => 0,
                'attendance_status' => 'present', // new status column
            ];
        } else {
            // Absent - all times null
            return [
                'employee_id' => Employee::inRandomOrder()->value('id') ?? 1,
                'device_id' => 'DEVICE-001',
                'date' => now()->toDateString(),
                'check_in' => null,
                'check_out' => null,
                'total_hours' => null,
                'overtime_minutes' => 0,
                'attendance_status' => 'absent', // new status column
            ];
        }
    }
}
