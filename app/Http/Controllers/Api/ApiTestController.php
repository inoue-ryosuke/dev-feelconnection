<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiTestController extends Controller
{
    
    public function getJsonSample(Request $request)
    {
        $response = [
            'name' => 'sample',
            'parameters' => [
                'param1' => '1',
                'param2' => ['p' => '2']
            ]
        ];
        
        return response()->json($response);
    }
    
}
