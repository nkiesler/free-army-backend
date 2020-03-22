<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $verify_link;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $verify_link)
    {
        $this->user = $user;
        $this->verify_link = $verify_link;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('verification');
    }
}
