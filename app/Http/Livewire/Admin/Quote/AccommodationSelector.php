<?php

namespace App\Http\Livewire\Admin\Quote;

use App\Http\Livewire\Abstract\AccommodationByDateComponent;
use App\Models\Accommodation\Accommodation;
use App\Models\Quote\Quote;
use App\Repository\Storage\Rooming\AccommodationByDateStorage;
use Carbon\Carbon;

class AccommodationSelector extends AccommodationByDateComponent
{
    public int $quote;
    public int $travellers;
    public string $type = 'quote';

    public function mount(Accommodation|int|null $accommodation = null, Carbon|string|null $start = null, Carbon|string|null $end = null, Quote|int|null $quote = null, int|null $travellers = 0)
    {
        parent::mount($accommodation, $start, $end);

        if ($quote instanceof Quote) {
            $this->quote = $quote->id;
        } else {
            $this->quote = $quote;
        }

        $this->travellers = $travellers;
    }

    public function save(): void
    {
        $quote = Quote::find($this->quote);
        if ($quote === null) { return; }
        $quote->accommodation()->delete();
        foreach ($this->fetchData() as $data) {
            if ($this->selected($data)) {
                $data->addToQuote($quote, $this->getQuantity($data));
            }
        }
        $this->toast('Accommodation Saved Successfully', 'Successfully removed accommodation and added new ones to the quote', 'success');
    }

    public function getQuantity(AccommodationByDateStorage $storage): int
    {
        foreach ($this->selected as $key => $data) {
            if (($data['id'] ?? null) === $storage->accommodation->id
                && ($data['room'] ?? null) === $storage->room->id
                && ($data['board'] ?? null) === $storage->board->id
                && ($data['category'] ?? null) === $storage->category?->id) {
                return $data['quantity'] ?? 0;
            }
        }
        return 0;
    }

    public function addQuantity(int $accommodation, int $room, int $board, int|null $category): void
    {
        foreach ($this->selected as $key => $data) {
            if (($data['id'] ?? null) === $accommodation
                && ($data['room'] ?? null) === $room
                && ($data['board'] ?? null) === $board
                && ($data['category'] ?? null) === $category) {
                $data['quantity'] = ($data['quantity'] ?? 0) + 1;
                $this->selected[$key] = $data;
                return;
            }
        }
        $this->selected[] = [
            'id' => $accommodation,
            'room' => $room,
            'board' => $board,
            'category' => $category,
            'quantity' => 1,
        ];
    }

    public function removeQuantity(int $accommodation, int $room, int $board, int|null $category): void
    {
        foreach ($this->selected as $key => $data) {
            if (($data['id'] ?? null) === $accommodation
                && ($data['room'] ?? null) === $room
                && ($data['board'] ?? null) === $board
                && ($data['category'] ?? null) === $category) {
                $quantity = ($data['quantity'] ?? 0) - 1;
                if ($quantity < 1) {
                    unset($this->selected[$key]);
                } else {
                    $data['quantity'] = $quantity;
                    $this->selected[$key] = $data;
                }
            }
        }
    }

    public function getPackageType(): string
    {
        return 'Quote';
    }

    public function getReturnUrl(): string
    {
        return route('quotes.view', ['quote' => $this->quote]);
    }
}
