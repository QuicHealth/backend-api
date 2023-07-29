<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class AuthServices
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }
    public function authServices()
    {
        return $this;
    }

    public function forgetPassword($email)
    {
        $user = $this->model->where('email', $email)->first();
        if ($user) {

            $token = mt_rand(99999, 99999999) . Str::random(12) . mt_rand(99999, 99999999) . Str::random(12);

            $user->update([
                'remember_token' => $token
            ]);

            $this->sendEmail($user, $token);

            return $token;
        }
        return false;
    }

    public function resetPassword($data)
    {

        $user = $this->model->where('remember_token', $data['token'])->first();

        if ($user) {
            $user->update([
                'password' => bcrypt($data['password']),
                'remember_token' => mt_rand(99999, 99999999) . Str::random(12) . mt_rand(99999, 99999999) . Str::random(12)
            ]);
            return true;
        }
        return false;
    }

    public function sendEmail($email, $token)
    {
    }

    public function logout($token)
    {
        // logout user

        if (auth($token)->check()) {

            auth($token)->logout();

            return response()->json([
                'message' => 'Successfully logged out'
            ]);
        }
    }
}
