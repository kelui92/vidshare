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
Route::group(['namespace' => 'Api\V1\Auth'], function() {
    Route::post('/v1/register', 'RegisterController@register');
    Route::post('/v1/login', 'LoginController@login');
});

//Authenticated user.
Route::group(['namespace' => 'Api\V1\Authenticated'], function() {
    Route::middleware('auth:api')->get('/v1/dashboard', 'DashboardController@index');
});

