<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Loan extends Model
{
    use HasFactory;

    protected $table = 'loans';

    /**
     * The attributes that are mass assignable.
     * Note: calculated fields like total_outstanding are usually guarded or calculated in the controller/model events.
     */
    protected $fillable = [
        'client_id',
        'loan_category_id',
        'group_center_id',
        'loan_number',
        'disbursement_date',
        'status',
        'first_payment_date',
        'last_payment_date',
        'next_payment_date',
        'delinquency_status',
        'total_interest',
        'total_payable',
        'outstanding_principal',
        'outstanding_interest',
        'outstanding_fees',
        'total_outstanding',
        'total_paid',
        'interest_paid',
        'fees_paid',
        'closed_at',
        'closure_reason',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'disbursement_date' => 'date',
        'first_payment_date' => 'date',
        'last_payment_date' => 'date',
        'next_payment_date' => 'date',
        'closed_at' => 'datetime',
        'total_interest' => 'decimal:2',
        'total_payable' => 'decimal:2',
        'outstanding_principal' => 'decimal:2',
        'outstanding_interest' => 'decimal:2',
        'outstanding_fees' => 'decimal:2',
        'total_outstanding' => 'decimal:2',
        'total_paid' => 'decimal:2',
        'interest_paid' => 'decimal:2',
        'fees_paid' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Get the client (borrower) associated with the loan.
     */
    public function client(): BelongsTo
    {
        // Assuming your client/user model is named 'User' or 'Client'
        // Since the migration uses client_id, we'll assume a Client model exists.
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * Get the category definition for the loan.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(LoanCategory::class, 'loan_category_id');
    }

    public function groupCenter(): BelongsTo // <-- NEW RELATIONSHIP
    {
        return $this->belongsTo(GroupCenter::class, 'group_center_id');
    }


    /**
     * Get the user who created the loan.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the loan.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function repaymentSchedules(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RepaymentSchedule::class);
    }

    /**
     * A loan has many payments recorded against it.
     * Using FQN for the return type to avoid TypeError.
     */
    public function payments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Payment::class);
    }
    // You would typically add a hasMany relationship to 'Repayment' or 'Transaction' here.
}