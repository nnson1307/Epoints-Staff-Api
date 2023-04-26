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
Route::prefix('chat')->group(function() {
    Route::post('register', 'ChatController@registerAction');
});

Route::middleware('auth')->prefix('chat')->group(function() {
    Route::post('change-password', 'ChatController@changePasswordAction');
    Route::post('change-avatar', 'ChatController@changeAvatarAction');
    Route::post('remove-user', 'ChatController@removeUserAction');
    Route::post('profile', 'ChatController@profileAction');
    Route::post('update-profile', 'ChatController@updateProfileAction');
    Route::post('profile-web', 'ChatController@profileWebAction');
    //Lấy ds nhân viên có quyền chat
    Route::post('get-staff-chat', 'ChatController@getStaffChat');
});
