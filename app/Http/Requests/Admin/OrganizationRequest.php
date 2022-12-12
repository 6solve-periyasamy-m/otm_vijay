<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $name
 * @property string $phone
 * @property string $email
 * @property string $internal_notes
 * @property string $external_notes
 */
class OrganizationRequest extends FormRequest
{
    public function getData(): array
    {
        return [
            'name' => $this->name,
            'contact_number' => $this->phone,
            'contact_email' => $this->email,
            'internal_notes' => $this->internal_notes,
            'external_notes' => $this->external_notes,
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'email' => 'nullable|email',
        ];
    }
}
