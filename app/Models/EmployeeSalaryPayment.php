<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeSalaryPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_salary_id',
        'employee_id',
        'payment_date',
        'amount_paid',
        'currency',
        'payment_method',
        'reference_number',
        'attachment',
        'notes',
        'created_by',
        'updated_by',
        'status',
    ];

    /**
     * Relationships
     */
    public function employeeSalary()
    {
        return $this->belongsTo(EmployeeSalary::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
