<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, BelongsToCompany;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'role_id',
        'name',
        'email',
        'password',
        'reset_token',
        'type',
        'is_active',
        'user_type',
        'reference_num',
        'language',
        'timezone',
        'bio',
        'phone'
    ];

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($model) {
            if (app()->runningInConsole()) {
                return true; // allow seeding
            }

            if ($model->type === 'default') {
                throw new \Exception("Default users cannot be updated.");
            }

            if (!$model->isSuperAdmin() && empty($model->company_id)) {
                throw new \Exception("Users must always have a company_id.");
            }
        });

        static::creating(function ($model) {
            if (app()->runningInConsole()) {
                return true; // allow seeding
            }

            if (!$model->isSuperAdmin() && empty($model->company_id)) {
                throw new \Exception("Users must belong to a company.");
            }
        });

        static::deleting(function ($model) {
            if (app()->runningInConsole()) {
                return true; // allow seeding
            }

            if ($model->type === 'default') {
                throw new \Exception("Default users cannot be deleted.");
            }
        });
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'created_at' => 'datetime:Y-m-d H:i:s',
            'updated_at' => 'datetime:Y-m-d H:i:s',
        ];
    }


    public function assignedLeads(): HasMany
    {
        return $this->hasMany(Lead::class, 'assigned_to');
    }

    public function settings(): HasOne
    {
        return $this->hasOne(Setting::class);
    }

    // Helper method to check super admin
    public function isSuperAdmin(): bool
    {
        return $this->user_type === 'super_admin';
    }
}
