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
        'occupation',

    ];

    // Relationship
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

}
