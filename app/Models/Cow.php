<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Cow extends Model
{
    protected $fillable = [
        'category_id', 'name', 'slug', 'breed', 'age', 'gender', 'color',
        'rescued_at', 'rescue_story', 'description', 'image', 'gallery',
        'monthly_sponsorship_amount', 'is_available_for_sponsorship',
        'is_featured', 'status', 'sort_order',
    ];

    protected $casts = [
        'rescued_at' => 'date',
        'gallery' => 'array',
        'is_available_for_sponsorship' => 'boolean',
        'is_featured' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Cow $cow) {
            if (empty($cow->slug)) {
                $cow->slug = Str::slug($cow->name) . '-' . Str::lower(Str::random(5));
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(CowCategory::class, 'category_id');
    }

    public function sponsorships(): HasMany
    {
        return $this->hasMany(CowSponsorship::class);
    }

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    public function scopeActive(Builder $q): Builder
    {
        return $q->where('status', 'active');
    }

    public function scopeFeatured(Builder $q): Builder
    {
        return $q->where('is_featured', true);
    }

    public function scopeAvailableForSponsorship(Builder $q): Builder
    {
        return $q->where('is_available_for_sponsorship', true)->where('status', 'active');
    }
}
