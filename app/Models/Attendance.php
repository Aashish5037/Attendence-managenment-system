<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'device_id',
        'date',
        'check_in',
        'check_out',
        'total_hours',
        'overtime_minutes',
        'attendance_status',
    ];

    // Automatically calculate hours and overtime on save
    protected static function booted()
    {
        // ...existing code...
    }

    // Relationship
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
