<?php

namespace App\Models;

use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Setting extends Model
{
    use BelongsToCompany;
    
    protected $fillable = [
        'user_id',
        'company_name',
        'website',
        'industry',
        'employees',
        'address',
        'billing_email',
        'notification_preferences',
        'notification_channels',
        'digest_frequency',
        'quiet_hours_start',
        'quiet_hours_end',
        'custom_fields',
    ];

    protected $casts = [
        'notification_preferences' => 'array',
        'notification_channels' => 'array',
        'custom_fields' => 'array',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}