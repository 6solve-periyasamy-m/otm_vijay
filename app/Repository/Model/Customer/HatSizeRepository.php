<?php

namespace App\Repository\Model\Customer;

use App\Models\Customer\HatSize;
use App\Repository\Abstracts\AttributeRepository;
use App\Repository\Authentication\PermissionsRepository;
use Illuminate\Support\Collection;

/**
 * @property-read HatSize $model
 */
class HatSizeRepository extends AttributeRepository
{
    public static function getName(): string
    {
        return 'Hat Size';
    }

    /**
     * @param bool $trashed
     * @return HatSize[]|Collection
     */ 
    public static function getAll(bool $trashed = false): array|Collection
    {
        if ($trashed) {
            return HatSize::withTrashed()->withCount('customers')->get();
        } else {
            return HatSize::withCount('customers')->get();
        }
    }

    public static function getCreateUrl(): string|null
    {
        if (PermissionsRepository::canCurrentUser('create', HatSize::class)) {
            return route('hat-sizes.create');
        }
        return null;
    }

    public function getEditUrl(): string|null
    {
        if (PermissionsRepository::canCurrentUser('update', HatSize::class)) {
            return route('hat-sizes.update', ['hatSize' => $this->model,]);
        }
        return null;
    }

    public function getDeleteUrl(): string|null
    {
        if (PermissionsRepository::canCurrentUser('delete', HatSize::class) && $this->canDelete()) {
            return route('hat-sizes.delete', ['hatSize' => $this->model,]);
        }
        return null;
    }

    public function getRelatedCount(): int
    {
        return $this->model->customers()->count();
    }

    public static function find($id): HatSize|null
    {
        return HatSize::find($id);
    }
}
