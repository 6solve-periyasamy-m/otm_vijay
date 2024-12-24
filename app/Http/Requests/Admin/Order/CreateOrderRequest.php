<?php

namespace App\Http\Requests\Admin\Order;

use App\Models\Customer\Customer;
use App\Models\Tour\Tour;
use App\Repository\Storage\ConvertedCustomer;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Customer\Organization;

/**
 * @property string $ordered_on
 * @property int $tour_id
 * @property int|null $organization_id
 * @property float|null $deposit
 * @property array $lead_booker
 * @property array|null $customers
 * @property string|null $internal_notes
 * @property string|null $external_notes
 * @property string|null $should_invoice
 */
class CreateOrderRequest extends FormRequest
{
    private Tour $tour;
    public function getTour(): Tour
    {
        if (empty($this->tour)) {
            /** @var Tour $tour */
            $tour = Tour::with(['accommodationInventoryTours', 'activityInventoryTours', 'flightInventoryTours', 'transportInventoryTours',])->where('id', '=', $this->tour_id)->first();
            $this->tour = $tour;
        }
        return $this->tour;
    }

    public function doEmail(): bool
    {
        return $this->should_invoice == 'on';
    }

    public function getData(): array
    {
        return [
            'ordered_on' => $this->ordered_on,
            'internal_notes' => $this->internal_notes,
            'external_notes' => $this->external_notes,
            'deposit' => $this->deposit ?? $this->getTour()->deposit_amount,
            'invoice_footer' => $this->getTour()->invoice_footer,
            'organization_id' => $this->organization_id,
            'agent_id' => $this->agent_id,
            'commission' => $this->commission,
        ];
    }

    public function getLeadBooker(): ConvertedCustomer
    {
        return new ConvertedCustomer(
            Customer::find($this->lead_booker['id']),
            isset($this->lead_booker['paying']),
            isset($this->lead_booker['travelling']),
        );
    }

    /**
     * @return ConvertedCustomer[]
     */
    public function getCustomers(): array
    {
        $customers = [];
        foreach ($this->customers ?? []  as $customer) {
            if (isset($customer['id'])) {
                $customers[] = new ConvertedCustomer(
                    Customer::find($customer['id']),
                    isset($customer['paying']),
                    isset($customer['travelling']),
                );
            }
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
            'ordered_on' => 'required|date',
            'deposit' => 'nullable|numeric|min:0',
            'tour_id' => 'required|integer|exists:tours,id',
            'lead_booker.id' => 'required|integer|exists:customers,id',
            'organization_id' => 'nullable|integer|exists:organizations,id',
            'agent_id' => 'nullable|integer|exists:agents,id',
            'commission' => 'nullable|numeric|min:0',
        ];
    }
}
