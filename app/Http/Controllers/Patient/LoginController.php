<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Jobs\MailSendingJob;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as RES;
use Illuminate\Support\Str;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|between:2,100',
            'lastname' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'gender' => 'required|string',
            'phone' => 'required|string|unique:users',
            'dob' => 'required|string',
            'password' => 'required|string|confirmed|min:6',
        ]);

        if ($validator->fails()) {

            return response(
                [
                    'status' => true,
                    'message' => 'error',
                    'data' => $validator->errors()->toArray(),
                ],
                RES::HTTP_BAD_REQUEST
            );
        }

        $user = new User();
        $user->email = $request->email;
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->gender = $request->gender;
        $user->dob = $request->dob;
        $user->phone = $request->phone;
        $user->password = bcrypt($request->password);

        // $data = [
        //     'email' => $request->email,
        //     'subject' => 'Welcome Mail',
        //     'name' => 'Welcome Mail',
        //     'view' => 'mail.mail',
        //     'content' =>
        //     'Welcome to Quichealth, we are happy to have you here.',
        // ];
        // // //  return env( 'MAIL_USERNAME' );
        // // MailSendingJob::dispatch($data);

        $user->save();

        $credentials = $request->only('email', 'password');

        if ($token = JWTAuth::attempt($credentials)) {
            $user = User::where('id', auth::user($token)->id)->first();

            return response()->json([
                'status' => true,
                'message' => 'Registration Successful',
                'token' => $token,
                'user' => $user,
            ]);
        } else {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Invalid login details'
                ],
                401
            );
        }
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        // if ($token = Auth::attempt($credentials)) {
        if ($token = JWTAuth::attempt($credentials)) {
            $user = User::where('email', $request->email)->first();

            return response([
                'status' => true,
                'message' => 'Login Successful',
                'token' => $token,
                'data' => $user,
            ], 200);
        } else {
            return response([
                'status' => false,
                'message' => 'Invalid login detail'
            ], 401);
        }
    }

    public function forget_password(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
        ]);

        $user = User::where('email', $request->input('email'))->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Account not found'
            ], 400);
        }

        $user->remember_token =
            mt_rand(99999, 99999999) .
            Str::random(12) .
            mt_rand(99999, 99999999) .
            Str::random(12);
        $user->save();

        $link =
            env('APP_FRONTEND') . '/reset-password/' . $user->remember_token;

        $data = [
            'email' => $request->email,
            'name' => 'Reset Password',
            'view' => 'mail.mail',
            'subject' => 'Reset Password',
            'content' =>
            '<p>Click on the below link to reset your password <p><a href="' .
                $link .
                '">' .
                $link .
                '</a></p></p>',
        ];

        MailSendingJob::dispatch($data);

        return response()->json([
            'status' => true,
            'message' => 'Reset mail has been sent'
        ]);
    }

    public function verify_password(Request $request)
    {
        $token = $request->query('code');

        if (!isset($token)) {
            return response()->json(['status' => false, 'message' => 'Token not found'], 401);
        }

        $user = User::where('remember_token', $token)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Expired link'
            ], 401);
        }

        return response()->json(['status' => true, 'message' => 'correct token']);
    }

    public function reset_password(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|confirmed',
            'token' => 'required',
        ]);

        $user = User::where(
            'remember_token',
            $request->input('token')
        )->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Account not found'
            ], 401);
        }

        $user->password = bcrypt($request->input('password'));
        $user->remember_token =
            mt_rand(99999, 99999999) .
            Str::random(12) .
            mt_rand(99999, 99999999) .
            Str::random(12);
        $user->save();

        return response()->json(['status' => true, 'message' => 'Password reset was successful']);
    }
}

// eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC92MVwvcmVnaXN0ZXIiLCJpYXQiOjE2MzI5NTMxMzgsImV4cCI6MTYzMjk1NjczOCwibmJmIjoxNjMyOTUzMTM4LCJqdGkiOiJVMnYwWWxObXBNZHB2U0lKIiwic3ViIjoxLCJwcnYiOiI4N2UwYWYxZWY5ZmQxNTgxMmZkZWM5NzE1M2ExNGUwYjA0NzU0NmFhIn0.VRLmM70ONppRdmELzu5a68SYpD8ZP2_phGw9N3-mx4c
// eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC92MVwvbG9naW4iLCJpYXQiOjE2MzI5NTMzNzMsImV4cCI6MTYzMjk1Njk3MywibmJmIjoxNjMyOTUzMzczLCJqdGkiOiJkYXJMeTkzQkwyZWo3aktwIiwic3ViIjoxLCJwcnYiOiI4N2UwYWYxZWY5ZmQxNTgxMmZkZWM5NzE1M2ExNGUwYjA0NzU0NmFhIn0.jmDyBD0X7Y9RHOCnsZ66qKkDy2zNKpVBJlHFXMfDIQA
