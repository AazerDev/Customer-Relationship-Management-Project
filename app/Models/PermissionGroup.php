<?php

namespace App\Models;

use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionGroup extends Model
{
    use HasFactory, BelongsToCompany;
    protected $fillable = ['creator_id','name','permissions','type', 'price','duration'];

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($model) {
            if ($model->type === 'default') {
                throw new \Exception("Default permission groups cannot be updated.");
            }
        });

        static::deleting(function ($model) {
            if ($model->type === 'default') {
                throw new \Exception("Default permission groups cannot be deleted.");
            }
        });

        static::addGlobalScope('excludePackageAndDefault', function ($builder) {
            if (auth()->check() && auth()->user()->user_type == 'admin') {
                $builder->where('type', null);
            }
        });

    }
    
}
