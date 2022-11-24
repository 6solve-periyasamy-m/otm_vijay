<?php

namespace App\Http\Requests;

use App\Repository\Storage\RemoteGroup;
use Illuminate\Foundation\Http\FormRequest;


class RoomingRequest extends FormRequest
{
    private array $inflated;

    /**
     * @return RemoteGroup[]
     */
    public function getData(): array
    {
        if (!isset($this->inflated)) {
            $data = [];
            foreach ($this->data as $datum) {
                $data[] = new RemoteGroup($datum['customers'], $datum['rooms']);
            }
            $this->inflated = $data;
        }
        return $this->inflated;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'data.*.customers.*' => 'required|integer|exists:customers,id',
            'data.*.rooms.*' => 'required|integer|exists:accommodation_inventory_tours,id',
        ];
    }
}
