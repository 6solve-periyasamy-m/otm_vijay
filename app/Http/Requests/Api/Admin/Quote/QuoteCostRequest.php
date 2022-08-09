<?php

namespace App\Http\Requests\Api\Admin\Quote;

use App\Http\Requests\Api\Admin\AuthorizedRequest;

/**
 * @property int $paying
 * @property int $travelling
 */
class QuoteCostRequest extends AuthorizedRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'paying' => 'required|numeric|min:0',
            'travelling' => 'required|numeric|min:0'
        ];
    }
}
