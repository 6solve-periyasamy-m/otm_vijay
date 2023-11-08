<?php

namespace App\Repository\Model\Merchandise;

use App\Models\Merchandise\Merchandise;
use App\Models\Merchandise\MerchandiseInventory;
use App\Models\Tour\Tour;
use App\Repository\Abstracts\ModelRepository;
use App\Repository\Traits\Component\IsMerchandise;
use DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;

class MerchandiseRepository extends ModelRepository
{
    use IsMerchandise;

    private Merchandise $component;

    public function __construct(Merchandise $component)
    {
        $this->component = $component;
    }

    /**
     * @return Collection|MerchandiseInventory[]
     */
    public function getInventory(): Collection|array
    {
        return $this->component->inventory()->groupBy('variant_id')->with('variant', 'tourComponents')->withCount('tourComponents')->get();
    }
    
    public function addAllInventoryToTour(Tour $tour, string $type)
    {
        foreach ($this->component->inventory as $inventory) {
            $inventory->repository->addToTour($tour, $type);
        }
    }

    public function getInventoryIncludedOnTourCount(Tour $tour): int
    {
        $query = DB::table('merchandise_inventories');
        $query->join('merchandises', 'merchandise_inventories.merchandise_id', '=', 'merchandises.id');
        $query->join('merchandise_inventory_tours', 'merchandise_inventory_tours.merchandise_inventory_id', '=', 'merchandise_inventories.id');
        $query->where('merchandise_inventory_tours.tour_id', '=', $tour->id);
        $query->where('merchandises.id', '=', $this->component->id);
        $query->select('merchandise_inventories.id');

        return $query->get()->count();
    }

    public static function create(array $data, ?UploadedFile $image = null): Merchandise
    {
        if ($image !== null) {
            $data['image_url'] = store_file($image);
        }
        return Merchandise::create($data);
    }

    public function createInventory(array $data, ?UploadedFile $image = null): MerchandiseInventory
    {
        return MerchandiseInventoryRepository::create($this->component, $data, $image);
    }

    public function get(): Merchandise
    {
        return $this->component;
    }

    public function update(array $data): Merchandise
    {
        $this->component->update($data);
        $this->save();
        return $this->get();
    }

    public function updateWithImage(array $data, ?UploadedFile $image = null): Merchandise
    {
        if ($image !== null) {
            $data['image_url'] = store_file($image);
        }
        return $this->update($data);
    }

    public function save(): bool
    {
        return $this->component->save();
    }

    public function getOrderCount(): int
    {
        $count = 0;
        /** @var MerchandiseInventory $inventory */
        foreach ($this->component->inventory as $inventory) {
            $count += $inventory->repository->getOrderCount();
        }
        return $count;
    }

    public function delete(): bool
    {
        foreach ($this->component->inventory()->withCount('tourComponents')->get() as $inventory) {
            if ($inventory->tourComponents()->count() > 0) return false;
        }
        return $this->component->delete();
    }

    public function isDeleted(): bool
    {
        return $this->component->trashed();
    }

    public function __toString(): string
    {
        return $this->component->name;
    }

    public static function find($id): Merchandise|null
    {
        return Merchandise::find($id);
    }
}
