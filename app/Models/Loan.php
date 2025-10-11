<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;


class Loan extends Model
{
    use HasFactory;

    protected $table = 'loans';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'group_center_id',
        'group_id',
        'client_id',
        'collection_officer_id',
        'loan_category_id',
        'loan_number',

        // client requests
        'amount_requested',
        'client_payable_frequency',
        'status',

        // approval and fees
        'amount_disbursed',
        'membership_fee',
        'insurance_fee',
        'officer_visit_fee',
        'other_fee',
        'penalty_fee',
        'preclosure_fee',
        'interest_rate',
        'interest_amount',
        'repayment_frequency',
        'max_term_days',
        'max_term_months',
        'total_days_due',
        'principal_due',
        'interest_due',
        'disbursement_date',

        // repayments
        'amount_paid',
        'preclosure_fee_paid',
        'penalty_fee_paid',
        'other_fee_paid',

        // tracking & balance
        'outstanding_balance',
        'start_date',
        'end_date',
        'days_left',

        // closure
        'closed_at',
        'closure_reason',

        // system fields
        'currency',
        'created_by',
        'approved_by',
        'updated_by',
        'is_active',
        'is_new_client',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'amount_requested' => 'decimal:2',
        'amount_disbursed' => 'decimal:2',
        'client_payable_frequency' => 'decimal:2',
        'membership_fee' => 'decimal:2',
        'insurance_fee' => 'decimal:2',
        'officer_visit_fee' => 'decimal:2',
        'other_fee' => 'decimal:2',
        'penalty_fee' => 'decimal:2',
        'preclosure_fee' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'interest_amount' => 'decimal:2',
        'principal_due' => 'decimal:2',
        'interest_due' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'penalty_fee_paid' => 'decimal:2',
        'preclosure_fee_paid' => 'decimal:2',
        'other_fee_paid' => 'decimal:2',
        'outstanding_balance' => 'decimal:2',
        'is_active' => 'boolean',
        'is_new_client' => 'boolean',
        'disbursement_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
        'closed_at' => 'datetime',
        'created_at' => 'date',
    ];

    /**
     * Define relationships.
     */

    // 🔗 Client who took the loan
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    // 🔗 Loan category (product)
    public function loanCategory()
    {
        return $this->belongsTo(LoanCategory::class);
    }

    // 🔗 Group or center relationships
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function groupCenter()
    {
        return $this->belongsTo(GroupCenter::class);
    }
    public function collectionOfficer()
    {
        return $this->belongsTo(Employee::class, 'collection_officer_id');
    }

    // 🔗 Creator / approver users
    public function creator()
    {
        return $this->belongsTo(Employee::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(Employee::class, 'approved_by');
    }

    public function updater()
    {
        return $this->belongsTo(Employee::class, 'updated_by');
    }
    public function repaymentSchedules()
    {
        return $this->hasMany(RepaymentSchedule::class);
    }

    /**
     * 🔢 Accessors & Calculations
     */

    // Automatically get total fees (even though it's virtual in DB)
    public function getTotalFeeAttribute()
    {
        return ($this->membership_fee ?? 0)
             + ($this->insurance_fee ?? 0)
             + ($this->other_fee ?? 0)
             + ($this->penalty_fee ?? 0)
             + ($this->preclosure_fee ?? 0);
    }

    public function getTotalDueAttribute()
    {
        return ($this->principal_due ?? 0) + ($this->interest_due ?? 0);
    }

    public function getRepayableAmountAttribute()
    {
        return ($this->amount_disbursed ?? 0)
             + ($this->interest_amount ?? 0);
    }

    public function getTotalAmountPaidAttribute()
    {
        return ($this->penalty_fee_paid ?? 0)
             + ($this->preclosure_fee_paid ?? 0)
             + ($this->amount_paid ?? 0)
             + ($this->other_fee_paid ?? 0);
    }

    public function getProfitLossAmountAttribute()
    {
        return $this->total_amount_paid
             + ($this->membership_fee ?? 0)
             + ($this->insurance_fee ?? 0)
             - $this->amount_disbursed;
    }

    public function getOutstandingBalanceAttribute()
{
    return max(0, $this->repayable_amount - $this->amount_paid);
}

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    // You would typically add a hasMany relationship to 'Repayment' or 'Transaction' here.
}