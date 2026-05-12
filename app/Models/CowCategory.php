<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CowCategory extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'sort_order'];

    public function cows(): HasMany
    {
        return $this->hasMany(Cow::class, 'category_id');
    }
}
