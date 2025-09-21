<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    public const STATUS_UNREAD = 'unread';
    public const STATUS_READ = 'read';

    protected $fillable = [
        'phone_number_id',
        'from_number',
        'body',
        'received_at',
        'status',
        'payload',
    ];

    protected $casts = [
        'received_at' => 'datetime',
        'payload' => 'array',
    ];

    public function phoneNumber()
    {
        return $this->belongsTo(PhoneNumber::class);
    }
}
