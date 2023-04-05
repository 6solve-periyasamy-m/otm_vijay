<?php

namespace App\Repository\Facades;

use App\Repository\SettingsRepository;
use Carbon\Carbon;

class Settings
{
    private SettingsRepository $repository;

    public function __construct()
    {
        $this->repository = SettingsRepository::getInstance();
    }

    public function get($key, $default = null): ?string
    {
        return $this->repository->getOrDefault($key, $default);
    }

    public function getBoolean($key, $default = false): bool
    {
        return $this->repository->getBoolean($key, $default);
    }

    public function set($key, $value): void
    {
        $this->repository->set($key, $value);
    }

    public function setAll(array $keys): void
    {
        $this->repository->setAll($keys);
    }

    public function authorize(string $key, int $seconds): void
    {
        $this->repository->authorize($key, $seconds);
    }

    public function authorized(string $key): bool
    {
        return $this->repository->authorized($key);
    }

    public function isLocked(string $key, Carbon $from, Carbon $to): bool
    {
        return $from->subDays(setting("{$key}.lock", 30))->lte(now()) && $to->addDays(setting("{$key}.unlock", 0))->gte(now());
    }
}
