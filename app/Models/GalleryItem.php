<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GalleryItem extends Model
{
    protected $fillable = ['album_id', 'type', 'file_path', 'external_url', 'caption', 'alt_text', 'sort_order'];

    public function album(): BelongsTo
    {
        return $this->belongsTo(GalleryAlbum::class, 'album_id');
    }

    /**
     * Normalise any YouTube URL form (watch, youtu.be, shorts, live) into an
     * embeddable URL. Plain watch URLs cannot be put in an <iframe> directly.
     */
    public function getEmbedUrlAttribute(): ?string
    {
        $url = $this->external_url;
        if (! $url) {
            return null;
        }

        // Already an embed URL.
        if (str_contains($url, '/embed/')) {
            return $url;
        }

        // Pull the 11-char video id out of the common YouTube URL shapes.
        if (preg_match('~(?:youtube\.com/(?:watch\?(?:.*&)?v=|shorts/|live/)|youtu\.be/)([A-Za-z0-9_-]{11})~', $url, $m)) {
            return 'https://www.youtube.com/embed/' . $m[1];
        }

        // Non-YouTube (e.g. Vimeo) — return as-is.
        return $url;
    }
}
