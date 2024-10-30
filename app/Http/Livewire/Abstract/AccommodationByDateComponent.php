<?php

namespace App\Http\Livewire\Abstract;

use App\Http\Livewire\SendsEvents;
use App\Models\Accommodation\Accommodation;
use App\Repository\Model\Accommodation\AccommodationRepository;
use App\Repository\Storage\Rooming\AccommodationByDateStorage as AccommodationStorage;
use Carbon\Carbon;
use Livewire\Component;

abstract class AccommodationByDateComponent extends Component
{
    use LivewireForm, SendsEvents;

    public int|null $accommodation = null;
    public int|null $room = null;
    public int|null $board = null;
    public int|null $category = null;
    public string|null $start = null;
    public string|null $end = null;
    public array $selected = [];

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

    abstract public function save(): void;

    public function shouldShow(AccommodationStorage $storage): bool
    {
        return ($this->room === null || $this->room === $storage->room->id) &&
            ($this->board === null || $this->board === $storage->board->id) &&
            ($this->category === null || $this->category === $storage->category->id);
    }

    public function render()
    {
        return view('livewire.admin.accommodation-selector');
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

    public function select(int $accommodation, int $room, int $board, int|null $category): void
    {
        $key = $this->internalSelected($accommodation, $room, $board, $category);
        if ($key !== null) {
            unset($this->selected[$key]);
        } else {
            $this->selected[] = [
                'id' => $accommodation,
                'room' => $room,
                'board' => $board,
                'category' => $category,
            ];
        }
    }

    public function selected(AccommodationStorage $storage): bool
    {
        return $this->internalSelected($storage->accommodation->id, $storage->room->id, $storage->board->id, $storage->category?->id) !== null;
    }

    private function internalSelected(int $accommodation, int $room, int $board, int|null $category): int|null
    {
        foreach ($this->selected as $key => $data) {
            if (($data['id'] ?? null) === $accommodation
                && ($data['room'] ?? null) === $room
                && ($data['board'] ?? null) === $board
                && ($data['category'] ?? null) === $category)
            {
                return $key;
            }
        }
        return null;
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
