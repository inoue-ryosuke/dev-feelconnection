<?php 

$router->post('api/get_json_sample', [
	'uses' => 'App\Http\Controllers\Api\ApiTestController@getJsonSample',
	'as' => 'api.get_json_sample.get',
	'middleware' => ['logger', 'ua', 'web.nocsrf', 'api.logger', 'append', 'auth:api', 'maintenance', 'token'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('api/auth', [
	'uses' => 'App\Http\Controllers\Api\AuthController@getUserInfo',
	'as' => 'api.auth.get',
	'middleware' => ['logger', 'ua', 'append', 'maintenance', 'api'],
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
