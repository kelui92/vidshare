<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6'
        ]);
    }

    /**
     * Login a new user instance returning a JWT access/refresh token for the API to use.
     *
     * @param Request $request
     * @return \App\User
     */
    protected function login(Request $request)
    {
        //TODO: refactor this.
        $data = $request->all();
        $validatorCheck = $this->validator($data);

        //return any validation check messages.
        if($validatorCheck->fails()) {
            return response()->json(["message"=>$validatorCheck->errors()], 422);
        }

        if(Auth::attempt(['email' => $data['email'], 'password' => $data['password']])){
            $user = Auth::user();

            if(!$user['verified']) {
                return response()->json(['error'=>'Verification Code Required.'], 422);
            }

            $request->request->add([
                'grant_type'    => "password",
                'client_id'     => "2",
                'client_secret' => "4J4XH5WeoiNCzUaoQWu9bcwlVzczvraP3BCcWG8o",
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
