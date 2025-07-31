<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payroll extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_id',
        'period_date',
        'overtime_pay',
        'net_pay',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

   
}
