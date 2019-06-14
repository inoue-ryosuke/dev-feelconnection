<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MailAuthController extends ApiController
{
    //
    /**
     * API-xx: 紹介URL有効性確認API
     * @GET("api/mailauth/{token}", as="api.mailauth.get")
     * @param Request $request
     * @return
     */
    public function index($token, Request $request)
    {
        
        logger(__CLASS__.':'.__METHOD__.' start');
        
        logger($token);
        logger($request->all());
        
        $subject = config('constant.mailCheck.registMailSubject', []);
        logger($subject);
        
        
        logger(__CLASS__.':'.__METHOD__.' end');
    
    }
    
}
