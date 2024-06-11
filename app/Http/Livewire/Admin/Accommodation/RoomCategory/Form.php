<?php

namespace App\Http\Livewire\Admin\Accommodation\RoomCategory;

use App\Http\Livewire\SendsEvents;
use App\Models\Accommodation\RoomCategory;
use LivewireUI\Modal\ModalComponent;

class Form extends ModalComponent
{
    use SendsEvents;

    /** @var RoomCategory */
    public RoomCategory|int|null $category;

    public function mount(RoomCategory|int|null $category = null)
    {
        $this->category = RoomCategory::getForMount($category);
    }

    public function save()
    {
        $this->validate();
        $this->category->save();
        $this->refreshTables();
        $this->closeModal();
    }
    public function render()
    {
        return view('livewire.admin.accommodation.room-category.form');
    }

    public function rules()
    {
        return ['category.name' => 'required|string',];
    }
}
