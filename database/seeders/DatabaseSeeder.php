<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Payroll;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a fixed user
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
            ]
        );

      // In DatabaseSeeder.php (run method)

Employee::factory(10)->create();

$employees = Employee::all();
$startDate = Carbon::now()->subMonth()->startOfDay();
$endDate = Carbon::now()->startOfDay();

foreach ($employees as $employee) {
    $date = $startDate->copy();

    while ($date <= $endDate) {
        if (!in_array($date->dayOfWeek, [Carbon::SATURDAY, Carbon::SUNDAY])) {
            if (rand(1, 100) <= 80) { // 80% present
                $attendance = Attendance::factory()->create([
                    'employee_id' => $employee->id,
                    'date' => $date->toDateString(),
                ]);

                // Calculate overtime minutes if total_hours > 8
                $attendance->overtime_minutes = max(0, (int)(($attendance->total_hours - 8) * 60));
                $attendance->save();

                // Calculate payroll for this attendance
                $hourlyRate = $employee->employee_Hourly_pay ?? 0;
                $totalHours = $attendance->total_hours ?? 0;
                $overtimeMinutes = $attendance->overtime_minutes ?? 0;

                $regularHours = min(8, $totalHours);
                $regularPay = $hourlyRate * $regularHours;
                $overtimePay = ($overtimeMinutes / 60) * $hourlyRate * 1.5;

                Payroll::create([
                    'employee_id' => $employee->id,
                    'period_date' => $date->toDateString(),
                    'overtime_pay' => $overtimePay,
                    'net_pay' => $regularPay + $overtimePay,
                ]);
            }
        }
        $date->addDay();
    }
}

    }
}
