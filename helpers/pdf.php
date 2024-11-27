<?php

use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Contracts\View\Factory;
use Spatie\Browsershot\Browsershot;
use Symfony\Component\HttpFoundation\StreamedResponse;

if(!function_exists('puppeteer')) {
    /**
     * @param \Illuminate\Contracts\View\View|Factory $view
     * @param bool $response Should it be a streamed response, or just formatted for other uses
     * @return StreamedResponse|string
     */
    function puppeteer(\Illuminate\Contracts\View\View|Factory $view, bool $response = true): StreamedResponse|string
    {
        $invoice = Browsershot::html($view->render());
        $invoice->showBackground()->margins(10, 2, 10, 2);
        if (!$response) return $invoice->pdf();
        return response()->stream(function () use ($invoice) { echo $invoice->pdf(); }, 200, ['Content-Type' => 'application/pdf']);
    }
}

if(!function_exists('dompdf')) {
    /**
     * @param \Illuminate\Contracts\View\View|Factory $view
     * @param bool $response Should it be a streamed response, or just formatted for other uses
     * @return StreamedResponse|string
     */
    function dompdf(\Illuminate\Contracts\View\View|Factory $view, bool $response = true): StreamedResponse|string
    {
        // $html = $view->render();   
        // if (!$response) return $html;
        // return response()->stream(function () use ($html) { echo $html; }, 200, ['Content-Type' => 'text/html']);
        $dompdf = new Dompdf((new Options())->set('dpi', 96)->set('isHtml5ParserEnabled', true));
        $dompdf->setPaper('A4', 'portrait');

        $dompdf->loadHtml($view->render());
        $dompdf->render();

        if (!$response) return $dompdf->output();

        return response()->stream(function () use ($dompdf) { echo $dompdf->output(); }, 200, ['Content-Type' => 'application/pdf']);
    }
}

