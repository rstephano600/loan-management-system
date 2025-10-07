<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Client extends Model
{
    use HasFactory;

    protected $table = 'clients';

    protected $fillable = [
        'client_type',
        'business_name',
        'business_registration_number',
        'tax_identification_number',
        'industry_sector',
        'years_in_business',
        'number_of_employees',
        'first_name',
        'last_name',
        'middle_name',
        'email',
        'phone',
        'alternative_phone',
        'address_line1',
        'address_line2',
        'city',
        'state_province',
        'postal_code',
        'country',
        'credit_score',
        'credit_rating',
        'risk_category',
        'status',
        'blacklist_reason',
        'assigned_loan_officer_id',
        'kyc_completed',
        'kyc_completed_at'
    ];

    // Relationships
    public function loanOfficer()
    {
        return $this->belongsTo(User::class, 'assigned_loan_officer_id');
    }

    public function documents()
    {
        return $this->hasMany(ClientDocument::class, 'client_id');
    }

    public function financialInfo()
    {
        return $this->hasMany(ClientFinancialInfo::class, 'client_id');
    }
}
