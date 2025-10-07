<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Referee extends Model
{
    use HasFactory;

    protected $table = 'referee';

    protected $fillable = [
        'employee_id',
        'first_name',
        'last_name',
        'gender',
        'email',
        'phone',
        'address',
        'other_informations',
    ];

    // Relationship
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getFullNameAttribute()
    {
        return trim("{$this->first_name} {$this->last_name}");
    }
}
