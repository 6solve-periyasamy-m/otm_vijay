<?php

namespace App\Repository\Abstracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;

abstract class AttributeRepository extends ModelRepository
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function get(): Model
    {
        return $this->model;
    }

    public function update(array $data): Model
    {
        $this->model->update($data);
        $this->save();
        return $this->get();
    }

    public function save(): bool
    {
        return $this->model->save();
    }

    public function delete(): bool
    {
        if ($this->canDelete()) {
            return $this->model->delete();
        }
        return false;
    }

    public function isDeleted(): bool
    {
        return $this->model->trashed();
    }

    public function __toString(): string
    {
        return $this->model->name;
    }

    public function canDelete(): bool
    {
        return $this->getRelatedCount() === 0;
    }

    public static function getSafeName(): string
    {
        return preg_replace('/[^A-Za-z0-9-_]/', '', static::getName());
    }

    /**
     * A Redirect to the URL that the response should return to
     * @return RedirectResponse
     */
    public function getReturnURL(): RedirectResponse
    {
        return redirect()->route('attributes.edit');
    }

    public static function getCreateModal(): string|null { return null; }

    public static abstract function getCreateUrl(): string|null;
    public static abstract function getAll(bool $trashed = false): array|Collection;
    public static abstract function getName(): string;
    public abstract function getEditUrl(): string|null;
    public abstract function getDeleteUrl(): string|null;
    public abstract function getRelatedCount(): int;
}
