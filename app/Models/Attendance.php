<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;



use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id', // foreign key to employees table
        'device_id',   // nullable string, now seeded
        'date',
        'check_in',
        'check_out',
        'total_hours',
        'overtime_minutes',
    ];

    // Automatically calculate overtime_minutes on save
    protected static function booted()
    {
        static::saving(function ($attendance) {
            if (isset($attendance->total_hours) && $attendance->total_hours > 8) {
                $attendance->overtime_minutes = (int)(($attendance->total_hours - 8) * 60);
            } else {
                $attendance->overtime_minutes = 0;
            }
        });
    }


    // Relationship to Employee
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
