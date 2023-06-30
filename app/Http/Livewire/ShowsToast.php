<?php

namespace App\Http\Livewire;

trait ShowsToast
{
    /**
     * @param string $title
     * @param string $body
     * @param bool $hide
     * @param int $delay
     * @return void
     */
    public function toast(string $title, string $body, string $color = 'primary', bool $hide = false, int $delay = 5000): void
    {
        $this->emit('showToast', ['title' => $title, 'body' => $body, 'color' => $color, 'autoHide' => $hide, 'delay' => $delay]);
    }
}
