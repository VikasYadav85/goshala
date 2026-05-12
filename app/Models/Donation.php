<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Donation extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_SUCCESS = 'success';
    public const STATUS_FAILED = 'failed';
    public const STATUS_REFUNDED = 'refunded';

    protected $fillable = [
        'reference_no', 'donation_category_id', 'campaign_id', 'cow_id',
        'donor_name', 'donor_email', 'donor_phone', 'donor_pan',
        'donor_address', 'donor_city', 'donor_state', 'donor_pincode', 'donor_country',
        'amount', 'currency', 'frequency', 'is_anonymous', 'wants_80g_receipt',
        'dedication', 'message',
        'payment_method', 'payment_status',
        'razorpay_order_id', 'razorpay_payment_id', 'razorpay_signature',
        'paid_at', 'payment_meta',
        'receipt_no', 'receipt_issued_at',
    ];

    protected $casts = [
        'is_anonymous' => 'boolean',
        'wants_80g_receipt' => 'boolean',
        'paid_at' => 'datetime',
        'receipt_issued_at' => 'datetime',
        'payment_meta' => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function (Donation $d) {
            if (empty($d->reference_no)) {
                $d->reference_no = 'GSST-' . now()->format('Ymd') . '-' . Str::upper(Str::random(6));
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(DonationCategory::class, 'donation_category_id');
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function cow(): BelongsTo
    {
        return $this->belongsTo(Cow::class);
    }

    public function scopeSuccessful(Builder $q): Builder
    {
        return $q->where('payment_status', self::STATUS_SUCCESS);
    }

    public function scopeRecent(Builder $q): Builder
    {
        return $q->orderByDesc('created_at');
    }
}
