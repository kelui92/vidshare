<?php

namespace App\Http\Controllers\Api\V1\Access;

use App\Http\Controllers\Api\V1\AbstractAccessController;
use App\Mail\VerifyUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivateController extends AbstractAccessController
{
    public function resendActivationCode(Request $request)
    {
        $validationString = [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6'
        ];
        $this->validate($request, $validationString);

        $data = $request->all();
        if(Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
            $user = Auth::user();

            if($user['verified']) {
                return $this->sendErrorJson('This account is already activated', 422);
            }

            $authorizationCode = str_random(7);
            $user['authorization_code'] = $authorizationCode;
            $user->save();
            $params = [$user['name'], $authorizationCode];
            $this->sendMail($user['email'], VerifyUser::class, $params);

            return $this->sendSuccessJson("An email with a verification code has been sent.", 200);
        }
        else {
            return $this->sendErrorJson('Unauthorized', 401);
        }
    }

    public function activate(Request $request)
    {
        $validationString = [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
            'authorization_code' => 'required'
        ];
        $this->validate($request, $validationString);

        $data = $request->all();
        if(Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
            $user = Auth::user();

            if($user['verified']) {
                return $this->sendErrorJson('This account is already activated', 422);
            }

            //activate user
            return $this->activateUserWithAuthorizationCode($user, $data['authorization_code']);
        }
        else {
            return $this->sendErrorJson('Unauthorized', 401);
        }
    }

    private function activateUserWithAuthorizationCode($user, $inputAuthorizationCode)
    {
        if($user['authorization_code'] == $inputAuthorizationCode) {
            $user['authorization_code'] = null;
            $user['verified'] = true;
            $user->save();
            return $this->sendSuccessJson('This account has been activated');
        }
        else {
            return $this->sendErrorJson('Authorization Code is incorrect', 422);
        }
    }
}
