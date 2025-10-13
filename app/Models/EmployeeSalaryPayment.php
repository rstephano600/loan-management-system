<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeSalaryPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_salary_id',
        'amount_paid',
        'payment_date',
        'payment_method',
        'currency',
        'status',
        'attachment',
        'created_by',
        'updated_by',
    ];

    // Relations

    public function salary()
    {
        return $this->belongsTo(EmployeeSalary::class, 'employee_salary_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scopes

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
