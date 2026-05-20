<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountSixthMemberBranch extends Model
{
    protected $table = 'account_sixth_member_branches';

    protected $fillable = [
        'FifthRoot_id',
        'SixthAccountCode',
        'SixthAccountName',
        'Category',
        'User_id',
        'Status',
        'AuditingStatus',
        'ReportStatus'
    ];

    public function fifthBranch()
    {
        return $this->belongsTo(AccountFifthGroupBranch::class, 'FifthRoot_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'User_id');
    }
}