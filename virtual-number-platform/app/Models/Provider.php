<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'api_key',
        'api_secret',
        'status',
        'configuration',
    ];

    protected $casts = [
        'configuration' => 'array',
        'status' => 'boolean',
    ];

    public function phoneNumbers()
    {
        return $this->hasMany(PhoneNumber::class);
    }
}
