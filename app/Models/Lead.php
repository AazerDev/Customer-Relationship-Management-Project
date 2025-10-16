<?php

namespace App\Models;

use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lead extends Model
{
    use SoftDeletes, BelongsToCompany;

    protected $table = 'leads';

    protected $fillable = [
        'client_id',
        'name',
        'source',
        'status',
        'notes',
        'tags',
        'file',
        'assigned_to',
        'last_contacted'
    ];

    protected $casts = [
        'tags' => 'array',
        'last_contacted'  => 'datetime:Y-m-d H:i:s',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }


    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
    
}
