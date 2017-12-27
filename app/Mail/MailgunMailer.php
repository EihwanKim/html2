<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailgunMailer extends Mailable
{
    use Queueable, SerializesModels;

    protected $price_info;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($price_info)
    {
        //
        $this->price_info = $price_info;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.notify')
            ->with(['price_info' => $this->price_info]);
    }
}