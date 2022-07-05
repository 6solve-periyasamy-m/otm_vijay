<?php

namespace App\Repository\Model\Order;

use App\Models\Order\Order;
use Exception;
use Illuminate\Support\Collection;
use mikehaertl\pdftk\Pdf;
use Storage;
use ZipArchive;

class AtolRepository
{
    public Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * @param Collection<Order> $orders
     * @param string $name
     * @return string|null
     */
    public static function generateAllAtolCertificates(Collection $orders, string $name): ?string
    {
        Storage::makeDirectory('uploads/atol');
        while (true) {
            try {
                $filename = str_replace(' ', '_', strtolower($name)) . '-' . now()->unix();
                $directory = 'public/' . $filename;
                if (Storage::exists($directory)) continue;
                Storage::makeDirectory($directory);
                break;
            } catch (Exception) {
                continue;
            }
        }
        foreach ($orders as $order) {
            if ($order->cancelled) continue;
            if (!$order->has_atol) continue;
            $atol = $order->repository->getAtolRepository()->generateAtolCertificate();
            $saved = $atol->saveAs(Storage::path($directory) . '/' . $order->booking_reference . '.pdf');
            if (!$saved) {
                dd($atol->getError());
            }
        }
        $zip = new ZipArchive();
        if ($zip->open(Storage::path('uploads/atol/' . $filename . '.zip'), ZipArchive::CREATE) === true) {
            foreach (Storage::files($directory) as $file) {
                $exploded = explode('/', $file);
                $zip->addFile(Storage::path($file), trim(end($exploded)));
            }
            $zip->close();
            Storage::deleteDirectory($directory);
            return asset('uploads/atol/' . $filename . '.zip');
        } else
            return null;
    }

    public function showAtolCertificate(): bool
    {
        return $this->generateAtolCertificate()->send();
    }

    public function generateAtolCertificate(): Pdf
    {
        $data = $this->generateFlightList();
        $protected = $data['normal'];
        $excess = $data['excess'];
        $pdf = new Pdf(Storage::path('templates/' . (empty($excess) ? 'atol-template.pdf' : 'atol-template-excess.pdf')));
        $pdf->fillForm([
            'companyName' => setting('company.name'),
            'companyName2' => setting('company.name'),
            'issuerName' => setting('atol.issuer'),
            'issueDate' => f_date($this->order->ordered_on),
            'atolNumber' => setting('atol.number'),
            'reference' => $this->order->booking_reference,
            'customerNames' => $this->order->customer_names,
            'customerCount' => $this->order->customer_count,
            'protected' => $protected,
            'excess' => $excess,
        ])->flatten();

        return $pdf;
    }

    public function generateFlightList(): array
    {
        $inbound = [];
        $outbound = [];
        foreach ($this->order->orderCustomers as $orderCustomer) {
            foreach ($orderCustomer->orderFlights as $orderFlight) {
                if ($orderFlight->tourComponent->flight_type == 'Inbound') {
                    $inbound[$orderFlight->tourComponent->id] = $orderFlight->tourComponent;
                } elseif ($orderFlight->tourComponent->flight_type == 'Outbound') {
                    $outbound[$orderFlight->tourComponent->id] = $orderFlight->tourComponent;
                }
            }
        }
        $string = '';
        $excessString = '';
        $excess = 3 + (count($outbound) < 3 ? 3 - count($outbound) : 0);
        foreach ($inbound as $tourComponent) {
            if ($excess > 0) {
                $string .= $tourComponent->atol_string . "\n";
                $excess--;
            } else {
                $excessString .= $tourComponent->atol_string . "\n";
            }
        }
        $excess += 3;
        foreach ($outbound as $tourComponent) {
            if ($excess > 0) {
                $string .= $tourComponent->atol_string . "\n";
                $excess--;
            } else {
                $excessString .= $tourComponent->atol_string . "\n";
            }
        }
        return ['normal' => $string, 'excess' => $excessString,];
    }
}
