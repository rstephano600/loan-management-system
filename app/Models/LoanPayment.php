<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_id',
        'client_id',
        'repayment_schedule_id',
        'group_center_id',
        'payment_date',
        'amount',
        'principal_component',
        'interest_component',
        'fees_component',
        'penalty_component',
        'payment_method',
        'reference_number',
        'receipt_number',
        'remarks',
        'status',
        'created_by',
        'updated_by',
    ];

    // Relationships
    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function repaymentSchedule()
    {
        return $this->belongsTo(RepaymentSchedule::class);
    }

    public function groupCentre()
    {
        return $this->belongsTo(GroupCenter::class);
    }
}
