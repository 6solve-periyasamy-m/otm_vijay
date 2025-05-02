<?php

namespace App\Http\Livewire\Admin\Tour;

use App\Http\Livewire\Abstract\AccommodationByDateComponent;
use App\Models\Accommodation\Accommodation;
use App\Models\Tour\Tour;
use Carbon\Carbon;

class AccommodationSelector extends AccommodationByDateComponent
{
    public int $tour;
    public string $component_type = 'Included';
    public float|null $price = 0;

    public function mount(Accommodation|int|null $accommodation = null, Carbon|string|null $start = null, Carbon|string|null $end = null, Tour|int|null $tour = null)
    {
        parent::mount($accommodation, $start, $end);


        if ($tour instanceof Tour) {
            $this->tour = $tour->id;
        } else {
            $this->tour = $tour;
        }
    }

    public function save(): void
    {
        $tour = Tour::find($this->tour);
        if ($tour === null) { return; }
        if (!in_array($this->component_type, $this->getAvailableTypes())) {
            dd($this->component_type);
            $this->toast('Invalid Type', 'Invalid component type provided', 'danger');
            return;
        }
        //$tour->accommodationInventoryTours()->delete();
        foreach ($this->fetchData() as $data) {
            if ($this->selected($data)) {
                $data->addToTour($tour, $this->component_type, $this->price);
            }
        }
        $tour->repository->autoAssignTemplating();
        $this->toast('Accommodation Saved Successfully', 'Successfully removed accommodation and added new ones to the tour', 'success');
    }

    public function getAvailableTypes(): array
    {
        $types = ['Included' => 'Included', 'Add-on' => 'Add-on'];
        if (config('app.features.kpt', false) || config('app.features.bleeding-edge', false)) {
            $types['Upgrade'] = 'Upgrade';
        }
        return $types;
    }

    public function render()
    {
        return view('livewire.admin.tour.accommodation-selector');
    }

    public function getPackageType(): string
    {
        return 'Tour';
    }
    
    public function getReturnUrl(): string
    {
        return route('tours.view', ['tour' => $this->tour]);
    }
}
