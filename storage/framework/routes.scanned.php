<?php 

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

$router->get('api/reservation_modal/{sid}', [
	'uses' => 'App\Http\Controllers\Api\ReservationModalController@reservationModalApi',
	'as' => 'api.reservation_modal.get',
	'middleware' => [],
	'where' => [],
	'domain' => NULL,
]);
