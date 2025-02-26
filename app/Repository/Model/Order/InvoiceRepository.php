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

    public function mergeBillablesByQuantity(Collection $items): Collection
    {
        $merged_items = collect();
        $previous_item = null;

        foreach ($items->sortBy(fn($item) => $this->extractCheckInDate($item->description)) as $item) {
            if ($previous_item && $this->canMerge($previous_item, $item)) {
                preg_match('/\((\d{2}\/\d{2}\/\d{4}) (\d{2}:\d{2}) to (\d{2}\/\d{2}\/\d{4}) (\d{2}:\d{2})\)/', $previous_item->description, $prev_matches);
                preg_match('/\((\d{2}\/\d{2}\/\d{4}) (\d{2}:\d{2}) to (\d{2}\/\d{2}\/\d{4}) (\d{2}:\d{2})\)/', $item->description, $curr_matches);

                if ($prev_matches && $curr_matches) {
                    $new_description = str_replace($prev_matches[0], "({$prev_matches[1]} {$prev_matches[2]} to {$curr_matches[3]} {$curr_matches[4]})", $previous_item->description);
                    $previous_item->description = $new_description;
                    $previous_item->setQuantity($item->getQuantity());
                }
            } else {
                if ($previous_item) {
                    $merged_items->push($previous_item);
                }
                $previous_item = clone $item;
            }
        }

        if ($previous_item) {
            $merged_items->push($previous_item);
        }
        return $merged_items;
    }

    /**
     * Checks if two billable items can be merged.
     */
    private function canMerge($item1, $item2): bool
    {
        return $this->extractHotelName($item1->description) === $this->extractHotelName($item2->description)
            && $this->extractRoomType($item1->description) === $this->extractRoomType($item2->description)
            && $this->extractCheckOutDate($item1->description) === $this->extractCheckInDate($item2->description);
    }

    /**
     * Extract hotel name from description.
     */
    private function extractHotelName($description): string
    {
        return trim(explode("(", $description)[0]);
    }

    /**
     * Extract check-in date from description.
     */
    private function extractCheckInDate($description): ?string
    {
        if (preg_match('/\((\d{2}\/\d{2}\/\d{4}) \d{2}:\d{2} to/', $description, $matches)) {
            return $matches[1];
        }
        return null;
    }

    /**
     * Extract check-out date from description.
     */
    private function extractCheckOutDate($description): ?string
    {
        if (preg_match('/to (\d{2}\/\d{2}\/\d{4}) \d{2}:\d{2}\)/', $description, $matches)) {
            return $matches[1];
        }
        return null;
    }

    /**
     * Extract room type from description.
     */
    private function extractRoomType($description): string
    {
        if (preg_match('/\)([^)]+)$/', $description, $matches)) {
            return trim($matches[1]);
        }
        return '';
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
