<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class YouTubeUrl implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || ! preg_match(
            '~^https?://(?:www\.)?(?:youtube\.com/(?:watch\?(?:.*&)?v=|shorts/|live/|embed/)|youtu\.be/)[A-Za-z0-9_-]{11}(?:[?&/].*)?$~i',
            $value,
        )) {
            $fail('Enter a valid YouTube watch, short, live, youtu.be, or embed URL.');
        }
    }
}
