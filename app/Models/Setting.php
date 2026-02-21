<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function isSystemLocked(): bool
    {
        return self::where('key', 'system_lockdown')->where('value', '1')->exists();
    }
}
