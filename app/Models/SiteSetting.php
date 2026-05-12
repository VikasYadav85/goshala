<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $fillable = ['group', 'key', 'value', 'type', 'label', 'description', 'sort_order'];

    public static function get(string $key, mixed $default = null): mixed
    {
        $settings = Cache::rememberForever('site_settings', function () {
            return self::all()->keyBy('key');
        });

        $row = $settings->get($key);
        if (! $row) {
            return $default;
        }

        return match ($row->type) {
            'boolean' => filter_var($row->value, FILTER_VALIDATE_BOOLEAN),
            'number'  => is_numeric($row->value) ? $row->value + 0 : 0,
            'json'    => json_decode((string) $row->value, true) ?? $default,
            default   => $row->value,
        };
    }

    public static function put(string $key, mixed $value, string $type = 'string', string $group = 'general'): self
    {
        $row = self::updateOrCreate(
            ['key' => $key],
            [
                'group' => $group,
                'type'  => $type,
                'value' => is_array($value) ? json_encode($value) : $value,
            ],
        );
        Cache::forget('site_settings');

        return $row;
    }

    public static function flushCache(): void
    {
        Cache::forget('site_settings');
    }
}
