<x-mail::message>
# Daily Attendance Records

Hello {{ $employee->employee_name }},<br>

Here is your attendance record for {{ \Carbon\Carbon::parse($attendance->date)->format('Y-m-d') }}:

Check In: {{ $attendance->check_in ?? '-' }}  
Check Out: {{ $attendance->check_out ?? '-' }}  
Total Hours: {{ $attendance->total_hours ?? '-' }}  
Overtime (minutes): {{ $attendance->overtime_minutes ?? '-' }}
<!-- 

@if($payroll)
---
**Net Pay for the Day:** **{{ $payroll->overtime_pay ? 'Rs ' . number_format($payroll->overtime_pay, 2) : '-' }}**  
**Overtime Pay:** **{{ $payroll->net_pay ? 'Rs ' . number_format($payroll->net_pay, 2) : '-' }}**
@endif -->

If you have any questions, please contact HR.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
