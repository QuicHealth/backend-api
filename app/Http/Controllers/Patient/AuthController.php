<?php

namespace App\Http\Controllers\Patient;

use App\Models\User;
use Illuminate\Support\Str;
use App\Services\OtpService;
use Illuminate\Http\Request;
use App\Models\HealthProfile;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\PatientResource;
use App\Http\Controllers\helpController;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response as RES;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

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
        $user->gender = $request->gender;
        $user->phone = $request->phone;
        $user->dob = $request->dob;
        $user->password = bcrypt($request->password);


        $user->save();
        // $user->notify(new UserRegisterNotification($user));

        if (!$user->save()) {
            $status = false;
            $message = 'Registration Unsuccessful';
            $code = RES::HTTP_UNAUTHORIZED;
            return helpController::getResponse($status, $code, $message);
        }

        $this->createHealthProfileForNewUser($user->id);

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

        $credentials = ['email' => $email, 'password' => $password];

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

            $this->createHealthProfileForNewUser($user->id);

            // login the user in
            return $this->getAuthToken($credentials);
        }
    }

    protected function createHealthProfileForNewUser($userId)
    {

        $healthProfile = new HealthProfile();
        $healthProfile->user_id = $userId;
        $healthProfile->save();
    }

    public function forget_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            $status = false;
            $message = 'error';
            $data = $validator->errors()->toArray();
            $code = RES::HTTP_BAD_REQUEST;
            return helpController::getResponse($status, $code, $message,  $data);
        }

        $response = Password::sendResetLink(
            $request->only('email')
        );

        switch ($response) {
            case Password::RESET_LINK_SENT:
                return trans($response);

            default:
                throw ValidationException::withMessages(['email' => trans($response)]);
        }
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

    public function send_email_verification(Request $request)
    {
        //
    }

    public function verify_email(Request $request)
    {
    }


    public function send_sms_verification(Request $request)
    {
        //
    }

    public function verify_phone(Request $request)
    {
    }

    public function reset_password(Request $request, $token)
    {
        // $this->validate($request, [
        //     'password' => 'required|confirmed',
        //     'token' => 'required'
        // ]);

        // $user = User::where('remember_token', $request->input('token'))->first();

        // if (!$user) {
        //     return response()->json([
        //         'status' => false,
        //         'msg' => "Account not found"
        //     ]);
        // }

        // $user->password = bcrypt($request->input('password'));
        // $user->remember_token = mt_rand(99999, 99999999) . Str::random(12) . mt_rand(99999, 99999999) . Str::random(12);
        // $user->save();

        // return response()->json(['status' => true, 'msg' => "Password reset was successful"]);

        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
            'token' => $token,
            'password_confirmation' => $request->password_confirmation

        ];

        $response = Password::reset(
            $credentials,
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                // event(new PasswordReset($user));
            }
        );

        switch ($response) {
            case Password::PASSWORD_RESET:
                return trans($response);

            default:
                throw ValidationException::withMessages(['token' => trans(Password::INVALID_TOKEN)]);
        }
    }

    protected function getAuthToken($credentials)
    {
        if ($token = JWTAuth::attempt($credentials)) {
            $user = User::whereEmail($credentials['email'])->first();

            $status = true;
            $message = 'Login Successful';
            $code = RES::HTTP_OK;
            $data = [
                'user' => new PatientResource($user),
                'token' => $this->createNewToken($token),
            ];

            return helpController::getResponse($status, $code, $message,  $data);
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