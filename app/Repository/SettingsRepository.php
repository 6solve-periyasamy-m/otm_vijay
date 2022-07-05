<?php

namespace App\Repository;

use App\Models\System\Setting;
use Carbon\Carbon;

class SettingsRepository
{
    private static SettingsRepository $instance;
    private array $cache;
    private Carbon $cacheTime;

    public function __construct()
    {
        $this->verifyCache();
    }

    public static function getInstance(): SettingsRepository
    {
        if (!isset(SettingsRepository::$instance)) SettingsRepository::$instance = new SettingsRepository();
        return SettingsRepository::$instance;
    }

    public function get($key): ?string
    {
        return $this->getOrDefault($key);
    }

    public function getOrDefault($key, $default = null): ?string
    {
        $this->verifyCache();
        return array_key_exists($key, $this->cache) ? $this->cache[$key] : $default;
    }

    public function getBoolean($key, $default = false): bool
    {
        return $this->getOrDefault($key, ($default ? 1 : 0)) == 1;
    }


    public function set(string $key, ?string $value): SettingsRepository
    {
        return $this->internalSet($key, $value);
    }

    public function setAll(array $keys): SettingsRepository
    {
        foreach ($keys as $key => $value) {
            $this->internalSet($key, $value, false);
        }
        $this->verifyCache();
        return $this;
    }


    private function internalSet(string $key, ?string $value, bool $recache = true): SettingsRepository
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
        if ($recache) $this->verifyCache();
        return $this;
    }

    public function authorize(string $key, int $seconds): SettingsRepository
    {
        return $this->set($key, $seconds < 0 ? -1 : now()->addSeconds($seconds)->unix());
    }

    public function authorized(string $key): bool
    {
        $time = $this->getOrDefault($key, 0);
        if ($time == 0) return false;
        return $time === -1 || Carbon::createFromTimestamp($time)->isAfter(now());
    }

    public function verifyCache(): SettingsRepository
    {
        if (!isset($this->cacheTime) || $this->cacheTime->diffInMinutes(now()) > 15) {
            $this->cache = [];
            $this->cacheTime = now();
            foreach (Setting::all() as $setting) {
                $this->cache[$setting->key] = $setting->value;
            }
        }
        return $this;
    }
}
