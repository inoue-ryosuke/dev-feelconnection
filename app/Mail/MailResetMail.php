<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailResetMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $mailaddress;
    protected $token;
    
    const CONFIG_KEY = 'constant.mailCheck.mailReset';
    
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
        
        return $this->from(config(self::CONFIG_KEY.'.from', []))
                    ->subject(config(self::CONFIG_KEY.'.subject', []))
                    ->view('mails.mailreset_mail')
                    ->with(['authUrl' => $authUrl]);
    }
}
