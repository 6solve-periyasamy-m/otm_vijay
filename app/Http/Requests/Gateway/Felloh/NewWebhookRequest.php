<?php

namespace App\Http\Requests\Gateway\Felloh;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property int $amount Transaction amount in pence
 * @property array{id: string, booking_reference: string} $booking Booking Details
 * @property string $completed_at
 * @property string $currency
 * @property array{id: string} $payment_link
 * @property array{name: string, reference: string} $provider
 * @property string $status
 * @property array{id: string} $transaction
 */
class NewWebhookRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [];
    }
}
