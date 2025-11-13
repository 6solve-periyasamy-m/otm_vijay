<?php

namespace App\Repository\Model\Customer;

use App\Models\Customer\LoyaltyNumberType;
use App\Repository\Abstracts\AttributeRepository;
use App\Repository\Authentication\PermissionsRepository;
use Illuminate\Support\Collection;

/**
 * @property-read LoyaltyNumberType $model
 */
class LoyaltyNumberTypeRepository extends AttributeRepository
{
    public static function getName(): string
    {
        return 'Loyalty Number Type';
    }

    /**
     * @param bool $trashed
     * @return LoyaltyNumberType[]|Collection
     */ 
    public static function getAll(bool $trashed = false): array|Collection
    {
        return LoyaltyNumberType::withCount('numbers')->get();
    }

    public static function getCreateUrl(): string|null
    {
        return null;
    }

    public static function getCreateModal(): string|null
    {
        if (PermissionsRepository::canCurrentUser('create', LoyaltyNumberType::class)) {
            return 'admin.customer.loyalty-number-type.form';
        }
        return null;
    }

    public function getEditUrl(): string|null
    {
        if (PermissionsRepository::canCurrentUser('update', LoyaltyNumberType::class)) {
            return route('loyalty-number-type.edit', ['type' => $this->model,]);
        }
        return null;
    }

    public function getDeleteUrl(): string|null
    {
        if (PermissionsRepository::canCurrentUser('delete', LoyaltyNumberType::class) && $this->canDelete()) {
            return route('loyalty-number-type.delete', ['type' => $this->model,]);
        }
        return null;
    }

    public function getRelatedCount(): int
    {
        return $this->model->numbers()->count();
    }

    public static function find($id): LoyaltyNumberType|null
    {
        return LoyaltyNumberType::find($id);
    }
}
