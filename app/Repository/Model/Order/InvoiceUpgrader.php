<?php

namespace App\Repository\Model\Order;

use App\Models\Order\Invoice\Invoice;
use Illuminate\Console\OutputStyle;

class InvoiceUpgrader
{
    const LATEST_VERSION = 2;

    public static function upgradeAll(OutputStyle|null $style = null): void
    {
        $invoices = Invoice::where('generator_version', '<', self::LATEST_VERSION)->get();
        $bar = $style?->createProgressBar($invoices->count());
        foreach ($invoices as $invoice) {
            $invoice = self::version_1_to_2($invoice);
            $bar->advance();
        }
        $bar->finish();
    }

    public static function version_1_to_2(Invoice $invoice): Invoice
    {
        if ($invoice->generator_version !== 1) return $invoice;
        $installmentTotal = 0;
        foreach ($invoice->installments as $installment) {
            $paymentTotal = 0;
            $installmentTotal += $installment->amount;
            foreach ($invoice->payments as $payment) {
                $paymentTotal += $payment->amount;
                if ($paymentTotal >= $installmentTotal) {
                    $installment->paid_on = $payment->date;
                    $installment->save();
                    break;
                }
            }
            if ($installment->paid_on === null) {
                break;
            }
        }
        $invoice->generator_version = 2;
        $invoice->save();
        return $invoice;
    }
}