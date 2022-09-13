<?php

namespace App\Repository\Model\Accommodation;

use App\Models\Accommodation\Accommodation;
use App\Repository\Abstracts\ModelRepository;
use App\Repository\Interfaces\HasRoomingList;
use Illuminate\Support\Collection;

class AccommodationRepository extends ModelRepository implements HasRoomingList
{
    private Accommodation $accommodation;

    public function __construct(Accommodation $accommodation)
    {
        $this->accommodation = $accommodation;
    }

    /**
     * @inheritDoc
     */
    public function getRoomingList(): Collection|array
    {
        return $this->accommodation->orderComponents()->with(
            ['group',
            'group.orderCustomers',
            'group.orderCustomers.customer',
            'accommodationInventoryTour',
            'accommodationInventoryTour.inventory',
            'accommodationInventoryTour.accommodationInventory.accommodation',
            'accommodationInventoryTour.accommodationInventory.roomType',
            'accommodationInventoryTour.accommodationInventory.boardType']
        )->get();
    }

    public function get(): Accommodation
    {
        return $this->accommodation;
    }

    public function update(array $data): Accommodation
    {
        $this->accommodation->update($data);
        $this->save();
        return $this->get();
    }

    public function save(): bool
    {
        return $this->accommodation->save();
    }

    public function delete(): bool
    {
        return $this->accommodation->delete();
    }

    public function isDeleted(): bool
    {
        return $this->accommodation->trashed();
    }

    public function __toString(): string
    {
        return "{$this->accommodation->name} ({$this->accommodation->address->region}, {$this->accommodation->address->country})";
    }
}
