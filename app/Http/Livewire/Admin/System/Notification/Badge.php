<?php

namespace App\Http\Livewire\Admin\System\Notification;

use Auth;
use Livewire\Component;

class Badge extends Component
{
    protected $listeners = ['refreshLivewireDatatable' => 'render'];
    public function render()
    {
        return view('livewire.admin.system.notification.badge', ['unseen' => Auth::user()?->unseen()]);
    }
}
