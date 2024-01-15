<?php

namespace App\Repository\Model\Order;

use App\Models\Order\Invoice\Invoice;
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
}
