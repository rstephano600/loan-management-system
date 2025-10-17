<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * ===========================================
     * ROLE DEFINITIONS
     * ===========================================
     */
    public const ROLE_ADMIN           = 'admin';
    public const ROLE_DIRECTOR        = 'director';
    public const ROLE_CEO             = 'ceo';
    public const ROLE_SHAREHOLDERS    = 'shareholders';
    public const ROLE_MANAGER         = 'manager';
    public const ROLE_MARKETING_OFFICER = 'marketingofficer';
    public const ROLE_HR              = 'hr';
    public const ROLE_ACCOUNTANT      = 'accountant';
    public const ROLE_SECRETARY       = 'secretary';
    public const ROLE_LOAN_OFFICER    = 'loanofficer';
    public const ROLE_CLIENT          = 'client';
    public const ROLE_USER            = 'user';

    /**
     * Return all available roles.
     */
    public static function getRoles(): array
    {
        return [
            self::ROLE_ADMIN,
            self::ROLE_DIRECTOR,
            self::ROLE_CEO,
            self::ROLE_SHAREHOLDERS,
            self::ROLE_MANAGER,
            self::ROLE_MARKETING_OFFICER,
            self::ROLE_HR,
            self::ROLE_ACCOUNTANT,
            self::ROLE_SECRETARY,
            self::ROLE_LOAN_OFFICER,
            self::ROLE_CLIENT,
            self::ROLE_USER,
        ];
    }

    /**
     * ===========================================
     * MASS ASSIGNABLE FIELDS
     * ===========================================
     */
    protected $fillable = [
        'username',
        'email',
        'phone',
        'password',
        'role',
        'status',
        'created_by',
        'updated_by',
        'is_loged',
        'failed_login_attempts',
        'locked_until',
        'email_verified_at',
        'phone_verified_at',
    ];

    /**
     * Hidden fields
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attribute casting
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'locked_until' => 'datetime',
        'is_loged' => 'boolean',
        'password' => 'hashed',
    ];

    /**
     * Default values
     */
    protected $attributes = [
        'status' => 'active',
        'role' => self::ROLE_USER,
    ];

    /**
     * ===========================================
     * RELATIONSHIPS
     * ===========================================
     */
    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    public function client()
    {
        return $this->hasOne(Client::class);
    }

    /**
     * ===========================================
     * SCOPES
     * ===========================================
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeRole($query, $role)
    {
        return $query->whereIn('role', (array) $role);
    }

     protected static function booted()
    {
        static::creating(function ($user) {
            $user->generateName();
        });

        static::updating(function ($user) {
            $user->generateName();
        });

    }

    public function generateName()
    {
        // Check if user is linked to an employee
        if ($this->employee) {
            $this->name = trim("{$this->employee->last_name}, {$this->employee->first_name} {$this->employee->middle_name}");
        }

        // Or if linked to a client
        elseif ($this->client) {
            $this->name = trim("{$this->client->last_name}, {$this->client->first_name} {$this->client->middle_name}");
        }
    }

    /**
     * ===========================================
     * ROLE CHECK HELPERS
     * ===========================================
     */
    public function hasRole($role): bool
    {
        return in_array($this->role, (array) $role);
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, [
            self::ROLE_ADMIN,
            self::ROLE_DIRECTOR,
            self::ROLE_CEO,
        ]);
    }

    public function isManagement(): bool
    {
        return in_array($this->role, [
            self::ROLE_MANAGER,
            self::ROLE_DIRECTOR,
            self::ROLE_CEO,
        ]);
    }

    public function isHR(): bool
    {
        return $this->role === self::ROLE_HR;
    }

    public function isFinance(): bool
    {
        return in_array($this->role, [
            self::ROLE_ACCOUNTANT,
            self::ROLE_SHAREHOLDERS,
        ]);
    }

    public function isLoanOfficer(): bool
    {
        return $this->role === self::ROLE_LOAN_OFFICER;
    }

    public function isClient(): bool
    {
        return $this->role === self::ROLE_CLIENT;
    }

    public function isEmployee(): bool
    {
        return in_array($this->role, [
            self::ROLE_HR,
            self::ROLE_MANAGER,
            self::ROLE_ACCOUNTANT,
            self::ROLE_MARKETING_OFFICER,
            self::ROLE_SECRETARY,
            self::ROLE_LOAN_OFFICER,
        ]);
    }

    /**
     * ===========================================
     * ADDITIONAL LOGIC
     * ===========================================
     */
    public function lockAccount(int $minutes = 15)
    {
        $this->update([
            'locked_until' => now()->addMinutes($minutes),
            'status' => 'suspended',
        ]);
    }

    public function unlockAccount()
    {
        $this->update([
            'locked_until' => null,
            'failed_login_attempts' => 0,
            'status' => 'active',
        ]);
    }

    public function shareholder()
    {
        return $this->hasOne(Shareholder::class);
    }

}
