<?php

namespace App\Http\Requests\Api\Admin\Quote;

use App\Http\Requests\Api\Admin\AuthorizedRequest;

/**
 * @property int $count
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
            'count' => 'required|numeric|min:1',
        ];
    }
}
