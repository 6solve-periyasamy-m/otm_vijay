<?php

namespace App\Http\Livewire\Abstract;

use App\Models\Helper\Enum\QuoteStatus;
use App\View\Components\Badge\Quote as QuoteBadge;
use Mediconesystems\LivewireDatatables\Column;

class QuoteBadgeColumn extends Column
{
    public function __construct()
    {
        parent::__construct();
        $this->callback = static function ($value) {  return (new QuoteBadge(QuoteStatus::from($value)))->render(); };
        $this->exportCallback = static function ($value) { return QuoteStatus::from($value)->description(); };
    }
}
