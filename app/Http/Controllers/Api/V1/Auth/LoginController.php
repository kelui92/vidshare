<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class LoginController extends Controller
{

    /**
     * Login a new user instance returning a JWT access/refresh token for the API to use.
     *
     * @param Request $request
     * @return \App\User
     */
    protected function login(Request $request)
    {
        $data = $request->all();
        if(Auth::attempt(['email' => $data['email'], 'password' => $data['password']])){
            $user = Auth::user();

            //TODO: Check email authorized.

            $request->request->add([
                'grant_type'    => "password",
                'client_id'     => "2",
                'client_secret' => "guwD1sISWrYJRUHMHW7Zp1UqEaEy7R2BZsJ4P1P6",
                'username'      => $user['email'],
                'scope'         => null,
            ]);

            $proxy = Request::create(
                'oauth/token',
                'POST'
            );

            return Route::dispatch($proxy);
        }
        else{
            return response()->json(['error'=>'Unauthorized'], 401);
        }
    }
}
