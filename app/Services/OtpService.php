<?php

namespace App\Services;

use App\Models\UserOtp;
use App\Classes\Helpers;
use App\Services\SmsService;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendForgetPasswordOtpMail;
use Illuminate\Database\Eloquent\Model;

class OtpService
{
    protected $model;
    protected $medium;
    protected $duration;
    protected $reason;


    public function __construct(Model $model, $medium, $reason, $duration = 30)
    {
        $this->model = $model;
        $this->medium = $medium;
        $this->duration = $duration;
        $this->reason = $reason;
    }

    protected function saveOtp()
    {
        $user_id = $this->model->id;
        $code = Helpers::createOtp(5);
        $medium = $this->medium;
        $duration = $this->duration;
        $reason = $this->reason;

        // create or update the record on UserOtp table

        $userOtp = UserOtp::updateOrCreate(
            [
                'user_id' => $user_id,
                'medium' => $medium
            ],
            [
                'code' => $code,
                'medium' => $medium,
                'duration' => $duration,
                'reason' => $reason
            ]

        );

        // check if the record is created or updated

        if ($userOtp) {
            // return the otp
            return $code;
        }

        return false;
    }

    protected function findOtp()
    {
        // find the otp
        $user_id = $this->model->id;
        $medium = $this->medium;

        $user_otp = UserOtp::WhereUserId($user_id)->WhereMedium($medium)->first();

        if ($user_otp) {
            return $user_otp->code;
        }

        return false;
    }

    public function send()
    {
        $code = $this->saveOtp();
        $userEmail = $this->model->email;
        $fullname = $this->model->fullname;


        if (!$code) {
            return [
                'status' => false,
                'msg' => 'Failed to send OTP'
            ];
        }

        try {
            if ($this->medium === 'phoneNumber') {
                $sms =  new SmsService();
                return $sms->sendForgetPasswordSms($this->model->phoneNumber, $code);
            }
            if ($this->medium === 'email') {
                // send email
                $data = [
                    'fullname' => $fullname,
                    'code' => $code
                ];
                return Mail::to($userEmail)->send(new SendForgetPasswordOtpMail($data));
            }
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'msg' => $th->getMessage()
            ];
        }
    }

    // public function delete()
    // {
    //     $code = GenerateOtp::whereUserId($this->userId)->whereVerificationMedium($this->medium)->whereType($this->type)->first();
    //     if (!$code) {
    //         return back()->with('error', "OTP Not found");
    //     }
    //     $code->delete();
    //     return true;
    // }

    // public function verify(Request $request)
    // {
    //     $user = User::find(Auth::user()->id);
    //     $phoneOTP =  $request->phone;
    //     $emailOTP =  $request->email;

    //     if ($phoneOTP) {

    //         $validate = Validator::make($request->all(), [
    //             "phone" => "required|max:6|min:6"
    //         ]);

    //         if ($validate->fails()) {
    //             return json_encode($validate->errors()->toArray());
    //         }

    //         $otpVerification =  GenerateOtp::whereUserId($user->id)->where('verification_medium', 'phoneNumber')->whereCode($phoneOTP)->whereStatus(0)->first();

    //         if (!$otpVerification) {
    //             $result = [
    //                 'error' => 'Invalid OTP'
    //             ];
    //             return json_encode($result);
    //         }

    //         $user->update(['isPhoneNumberVerified' => 1]);

    //         $otpVerification->delete();

    //         if ($user->isEmailVerified !== 1) {

    //             Session::forget('accountVerified');

    //             $result = [
    //                 'success' => 'Phone number verified successfully, please verify your email address'
    //             ];

    //             return json_encode($result);
    //         } else {
    //             Session::forget('accountVerified');

    //             $result = [
    //                 'success' => 'Account verified successfully'
    //             ];

    //             return json_encode($result);
    //         }
    //         //     // return redirect()->route('dashboard');

    //     }
    //     if ($emailOTP) {
    //         //     $validate = request()->validate(["email" => "required|array|max:6|min:6"]);
    //         //     $formatOtpToSingleString = implode("", $validate["email"]);

    //         $validate = Validator::make($request->all(), [
    //             "email" => "required|max:6|min:6"
    //         ]);

    //         if ($validate->fails()) {
    //             return json_encode($validate->errors()->toArray());
    //         }

    //         $otpVerification =  GenerateOtp::whereUserId($user->id)->where('verification_medium', 'email')->whereCode($emailOTP)->whereStatus(0)->first();
    //         if (!$otpVerification) {
    //             // return back()->with('error', 'Invalid OTP');
    //             $result = [
    //                 'error' => 'Invalid OTP'
    //             ];
    //             return json_encode($result);
    //         }
    //         $user->update(['isEmailVerified' => 1, 'email_verified_at' => now()]);
    //         $otpVerification->delete();
    //         if ($user->isPhoneNumberVerified !== 1) {
    //             Session::forget('accountVerified');
    //             // return back()->with('message', 'Email  verified successfully, please verify your Phone number address');
    //             $result = [
    //                 'success' => 'Email verified successfully, please verify your Phone number address'
    //             ];
    //             return json_encode($result);
    //         } else {
    //             Session::forget('accountVerified');

    //             $result = [
    //                 'success' => 'Account verified successfully'
    //             ];
    //             return json_encode($result);
    //         }
    //         // return redirect()->route('dashboard');
    //     }
    // }
}
