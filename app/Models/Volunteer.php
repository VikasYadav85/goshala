<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Volunteer extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';
    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'full_name', 'email', 'phone', 'date_of_birth', 'gender',
        'city', 'state', 'country', 'occupation',
        'areas_of_interest', 'availability',
        'previous_experience', 'motivation', 'referral_source',
        'status', 'admin_notes', 'approved_at', 'approved_by',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'areas_of_interest' => 'array',
        'availability' => 'array',
        'approved_at' => 'datetime',
    ];

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
