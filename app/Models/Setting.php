<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'group', 'type'];

    public static function getValue($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        if (!$setting) return $default;

        if ($setting->type === 'json' || $setting->type === 'image_gallery') {
            return json_decode($setting->value, true) ?: [];
        }

        return $setting->value;
    }
}
