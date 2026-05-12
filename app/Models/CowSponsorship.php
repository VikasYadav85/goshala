<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CowSponsorship extends Model
{
    protected $fillable = [
        'cow_id', 'sponsor_name', 'sponsor_email', 'sponsor_phone',
        'plan', 'amount', 'start_date', 'end_date', 'status', 'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function cow(): BelongsTo
    {
        return $this->belongsTo(Cow::class);
    }
}
