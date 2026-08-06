<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Facture extends Mailable
{
    use Queueable, SerializesModels;
    public $body;
    public $detail;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($body, $detail)
    {
        $this->body = $body;
        $this->detail = $detail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.envoiMailWamsCo')->with('body',$this->body,'detail',$this->detail);   
    }
}