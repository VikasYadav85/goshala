<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Campaign extends Model
{
    protected $fillable = [
        'title', 'slug', 'short_description', 'description', 'image', 'gallery',
        'goal_amount', 'raised_amount', 'start_date', 'end_date',
        'status', 'is_emergency', 'is_featured', 'sort_order',
    ];

    protected $casts = [
        'gallery' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_emergency' => 'boolean',
        'is_featured' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Campaign $c) {
            if (empty($c->slug)) {
                $c->slug = Str::slug($c->title) . '-' . Str::lower(Str::random(5));
            }
        });
    }

    public function updates(): HasMany
    {
        return $this->hasMany(CampaignUpdate::class);
    }

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    public function getProgressPercentageAttribute(): int
    {
        if ($this->goal_amount <= 0) {
            return 0;
        }
        return (int) min(100, round(($this->raised_amount / $this->goal_amount) * 100));
    }

    public function scopeActive(Builder $q): Builder
    {
        return $q->where('status', 'active');
    }

    public function scopeFeatured(Builder $q): Builder
    {
        return $q->where('is_featured', true);
    }
}
