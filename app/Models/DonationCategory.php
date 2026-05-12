<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DonationCategory extends Model
{
    protected $fillable = [
        'name', 'slug', 'icon', 'image', 'short_description', 'description',
        'suggested_amounts', 'default_amount', 'is_active', 'is_featured', 'sort_order',
    ];

    protected $casts = [
        'suggested_amounts' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    public function scopeActive(Builder $q): Builder
    {
        return $q->where('is_active', true);
    }
}
