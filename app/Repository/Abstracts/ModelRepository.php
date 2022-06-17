<?php

namespace App\Repository\Abstracts;

use Illuminate\Database\Eloquent\Model;

abstract class ModelRepository
{
    public abstract function get(): Model;
    public abstract function update(array $data): Model;
    public abstract function save(): bool;
    public abstract function delete(): bool;
    public abstract function isDeleted(): bool;
    public abstract function __toString(): string;
}
