<?php

namespace App\Http\Livewire\Abstract;

use Carbon\Carbon;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

abstract class StandaloneDatatable extends LivewireDatatable
{
    public abstract function getClass(): string;

    public function filter($query, Carbon|string|null $from, Carbon|string|null $to, $startColumn = 'starts_at', $endColumn = 'ends_at')
    {
        if (!empty($from)) {
            $query->where('departs_at', '>', is_string($from) ? Carbon::parse($from) : $from);
        }
        if (!empty($end)) {
            $query->where('arrives_at', '<', is_string($to) ? Carbon::parse($to) : $to);
        }
        return $query;
    }
}