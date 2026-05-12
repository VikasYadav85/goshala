<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class GalleryAlbum extends Model
{
    protected $fillable = ['name', 'slug', 'category', 'description', 'cover_image', 'is_published', 'sort_order'];

    protected $casts = ['is_published' => 'boolean'];

    protected static function booted(): void
    {
        static::creating(function (GalleryAlbum $a) {
            if (empty($a->slug)) {
                $a->slug = Str::slug($a->name) . '-' . Str::lower(Str::random(5));
            }
        });
    }

    public function items(): HasMany
    {
        return $this->hasMany(GalleryItem::class, 'album_id')->orderBy('sort_order');
    }

    public function scopePublished(Builder $q): Builder
    {
        return $q->where('is_published', true);
    }
}
