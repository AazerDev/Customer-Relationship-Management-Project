<?php

namespace App\Models;

use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleGroup extends Model
{
    use HasFactory, BelongsToCompany;
    protected $fillable = ['creator_id','role_name','role_id','assign_group_id','type'];

     protected static function boot()
    {
        parent::boot();

        static::updating(function ($model) {
            if ($model->type === 'default') {
                throw new \Exception("Default role groups cannot be updated.");
            }
        });

        static::deleting(function ($model) {
            if ($model->type === 'default') {
                throw new \Exception("Default role groups cannot be deleted.");
            }
        });

        // Global scope: hide "package" and "default" when user_type = admin
        static::addGlobalScope('excludePackageAndDefault', function ($builder) {
            if (auth()->check() && auth()->user()->user_type == 'admin') {
                $builder->where('type', null);
            }
        });
    }
    
    function permissionGroup(){
        return $this->hasOne(PermissionGroup::class,'id','assign_group_id');
    }
}
