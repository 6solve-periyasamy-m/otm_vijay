<?php

namespace App\Repository\Model\Order;

use App\Models\Order\Component\OrderAccommodation;
use App\Models\Order\Invoice\Invoice;
use App\Models\Order\Invoice\InvoiceAdjustment;
use App\Models\Order\Invoice\InvoiceBillable;
use App\Models\Order\Invoice\InvoiceBrand;
use App\Models\Order\Invoice\InvoiceCustomer;
use App\Models\Order\Invoice\InvoiceGroup;
use App\Models\Order\Invoice\InvoiceInstallment;
use App\Models\Order\Invoice\InvoicePayment;
use App\Models\Order\Order;

class InvoiceGenerator
{
    private Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function generate(bool $save = false): Invoice
    {
        $invoice = new Invoice([
            'order_id' => $this->order->id,
            'name' => $this->order->tour->name,
            'cancelled' => $this->order->cancelled,
            'invoice_number' => $this->order->invoices()->count() + 1,
            'booking_reference' => $this->order->booking_reference,
            'generated' => now(),
            'invoice_footer' => $this->order->invoice_footer,
            'order_notes' => $this->order->external_notes,
            'total_paid' => $this->order->paid,
            'total_cost' => $this->order->total,
            'tax_name' => $this->order->taxBracket()?->name,
            'tax_amount' => $this->order->getTaxes(),
        ]);
        return $save ? $this->generateSaved($invoice) : $this->generateTemporary($invoice);
    }

    private function generateSaved(Invoice $invoice): Invoice
    {
        $brand = $this->generateBrand();
        $brand->save();
        $invoice->invoice_brand_id = $brand->id;
        $invoice->save();
        $invoice->customers()->saveMany($this->generateCustomers($invoice->id));
        $invoice->groups()->saveMany($this->generateGroups($invoice->id));
        $invoice->adjustments()->saveMany($this->generateAdjustments());
        $invoice->installments()->saveMany($this->generateInstallments());
        $invoice->payments()->saveMany($this->generatePayments());
        return $invoice;
    }

    private function generateTemporary(Invoice $invoice): Invoice
    {
        $customers = $this->generateCustomers();
        $lead = null;
        foreach ($customers as $customer) { if ($customer->lead) { $lead = $customer; break; }}
        $invoice->setRelations([
            'brand' => $this->generateBrand(),
            'customers' => $customers,
            'lead' => $lead,
            'groups' => $this->generateGroups(),
            'adjustments' => $this->generateAdjustments(),
            'installments' => $this->generateInstallments(),
            'payments' => $this->generatePayments(),
        ]);
        return $invoice;
    }

    private function generateBrand(): InvoiceBrand
    {
        $brand = $this->order->tour->brand;

        return InvoiceBrand::make([
            'name' => $brand->name,
            'website' => $brand->url,
            'email' => $brand->name,
            'telephone' => $brand->name,
            'address_line_1' => $brand->address->address_line_1,
            'address_line_2' => $brand->address->address_line_2,
            'town' => $brand->address->town,
            'region' => $brand->address->region,
            'country' => $brand->address->country->name,
            'postcode' => $brand->address->postcode,
            'vat_code' => setting('company.vat'),
            'logo' => $brand->image_path,
            'header_image' => null, // Will be implemented in future
            'footer_image' => null, // Will be implemented in future
        ]);
    }

    /**
     * @return InvoiceInstallment[]
     */
    private function generateInstallments(): array
    {
        $travellers = $this->order->orderCustomers()->count();
        $data = [];
        if ($this->order->booking_fee > 0) {
            $data[] = new InvoiceInstallment([
                'due' => $this->order->ordered_on,
                'description' => __('invoice.installment.booking-fee', ['amount' => f_currency($this->order->booking_fee)]),
                'amount' => $this->order->booking_fee,
                'paid' => $this->order->booking_fee < $this->order->paid,
            ]);
        }
        if ($this->order->deposit > 0) {
            $data[] = new InvoiceInstallment([
                'due' => $this->order->ordered_on,
                'description' =>
                    __('invoice.installment.deposit.' . ($travellers > 1 ? 'multiple' : 'single'), [
                        'amount' => f_currency($this->order->deposit),
                        'travellers' => $travellers,
                        'total' => f_currency($this->order->calculated_deposit)
                    ]),
                'amount' => $this->order->calculated_deposit,
                'paid' => $this->order->deposit_paid,
            ]);
        }
        foreach ($this->order->repository->getInstallments() as $installment) {
            $data[] = new InvoiceInstallment([
                'due' => $installment->due_on,
                'description' =>
                    __('invoice.installment.installment.' . ($travellers > 1 ? 'multiple' : 'single'), [
                        'amount' => f_currency($installment->amount),
                        'travellers' => $travellers,
                        'total' => f_currency($installment->calculated_amount)
                    ]),
                'amount' => $installment->calculated_amount,
                'paid' => $installment->repository->getRemainingAmount() <= 0,
            ]);
        }
        if ($this->order->remaining_installment > 0) {
            $data[] = new InvoiceInstallment([
                'due' => $this->order->tour->final_payment,
                'description' => __('invoice.installment.remaining', ['amount' => f_currency($this->order->remaining_installment),]),
                'amount' => $this->order->remaining_installment,
                'paid' => $this->order->paid >= $this->order->total,
            ]);
        }
        return $data;
    }

