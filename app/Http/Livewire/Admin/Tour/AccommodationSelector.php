<?php

namespace App\Http\Livewire\Admin\Tour;

use App\Http\Livewire\Abstract\AccommodationByDateComponent;
use App\Models\Accommodation\Accommodation;
use App\Models\Tour\Tour;
use Carbon\Carbon;

class AccommodationSelector extends AccommodationByDateComponent
{
    public int $tour;

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
        $tour->accommodationInventoryTours()->delete();
        foreach ($this->fetchData() as $data) {
            if ($this->selected($data)) {
                $data->addToTour($tour);
            }
        }
        $this->toast('Accommodation Saved Successfully', 'Successfully removed accommodation and added new ones to the tour', 'success');
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
