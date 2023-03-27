<?php

namespace App\Models\Traits;

use App\Repository\Abstracts\ModelRepository;

trait HasRepository
{
    private ModelRepository $internal_repository;

    public function getRepositoryAttribute()
    {
        $repo = $this->repositoryClass ?? '\\App\\Repository\\Model\\' . str_replace('App\\Models\\', '', self::class) . 'Repository';
        isset($this->internal_repository) || $this->internal_repository = new $repo($this);
        return $this->internal_repository;
    }
}
