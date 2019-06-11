<?php 

$router->post('api/get_json_sample', [
	'uses' => 'App\Http\Controllers\Api\ApiTestController@getJsonSample',
	'as' => 'api.get_json_sample.get',
	'middleware' => ['logger', 'ua', 'web.nocsrf', 'api.logger', 'append', 'auth:api', 'maintenance', 'token'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('api', [
	'uses' => 'App\Http\Controllers\Api\AuthController@index',
	'as' => 'api.get',
	'middleware' => [],
	'where' => [],
	'domain' => NULL,
]);

$router->post('api/auth', [
	'uses' => 'App\Http\Controllers\Api\AuthController@getUserInfo',
	'as' => 'api.auth.get',
	'middleware' => [],
	'where' => [],
	'domain' => NULL,
]);

$router->get('api/invite/{invite_code}', [
	'uses' => 'App\Http\Controllers\Api\InviteController@validateInviteCode',
	'as' => 'api.invite.get',
	'middleware' => [],
	'where' => [],
	'domain' => NULL,
]);

$router->post('api/instructors', [
	'uses' => 'App\Http\Controllers\Api\UserMasterController@getInstructors',
	'as' => 'api.instructors.get',
	'middleware' => [],
	'where' => [],
	'domain' => NULL,
]);

$router->get('api/apple_music', [
	'uses' => 'App\Http\Controllers\Api\MusicController@getMusicPlaylist',
	'as' => 'api.apple_music.get',
	'middleware' => [],
	'where' => [],
	'domain' => NULL,
]);

$router->post('api/reservation_modal', [
	'uses' => 'App\Http\Controllers\Api\ReservationModalController@reservationModalApi',
	'as' => 'api.reservation_modal.post',
	'middleware' => [],
	'where' => [],
	'domain' => NULL,
]);
