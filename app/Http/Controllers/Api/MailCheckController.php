<?php

namespace App\Http\Controllers\Api;

use App\Libraries\Logic\Loader;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

use App\Models\RegistAuth;

class MailCheckController extends ApiController
{
    //
    use ApiLogicTrait;
    
    /**
     * @POST("api/mailcheck/regist", as="api.mailcheck.regist.post")
     * @param Request $request
     * @return
     */
    public function regist(Request $request)
    {
    
        logger(__CLASS__.':'.__METHOD__.' start');
        logger($request->all());

        $type = $request->input('type');
        $payload = $request->getContent();
        $payload = json_decode($payload, true);
        $response = $this->getMailCheckSelectLogic()->validateMailAddress(RegistAuth::IS_REGIST, $payload);
        
        logger(__CLASS__.':'.__METHOD__.' end');
        return response()->json($response);
    }
    
    /**
     * @POST("api/mailcheck/passwdreset", as="api.mailcheck.passwdreset.post")
     * @param Request $request
     * @return
     */
    public function passwdReset(Request $request)
    {
    
        logger(__CLASS__.':'.__METHOD__.' start');
        logger($request->all());

        $type = $request->input('type');
        $payload = $request->getContent();
        $payload = json_decode($payload, true);
        $response = $this->getMailCheckSelectLogic()->validateMailAddress(RegistAuth::IS_PASSWD, $payload);
        
        logger(__CLASS__.':'.__METHOD__.' end');
        return response()->json($response);
    }
    
    /**
     * @POST("api/mailcheck/mailreset", as="api.mailcheck.mailreset.post")
     * @param Request $request
     * @return
     */
    public function mailReset(Request $request)
    {
    
        logger(__CLASS__.':'.__METHOD__.' start');
        logger($request->all());

        $type = $request->input('type');
        $payload = $request->getContent();
        $payload = json_decode($payload, true);
        $response = $this->getMailCheckSelectLogic()->validateMailAddress(RegistAuth::IS_MAILADDRESS, $payload);
        
        logger(__CLASS__.':'.__METHOD__.' end');
        return response()->json($response);
    }
    
}
