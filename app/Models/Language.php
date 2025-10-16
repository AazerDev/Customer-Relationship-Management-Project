<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Language extends Model
{
    protected $fillable = [
        'code',
        'name',
        'native_name',
        'flag',
        'is_rtl',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_rtl' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    // Relationships
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'language', 'code');
    }

    public function settings(): HasMany
    {
        return $this->hasMany(Setting::class, 'language', 'code');
    }

    // Helper methods
    public function getDisplayNameAttribute()
    {
        return $this->native_name ?: $this->name;
    }

    public function getFlagIconAttribute()
    {
        return $this->flag ?: '🌐';
    }
}