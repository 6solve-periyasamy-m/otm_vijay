<?php

namespace App\Repository\Model\Order;

use App\Models\Order\Invoice\Invoice;
use Exception;
use Illuminate\Console\OutputStyle;
use Log;
use Settings;

class InvoiceUpgrader
{
    const LATEST_VERSION = 4;

    public static function upgradeAll(OutputStyle|null $style = null): void
    {
        $invoices = Invoice::where('generator_version', '<', self::LATEST_VERSION)->get();
        $bar = $style?->createProgressBar($invoices->count());
        $errors = "";
        foreach ($invoices as $invoice) {
            try {
                $invoice = self::version_1_to_2($invoice);
                $invoice = self::version_2_to_3($invoice);
                $invoice = self::version_3_to_4($invoice);
            } catch (Exception $e) {
                try {
                    Log::error($e);
                } catch (Exception $e) {
                    $errors .= "Failed to log error: " . $e->getMessage() . PHP_EOL;
                }
                $errors .= "Failed to refresh invoice: " . $invoice->booking_reference . PHP_EOL;
            }
            $bar->advance();
        }
        $bar->finish();
        if (!empty($errors)) {
            echo PHP_EOL . $errors;
        }
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

    public static function version_2_to_3(Invoice $invoice): Invoice
    {
        if ($invoice->generator_version !== 2) return $invoice;
        $invoice->event = $invoice->order?->tour?->event?->name;
        foreach ($invoice->customers as $customer) {
            $lastName = explode(' ', $customer->full_name);
            $firstName = array_splice($lastName, 0, round(sizeof($lastName)/2));
            $customer->first_name = implode(' ', $firstName);
            $customer->last_name = implode(' ', $lastName);
            $customer->save();
        }
        $invoice->generator_version = 3;
        $invoice->save();
        return $invoice;
    }

    public static function version_3_to_4(Invoice $invoice): Invoice
    {
        if ($invoice->generator_version !== 3) return $invoice;
        $invoice->currency_id = $invoice->order->currency_id ?? Settings::currency()->id;
        $invoice->agent_id = $invoice->order->agent_id;
        $invoice->organization_id = $invoice->order->organization_id;
        $invoice->generator_version = 4;
        $invoice->save();
        return $invoice;
    }
}