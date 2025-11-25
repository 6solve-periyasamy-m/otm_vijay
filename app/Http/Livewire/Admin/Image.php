<?php

namespace App\Http\Livewire\Admin;

use LivewireUI\Modal\ModalComponent;

class Image extends ModalComponent
{
    public string $image;

    public function render()
    {
        return view('livewire.admin.image');
    }
}
