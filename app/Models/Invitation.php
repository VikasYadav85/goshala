<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invitation extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_SENT = 'sent';
    public const STATUS_FAILED = 'failed';

    protected $fillable = [
        'invitee_name', 'invitee_email', 'invitee_phone',
        'occasion', 'event_date', 'event_time', 'venue', 'message',
        'status', 'sent_at', 'created_by',
    ];

    protected $casts = [
        'event_date' => 'date',
        'sent_at' => 'datetime',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
