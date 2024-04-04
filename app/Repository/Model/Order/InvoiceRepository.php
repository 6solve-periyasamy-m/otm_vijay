<?php

namespace App\Repository\Model\Order;

use App\Models\Order\Invoice\Invoice;
use App\Repository\Storage\Invoice\QuantityBillable;
use Illuminate\Support\Collection;
use Spatie\Browsershot\Browsershot;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InvoiceRepository
{
    public readonly Invoice $invoice;

    /**
     * @param Invoice $invoice
     */
    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function getResponseStream(): StreamedResponse
    {
        $invoice = Browsershot::html(view('pdf.invoices.columns', ['invoice' => $this->invoice,])->render())->noSandbox();
        $invoice->showBackground()->margins(10, 2, 10, 2);
        return response()->stream(function () use ($invoice) { echo $invoice->pdf(); }, 200, ['Content-Type' => 'application/pdf']);
    }

    /**
     * @return Collection<string, QuantityBillable>
     */
    public function getItemsByQuantity(): Collection
    {
        $data = collect();
        foreach ($this->invoice->customers as $customer) {
            foreach ($customer->billables as $billable) {
                $qBillable = $data->get($billable->shared_key, new QuantityBillable($billable->description, $billable->shared_key, $billable->amount, $billable->is_base));
                $data->put($billable->shared_key, $qBillable->addQuantity());
            }
        }
        foreach ($this->invoice->groups as $group) {
            foreach ($group->billables as $billable) {
                $qBillable = $data->get($billable->shared_key, new QuantityBillable($billable->description, $billable->shared_key, $billable->amount, $billable->is_base));
                $data->put($billable->shared_key, $qBillable->addQuantity());
            }
        }
        return $data;
    }
}
