<?php

namespace App\Http\Requests\Booking;

/**
 * @property array{room: string, travellers: int}[] $rooming
 */
class ApiRoomingRequest extends BookingOverviewRequest
{
    public function rules(): array
    {
        return [
            'rooming.*.room' => 'required|string',
            'rooming.*.travellers' => 'required|integer|min:1',
            ...parent::rules()
        ];
    }
}