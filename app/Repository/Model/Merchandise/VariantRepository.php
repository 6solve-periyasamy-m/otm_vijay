<?php

namespace App\Repository\Model\Merchandise;

use App\Models\Merchandise\Variant;
use App\Repository\Abstracts\AttributeRepository;
use App\Repository\Authentication\PermissionsRepository;
use Illuminate\Support\Collection;

/**
 * @property-read Variant $model
 */
class VariantRepository extends AttributeRepository
{
    public static function getName(): string
    {
        return 'Variants';
    }

    /**
     * @param bool $trashed
     * @return Variant[]|Collection
     */ 
    public static function getAll(bool $trashed = false): array|Collection
    {
        if ($trashed) {
            return Variant::withTrashed()->withCount('inventories')->get();
        } else {
            return Variant::withCount('inventories')->get();
        }
    }

    public static function getCreateUrl(): string|null
    {
        return route('merchandise.variant.create');
    }

    public function getEditUrl(): string|null
    {
        return route('merchandise.variant.update', ['size' => $this->model,]);
    }

    public function getDeleteUrl(): string|null
    {
        if ($this->canDelete()) {
            return route('merchandise.variant.delete', ['size' => $this->model,]);
        }
        return null;
    }

    public function getRelatedCount(): int
    {
        return $this->model->inventories()->count();
    }
}
