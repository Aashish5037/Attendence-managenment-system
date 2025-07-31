<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\Employee;
use App\Models\Payroll;
use App\Mail\DailyAttendanceMail;
use Carbon\Carbon;

class SendDailyAttendanceEmails extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'app:send-daily-attendance-emails';

    /**
     * The console command description.
     */
    protected $description = 'Send daily attendance emails to all employees';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = Carbon::now()->subDay()->toDateString(); // yesterday's date
        Log::info("Starting attendance email job for date: $date");

      //  ------------------ ORIGINAL CODE ------------------

        $employees = Employee::with(['attendances' => function ($query) use ($date) {
            $query->where('date', $date);
        }])->get();

        if ($employees->isEmpty()) {
            Log::info("No employees found for attendance email.");
            return;
        }

        foreach ($employees as $employee) {
            $attendance = $employee->attendances->first();

            if ($attendance && !empty($employee->employee_email)) {
                try {
                    $payroll = Payroll::where('employee_id', $employee->id)
                        ->where('period_date', $date)
                        ->first();

                    Mail::to($employee->employee_email)
                        ->send(new DailyAttendanceMail($employee, $attendance, $payroll));

                    Log::info("Mail sent to: {$employee->employee_email}");
                } catch (\Exception $e) {
                    Log::error("Failed to send mail to {$employee->employee_email}: " . $e->getMessage());
                }
            } else {
                Log::info("Skipped employee ID {$employee->id} - Email: " . ($employee->employee_email ?? 'none') . " - Attendance: " . ($attendance ? 'present' : 'absent'));
            }
        }


        // ------------------ TEMPORARY TEST CODE ------------------

        // Send email to only one employee, but deliver to your test Gmail
    //     $employees = Employee::with(['attendances' => function ($query) use ($date) {
    //         $query->where('date', $date);
    //     }])->take(1)->get();

    //     if ($employees->isEmpty()) {
    //         Log::info("No employees found for test attendance email.");
    //         return;
    //     }

    //     foreach ($employees as $employee) {
    //         $attendance = $employee->attendances->first();

    //         if ($attendance && !empty($employee->employee_email)) {
    //             try {
    //                 $payroll = Payroll::where('employee_id', $employee->id)
    //                     ->where('period_date', $date)
    //                     ->first();

    //                 Mail::to('my mail@gmail.com')
    //                     ->send(new DailyAttendanceMail($employee, $attendance, $payroll));

    //                 Log::info("Test mail sent to my mail@gmail.com for employee: {$employee->employee_email}");
    //             } catch (\Exception $e) {
    //                 Log::error("Failed to send test mail: " . $e->getMessage());
    //             }
    //         } else {
    //             Log::info("Skipped employee ID {$employee->id} (no email or attendance)");
    //         }
    //     }

    //     $this->info("Test daily attendance email sent.");
    //     Log::info("Finished test attendance email job for date: $date");
     }
}
