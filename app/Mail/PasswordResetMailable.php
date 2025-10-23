<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetMailable extends Mailable
{
    use Queueable, SerializesModels;

    private string $email;
    private string $token;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $email, string $token)
    {
        $this->email = $email;
        $this->token = $token;
    }

    public function build()
    {
        return $this->subject($this->subject)->view('mail.reset-password', ['reset' => route('password.get-new', ['token' => $this->token, 'email' => $this->email,]),]);
    }

}
