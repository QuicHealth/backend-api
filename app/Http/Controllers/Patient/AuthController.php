<?php

namespace App\Http\Controllers\Patient;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\PatientResource;
use App\Http\Controllers\helpController;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response as RES;

class AuthController extends Controller
{
    protected const AUTH_TYPE = "google";

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|between:2,100',
            'lastname' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'gender' => 'sometimes|string',
            'phone' => 'sometimes|string|unique:users',
            'dob' => 'sometimes|string',
            'password' => 'required|string|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            $status = false;
            $message = 'error';
            $data = $validator->errors()->toArray();
            $code = RES::HTTP_BAD_REQUEST;
            return helpController::getResponse($status, $code, $message,  $data);
        }

        $user = new User();
        $user->unique_id = uniqid();
        $user->email = $request->email;
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->password = bcrypt($request->password);


        $user->save();
        // $user->notify(new UserRegisterNotification($user));


        if (!$user->save()) {
            $status = false;
            $message = 'Registration Unsuccessful';
            $code = RES::HTTP_UNAUTHORIZED;
            return helpController::getResponse($status, $code, $message);
        }

        $status = true;
        $message = 'Registration successful, please login to continue';
        $code = RES::HTTP_OK;
        $data = [new PatientResource($user)];
        return helpController::getResponse($status, $code, $message,  $data);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            $status = false;
            $message = 'error';
            $data = $validator->errors()->toArray();
            $code = RES::HTTP_BAD_REQUEST;
            return helpController::getResponse($status, $code, $message,  $data);
        }

        $credentials = $request->only('email', 'password');

        if ($token = JWTAuth::attempt($credentials)) {
            $user = User::where('email', $request->email)->first();

            $status = true;
            $message = 'Login Successful';
            $code = RES::HTTP_OK;
            $data = [
                'user' => new PatientResource($user),
                'token' => $this->createNewToken($token),
            ];
            return helpController::getResponse($status, $code, $message,  $data);
        } else {
            return response([
                'status' => false,
                'message' => 'Invalid login details'
            ]);
        }
    }

    public function authenicateWithGoogle(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'bail|required',
            'given_name' => 'bail|required',
            'family_name' => 'bail|required',
            'sub' => 'bail|required',
            'picture' => 'sometimes',

        ]);

        if ($validator->fails()) {
            $status = false;
            $message = 'error';
            $data = $validator->errors()->toArray();
            $code = RES::HTTP_BAD_REQUEST;
            return helpController::getResponse($status, $code, $message,  $data);
        }

        $email = $request->email;
        $firstname = $request->given_name;
        $lastname = $request->family_name;
        $password = $request->sub;
        $photo = $request->picture;

        $credentials = [$email, $password];

        $checkEmail = User::whereEmail($email)->first();

        if (!empty($checkEmail)) {
            if ($checkEmail->auth_type != self::AUTH_TYPE) {
                return response()->json([
                    'status' => false,
                    'message' => 'Sign up was not by google please use the form'
                ]);
            }

            return $this->getAuthToken($credentials);
        }

        $user = new User();

        $user->email = $email;
        $user->password = Hash::make($password);
        $user->firstname = $firstname;
        $user->lastname = $lastname;
        $user->email_verified_at = Carbon::now();
        $user->unique_id = uniqid();
        $user->auth_type = self::AUTH_TYPE;
        $user->profile_pic_link = $photo;

        if ($user->save()) {
            // login the user in
            return $this->getAuthToken($credentials);
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

        // MailSendingJob::dispatch($data);

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

    protected function getAuthToken($credentials)
    {
        if ($token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'status' => true,
                'message' => 'Login Successful',
                'token' => $this->createNewToken($token)
            ]);
        } else {
            return response()->json([
                'status' => true,
                'message' => 'Auth failed, please reach out to our support team'
            ]);
        }
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
            'expires_in' => config('jwt.ttl') * 24
        ]);
    }
}
