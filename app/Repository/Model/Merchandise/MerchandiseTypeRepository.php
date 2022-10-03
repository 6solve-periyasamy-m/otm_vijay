<?php

namespace App\Repository\Model\Merchandise;

use App\Models\Merchandise\MerchandiseType;
use App\Repository\Abstracts\AttributeRepository;
use Illuminate\Support\Collection;

/**
 * @property-read MerchandiseType $model
 */
class MerchandiseTypeRepository extends AttributeRepository
{
    public static function getName(): string
    {
        return 'Merchandise Type';
    }

    /**
     * @param bool $trashed
     * @return MerchandiseType[]|Collection
     */ 
    public static function getAll(bool $trashed = false): array|Collection
    {
        if ($trashed) {
            return MerchandiseType::withTrashed()->withCount('merchandise')->get();
        } else {
            return MerchandiseType::withCount('merchandise')->get();
        }
    }

    public static function getCreateUrl(): string|null
    {
        return route('merchandise.type.create');
    }

    public function getEditUrl(): string|null
    {
        return route('merchandise.type.update', ['type' => $this->model,]);
    }

    public function getDeleteUrl(): string|null
    {
        if ($this->canDelete()) {
            return route('merchandise.type.delete', ['type' => $this->model,]);
        }
        return null;
    }

    public function getRelatedCount(): int
    {
        return $this->model->merchandise()->count();
    }
}
