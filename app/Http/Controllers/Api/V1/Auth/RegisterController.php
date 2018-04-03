<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Mail\VerifyUser;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param Request $request
     * @return \App\User
     */
    protected function register(Request $request)
    {
        //TODO: refactor this.
        $data = $request->all();
        $validatorCheck = $this->validator($data);

        //return any validation check messages.
        if($validatorCheck->fails()) {
            return response()->json(["message"=>$validatorCheck->errors()], 422);
        }

        $authorizationCode = str_random(7);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'authorization_code' => $authorizationCode
        ]);


        Mail::to($data['email'])->send(new VerifyUser($data['name'], $authorizationCode));
        return response()->json(["message"=>"An email with a verification code has been sent."], 200);

    }
}
