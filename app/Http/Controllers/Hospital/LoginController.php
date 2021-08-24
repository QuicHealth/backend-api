<?php

namespace App\Http\Controllers\Hospital;

use App\Hospital;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if ($token = JWTAuth::attempt($credentials)) {
            $hospital = Hospital::where('email', $request->email)->with('doctors')->first();
            return response([
                'status' => true,
                'message' => 'Login Successful',
                'token' => $token,
                'data' => $hospital,
            ], 200);
        } else {
            return response([
                'status' => false,
                'message' => 'Invalid login details',
                'Http_code' => http_response_code(),
            ]);
        }
    }

    public function forgetPassword(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
        ]);

        $hospital = Hospital::where('email', $request->input('email'))->first();

        if (!$hospital) {
            return response()->json([
                'status' => false,
                'msg' => "Account not found",
            ]);
        }

        $hospital->remember_token = mt_rand(99999, 99999999) . Str::random(12) . mt_rand(99999, 99999999) . Str::random(12);
        $hospital->save();

        $link = env('APP_FRONTEND') . "/reset-password/" . $hospital->remember_token;

        $data = [
            'email' => $request->email,
            'name' => 'Reset Password',
            'view' => 'mail.mail',
            'subject' => 'Reset Password',
            'content' => '<p>Click on the below link to reset your password <p><a href="' . $link . '">' . $link . '</a></p></p>',
        ];

        MailSendingJob::dispatch($data);

        return response()->json([
            'status' => true,
            'msg' => "Reset mail has been sent",
        ]);

    }

    public function resetPassword(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|confirmed',
            'token' => 'required',
        ]);

        $Hospital = Hospital::where('remember_token', $request->input('token'))->first();

        if (!$Hospital) {
            return response()->json([
                'status' => false,
                'msg' => "Account not found",
            ]);
        }

        $Hospital->password = bcrypt($request->input('password'));
        $Hospital->remember_token = mt_rand(99999, 99999999) . Str::random(12) . mt_rand(99999, 99999999) . Str::random(12);
        $Hospital->save();

        return response()->json(['status' => true, 'msg' => "Password reset was successful"]);

    }
}
