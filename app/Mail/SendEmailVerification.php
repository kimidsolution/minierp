<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmailVerification extends Mailable
{
    use Queueable, SerializesModels;

    protected $url;
    protected $email;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($url, $email)
    {
        $this->url = $url;
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(env('MAIL_FROM_ADDRESS'))
                    ->view('email.userverification')
                    ->with([
                        'url_verification' => $this->url,
                        'email' => $this->email,
                        'url_image' => asset('images/handshake.png')
                    ]);
    }
}
