<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class WaveController extends Controller
{
    public function add(Request $request)
    {
        $this->validate($request, [
            'appointment_id' => 'required',
            'amount' => 'required|numeric',
        ]);

        //This generates a payment reference
        $payment = [
            "public_key" => env('FLW_PUBLIC_KEY'),
            "tx_ref" => time(),
            "amount" => request()->amount,
            "currency" => "NGN",
            "payment_options" => "card, ussd, banktransfer",
            "redirect_url" => route('payment.status'),
            "meta" => [
                "price" => request()->amount,
                "appointment_id" => request()->appointment_id
            ],
            "customer" => [
                "name" => auth()->user()->name,
                "email" => auth()->user()->email
            ],
            "customizations" => [
                'title' => "QuicHealth",
                'description' => "QuicHealth Doctors Appointment",
                // 'logo' => "https://www.logolynx.com/images/logolynx/22/2239ca38f5505fbfce7e55bbc0604386.jpeg",
            ],
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.flutterwave.com//v3/payments',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($payment),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.env('FLW_SECRET_KEY'),
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $res = json_decode($response);

        if ($response) {
            return $res;
        } else {
            return response([
                'status' => "error",
            ], 500);
        }

    }

    public function status()
    {
        $status = request()->status;

        if($status == 'cancelled')
        {
            return response([
                'status' => "error",
                'data' => $status
            ], 500);
        }else
        if($status == 'successful')
        {
            $curl = curl_init();

            $txid = request()->transaction_id;
            // // dd($txid);

            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.flutterwave.com/v3/transactions/'.$txid.'/verify',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.env('FLW_SECRET_KEY'),
                'Content-Type: application/json'
            ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            // echo $response;

            $res = json_decode($response);
            // dd($res);

            if ($res->status == 'success') {
                $appointment = Appointment::find($res->data->meta->appointment_id);

                if ($appointment) {
                    $appointment->payment_status = 'PAID';
                    $appointment->save();

                    // return $appointment;
                } else {
                    return false;
                }
            }

            if ($response) {
                return $res;
            } else {
                return response([
                    'status' => "error",
                    'data' => []
                ], 500);
            }
        }
    }
}
