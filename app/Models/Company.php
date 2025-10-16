<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'role_id',
        'logo',
        'company_name',
        'subdomain',
        'package_start_at',
        'package_end_at',
        'ai_models',
        'feature_modules',
        'status',
        'onboarded_at',
        'activated_at',
        'admin_email',
    ];

    protected $casts = [
        'ai_models' => 'array',
        'feature_modules' => 'array',
        'onboarded_at' => 'datetime:Y-m-d H:i:s',
        'activated_at' => 'datetime:Y-m-d H:i:s',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    // Helper methods
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function hasFeatureModule(string $module): bool
    {
        return in_array($module, $this->feature_modules ?? []);
    }

    public function hasAiModel(string $model): bool
    {
        return in_array($model, $this->ai_models ?? []);
    }

    // Company → RoleGroup (by role_id)
    public function roleGroup()
    {
        return $this->hasOne(RoleGroup::class, 'role_id', 'role_id')
            ->with('permissionGroup'); // eager load permission group
    }

    // Company → User (by role_id)
    public function user()
    {
        return $this->hasOne(User::class, 'role_id', 'role_id');
    }

}
