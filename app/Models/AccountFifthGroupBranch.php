<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class AccountFifthGroupBranch extends Model
{
    protected $table = 'account_fifth_group_branches';

    protected $fillable = [
        'FourthRoot_id',
        'FifthAccountCode',
        'FifthAccountName',
        'Category',
        'User_id',
        'Status',
        'AuditingStatus',
        'ReportStatus'
    ];

    public function fourthBranch()
    {
        return $this->belongsTo(AccountFourthCenterBranch::class, 'FourthRoot_id');
    }

    public function sixthBranches()
    {
        return $this->hasMany(AccountSixthMemberBranch::class, 'FifthRoot_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'User_id');
    }
}
