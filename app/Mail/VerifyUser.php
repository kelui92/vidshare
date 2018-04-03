<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class VerifyUser extends Mailable
{
    use Queueable, SerializesModels;

    public $name;

    public $authorizationCode;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $authorizationCode)
    {
        //
        $this->name = $name;
        $this->authorizationCode = $authorizationCode;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.verify');
    }
}
