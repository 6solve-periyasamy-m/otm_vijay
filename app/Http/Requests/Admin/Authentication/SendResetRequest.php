<?php

namespace App\Http\Requests\Admin\Authentication;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $email
 */
class SendResetRequest extends FormRequest
{
    public function prepareForValidation(): void
    {
        $this->email = strtolower($this->email ?? "");
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'email' => 'required',
        ];
    }
}
