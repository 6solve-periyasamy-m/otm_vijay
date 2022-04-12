<?php

namespace App\Repository;

use App\Models\System\Setting;

class SettingsRepository
{

    public static function get($key)
    {
        $setting = Setting::find($key);
        return $setting?->value;
    }

    public static function getOrDefault($key, $default)
    {
        return Setting::find($key)?->value ?? $default;
    }

    public static function getBoolean($key, $default = false)
    {
        return Setting::find($key)?->value == 1 ?? $default;
    }

    public static function set($key, $value)
    {
        $setting = Setting::find($key);
        if (isset($setting)) {
            if (isset($value)) {
                $setting->update(['value' => $value,]);
            } else {
                $setting->delete();
            }
        } else {
            if (isset($value)) {
                Setting::create(['key' => $key, 'value' => $value]);
            }
        }
        return $value;
    }

    public static function setIfNotExists($key, $value)
    {
        $setting = Setting::find($key);
        if (isset($setting)) {
            return null;
        } else {
            if (isset($value)) {
                Setting::create(['key' => $key, 'value' => $value]);
            }
        }
        return $value;
    }

    public static function update($key, $value)
    {
        $setting = Setting::find($key);
        if (isset($setting)) {
            if (isset($value)) {
                $setting->update(['value' => $value,]);
            } else {
                $setting->delete();
            }
        }
        return $value;
    }

    public static function setAll($array)
    {
        foreach ($array as $key => $value) {
            SettingsRepository::set($key, $value);
        }
    }
}
