<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TemplatedMailable extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var string
     */
    private $body;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $body)
    {
        $this->body = $body;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): TemplatedMailable
    {
        return $this->view('mail.templated', ['content' => $this->body,]);
    }
}
