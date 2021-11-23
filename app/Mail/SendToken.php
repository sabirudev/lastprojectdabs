<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendToken extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    /* 
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        //
        $this->data = $data;
    }

    /*
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = str_replace("https://", "", url('/'));
        return $this->from('no-reply@' . $email)->subject('DABS-TRUFFLE BELLY AKSES GAME')
            ->view('blasting_email')->with($this->data);
    }
}
