<?php

namespace App\Repository\Model\Quote;

use App\Models\Quote\SentQuote;
use Illuminate\Contracts\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class QuotePdfGenerator
{
    private SentQuote $quote;

    public function __construct(SentQuote $quote)
    {
        $this->quote = $quote;
    }

    private function getStyle(): int
    {
        return (int)setting('quote.style', 1);
    }

    private function isPuppeteer()
    {
        return $this->getStyle() === 1;
    }

    public function getView(): View
    {
        return match ($this->getStyle()) {
            2 =>  view('pdf.quotes.columns', ['sent' => $this->quote,]),
            default => view('pdf.quotes.columns', ['sent' => $this->quote,]),
        };
    }

    public function getResponseStream(): StreamedResponse
    {
        if ($this->isPuppeteer()) {
            return puppeteer($this->getView());
        } else {
            return dompdf($this->getView());
        }
    }

    public function getContent(): string
    {
        if ($this->isPuppeteer()) {
            return puppeteer($this->getView(), false);
        } else {
            return dompdf($this->getView(), false);
        }
    }
}