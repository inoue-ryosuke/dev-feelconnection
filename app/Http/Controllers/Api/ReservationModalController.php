<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * 予約モーダルコントローラー
 *
 */
class ReservationModalController extends Controller
{

    /**
     * 予約モーダルAPI
     *
     * @POST("api/reservation_modal", as="api.reservation_modal.post")
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reservationModalApi(Request $request)
    {
        $response = [ 'test' => 100 ];

        return response()->json($response);
    }
}
