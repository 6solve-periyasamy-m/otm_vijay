<?php

namespace App\Http\Livewire\Admin\Tour;

use App\Http\Livewire\Abstract\LivewireForm;
use App\Models\Tour\Tour;
use Carbon\Carbon;
use LivewireUI\Modal\ModalComponent;

class Duplicate extends ModalComponent
{
    use LivewireForm;

    public Tour $tour;
    public bool $toDate = false;
    public $date;

    public function mount(Tour|int $tour = null): void
    {
        $this->tour = Tour::getForMount($tour);
    }

    public function save()
    {
        $this->validate();
        if ($this->toDate) {
            $duplicate = $this->tour->repository->duplicateToDate(Carbon::createFromFormat('Y-m-d', $this->date));
        } else {
            $duplicate = $this->tour->repository->duplicate();
        }
        return redirect()->route('tours.view', ['tour' => $duplicate,]);
    }

    public function render()
    {
        return view('livewire.admin.tour.duplicate');
    }

    public function rules(): array
    {
        return [
            'toDate' => 'nullable|boolean',
            'date' => 'nullable|date_format:Y-m-d',
        ];
    }
}
