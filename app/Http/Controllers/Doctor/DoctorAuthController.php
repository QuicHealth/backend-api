<?php

namespace App\Http\Controllers\Doctor;

use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Services\AuthServices;
use App\Actions\DoctorAuthAction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;


class DoctorAuthController extends Controller
{

    public $authSerivce;

    public function __construct()
    {
        $this->authSerivce = new AuthServices(new Doctor);
    }
    public function doctorsLogin(Request $request)
    {
        return DoctorAuthAction::run($request);
    }

    public function doctorsForgetPassword(Request $request)
    {
        $this->validate($request, [
            'email' => 'required'
        ]);

        $token =  $this->authSerivce->forgetPassword($request->email);

        // send email to user with token
        return response([
            'status' => true,
            'message' => 'Check your email to reset your password',
            'token' => $token
        ]);
    }

    public function reset_password(Request $request)
    {
        $this->validate($request, [
            'token' => 'required',
            'password' => 'required|confirmed',
        ]);

        $this->authSerivce->resetPassword($request->all());
    }

    public function logout(Request $request)
    {
        $this->authSerivce->logout($request->token);
    }
}
