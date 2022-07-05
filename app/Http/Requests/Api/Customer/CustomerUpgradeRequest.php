<?php

namespace App\Http\Requests\Api\Customer;

use App\Repository\Abstracts\ComponentUpgradeRepository;
use App\Repository\Abstracts\OrderComponentRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property-read $model
 * @property-read $component
 * @property-read $upgrade
 */
class CustomerUpgradeRequest extends FormRequest
{
    public function getOrderComponent(): ?OrderComponentRepository
    {
        return OrderComponentRepository::getComponent($this->model, $this->component);
    }

    public function getUpgradeComponent(): ?ComponentUpgradeRepository
    {
        return ComponentUpgradeRepository::getComponent($this->model, $this->upgrade);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'model' => Rule::in(['accommodation', 'activity', 'flight', 'transport', ]),
            'component' => 'required|integer', // Cannot validate for specific model
            'upgrade' => 'required|integer', // Cannot validate for specific model
        ];
    }
}
