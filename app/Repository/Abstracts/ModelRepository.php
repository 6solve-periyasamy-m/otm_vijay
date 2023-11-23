<?php

namespace App\Repository\Abstracts;

use Illuminate\Database\Eloquent\Model;
use Livewire\Wireable;

abstract class ModelRepository implements Wireable
{
    public abstract static function find($id): Model|null;
    public abstract function get(): Model;
    public abstract function update(array $data): Model;
    public abstract function save(): bool;
    public abstract function delete(): bool;
    public abstract function isDeleted(): bool;
    public abstract function __toString(): string;

    public function toLivewire(): array
    {
        return ['class' => static::class, 'id' => $this->get()->id];
    }

    public static function fromLivewire($value): static
    {
        return $value['class']::find($value['id'])?->repository;
    }
}
