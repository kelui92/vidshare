<?php

namespace App\Http\Controllers\Api\V1\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ActivateController extends Controller
{
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
            'authorization_code' => 'required'
        ]);
    }

    protected function activate(Request $request) {
        //TODO: refactor this.
        $data = $request->all();
        $validatorCheck = $this->validator($data);

        //return any validation check messages.
        if($validatorCheck->fails()) {
            return response()->json(["message"=>$validatorCheck->errors()], 422);
        }

        if(Auth::attempt(['email' => $data['email'], 'password' => $data['password']])){
            $user = Auth::user();

            if($user['verified']) {
                return response()->json(['error'=>'This account is already activated'], 422);
            }

            //check authorization code
            if($this->checkAuthorizationCode($user['authorization_code'],$data['authorization_code'])) {
                $user['authorization_code'] = null;
                $user['verified'] = true;
                $user->save();
                return response()->json(['message'=>'This account has been activated'], 200);
            }
            else {
                return response()->json(['message'=>'Authorization Code is incorrect'], 422);
            }
        }
        else {
            return response()->json(['error'=>'Unauthorized'], 401);
        }
    }

    private function checkAuthorizationCode($savedAuthorizationCode, $inputAuthorizationCode) {
        if($savedAuthorizationCode == $inputAuthorizationCode) {
            return true;
        }
        return false;
    }
}
