<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'title', 'slug', 'hero_title', 'hero_subtitle', 'hero_image',
        'body', 'sections',
        'seo_title', 'seo_description', 'seo_image',
        'is_published',
    ];

    protected $casts = [
        'sections' => 'array',
        'is_published' => 'boolean',
    ];
}
