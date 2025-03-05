<?php

namespace App\Repository\Model\Order;

use App\Models\Order\Invoice\Invoice;
use App\Models\Order\Invoice\InvoiceBrand;
use App\Repository\Storage\Invoice\QuantityBillable;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;

use Carbon\Carbon;

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

    private function getPuppeteerStream(): StreamedResponse
    {
        return puppeteer(view('pdf.invoices.columns', ['invoice' => $this->invoice,]));
    }

     public function getResponseStream(): StreamedResponse
     {
         $style = (int)setting('invoice.style', 1);
         if ($style === 1) {
             return $this->getPuppeteerStream();
         } else {
             /** @noinspection PhpMatchExpressionWithOnlyDefaultArmInspection Will have more expressions in future, but not at the moment */
             return match ($style) {
                 default => $this->getDomPDFStream(),
             };
         }
     }

    public function getDomPDFStream(string $view = 'pdf.invoices.tax_invoice'): StreamedResponse
    {
        $dompdf = new Dompdf((new Options())->set('dpi', 96)->set('isHtml5ParserEnabled', true));
        $dompdf->setPaper('A4', 'portrait');

        $dompdf->loadHtml(view($view, ['invoice' => $this->invoice,])->render());
        $dompdf->render();

        return dompdf(view($view, ['invoice' => $this->invoice,]));
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
                    $qBillable = $accom_data->get($billable->shared_key, new QuantityBillable($billable->description, $billable->shared_key, $billable->amount, $billable->is_base));
                    $accom_data->put($billable->shared_key, $qBillable->addQuantity());
                    if (strpos($billable->shared_key, "transport") !== false) {
                        $billable->description = $this->transportDescriptionFormat($billable->description);
                    }
                }
            }
            $accom_data = $this->mergeBillablesByQuantity($accom_data);
        }
        $merged_data = $data->merge($accom_data);
        return $merged_data;
    }
    
    public function mergeBillablesByQuantity(Collection $billables): Collection
    {
        $merged = collect();
        $billables = $billables->sortBy(function ($billable) {
            preg_match('/(\d{2}\/\d{2}\/\d{4} \d{2}:\d{2}) to (\d{2}\/\d{2}\/\d{4} \d{2}:\d{2})/', $billable->description, $matches);
            return \Carbon\Carbon::createFromFormat('d/m/Y H:i', $matches[1]);
        });
        $grouped_billables = $billables->groupBy(function ($billable) {
            preg_match('/^(.*?)\((.*? to .*?)\) \((.*)\)$/', $billable->description, $matches);
            return "{$matches[1]}|{$matches[3]}";
        });
        foreach ($grouped_billables as $groupKey => $items) {
            $adjusted_items = collect();
            $total_processed_quantity = 0;

            $dates = $items->map(function ($item) {
                preg_match('/to (\d{2}\/\d{2}\/\d{4} \d{2}:\d{2})/', $item->description, $matches);
                return isset($matches[1]) ? \Carbon\Carbon::createFromFormat('d/m/Y H:i', $matches[1]) : null;
            });
            $max_end_date = $dates->filter()->max();
            foreach ($items as $index => $billable) {
                preg_match('/(\d{2}\/\d{2}\/\d{4} \d{2}:\d{2}) to (\d{2}\/\d{2}\/\d{4} \d{2}:\d{2})/', $billable->description, $matches);
                $start_date = \Carbon\Carbon::createFromFormat('d/m/Y H:i', $matches[1]);
                $end_date = \Carbon\Carbon::createFromFormat('d/m/Y H:i', $matches[2]);

                $current_quantity = $billable->getQuantity() - $total_processed_quantity;
                if ($current_quantity <= 0) continue;

                if ($index === 0) {
                    $new_start_date = $start_date;
                } else {
                    $new_start_date = $items[$index - 1]->description ? $start_date : $start_date;
                }

                $formatted_start_date = $new_start_date->format('d/m/Y H:i');
                $formatted_end_date = $max_end_date->format('d/m/Y H:i');

                $new_description = preg_replace('/\(\d{2}\/\d{2}\/\d{4} \d{2}:\d{2} to \d{2}\/\d{2}\/\d{4} \d{2}:\d{2}\)/',
                    "($formatted_start_date to $formatted_end_date)",
                    $billable->description
                );

                $newBillable = clone $billable;
                $newBillable->setQuantity($current_quantity);
                $newBillable->description = $new_description;

                $adjusted_items->push($newBillable);
                $total_processed_quantity += $current_quantity;
            }

            $merged = $merged->merge($adjusted_items);
        }

        return $merged->values();
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
