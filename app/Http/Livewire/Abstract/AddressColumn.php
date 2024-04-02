<?php

namespace App\Http\Livewire\Abstract;

use Mediconesystems\LivewireDatatables\Column;

class AddressColumn extends Column
{
    public static function table(string $table, string|null $country = null)
    {
        $country = $country ?? $table . '_country';
        return parent::callback(
            [
                "$table.address_line_1",
                "$table.address_line_2",
                "$table.town",
                "$table.region",
                "$country.name",
                "$table.postcode",
            ], 
            function (...$str) {
                return implode(', ', $str); 
        });
    }
}
