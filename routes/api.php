<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/
Route::group(['middleware' => 'api'], function () {
    Route::post('/get_json_sample', 'Api\ApiTestController@getJsonSample')->name('get_json_sample');
//    Route::post('/auth', 'Api\AuthController@getUserInfo')->name('api.auth.get');
//    Route::get('/invite/{invite_code}', 'Api\InviteController@validateInviteCode')->name('api.invite.get');
});
