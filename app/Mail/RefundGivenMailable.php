<?php

namespace App\Mail;

use App\Models\Payment;
use App\Repository\MailRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RefundGivenMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $payment;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($payment)
    {
        $this->payment = $payment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $body = MailRepository::getRefundGivenBody($this->payment);
        return $this->view('mail.templated', ['content' => $body,]);
    }
}
