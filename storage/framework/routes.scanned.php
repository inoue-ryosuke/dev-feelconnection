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
	'middleware' => ['logger', 'ua', 'api.logger', 'append', 'maintenance'],
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
