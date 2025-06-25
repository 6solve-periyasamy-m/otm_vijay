<?php

namespace App\Http\Livewire\Admin\Activity\SeatingMap;

use App\Http\Livewire\Abstract\LivewireForm;
use App\Http\Livewire\SendsEvents;
use App\Models\Activity\SeatingMap;
use Illuminate\Http\UploadedFile;
use Livewire\WithFileUploads;
use LivewireUI\Modal\ModalComponent;

class Form extends ModalComponent
{
    use LivewireForm, SendsEvents, WithFileUploads;

    public SeatingMap|int|null $map;
    public UploadedFile|string|null $image = null;

    public function mount(SeatingMap|int|null $map = null): void
    {
        $this->map = SeatingMap::getForMount($map);
    }

    public function save(): void
    {
        $this->validate();
        if ($this->image !== null) {
            $this->map->image_url = store_file($this->image, $this->map->image_url);
        } else {
            $this->addError('image', 'Image is required');
            return;
        }
        $this->map->save();
        $this->refreshTables();
        $this->toast('Seating Map Saved Successfully', 'Successfully saved changes to seating maps', 'success');
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.admin.activity.seating-map.form');
    }

    public function rules(): array
    {
        return [
            'map.name' => 'required|string|min:3',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
        ];
    }
}
