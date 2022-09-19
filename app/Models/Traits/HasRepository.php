<?php

namespace App\Models\Traits;

use App\Repository\Abstracts\ModelRepository;

trait HasRepository
{
    private ModelRepository $internal_repository;

    public function getRepositoryAttribute()
    {
        isset($this->internal_repository) || $this->internal_repository = new $this->repositoryClass($this);
        return $this->internal_repository;
    }
}
