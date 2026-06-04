<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function isSystemLocked(): bool
    {
        if (!Schema::hasTable('settings')) {
            return false;
        }

        return self::where('key', 'system_lockdown')->where('value', '1')->exists();
    }
}
