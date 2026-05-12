<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    protected $fillable = [
        'name', 'role', 'group', 'bio', 'photo',
        'email', 'phone', 'social_links',
        'is_published', 'sort_order',
    ];

    protected $casts = [
        'social_links' => 'array',
        'is_published' => 'boolean',
    ];

    public function scopePublished(Builder $q): Builder
    {
        return $q->where('is_published', true);
    }
}
