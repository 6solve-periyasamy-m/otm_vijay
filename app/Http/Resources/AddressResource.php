<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
       return [
            'address_line_1' => $this->address_line_1,
            'address_line_2' => $this->address_line_2,
            'town'           => $this->town,
            'region'         => $this->region,
            'postcode'       => $this->postcode,
            'country'        => $this->country?->name,
        ];
    }
}