    /**
     * @return InvoicePayment[]
     */
    private function generatePayments(): array
    {
        $data = [];
        foreach ($this->order->payments as $payment) {
            $data[] = new InvoicePayment([
                'date' => $payment->paid_on,
                'payee' => $payment->customer?->full_name,
                'amount' => $payment->amount,
                'method' => $payment->paymentMethod->name,
            ]);
        }
        return $data;
    }

    /**
     * @return InvoiceAdjustment[]
     */
    private function generateAdjustments(): array
    {
        $data = [];
        foreach ($this->order->adjustments as $adjustment) {
            $data[] = new InvoiceAdjustment([
                'date' => $adjustment->date,
                'amount' => $adjustment->amount,
                'description' => $adjustment->reason,
            ]);
        }
        return $data;
    }

    /**
     * @param int|null $invoice
     * @return InvoiceCustomer[]
     */
    private function generateCustomers(int|null $invoice = null): array
    {
        $data = [];
        foreach ($this->order->orderCustomers as $orderCustomer)
        {
            $customer = new InvoiceCustomer([
                'invoice_id' => $invoice,
                'lead' => $this->order->lead_booker_id === $orderCustomer->id,
                'full_name' => $orderCustomer->customer_name,
                'address_line_1' => $orderCustomer->customer->billingAddress->address_line_1,
                'address_line_2' => $orderCustomer->customer->billingAddress->address_line_2,
                'town' => $orderCustomer->customer->billingAddress->town,
                'region' => $orderCustomer->customer->billingAddress->region,
                'country' => $orderCustomer->customer->billingAddress->country->name,
                'postcode' => $orderCustomer->customer->billingAddress->postcode,
            ]);
            $total = $orderCustomer->tour_cost;
            $billables = [
                new InvoiceBillable([
                    'description' => __('invoice.customer.billable.base'),
                    'amount' => $orderCustomer->tour_cost,
                    'shared_key' => 'base-components'
                ]),
            ];

            if ($this->order->lead_booker_id === $orderCustomer->id) {
                if ($this->order->commission_amount !== null) {
                    $billables[] = new InvoiceBillable([
                        'description' => "Commission: {$this->order->commission}%",
                        'shared_key' => 'commission',
                        'amount' => $this->order->commission_amount,
                    ]);
                }
            }
            if ($orderCustomer->has_surcharge) {
                $billables[] = new InvoiceBillable([
                    'description' => __('invoice.customer.billable.surcharge'),
                    'amount' => $orderCustomer->single_occupancy_surcharge,
                    'shared_key' => "surcharge"
                ]);
                $total += $orderCustomer->single_occupancy_surcharge;
            }
            foreach ($orderCustomer->repository->getComponents(false, true, true, true, true, ['Add-on', 'Upgrade']) as $component) {
                $billable = $component->getInvoiceBillable();
                $total += $billable->amount;
                $billables[] = $billable;

            }
            foreach ($orderCustomer->adjustments as $adjustment) {
                $billables[] = new InvoiceBillable([
                    'description' => $adjustment->reason,
                    'amount' => $adjustment->amount,
                    'shared_key' => "adjustment-{$adjustment->id}"
                ]);
                $total += $adjustment->amount;
            }
            $customer->total_cost = $total;
            if ($invoice !== null) {
                $customer->save();
                $customer->billables()->saveMany($billables);
            } else {
                $customer->setRelation('billables', $billables);
            }
            $data[] = $customer;
        }
        return $data;
    }

    /**
     * @return InvoiceGroup[]
     */
    private function generateGroups(int|null $invoice = null): array
    {
        $data = [];
        foreach ($this->order->groups as $group) {
            $iGroup = new InvoiceGroup([
                'invoice_id' => $invoice,
                'name' => __('invoice.group.name', ['members' => $group->getMembers(),]),
            ]);
            $billables = [];
            $total = 0;
            /** @var OrderAccommodation $room */
            foreach ($group->rooms()->whereHas('tourComponent', function ($query) {
                return $query->where('tour_component_type', '=', 'Upgrade')->orWhere('tour_component_type', '=', 'Add-on');
            })->get() as $room) {
                $billable = $room->repository->getInvoiceBillable();
                $total += $room->tour_sales_price;
                $billables[] = $billable;
            }
            $iGroup->total_cost = $total;
            if (sizeof($billables) > 0) {
                if ($invoice !== null) {
                    $iGroup->save();
                    $iGroup->billables()->saveMany($billables);
                } else {
                    $iGroup->setRelation('billables', $billables);
                }
                $data[] = $iGroup;
            }
        }
        return $data;
    }
}