<?php

namespace App\Http\Requests\Admin\Quote;

use App\Models\Customer\Customer;
use App\Repository\Storage\ConvertedCustomer;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property int[] $travelling
 * @property int[] $paying
 */
class ConversionRequest extends FormRequest
{
    /**
     * @return ConvertedCustomer[]
     */
    public function getCustomers(): array
    {
        $customers = [];
        foreach ($this->travelling as $id) {
            $customers[] = new ConvertedCustomer(Customer::find($id), false, true);
        }
        foreach ($this->paying as $id) {
            $customers[] = new ConvertedCustomer(Customer::find($id), true, true);
        }
        return $customers;
    }

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
