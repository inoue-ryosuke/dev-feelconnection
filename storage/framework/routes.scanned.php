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

$router->post('api/auth/user/store', [
	'uses' => 'App\Http\Controllers\Api\AuthController@storeUser',
	'as' => 'api.auth.user.store',
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
	'middleware' => ['api', 'auth:customer'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('api/sheet_status/{sid}/{sheet_no}', [
	'uses' => 'App\Http\Controllers\Api\ReservationModalController@sheetStatusApi',
	'as' => 'api.sheet_status.get',
	'middleware' => ['api', 'auth:customer'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('api/sheet_status_extend', [
	'uses' => 'App\Http\Controllers\Api\ReservationModalController@sheetStatusExtendApi',
	'as' => 'api.sheet_status_extend.post',
	'middleware' => ['api', 'auth:customer'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('api/normal_reservation', [
	'uses' => 'App\Http\Controllers\Api\ReservationModalController@normalReservationApi',
	'as' => 'api.normal_reservation.post',
	'middleware' => ['api', 'auth:customer'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('api/sheet_change', [
	'uses' => 'App\Http\Controllers\Api\ReservationModalController@sheetChangeApi',
	'as' => 'api.sheet_change.post',
	'middleware' => ['api', 'auth:customer'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('api/reservation_cancel', [
	'uses' => 'App\Http\Controllers\Api\ReservationModalController@reservationCancelApi',
	'as' => 'api.reservation_cancel.post',
	'middleware' => ['api', 'auth:customer'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('api/mailcheck/regist', [
	'uses' => 'App\Http\Controllers\Api\MailCheckController@regist',
	'as' => 'api.mailcheck.regist.post',
	'middleware' => [],
	'where' => [],
	'domain' => NULL,
]);

$router->post('api/mailcheck/passwdreset', [
	'uses' => 'App\Http\Controllers\Api\MailCheckController@passwdReset',
	'as' => 'api.mailcheck.passwdreset.post',
	'middleware' => [],
	'where' => [],
	'domain' => NULL,
]);

$router->post('api/mailcheck/mailreset', [
	'uses' => 'App\Http\Controllers\Api\MailCheckController@mailReset',
	'as' => 'api.mailcheck.mailreset.post',
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

$router->post('api/zip_code', [
	'uses' => 'App\Http\Controllers\Api\ZipCodeController@getAddressByZipCode',
	'as' => 'api.zip_code.get',
	'middleware' => ['api', 'guest'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('api/account', [
	'uses' => 'App\Http\Controllers\Api\AccountController@validateAccount',
	'as' => 'api.account.post',
	'middleware' => ['api'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('api/memtype_change/confirm', [
	'uses' => 'App\Http\Controllers\Api\MemtypeChangeController@getMemtypeChangeConfirmPage',
	'as' => 'api.memtype_change.confirm',
	'middleware' => [],
	'where' => [],
	'domain' => NULL,
]);

$router->post('api/memtype_change/update', [
	'uses' => 'App\Http\Controllers\Api\MemtypeChangeController@updateMemtype',
	'as' => 'api.memtype_change.update',
	'middleware' => [],
	'where' => [],
	'domain' => NULL,
]);

$router->post('api/studio', [
	'uses' => 'App\Http\Controllers\Api\TenpoController@getTenpoInfo',
	'as' => 'api.studio.get',
	'middleware' => ['api', 'auth:customer'],
	'where' => [],
	'domain' => NULL,
]);
