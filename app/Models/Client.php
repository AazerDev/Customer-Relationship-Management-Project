<?php

namespace App\Models;

use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Client extends Model
{
    use HasFactory, BelongsToCompany;

    protected $table = "clients";
    protected $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'tags',
        'company_name',
        'profile_pic',
    ];

    protected $casts = [
        'tags' => 'array',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function leads()
    {
        return $this->hasMany(Lead::class, 'client_id');
    }
    
}
