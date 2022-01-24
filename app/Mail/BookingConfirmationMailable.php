<?php

namespace App\Mail;

use App\Models\Order;
use App\Repository\MailRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingConfirmationMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $body = MailRepository::getBookingConfirmationBody($this->order);
        return $this->view('mail.templated', ['content' => $body,]);
    }
}
