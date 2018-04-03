<?php

//use Illuminate\Http\Request;
//use App\Http\Controllers\Api;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

//Auth controls.
Route::group(['namespace' => 'Api\V1\Access'], function() {
    Route::post('/v1/register', 'RegisterController@register')->name('api.register');
    Route::post('/v1/login', 'LoginController@login')->name('api.login');
    Route::post('/v1/activate', 'ActivateController@activate')->name('api.activate');
    Route::post('/v1/activate/resend', 'ActivateController@resendActivationCode')->name('api.activate.resend');
});

//Authenticated user.
Route::group(['namespace' => 'Api\V1\Authenticated'], function() {
    Route::middleware('auth:api')->get('/v1/dashboard', 'DashboardController@index');
});

