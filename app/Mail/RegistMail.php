<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegistMail extends Mailable
{
    use Queueable, SerializesModels;

    const SUBJECT = '登録認証メール';  //  TODO    constの置き場に移動
    const FROM = 'kawai.toshifumi@xchange.jp';  //  TODO    constの置き場に移動


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
        
        $authUrl = 
        
        return $this->from(self::FROM)
                    ->subject(self::SEBJECT)
                    ->view('mails.regist_mail')
                    ->with(['authUrl' => $authUrl]);
    }
}
