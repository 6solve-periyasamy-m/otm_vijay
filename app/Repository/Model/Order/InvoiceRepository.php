<?php

namespace App\Repository\Model\Order;

use App\Models\Customer\Group;
use App\Models\Order\Invoice;
use App\Models\Order\Order;
use App\Models\Order\OrderCustomer;
use function now;

class InvoiceRepository
{
    public Invoice $invoice;

    /**
     * @param Invoice $invoice
     */
    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * @return Invoice The invoice stored in the repository
     */
    public function get(): Invoice
    {
        return $this->invoice;
    }

    /**
     * Saves the invoice into the database
     * @return Invoice
     */
    public function save(): Invoice
    {
        $this->invoice->save();
        return $this->invoice;
    }

    /**
     * Get the invoice with a specific number, or generate a new one if it is not found
     * @param Order $order The order the invoice is for
     * @param int $number The invoice number
     * @return InvoiceRepository
     */
    public static function getInvoiceById(Order $order, int $number = 0): InvoiceRepository
    {
        $invoice = $order->invoices()->where('number', $number)->first();
        if (!isset($invoice)) $invoice = InvoiceRepository::generateInvoice($order);
        return new InvoiceRepository($invoice);
    }

    private static function generateInvoice(Order $order): Invoice
    {
        $customers = [];
        $groups = [];
        $adjustments = [];
        $payments = [];
        foreach ($order->orderCustomers as $customer) {
            $customers[$customer->customer_name] = InvoiceRepository::processCustomerComponentsForInvoice($customer);
            foreach ($customer->adjustments as $adjustment) {
                $adjustments[] = ['description' => "Customer Adjustment ({$customer->customer_name}): {$adjustment->reason}", 'cost' => $adjustment->amount,];
            }
        }
        foreach ($order->groups() as $group) {
            $groups[$group->name] = InvoiceRepository::processGroupComponentsForInvoice($group);
        }
        foreach ($order->adjustments as $adjustment) {
            $adjustments[] = ['description' => "Manual Adjustment: {$adjustment->reason}", 'cost' => $adjustment->amount,];
        }
        foreach ($order->payments as $payment) {
            $payments[] = ['date' => $payment->paid_on, 'description' => "{$payment->paymentMethod}: {$payment->payment_type}", 'cost' => $payment->amount,];
        }
        return Invoice::make([
            'order_id' => $order->id,
            'number' => $order->invoices->count() + 1,
            'generated' => now(),
            'customers' => $customers,
            'adjustments' => ['total_cost' => $order->total_adjustments, 'billables' => $adjustments,],
            'payments' => ['total_cost' => $order->paid, 'billables' => $payments,],
            'groups' => $groups,
            'installments' => self::snapshotInstallments($order),
            'footer' => $order->invoice_footer,
            'total_cost' => $order->cost + $order->total_adjustments,
        ]);
    }

    private static function processCustomerComponentsForInvoice(OrderCustomer $orderCustomer): array
    {
        $data = [];
        $totalCost = 0;
        $included = "Base Components Include:\n";
        if ($orderCustomer->has_surcharge) {
            $data[] = ['description' => 'Single Occupancy Surcharge', 'cost' => $orderCustomer->single_occupancy_surcharge,];
            $totalCost += $orderCustomer->single_occupancy_surcharge;
        }
        foreach ($orderCustomer->orderAccommodation() as $orderInventory) {
            $tourInventory = $orderInventory->tourComponent;
            if ($tourInventory->tour_component_type == 'Included') {
                $included .= $tourInventory . "\n";
            }
        }
        foreach ($orderCustomer->orderActivities as $orderInventory) {
            $tourInventory = $orderInventory->tourComponent;
            if ($tourInventory->tour_component_type == 'Included') {
                $included .= $tourInventory . "\n";
            } else {
                $data[] = ['description' => "{$tourInventory}", 'cost' => $orderInventory->cost];
                $totalCost += $orderInventory->cost;
            }
        }
        foreach ($orderCustomer->orderFlights as $orderInventory) {
            $tourInventory = $orderInventory->tourComponent;
            if ($tourInventory->tour_component_type == 'Included') {
                $included .= $tourInventory . "\n";
            } else {
                $data[] = ['description' => "{$tourInventory}", 'cost' => $orderInventory->cost];
                $totalCost += $orderInventory->cost;
            }
        }
        foreach ($orderCustomer->orderTransports as $orderInventory) {
            $tourInventory = $orderInventory->tourComponent;
            if ($tourInventory->tour_component_type == 'Included') {
                $included .= $tourInventory . "\n";
            } else {
                $data[] = ['description' => "{$tourInventory}", 'cost' => $orderInventory->cost];
                $totalCost += $orderInventory->cost;
            }
        }
        foreach ($orderCustomer->orderMerchandise as $orderInventory) {
            $tourInventory = $orderInventory->tourComponent;
            if ($tourInventory->tour_component_type == 'Included') {
                $included .= $tourInventory . "\n";
            } else {
                $data[] = ['description' => "{$tourInventory}", 'cost' => $orderInventory->cost];
                $totalCost += $orderInventory->cost;
            }
        }
        $totalCost += $orderCustomer->tour_cost;
        return ['total_cost' => $totalCost, 'billables' => array_merge([['description' => $included, 'cost' => $orderCustomer->tour_cost,]], $data),];
    }

    /**
     * @param Group $group
     * @return array
     */
    private static function processGroupComponentsForInvoice(Group $group): array
    {
        $data = [];
        $totalCost = 0;
        $name = $group->name . ': ';
        foreach ($group->orderCustomers as $orderCustomer) {
            $name .= $orderCustomer->customer_name . ', ';
        }
        foreach ($group->rooms as $orderInventory) {
            $tourInventory = $orderInventory->tourComponent;
            if ($tourInventory->tour_component_type != 'Included') {
                $data[] = ['description' => "{$tourInventory}", 'cost' => $orderInventory->cost];
                $totalCost += $orderInventory->cost;
            }
        }
        return ['total_cost' => $totalCost, 'name' => substr($name, 0, -2), 'billables' => $data,];
    }

    private static function snapshotInstallments(Order $order): array
    {
        $data = [
            [
                'due' => 'With Order',
                'description' => InvoiceRepository::buildInstallmentString('Deposit', $order, $order->deposit, $order->calculated_deposit),
                'amount' => $order->calculated_deposit,
                'paid' => $order->paid >= $order->calculated_deposit,
            ],
        ];
        foreach ($order->installments as $installment) {
            $data[] = [
                'due' => $installment->due_on,
                'description' => InvoiceRepository::buildInstallmentString('Installment', $order, $installment->amount, $installment->calculated_amount),
                'amount' => $installment->calculated_amount,
                'paid' => $installment->paid,
            ];
        }
        $data[] = [
            'due' => $order->tour->final_payment,
            'description' => 'Remaining Balance: ' . f_currency($order->remaining_installment),
            'amount' => $order->remaining_installment,
            'paid' => $order->paid >= $order->cost,
        ];
        return $data;
    }

    private static function buildInstallmentString(string $type, Order $order, float $amount, float $calculated): string
    {
        return "{$type}: {$order->customer_count} Customer" . ($order->customer_count > 1 ? 's' : '')
            . " x " . f_currency($amount) . " = " . f_currency($calculated);
    }
}
