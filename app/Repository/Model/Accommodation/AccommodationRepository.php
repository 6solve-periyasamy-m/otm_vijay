<?php

namespace App\Repository\Model\Accommodation;

use App\Models\Accommodation\Accommodation;
use App\Repository\Abstracts\ModelRepository;
use App\Repository\Interfaces\Manifest\HasRoomingList;
use App\Repository\Storage\Rooming\AccommodationByDateStorage;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AccommodationRepository extends ModelRepository implements HasRoomingList
{
    private Accommodation $accommodation;

    public function __construct(Accommodation $accommodation)
    {
        $this->accommodation = $accommodation;
    }

    /**
     * @inheritDocOrder Report
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

    public static function find($id): Accommodation|null
    {
        return Accommodation::find($id);
    }

    /**
     * @param Carbon $start
     * @param Carbon $end
     * @return AccommodationByDateStorage[]
     */
    public static function getAllRoomsInRange(Carbon $start, Carbon $end): array
    {
        $data = [];
        foreach (Accommodation::all() as $accommodation) {
            $data = [
                ...$data,
                ...$accommodation->repository->getTypesByRange($start, $end),
            ];
        }
        return $data;
    }

    /**
     * @param Carbon $start Start date for range
     * @param Carbon $end End date for range
     * @return AccommodationByDateStorage[]
     */
    public function getTypesByRange(Carbon $start, Carbon $end): array
    {
        /** @var AccommodationByDateStorage[] $data */
        $data = [];
        foreach ($this->accommodation->inventory()->whereDate('check_in', '>=', $start)->whereDate('check_out', '<=', $end)->get() as $inventory)
        {
            $foundKey = null;
            $found = null;

            foreach ($data as $key => $datum) {
                if ($datum->matches($inventory)) {
                    $found = $datum;
                    $foundKey = $key;
                }
            }

            $found = $found ?? AccommodationByDateStorage::createFromInventory($inventory);
            $found->addRoom($inventory);

            if ($foundKey === null) { $data[] = $found; }
            else { $data[$foundKey] = $found; }
        }

        return $data;
    }
}
