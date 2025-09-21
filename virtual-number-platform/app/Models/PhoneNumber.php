<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class PhoneNumber extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUS_AVAILABLE = 'available';
    public const STATUS_ASSIGNED = 'assigned';
    public const STATUS_RESERVED = 'reserved';

    protected $fillable = [
        'provider_id',
        'number',
        'country',
        'capabilities',
        'status',
        'rented_by',
        'rented_at',
        'expires_at',
        'cost',
        'external_id',
    ];

    protected $casts = [
        'capabilities' => 'array',
        'rented_at' => 'datetime',
        'expires_at' => 'datetime',
        'cost' => 'decimal:2',
    ];

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'rented_by');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', self::STATUS_AVAILABLE);
    }

    public function isExpired(): bool
    {
        return $this->expires_at && Carbon::now()->greaterThan($this->expires_at);
    }
}
