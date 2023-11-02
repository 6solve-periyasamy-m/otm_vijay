<?php

namespace App\Http\Requests\Gateway\Opayo;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $VPSProtocol
 * @property string $TxType
 * @property string $VendorTxCode Payment Intention ID
 * @property string $Status Valid good responses: OK
 * @property string $StatusDetail Details of the status, should be logged if not Okay
 * @property string $Token Opayo Token
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
