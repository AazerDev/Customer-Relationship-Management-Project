<?php

namespace App\Traits;

use App\Models\Scopes\CompanyScope as ScopesCompanyScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

trait BelongsToCompany
{
    protected static function bootBelongsToCompany()
    {
        static::creating(function ($model) {
            // auto-fill company_id on create if not set
            if (Auth::check() && !Auth::user()->isSuperAdmin()) {
                $model->company_id = Auth::user()->company_id;
            }
        });

        static::addGlobalScope('company', function (Builder $builder) {
            if (Auth::check() && !Auth::user()->isSuperAdmin()) {
                $builder->where($builder->getModel()->getTable() . '.company_id', Auth::user()->company_id);
            }
        });
    }
}
