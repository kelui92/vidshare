<?php

namespace App\Http\Controllers\Api\V1\Access;

use App\Http\Controllers\Api\V1\AbstractAccessController;
use App\Mail\VerifyUser;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends AbstractAccessController
{
    /**
     * Create a new user instance after a valid registration.
     *
     * @param Request $request
     * @return \App\User
     */
    public function register(Request $request)
    {
        $validationString = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ];
        $this->validate($request, $validationString);

        $data = $request->all();
        $authorizationCode = str_random(7);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'authorization_code' => $authorizationCode
        ]);

        $params = [$user['name'], $authorizationCode];
        $this->sendMail($user['email'], VerifyUser::class, $params);

        return $this->sendSuccessJson("An email with a verification code has been sent.", 200);

    }
}
