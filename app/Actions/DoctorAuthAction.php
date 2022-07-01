<?php

namespace App\Actions;

use App\Http\Resources\DoctorResource;
use App\Http\Controllers\helpController;
use Illuminate\Support\Facades\Validator;
use Lorisleiva\Actions\Concerns\AsAction;
use Symfony\Component\HttpFoundation\Response as RES;

class DoctorAuthAction
{
    use AsAction;

    public function handle($request)
    {
        $validated = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // dd($validated->fails());

        if ($validated->fails()) {
            $status = false;
            $message = 'error';
            $data = $validated->errors()->toArray();
            $code = RES::HTTP_BAD_REQUEST;
            return helpController::getResponse($status, $code, $message,  $data);
        }

        $credentials = $request->only('email', 'password');

        if ($token = auth('doctor_api')->attempt($credentials)) {

            $user = auth('doctor_api')->user();

            return response([
                'status' => true,
                'message' => 'Login Successful',
                'token' => $token,
                'user' => new DoctorResource($user),
                // 'data' => $user

            ], 200);
        } else {
            return response([
                'status' => false,
                'message' => 'Invalid login detail',
                'data' => $credentials
            ], 404);
        }
    }
}