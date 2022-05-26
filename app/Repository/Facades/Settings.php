<?php

namespace App\Repository\Facades;

use App\Repository\SettingsRepository;

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
}