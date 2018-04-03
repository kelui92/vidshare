<?php

namespace App\Http\Controllers\Api\V1\Access;

use App\Http\Controllers\Api\V1\AbstractAccessController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class LoginController extends AbstractAccessController
{
    /**
     * Login a new user instance returning a JWT access/refresh token for the API to use.
     *
     * @param Request $request
     * @return \App\User
     */
    public function login(Request $request)
    {
        $validationString = [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
            'client_id' => 'required',
            'client_secret' => 'required|string'
        ];
        $this->validate($request, $validationString);

        $data = $request->all();
        if(Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
            $user = Auth::user();

            if(!$user['verified']) {
                return $this->sendErrorJson('Verification Code Required.', 422);
            }

            $request->request->add([
                'grant_type'    => "password",
                'client_id'     => $data['client_id'],
                'client_secret' => $data['client_secret'],
                'username'      => $user['email'],
                'scope'         => null,
            ]);

            $proxy = Request::create(
                'oauth/token',
                'POST'
            );

            return Route::dispatch($proxy);
        }
        else {
            return $this->sendErrorJson('Unauthorized', 401);
        }
    }
}
