<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_code',
        'group_name',
        'group_type',
        'location',
        'description',
        'credit_officer_id',
        'registration_date',
        'is_active',
        'created_by',
        'updated_by',
    ];

    // Relationships
    public function creditOfficer()
    {
        return $this->belongsTo(Employee::class, 'credit_officer_id');
    }
    public function members()
{
    return $this->hasMany(GroupMember::class);
}

}
