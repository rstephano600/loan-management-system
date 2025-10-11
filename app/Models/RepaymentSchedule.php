<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepaymentSchedule extends Model
{
    use HasFactory;

    protected $table = 'repayment_schedules';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'loan_id',
        'installment_number',
        'due_day_number',
        'due_date',
        'principal_due',
        'interest_due',
        'penalty_due',
        'principal_paid',
        'interest_paid',
        'penalty_paid',
        'paid_date',
        'status',
        'payment_method',
        'created_by',
        'is_paid',
        'paid_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'due_date' => 'date',
        'principal_due' => 'decimal:2',
        'interest_due' => 'decimal:2',
        'penalty_due' => 'decimal:2',
        'principal_paid' => 'decimal:2',
        'interest_paid' => 'decimal:2',
        'penalty_paid' => 'decimal:2',
        'total_due' => 'decimal:2',
        'total_paid' => 'decimal:2',
    ];

    /**
     * Relationship: A repayment schedule belongs to a loan.
     */
    public function loan()
    {
        return $this->belongsTo(Loan::class, 'loan_id');
    }
        public function creator()
    {
        return $this->belongsTo(Employee::class, 'created_by');
    }

    public function payer()
    {
        return $this->belongsTo(Employee::class, 'paid_by');
    }

    public function updater()
    {
        return $this->belongsTo(Employee::class, 'updated_by');
    }

    /**
     * Accessor: Automatically compute remaining balance.
     */
    public function getBalanceAttribute()
    {
        return ($this->total_due ?? 0) - ($this->total_paid ?? 0);
    }

    /**
     * Scope: Filter by status.
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: Filter overdue schedules.
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue');
    }
}
