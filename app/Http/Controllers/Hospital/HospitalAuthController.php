<?php

namespace App\Http\Controllers\Hospital;

use App\Hospital;
use App\Mail\resetMail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\helpController;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as RES;

class HospitalAuthController extends Controller
{
    public function hospitalLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|',
        ]);

        if ($validator->fails()) {
            $status = false;
            $message = 'error';
            $data = $validator->errors()->toArray();
            $code = RES::HTTP_BAD_REQUEST;
            return helpController::getResponse($status, $message, $code, $data);
        }

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if ($token = Auth('hospital')->attempt($credentials)) {
            $user = Auth('hospital')->user();

            return response()->json([
                'status' => true,
                'msg' => 'Login Successfull',
                'token' => $token,
                'user' => $user,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Invalid login details',
            ]);
        }
    }

    public function hospitalForgetPassword(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
        ]);

        $hospital = Hospital::where('email', $request->input('email'))->first();

        if (!$hospital) {
            return response()->json([
                'status' => false,
                'msg' => 'Account not found',
            ]);
        }

        $hospital->remember_token =
            mt_rand(99999, 99999999) .
            Str::random(12) .
            mt_rand(99999, 99999999) .
            Str::random(12) .
            mt_rand(99999, 99999999) .
            Str::random(12);
        $hospital->save();

        $link =
            env('APP_FRONTEND') .
            '/hospital/reset-password/' .
            $hospital->remember_token;
        Mail::to($hospital->email)->send(
            new resetMail([
                'for' => 'Hospital',
                'link' => $link,
            ])
        );

        return response()->json([
            'status' => true,
            'msg' => 'Reset mail has been sent',
        ]);
    }

    public function hospitalVerifyPassword(Request $request)
    {
        $token = $request->query('code');

        if (!isset($token)) {
            return response()->json([
                'status' => false,
                'msg' => 'Token not found',
            ]);
        }

        $hospital = Hospital::where('remember_token', $token)->first();

        if (!$hospital) {
            return response()->json([
                'status' => false,
                'msg' => 'Expired link',
            ]);
        }

        return response()->json(['status' => true, 'msg' => 'correct token']);
    }

    public function hospitalResetPassword(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|confirmed',
            'token' => 'required',
        ]);

        $hospital = Hospital::where(
            'remember_token',
            $request->input('token')
        )->first();

        if (!$hospital) {
            return response()->json([
                'status' => false,
                'msg' => 'Account not found',
            ]);
        }

        $hospital->password = bcrypt($request->input('password'));
        $hospital->remember_token = mt_rand(99999, 99999999) . Str::random(12);
        $hospital->save();

        return response()->json([
            'status' => true,
            'msg' => 'Password reset was successful',
        ]);
    }
}
