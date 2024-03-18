<?php

namespace App\Http\Livewire\Abstract;

use Mediconesystems\LivewireDatatables\NumberColumn;

class CurrencyColumn extends NumberColumn
{
    public function __construct()
    {
        $this->callback = function ($value) {  return f_currency($value); };
        $this->exportCallback = function ($value) { return $value; };
    }
}