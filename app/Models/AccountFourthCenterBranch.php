<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountFourthCenterBranch extends Model
{
    protected $table = 'account_fourth_center_branches';

    protected $fillable = [
        'ThirdRoot_id',
        'SecondRoot_id',
        'FourthAccountCode',
        'FourthAccountName',
        'Category',
        'User_id',
        'Status',
        'AuditingStatus',
        'ReportStatus'
    ];

    public function thirdBranch()
    {
        return $this->belongsTo(AccountThirdBranch::class, 'ThirdRoot_id');
    }

    public function fifthBranches()
    {
        return $this->hasMany(AccountFifthGroupBranch::class, 'FourthRoot_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'User_id');
    }
}
