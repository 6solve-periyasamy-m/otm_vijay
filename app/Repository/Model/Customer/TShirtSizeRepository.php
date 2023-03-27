<?php

namespace App\Repository\Model\Customer;

use App\Models\Customer\TShirtSize;
use App\Repository\Abstracts\AttributeRepository;
use App\Repository\Authentication\PermissionsRepository;
use Illuminate\Support\Collection;

/**
 * @property-read TShirtSize $model
 */
class TShirtSizeRepository extends AttributeRepository
{
    public static function getName(): string
    {
        return 'T-Shirt Size';
    }

    /**
     * @param bool $trashed
     * @return TShirtSize[]|Collection
     */ 
    public static function getAll(bool $trashed = false): array|Collection
    {
        if ($trashed) {
            return TShirtSize::withTrashed()->withCount('customers')->get();
        } else {
            return TShirtSize::withCount('customers')->get();
        }
    }

    public static function getCreateUrl(): string|null
    {
        if (PermissionsRepository::canCurrentUser('create', TShirtSize::class)) {
            return route('t-shirt-sizes.create');
        }
        return null;
    }

    public function getEditUrl(): string|null
    {
        if (PermissionsRepository::canCurrentUser('update', TShirtSize::class)) {
            return route('t-shirt-sizes.update', ['tShirtSize' => $this->model,]);
        }
        return null;
    }

    public function getDeleteUrl(): string|null
    {
        if (PermissionsRepository::canCurrentUser('delete', TShirtSize::class) && $this->canDelete()) {
            return route('t-shirt-sizes.delete', ['tShirtSize' => $this->model,]);
        }
        return null;
    }

    public function getRelatedCount(): int
    {
        return $this->model->customers()->count();
    }
}
