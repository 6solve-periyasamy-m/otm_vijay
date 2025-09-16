<?php

namespace App\Http\Livewire\Admin\Accommodation\Inventory;

use App\Http\Livewire\Abstract\LivewireForm;
use App\Http\Livewire\SendsEvents;
use App\Models\Accommodation\Accommodation;
use App\Models\Accommodation\AccommodationInventory;
use Carbon\Carbon;
use Livewire\Component;

class Form extends Component
{
    use SendsEvents;
    use LivewireForm;

    public Accommodation|int $accommodation;
    public AccommodationInventory|int|null $inventory;
    public $minEndDate;
    public bool $checkOutUpdated = false;

    public function mount(Accommodation|int $accommodation, AccommodationInventory|int|null $inventory = null): void
    {
        $this->accommodation = Accommodation::getForMount($accommodation);
        $this->inventory = AccommodationInventory::getForMount($inventory);
        if ($this->inventory->check_in) {
            $this->minEndDate = Carbon::parse($this->inventory->check_in)->toDateTimeString();
        }
    }

    public function updated($key, $value): void
    {
        if ($key === 'inventory.check_in') {
            $this->handleCheckInChange($value);
        } else if ($key === 'inventory.check_out') {
            $this->checkOutUpdated = true;
        }
    }

    private function handleCheckInChange($value)
    {
        if ($value && !$this->checkOutUpdated) {
            $startDateTime = Carbon::parse($value);
            $this->minEndDate = $startDateTime->toDateTimeString();
            if ($this->inventory->check_out->lt($this->minEndDate)) {
                $this->inventory->check_out = $startDateTime->copy()->addHour()->toDateTimeString();
            }
        }
    }

    public function save(): void
    {
        $this->validate();
        $this->inventory->accommodation_id = $this->inventory->accommodation_id ?? $this->accommodation->id;
        if (!empty($this->inventory->id)) {
            if (!$this->inventory->repository->validateParent($this->inventory->stock_parent_id)) {
                $this->toast('Cannot Save', 'You cannot use this stock parent, as it is a child of this inventory', 'danger');
                return;
            }
        }
        $this->inventory->stock = $this->inventory->stock ?? 0;
        $this->inventory->check_in_time_confirmed = $this->inventory->check_in_time_confirmed ?? false;
        $this->inventory->check_out_time_confirmed = $this->inventory->check_out_time_confirmed ?? false;
        $this->inventory->fit_selectable = $this->inventory->fit_selectable ?? false;
        $this->inventory->save();
        $this->redirect(route('accommodations.view', ['accommodation' => $this->accommodation,]));
    }

    public function render()
    {
        return view('livewire.admin.accommodation.inventory.form');
    }

    public function rules(): array
    {
        return [
            'inventory.room_type_id' => 'required|integer|exists:room_types,id',
            'inventory.board_type_id' => 'required|integer|exists:board_types,id',
            'inventory.currency_id' => 'nullable|integer|exists:currencies,id',
            'inventory.room_category_id' => 'nullable|integer|exists:room_categories,id',
            'inventory.stock_parent_id' => 'nullable|integer|exists:accommodation_inventories,id',
            'inventory.check_in' => 'required|date',
            'inventory.check_in_time_confirmed' => 'nullable|boolean',
            'inventory.check_out' => 'required|date|after:inventory.check_in',
            'inventory.check_out_time_confirmed' => 'nullable|boolean',
            'inventory.fit_selectable' => 'nullable|boolean',
            'inventory.stock' => 'nullable|integer',
            'inventory.purchase_price' => 'nullable|numeric|gte:0',
            'inventory.sales_price' => 'nullable|numeric|gte:0',
            'inventory.internal_notes' => 'nullable|string',
            'inventory.external_notes' => 'nullable|string',
            'inventory.category_description' => 'nullable|string',
        ];
    }
}
