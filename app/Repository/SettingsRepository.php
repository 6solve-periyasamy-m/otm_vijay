<?php

namespace App\Repository;

use App\Models\Setting;

interface SettingsRepositoryInterface {
    public static function get($key);
    public static function getOrDefault($key, $default);
    public static function set($key, $value);
    public static function setIfNotExists($key, $value);
    public static function update($key, $value);
    public static function setAll($array);
}

class SettingsRepository implements SettingsRepositoryInterface
{

    public static function get($key)
    {
        $setting = Setting::find($key);
        return isset($setting) ? $setting->value : null;
    }

    public static function getOrDefault($key, $default)
    {
        $setting = Setting::find($key);
        return isset($setting) ? $setting->value : $default;
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
