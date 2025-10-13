<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeSalary extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'salary_level_id',
        'base_salary',
        'bonus',
        'currency',
        'effective_from',
        'effective_to',
        'status',
        'created_by',
        'updated_by',
    ];

    // Relations

    public function employee()
    {
        return $this->belongsTo(Employee::class, );
    }

    public function level()
    {
        return $this->belongsTo(SalaryLevel::class, 'salary_level_id');
    }
    public function salarylevel()
    {
        return $this->belongsTo(SalaryLevel::class, 'salary_level_id');
    }

    public function payments()
    {
        return $this->hasMany(EmployeeSalaryPayment::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Helper Methods

    public function totalPaid()
    {
        return $this->payments()->sum('amount_paid');
    }

    public function outstanding()
    {
        return ($this->base_salary + $this->bonus) - $this->totalPaid();
    }

    // Scopes

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function lastPayment()
{
    return $this->hasOne(EmployeeSalaryPayment::class)
        ->latest('payment_date');
}

}
