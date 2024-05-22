<?php

namespace App\Services;

use App\Classes\Helpers;
use Illuminate\Support\Facades\Http;

class SmsService
{
    private $api_key;

    private $termii_data;

    private $Base_URL;

    public function __construct($data)
    {
        $this->api_key = config("termii.api_key");

        $this->Base_URL = config('termii.base_URL');

        $data = [
            'api_key' => $this->api_key,
            "type" => "plain",
            "from" => "Quichealth",
            "channel" => "generic"
        ];

        $this->termii_data = json_encode($data);
    }

    private function base($url): string
    {
        return  $this->Base_URL . '/' . $url;
    }

    private function makeRequest($method, $url, $data = [])
    {
        if ($method === 'POST') {
            $request = Http::post($this->base($url), $data);
            // $status = $request->status();
            return $request;
        }
    }

    public function sendForgetPasswordSms($phone, $code)
    {
        return true;
    }

    public function sendOtp($phone, $sms)
    {
        $this->termii_data['to'] = Helpers::formatPhoneNumber($phone);
        $this->termii_data['sms'] = $sms;

        $data = json_encode($this->termii_data);

        $request = $this->makeRequest('POST', 'sms/send', $data);

        return $request;
    }

    public function accountVerificationSmsService($phone, $otp)
    {
        // private const _CONFIG ;
        // = config('termii.api_key')

        $curl = curl_init();
        // $api_key = config('termii.api_key');

        $data = [
            'api_key' => $this->api_key,
            "to" => $phone,
            "type" => "plain",
            "from" => "N-Alert",
            "channel" => "dnd",
            "sms" => "Your SureCheq Verification code is " . $otp . " Valid for 10 minutes, one-time use only.",
        ];

        $post_data = json_encode($data);

        try {
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.ng.termii.com/api/sms/send",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $post_data,
                CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/json"
                ),
            ));

            $response = curl_exec($curl);
            curl_close($curl);
            return $response;
        } catch (\Exception $th) {
            // Log::error('could not send sms ' . $th);
        }
    }

    public function receiversChequeAlert($phone, $cheque_date)
    {
        // logger("from services = ".$phone);
        $curl = curl_init();
        $data = [
            'api_key' => $this->api_key, //env('TERMII_API_SK'),
            "to" => $phone,
            "type" => "plain",
            "from" => "N-Alert",
            "channel" => "dnd",
            "sms" => "This is to inform you that you've recieved a cheque and will be due on $cheque_date "
        ];
        $post_data = json_encode($data);
        try {
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.ng.termii.com/api/sms/send",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $post_data,
                CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/json"
                ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            return $response;
        } catch (\Throwable $th) {
            // Log::error('could not send sms ' . $th);
        }
    }

    public function sendTeamInviteSMS(array $data)
    {
        $phone = $data['phone'];
        $name = $data['name'];
        $role = $data['role'];
        $email = $data['email'];
        $password = $data['password'];
        $admin = auth()->user()->fullname;
        $link =  url($data['link']);

        $mgs = "Hello $name, This is to inform you that, $admin have invited you to join the surecheque team as a $role. \n Your login details are as follows: \n Email: $email \n Password: $password, \n Please click on this link to login $link \n Thank you.";

        $curl = curl_init();

        $data = [
            'api_key' => $this->api_key, //env('TERMII_API_SK'),
            "to" => $phone,
            "type" => "plain",
            "from" => "N-Alert",
            "channel" => "dnd",
            "sms" =>  $mgs,
        ];

        $post_data = json_encode($data);
        try {
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.ng.termii.com/api/sms/send",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $post_data,
                CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/json"
                ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            return $response;
        } catch (\Throwable $th) {
            // Log::error('could not send sms ' . $th);
        }
    }
}
