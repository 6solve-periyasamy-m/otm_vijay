<?php

namespace App\Http\Requests\Gateway\Felloh;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property int $eventTimestamp
 * @property string $eventType
 * @property string $paymentId
 * @property string $transactionId
 * @property string $resourceType
 */
class WebhookRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
