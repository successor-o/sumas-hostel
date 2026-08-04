<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * A simple key/value store for system-wide settings (institution name,
 * support email, current academic session, ...). Values are surfaced to
 * the rest of the app by overriding config('sumas.*') at boot time.
 */
class Setting extends Model
{
    protected $primaryKey = 'key';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'key',
        'value',
    ];

    public static function get(string $key, mixed $default = null): mixed
    {
        return static::query()->find($key)?->value ?? $default;
    }

    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => (string) $value]);
    }

    /**
     * Push every persisted setting onto config('sumas.*') so views read the
     * saved values instead of the shipped defaults. Called at boot and after
     * an admin saves settings.
     */
    public static function applyToConfig(): void
    {
        foreach (static::all() as $setting) {
            config(['sumas.'.$setting->key => $setting->value]);
        }
    }
}
