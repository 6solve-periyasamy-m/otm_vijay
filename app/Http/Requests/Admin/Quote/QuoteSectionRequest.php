<?php

namespace App\Http\Requests\Admin\Quote;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

/**
 * @property string $title
 * @property string|null $body
 * @property string $order
 * @property string $hidden
 * @property UploadedFile|null $image
 */
class QuoteSectionRequest extends FormRequest
{
    public function getData(): array
    {
        return [
            'title' => $this->title,
            'body' => $this->body,
            'hidden' => $this->hidden == 'on',
            'order' => (float)$this->order ?? 0,
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
            'title' => 'required',
            'order' => 'required|numeric',
            'image' => 'nullable|image'
        ];
    }
}
