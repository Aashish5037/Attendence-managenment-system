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
        // 80% present, 20% absent
        $isPresent = $this->faker->boolean(80);

        if ($isPresent) {
            // Random check-in time between 08:00 and 10:00
            $checkIn = Carbon::today()
                ->setHour($this->faker->numberBetween(8, 10))
                ->setMinute($this->faker->numberBetween(0, 59))
                ->setSecond(0);

            // Check-out 8 to 10 hours later
            $checkOut = (clone $checkIn)->addHours($this->faker->numberBetween(8, 10))
                                        ->addMinutes($this->faker->numberBetween(0, 59));

            // Calculate total hours (decimal)
            $totalHours = $checkIn->diffInMinutes($checkOut) / 60;

            return [
                'employee_id'       => Employee::inRandomOrder()->value('id') ?? 1,
                'device_id'         => 'DEVICE-001',
                'date'              => $checkIn->toDateString(),
                'check_in'          => $checkIn->format('H:i:s'),
                'check_out'         => $checkOut->format('H:i:s'),
                'total_hours'       => round($totalHours, 2),
                'overtime_minutes'  => max(0, (int)(($totalHours - 8) * 60)), // Only if >8 hrs
                'attendance_status' => 'present',
            ];
        }

        // Absent
        return [
            'employee_id'       => Employee::inRandomOrder()->value('id') ?? 1,
            'device_id'         => 'DEVICE-001',
            'date'              => now()->toDateString(),
            'check_in'          => null,
            'check_out'         => null,
            'total_hours'       => 0,
            'overtime_minutes'  => 0,
            'attendance_status' => 'absent',
        ];
    }
}
