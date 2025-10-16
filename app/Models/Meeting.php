<?php

namespace App\Models;

use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Meeting extends Model
{
    use HasFactory, BelongsToCompany;
    protected $fillable = [
        'lead_id',
        'title',
        'date',
        'time',
        'participants',
        'status',
        'notes',
        'attachment',
    ];

    protected $casts = [
        'participants' => 'array',
        'date' => 'datetime:Y-m-d',
        'time' => 'datetime:Y-m-d H:i:s',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function customers()
    {
        return $this->belongsToMany(Client::class, 'meeting_customer', 'meeting_id', 'client_id');
    }
}
