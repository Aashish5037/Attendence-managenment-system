<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;
    protected $table = 'employes';

    protected $fillable = [
        'employee_biometric_id',
        'employee_name',
        'employee_email',
        'employee_position',
        'employee_Hourly_pay',
        'employee_overtime_pay',
    ];

    // An employee has many attendances
    public function attendances()
    {
        return $this->hasMany(\App\Models\Attendance::class, 'employee_id');
    }
}
