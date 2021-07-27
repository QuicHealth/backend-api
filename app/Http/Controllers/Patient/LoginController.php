<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Jobs\MailSendingJob;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{
    public function register(Request $request)
    {
        $this->validate($request, [
            'firstname'=>'required|string',
            'lastname'=>'required|string',
            'email'=>'required|unique:users',
            'gender'=>'required|string',
            'phone'=>'required|string',
            'dob'=>'required|string',
            'password'=>'required|string|confirmed'
        ]);

        $user = new User();
        $user->email = $request->email;
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->gender = $request->gender;
        $user->dob = $request->dob;
        $user->phone = $request->phone;
        $user->password = bcrypt($request->password);

        $data = [
            'email'=>$request->email,
            'subject'=>'Welcome Mail',
            'name'=>'Welcome Mail',
            'view'=>'mail.mail',
            'content'=>'Welcome to Quichealth, we are happy to have you here.'
        ];
    //        return env('MAIL_USERNAME');
        MailSendingJob::dispatch($data);

        $user->save();

        $credentials = $request->only('email', 'password');

        if ($token = JWTAuth::attempt($credentials)) {
            $user = User::where('id', auth::user($token)->id)->first();
            return response()->json([
                'status' => true,
                'msg' => "Registration Successful",
                'token' => $token,
                'user' => $user
            ]);
        } else {
            return response()->json(
                [
                    "status" => false,
                    "message" => "Invalid login details"
                ]
            );
        }
    }

    public function login(Request $request){
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');

        if($token = JWTAuth::attempt($credentials)){
            $user = User::where('email', $request->email)->first();
            return response([
                'status' => true,
                'message' => 'Login Successful',
                'token' => $token,
                'data' => $user
            ],200);
        }
        else{
            return response([
                'status' => false,
                'message' => 'Invalid login details'
            ]);
        }
    }

    public function forget_password(Request $request){
        $this->validate($request, [
            'email' => 'required',
        ]);

        $user = User::where('email', $request->input('email'))->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'msg' => "Account not found"
            ]);
        }

        $user->remember_token = mt_rand(99999, 99999999) . Str::random(12) . mt_rand(99999, 99999999) . Str::random(12);
        $user->save();

        $link = env('APP_FRONTEND') . "/reset-password/" . $user->remember_token;

        $data = [
            'email'=>$request->email,
            'name'=>'Reset Password',
            'view'=>'mail.mail',
            'subject'=>'Reset Password',
            'content' => '<p>Click on the below link to reset your password <p><a href="'. $link .'">'. $link .'</a></p></p>'
        ];

        MailSendingJob::dispatch($data);

        return response()->json([
            'status' => true,
            'msg' => "Reset mail has been sent"
        ]);
    }

    public function verify_password(Request $request){
        $token = $request->query('code');

        if (!isset($token)) {
            return response()->json(['status' => false, 'msg' => "Token not found"]);
        }

        $user = User::where('remember_token', $token)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'msg' => "Expired link"
            ]);
        }

        return response()->json(['status' => true, 'msg' => "correct token"]);
    }

    public function reset_password(Request $request){
        $this->validate($request, [
            'password' => 'required|confirmed',
            'token' => 'required'
        ]);

        $user = User::where('remember_token', $request->input('token'))->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'msg' => "Account not found"
            ]);
        }

        $user->password = bcrypt($request->input('password'));
        $user->remember_token = mt_rand(99999, 99999999) . Str::random(12) . mt_rand(99999, 99999999) . Str::random(12);
        $user->save();

        return response()->json(['status' => true, 'msg' => "Password reset was successful"]);
    }
}
