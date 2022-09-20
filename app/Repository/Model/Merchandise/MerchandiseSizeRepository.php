<?php

namespace App\Repository\Model\Merchandise;

use App\Models\Merchandise\MerchandiseSize;
use App\Repository\Abstracts\SmallModelRepository;
use App\Repository\Authentication\PermissionsRepository;
use Illuminate\Support\Collection;

/**
 * @property-read MerchandiseSize $model
 */
class MerchandiseSizeRepository extends SmallModelRepository
{
    public static function getName(): string
    {
        return 'Merchandise Size';
    }

    /**
     * @param bool $trashed
     * @return MerchandiseSize[]|Collection
     */ 
    public static function getAll(bool $trashed = false): array|Collection
    {
        if ($trashed) {
            return MerchandiseSize::withTrashed()->withCount('inventories')->get();
        } else {
            return MerchandiseSize::withCount('inventories')->get();
        }
    }

    public static function getCreateUrl(): string|null
    {
        return route('merchandise.size.create');
    }

    public function getEditUrl(): string|null
    {
        return route('merchandise.size.update', ['size' => $this->model,]);
    }

    public function getDeleteUrl(): string|null
    {
        if ($this->canDelete()) {
            return route('merchandise.size.delete', ['size' => $this->model,]);
        }
        return null;
    }

    public function getRelatedCount(): int
    {
        return $this->model->inventories()->count();
    }
}
