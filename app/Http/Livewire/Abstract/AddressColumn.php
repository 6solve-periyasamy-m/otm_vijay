<?php

namespace App\Http\Livewire\Abstract;

use App\Models\Location\Country;
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
            static function (...$lines) {
                $address = "";
                foreach ($lines as $line) {
                    if (empty($line)) { continue; }
                    if (!empty($address)) { $address .= ", "; }
                    $address .= $line;
                }
                return $address;
        })->filterable(Country::pluck('name'))->filterOn("$country.name");
    }
}
