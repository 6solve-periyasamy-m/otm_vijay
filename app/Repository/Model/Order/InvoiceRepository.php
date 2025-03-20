<?php

namespace App\Repository\Model\Order;

use App\Models\Order\Invoice\Invoice;
use App\Models\Order\Invoice\InvoiceBrand;
use App\Repository\Storage\Invoice\QuantityBillable;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Collection;
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

    private function getPuppeteerStream(bool $asStream = true): StreamedResponse|string
    {
        return puppeteer(view('pdf.invoices.columns', ['invoice' => $this->invoice,]), $asStream);
    }

     public function getResponseStream(bool $asStream = true): StreamedResponse|string
     {
         $style = (int)setting('invoice.style', 1);
         if ($style === 1) {
             return $this->getPuppeteerStream($asStream);
         } else {
             /** @noinspection PhpMatchExpressionWithOnlyDefaultArmInspection Will have more expressions in future, but not at the moment */
             return match ($style) {
                 default => $this->getDomPDFStream($asStream),
             };
         }
     }

    public function getDomPDFStream(bool $asStream = true, string $view = 'pdf.invoices.tax_invoice'): StreamedResponse|string
    {
        $dompdf = new Dompdf((new Options())->set('dpi', 96)->set('isHtml5ParserEnabled', true));
        $dompdf->setPaper('A4', 'portrait');

        $dompdf->loadHtml(view($view, ['invoice' => $this->invoice,])->render());
        $dompdf->render();

        return dompdf(view($view, ['invoice' => $this->invoice,]), $asStream);
    }

    /**
     * @return Collection<string, QuantityBillable>
     */
    public function getItemsByQuantity(): Collection
    {
        $data = collect();
        $accom_data = collect();
        foreach ($this->invoice->customers as $customer) {
            foreach ($customer->billables as $billable) {
                $qBillable = $data->get($billable->shared_key, new QuantityBillable($billable->description, $billable->shared_key, $billable->amount, $billable->is_base));
                $data->put($billable->shared_key, $qBillable->addQuantity());
                if (strpos($billable->shared_key, "transport") !== false) {
                    $billable->description = $this->transportDescriptionFormat($billable->description);
                }
            }
        }
        if ($this->invoice->groups) {
            foreach ($this->invoice->groups as $group) {
                foreach ($group->billables as $billable) {
                    $qBillable = $data->get($billable->shared_key, new QuantityBillable($billable->description, $billable->shared_key, $billable->amount, $billable->is_base));
                    $data->put($billable->shared_key, $qBillable->addQuantity());
                    if (strpos($billable->shared_key, "transport") !== false) {
                        $billable->description = $this->transportDescriptionFormat($billable->description);
                    }
                }
            }
        }
        return $data;
    }

    public function transportDescriptionFormat($invoice_description)
    {
        $description = preg_replace('/\([^)]+ to [^)]+\)/', '', $invoice_description, 1);
        if (preg_match('/\((\d{2}\/\d{2}\/\d{4}) (\d{2}:\d{2}) to (\d{2}\/\d{2}\/\d{4}) (\d{2}:\d{2})\)/', $description, $matches)) {
            $start_date = $matches[1];
            $end_date = $matches[3];
            $invoice_date = ($start_date === $end_date) ? $start_date : $start_date ." to ". $end_date;
            $description = preg_replace('/\((\d{2}\/\d{2}\/\d{4}) (\d{2}:\d{2}) to (\d{2}\/\d{2}\/\d{4}) (\d{2}:\d{2})\)/', "($invoice_date)", $description);
        }
        return $description;
    }

    public function forceDelete(): void
    {
        foreach ($this->invoice->customers as $customer) {
            $customer->billables()->forceDelete();
            $customer->delete();
        }
        foreach ($this->invoice->groups as $group) {
            $group->billables()->forceDelete();
            $group->delete();
        }
        $id = $this->invoice->invoice_brand_id;
        $this->invoice->adjustments()->delete();
        $this->invoice->installments()->delete();
        $this->invoice->payments()->delete();
        $this->invoice->delete();
        InvoiceBrand::find($id)?->delete();
    }
}
