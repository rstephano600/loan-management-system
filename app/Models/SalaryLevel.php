<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'default_salary',
        'currency',
        'status',
        'created_by',
        'updated_by',
    ];

    // Relations

    public function employeeSalaries()
    {
        return $this->hasMany(EmployeeSalary::class);
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

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
