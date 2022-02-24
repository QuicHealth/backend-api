<?php

namespace App\Http\Controllers\Patient;

use App\User;
use Illuminate\Support\Str;
use App\Jobs\MailSendingJob;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\helpController;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as RES;

class AuthController extends Controller
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
            $status = false;
            $message = 'error';
            $data = $validator->errors()->toArray();
            $code = RES::HTTP_BAD_REQUEST;
            return helpController::getResponse($status, $message, $code, $data);
        }

        $user = new User();
        $user->unique_id = uniqid();
        $user->email = $request->email;
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->gender = $request->gender;
        $user->dob = $request->dob;
        $user->phone = $request->phone;
        $user->password = bcrypt($request->password);

        $data = [
            'email' => $request->email,
            'subject' => 'Welcome Mail',
            'name' => 'Welcome Mail',
            'view' => 'mail.mail',
            'content' =>
            'Welcome to Quichealth, we are happy to have you here.',
        ];

        MailSendingJob::dispatch($data);

        $user->save();

        $credentials = $request->only('email', 'password');

        if ($token = JWTAuth::attempt($credentials)) {
            $user = User::where('id', auth::user($token)->id)->first();

            $status = true;
            $message = 'Registration Successful';
            $code = RES::HTTP_OK;
            $data = [
                'unique_id' => $user->unique_id,
                'name' => $user->firstname,
                'name' => $user->lastname,
                'email' => $user->email,
                'phone_number' => $user->phone,
                'token' => $this->createNewToken($token),
            ];
            return helpController::getResponse($status, $message, $code, $data);
        } else {

            $status = false;
            $message = 'Registration Unsuccessful';
            $code = RES::HTTP_UNAUTHORIZED;
            return helpController::getResponse($status, $message, $code);
        }
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');

        if ($token = JWTAuth::attempt($credentials)) {
            $user = User::where('email', $request->email)->first();

            $status = true;
            $message = 'Login Successful';
            $code = RES::HTTP_OK;
            $data = [
                'id' => $user->id,
                'name' => $user->firstname,
                'name' => $user->lastname,
                'email' => $user->email,
                'phone_number' => $user->phone,
                'token' => $this->createNewToken($token),
            ];
            return helpController::getResponse($status, $message, $code, $data);
        } else {
            return response([
                'status' => false,
                'message' => 'Invalid login details'
            ]);
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
                'msg' => "Account not found"
            ]);
        }

        $user->remember_token = mt_rand(99999, 99999999) . Str::random(12) . mt_rand(99999, 99999999) . Str::random(12);
        $user->save();

        $link = env('APP_FRONTEND') . "/reset-password/" . $user->remember_token;

        $data = [
            'email' => $request->email,
            'name' => 'Reset Password',
            'view' => 'mail.mail',
            'subject' => 'Reset Password',
            'content' => '<p>Click on the below link to reset your password <p><a href="' . $link . '">' . $link . '</a></p></p>'
        ];

        MailSendingJob::dispatch($data);

        return response()->json([
            'status' => true,
            'msg' => "Reset mail has been sent"
        ]);
    }

    public function verify_password(Request $request)
    {
        $token = $request->query('code');

        if (!isset($token)) {
            return response()->json([
                'status' => false,
                'msg' => "Token not found"
            ]);
        }

        $user = User::where('remember_token', $token)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'msg' => "Expired link"
            ]);
        }

        return response()->json(['status' => true, 'msg' => "correct token, redirect to reset password page"]);
    }

    public function reset_password(Request $request)
    {
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

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        if (auth()->check()) {

            auth()->logout();

            return response()->json([
                'message' => 'Successfully logged out'
            ]);
        }
    }


    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl') * 60
        ]);
    }
}