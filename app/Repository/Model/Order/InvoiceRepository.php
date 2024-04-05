<?php

namespace App\Repository\Model\Order;

use App\Models\Order\Invoice\Invoice;
use Spatie\Browsershot\Browsershot;
use Symfony\Component\HttpFoundation\StreamedResponse;

use Dompdf\Dompdf;
use Dompdf\Options;
use PDF;

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

    // public function getResponseStream(): StreamedResponse
    // {
    //     $invoice = Browsershot::html(view('pdf.invoices.columns', ['invoice' => $this->invoice,])->render())->noSandbox();
    //     $invoice->showBackground()->margins(10, 2, 10, 2);
    //     return response()->stream(function () use ($invoice) { echo $invoice->pdf(); }, 200, ['Content-Type' => 'application/pdf']);
    // }

    public function getResponseStream()
    {
        $invoice = $this->invoice;
        $html = view('pdf.invoices.tax_invoice', compact('invoice'))->render();

        // Create options for Dompdf
        $options = new Options();
        $options->set('dpi', 96);
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->setPaper('A4', 'portrait');
        
        $dompdf->loadHtml($html);

        // Render the PDF
        $dompdf->render();

        // Output PDF content as base64 encoded string
        $pdfContent = base64_encode($dompdf->output());

        // Pass the PDF content to the view
        return view('pdf.dom_pdf_preview', compact('pdfContent'));
    }
}
