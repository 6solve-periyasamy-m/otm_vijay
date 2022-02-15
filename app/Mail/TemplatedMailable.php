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
    public function __construct(string $subject, string $body)
    {
        $this->body = $body;
        $this->subject = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): TemplatedMailable
    {
        return $this->subject($this->subject)->view('mail.templated', ['content' => $this->body,]);
    }
}
