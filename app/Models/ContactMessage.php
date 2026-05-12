<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    public const STATUS_NEW = 'new';
    public const STATUS_READ = 'read';
    public const STATUS_REPLIED = 'replied';
    public const STATUS_SPAM = 'spam';
    public const STATUS_CLOSED = 'closed';

    protected $fillable = [
        'name', 'email', 'phone', 'subject', 'message_type', 'message',
        'ip_address', 'user_agent',
        'status', 'admin_reply', 'replied_at',
    ];

    protected $casts = [
        'replied_at' => 'datetime',
    ];
}
