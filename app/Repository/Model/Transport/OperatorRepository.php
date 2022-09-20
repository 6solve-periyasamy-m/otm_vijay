<?php

namespace App\Repository\Model\Transport;

use App\Models\Transport\Operator;
use App\Repository\Abstracts\SmallModelRepository;
use App\Repository\Authentication\PermissionsRepository;
use Illuminate\Support\Collection;

/**
 * @property-read Operator $model
 */
class OperatorRepository extends SmallModelRepository
{
    public static function getName(): string
    {
        return 'Operator';
    }

    /**
     * @param bool $trashed
     * @return Operator[]|Collection
     */
    public static function getAll(bool $trashed = false): array|Collection
    {
        if ($trashed) {
            return Operator::withTrashed()->withCount('transports')->get();
        } else {
            return Operator::withCount('transports')->get();
        }
    }

    public static function getCreateUrl(): string|null
    {
        if (PermissionsRepository::canCurrentUser('create', Operator::class)) {
            return route('operators.create');
        }
        return null;
    }

    public function getEditUrl(): string|null
    {
        if (PermissionsRepository::canCurrentUser('update', Operator::class)) {
            return route('operators.update', ['operator' => $this->model,]);
        }
        return null;
    }

    public function getDeleteUrl(): string|null
    {
        if (PermissionsRepository::canCurrentUser('delete', Operator::class) && $this->canDelete()) {
            return route('operators.delete', ['operator' => $this->model,]);
        }
        return null;
    }

    public function getRelatedCount(): int
    {
        return $this->model->transports()->count();
    }
}
