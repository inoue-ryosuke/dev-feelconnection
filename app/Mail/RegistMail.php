<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegistMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $mailaddress;
    protected $token;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        //
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
        $authUrl = route('api.mailauth.get', ['token' => $this->token]);
        
        return $this->from(config('constant.mailCheck.registMailFrom', []))
                    ->subject(config('constant.mailCheck.registMailSubject', []))
                    ->view('mails.regist_mail')
                    ->with(['authUrl' => $authUrl]);
    }
}
