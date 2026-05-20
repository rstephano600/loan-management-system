<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NextOfKin extends Model
{
    use HasFactory;

    protected $table = 'nist_of_kin'; // since table name isn’t pluralized by Laravel

    protected $fillable = [
        'employee_id',
        'first_name',
        'last_name',
        'gender',
        'email',
        'phone',
        'address',
        'other_informations',
        'relationship',
    ];

    // Relationship
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }


}
