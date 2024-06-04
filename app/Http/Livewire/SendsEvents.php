<?php

namespace App\Http\Livewire;

trait SendsEvents
{
    /**
     * @param string $title
     * @param string $body
     * @param string $color
     * @param bool $hide
     * @param int $delay
     * @return void
     */
    public function toast(string $title, string $body, string $color = 'primary', bool $hide = false, int $delay = 5000): void
    {
        $this->emit('showToast', ['title' => $title, 'body' => $body, 'color' => $color, 'autoHide' => $hide, 'delay' => $delay]);
    }

    public function toastFromLang(string $lang, string $color = 'primary', bool $hide = false, int $delay = 5000): void
    {
        $this->toast(__("$lang.title"), __("$lang.body"), $color, $hide, $delay);
    }

    public function updateValue(string $key, string|null $value): void
    {
        $this->dispatchBrowserEvent('updateValue', ['key' => $key, 'value' => $value]);
    }

    public function refreshTables(): void
    {
        $this->emit('refreshLivewireDatatable');
    }

    public function closeModal(): void
    {
        $this->emit('closeModal');
    }

    public function openInNewTab(string $url): void
    {
        $this->emit('openInNewTab', ['url' => $url,]);
    }

    public function refreshPage(): void
    {
        $this->emit('refreshPage');
    }
}
