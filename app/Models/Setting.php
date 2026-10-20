<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function isSystemLocked(): bool
    {
        static $requestCache = null;
        if ($requestCache !== null) {
            return $requestCache;
        }

        return $requestCache = Cache::remember('is_system_locked', 60, function () {
            if (!Schema::hasTable('settings')) {
                return false;
            }

            return self::where('key', 'system_lockdown')->where('value', '1')->exists();
        });
    }

    public static function clearLockdownCache(): void
    {
        Cache::forget('is_system_locked');
    }
}

