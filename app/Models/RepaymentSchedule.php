<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RepaymentSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_id',
        'group_center_id',
        'installment_number',
        'due_date',
        'principal_due',
        'interest_due',
        'fees_due',
        'total_due',
        'principal_outstanding',
        'amount_paid',
        'principal_paid',
        'interest_paid',
        'fees_paid',
        'total_paid',
        'days_late',
        'status',
        'paid_date',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_date' => 'date',
        'principal_due' => 'decimal:2',
        'interest_due' => 'decimal:2',
        'total_due' => 'decimal:2',
        'principal_outstanding' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        // ... all other decimal fields
    ];

    /**
     * Get the loan this installment belongs to.
     */
    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

    /**
     * Get the group center context for this installment.
     */
    public function groupCenter(): BelongsTo
    {
        return $this->belongsTo(GroupCenter::class);
    }

    /**
     * Get the payments that were applied to this schedule item (Many-to-Many).
     */
    public function payments(): BelongsToMany
    {
        return $this->belongsToMany(Payment::class, 'payment_schedule_allocations', 'repayment_schedule_id', 'payment_id')
                    ->withPivot(['amount_applied', 'principal_applied', 'interest_applied', 'fees_applied', 'penalty_applied']);
    }
}