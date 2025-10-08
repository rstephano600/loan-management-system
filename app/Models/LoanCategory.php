<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanCategory extends Model
{
    use HasFactory;

    protected $table = 'loan_categories';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'interest_rate',
        'max_term_months',
        'max_term_days',
        'principal_amount',
        'currency',
        'min_amount',
        'max_amount',
        'repayment_frequency',
        'installment_amount',
        'total_repayable_amount',
        'conditions',
        'is_active',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'interest_rate' => 'decimal:2',
        'principal_amount' => 'decimal:2',
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'installment_amount' => 'decimal:2',
        'total_repayable_amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Relationships
     */

    // If a LoanCategory is created by a user
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // If a LoanCategory is updated by a user
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // If LoanCategory has many loans
    public function loans()
    {
        return $this->hasMany(Loan::class, 'loan_category_id');
    }
}

