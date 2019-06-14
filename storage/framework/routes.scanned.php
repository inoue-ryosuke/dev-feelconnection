<?php 

$router->post('api/get_json_sample', [
	'uses' => 'App\Http\Controllers\Api\ApiTestController@getJsonSample',
	'as' => 'api.get_json_sample.get',
	'middleware' => [],
	'where' => [],
	'domain' => NULL,
]);

$router->get('api', [
	'uses' => 'App\Http\Controllers\Api\AuthController@index',
	'as' => 'api.get',
	'middleware' => ['api', 'auth:customer'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('api/auth', [
	'uses' => 'App\Http\Controllers\Api\AuthController@getAuthInfo',
	'as' => 'api.auth.get',
	'middleware' => ['api', 'auth:customer'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('api/auth/user', [
	'uses' => 'App\Http\Controllers\Api\AuthController@getUserInfo',
	'as' => 'api.auth.user.get',
	'middleware' => ['api', 'auth:customer'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('api/auth/user/dm_list/update', [
	'uses' => 'App\Http\Controllers\Api\AuthController@updateUserDmList',
	'as' => 'api.auth.user.dm_list.update',
	'middleware' => ['api', 'auth:customer'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('api/auth/user/update', [
	'uses' => 'App\Http\Controllers\Api\AuthController@updateUser',
	'as' => 'api.auth.user.update',
	'middleware' => ['api', 'auth:customer'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('api/invite/{invite_code}', [
	'uses' => 'App\Http\Controllers\Api\InviteController@validateInviteCode',
	'as' => 'api.invite.get',
	'middleware' => ['api', 'guest'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('api/instructors', [
	'uses' => 'App\Http\Controllers\Api\InstructorController@getInstructors',
	'as' => 'api.instructors.get',
	'middleware' => ['api'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('api/apple_music', [
	'uses' => 'App\Http\Controllers\Api\MusicController@getMusicPlaylist',
	'as' => 'api.apple_music.get',
	'middleware' => ['api'],
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

$router->get('api/sheet_status/{sid}/{sheet_no}', [
	'uses' => 'App\Http\Controllers\Api\ReservationModalController@sheetStatusApi',
	'as' => 'api.sheet_status.get',
	'middleware' => [],
	'where' => [],
	'domain' => NULL,
]);

$router->post('api/mailcheck', [
	'uses' => 'App\Http\Controllers\Api\MailCheckController@chkMail',
	'as' => 'api.mailcheck.post',
	'middleware' => [],
	'where' => [],
	'domain' => NULL,
]);

$router->get('api/mailauth/{token}', [
	'uses' => 'App\Http\Controllers\Api\MailAuthController@index',
	'as' => 'api.mailauth.get',
	'middleware' => [],
	'where' => [],
	'domain' => NULL,
]);

$router->post('api/user/login', [
	'uses' => 'App\Http\Controllers\Auth\LoginController@login',
	'as' => 'api.user.login',
	'middleware' => ['guest', 'api'],
	'where' => [],
	'domain' => NULL,
]);
