<?php

namespace App\Http\Controllers\Doctor;

use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Actions\DoctorAuthAction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;


class DoctorAuthController extends Controller
{

    public function doctorsLogin(Request $request)
    {
        return DoctorAuthAction::run($request);
    }

    public function doctorsForgetPassword(Request $request)
    {
        # code...
    }

    public function reset_password(Request $request)
    {
        # code...
    }
}