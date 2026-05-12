<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Event extends Model
{
    protected $fillable = [
        'title', 'slug', 'type', 'short_description', 'description', 'image', 'gallery',
        'starts_at', 'ends_at', 'venue', 'address', 'location_url',
        'rsvp_enabled', 'capacity', 'status', 'is_featured',
    ];

    protected $casts = [
        'gallery' => 'array',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'rsvp_enabled' => 'boolean',
        'is_featured' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Event $e) {
            if (empty($e->slug)) {
                $e->slug = Str::slug($e->title) . '-' . Str::lower(Str::random(5));
            }
        });
    }

    public function scopeUpcoming(Builder $q): Builder
    {
        return $q->where('status', 'upcoming')->where('starts_at', '>=', now());
    }

    public function scopePast(Builder $q): Builder
    {
        return $q->where('starts_at', '<', now());
    }
}
