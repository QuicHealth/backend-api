<?php

namespace App\Http\Controllers\Doctor;

use App\Doctor;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;


class DoctorAuthController extends Controller
{
    public function doctorsLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        // return $token = JWTAuth::attempt($credentials);

        if ($token = Auth('doctor')->attempt($credentials)) {
            // if ($token = JWTAuth::attempt($credentials)) {
            $user = Auth('doctor')->user();

            return response([
                'status' => true,
                'message' => 'Login Successful',
                'token' => $token,
                'data' => $user,
            ], 200);
        } else {
            return response([
                'status' => false,
                'message' => 'Invalid login detail',
                'data' => $credentials
            ], 404);
        }
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