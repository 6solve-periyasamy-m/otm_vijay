<?php

namespace App\Http\Livewire\Admin\System\Amenity;

use App\Http\Livewire\Abstract\LivewireForm;
use App\Http\Livewire\SendsEvents;
use App\Models\Accommodation\Amenity;
use Illuminate\Validation\Rule;
use LivewireUI\Modal\ModalComponent;
use Livewire\WithFileUploads;
use Illuminate\Http\UploadedFile;

class Form extends ModalComponent
{
    use SendsEvents, LivewireForm, WithFileUploads;

    public Amenity|int|null $amenity = null;
    public UploadedFile|string|null $image = null;

    public function mount(Amenity|int|null $amenity = null)
    {
        $this->amenity = Amenity::getForMount($amenity);
    }

    public function render()
    {
        return view('livewire.admin.system.amenity.form');
    }

    public function save(): void
    {
        $this->validate();
        if ($this->image !== null) {
            $this->amenity->image_url = store_file($this->image, $this->amenity->image_url);
        }
        $this->amenity->save();
        $this->refreshTables();
        $this->closeModal();
    }

    public function rules(): array
    {
        return [
            'amenity.name' => [
                'required',
                Rule::unique('amenities', 'name')->ignore($this->amenity->id),
            ],
            'image' => 'nullable|image|mimes:jpg,png,svg|max:1024',
        ];
    }
}
