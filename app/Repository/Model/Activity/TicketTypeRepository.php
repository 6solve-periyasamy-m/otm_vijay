<?php

namespace App\Repository\Model\Activity;

use App\Models\Activity\TicketType;
use App\Repository\Abstracts\AttributeRepository;
use App\Repository\Authentication\PermissionsRepository;
use Illuminate\Support\Collection;

/**
 * @property-read TicketType $model
 */
class TicketTypeRepository extends AttributeRepository
{
    public static function getName(): string
    {
        return 'Ticket Type';
    }

    /**
     * @param bool $trashed
     * @return TicketType[]|Collection
     */
    public static function getAll(bool $trashed = false): array|Collection
    {
        if ($trashed) {
            return TicketType::withTrashed()->withCount('inventories')->get();
        } else {
            return TicketType::withCount('inventories')->get();
        }
    }

    public static function getCreateUrl(): string|null
    {
        if (PermissionsRepository::canCurrentUser('create', TicketType::class)) {
            return route('ticket-types.create');
        }
        return null;
    }

    public function getEditUrl(): string|null
    {
        if (PermissionsRepository::canCurrentUser('update', TicketType::class)) {
            return route('ticket-types.update', ['ticketType' => $this->model,]);
        }
        return null;
    }

    public function getDeleteUrl(): string|null
    {
        if (PermissionsRepository::canCurrentUser('delete', TicketType::class) && $this->canDelete()) {
            return route('ticket-types.delete', ['ticketType' => $this->model,]);
        }
        return null;
    }

    public function getRelatedCount(): int
    {
        return $this->model->inventories()->count();
    }
}
