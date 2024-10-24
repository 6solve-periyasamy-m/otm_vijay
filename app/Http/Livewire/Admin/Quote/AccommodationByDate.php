<?php

namespace App\Http\Livewire\Admin\Quote;

use App\Http\Livewire\Abstract\LivewireForm;
use App\Models\Accommodation\Accommodation;
use App\Repository\Model\Accommodation\AccommodationRepository;
use App\Repository\Storage\Rooming\AccommodationByDateStorage as AccommodationStorage;
use Carbon\Carbon;
use Livewire\Component;

class AccommodationByDate extends Component
{
    use LivewireForm;

    public int|null $accommodation = null;
    public string|null $start = null;
    public string|null $end = null;
    
    public function mount(Accommodation|int|null $accommodation = null, Carbon|string|null $start = null, Carbon|string|null $end = null)
    {
        if ($start instanceof Carbon) {
            $this->start = $start->format('Y-m-d');
        } else {
            $this->start = $start;
        }

        if ($end instanceof Carbon) {
            $this->end = $end->format('Y-m-d');
        } else {
            $this->end = $end;
        }

        if ($accommodation instanceof Accommodation) {
            $this->accommodation = $accommodation->id;
        } else {
            $this->accommodation = $accommodation;
        }
    }

    public function render()
    {
        return view('livewire.admin.quote.accommodation-by-date');
    }

    public function updated($key, $value): void
    {
        $this->validateOnly($key);
        $this->render();
    }

    public function getStart(): Carbon|null
    {
        return Carbon::createFromFormat('Y-m-d', $this->start);
    }


    public function getEnd(): Carbon|null
    {
        return Carbon::createFromFormat('Y-m-d', $this->end);
    }

    /**
     * @return AccommodationStorage[]
     */
    public function fetchData(): array
    {
        if ($this->start === null || $this->end === null) {
            return  [];
        }

        if ($this->accommodation !== null) {
            return Accommodation::find($this->accommodation)?->repository->getTypesByRange($this->getStart(), $this->getEnd()) ?? [];
        }

        return AccommodationRepository::getAllRoomsInRange($this->getStart(), $this->getEnd());
    }

    public function rules(): array
    {
        return [
            'accommodation' => 'nullable|int|exists:accommodations,id',
            'start' => 'nullable|date|date_format:Y-m-d',
            'end' => 'nullable|date|date_format:Y-m-d',
        ];
    }
}
