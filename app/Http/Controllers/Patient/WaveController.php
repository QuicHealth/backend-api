<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\User;
use App\Notifications\PaymentSuccessfulNotification;
use Illuminate\Foundation\Auth\User as AuthUser;
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
                "name" => auth()->user()->firstname . ' ' . auth()->user()->lastname,
                "email" => auth()->user()->email
            ],
            "customizations" => [
                'title' => "QuicHealth",
                'description' => "QuicHealth Doctors Appointment",
                'logo' => "https://quichealthapi.herokuapp.com/assets/images/logo.png",
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
                'Authorization: Bearer ' . env('FLW_SECRET_KEY'),
                'Content-Type: application/json'
            ),
        ));

        $response = $this->saveResponse($request);

        $response = curl_exec($curl);

        curl_close($curl);

        $res = json_decode($response);

        if ($response) {
            return $res;
        } else {
            return response([
                'status' => "error",
            ], 404);
        }
    }

    public function status()
    {
        $data = request();
        $status = $data->status;

        if ($status == 'cancelled') {
            $this->saveNotPaid($data);
            return redirect('http://localhost:3000/select-appointment');
            return response([
                'status' => $status,
                'data' => $data
            ], 500);
        } else if ($status == 'successful') {
            $curl = curl_init();

            $txid = request()->transaction_id;
            // dd($txid);

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.flutterwave.com/v3/transactions/' . $txid . '/verify',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . env('FLW_SECRET_KEY'),
                    'Content-Type: application/json'
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            // echo $response;

            $res = json_decode($response);
            // dd($res);

            if ($res->status == 'success') {
                $appointment_id = $res->data->meta->appointment_id;
                $appointment = Appointment::find($appointment_id);

                if ($appointment) {
                    $appointment->payment_status = 'PAID';
                    $appointment->save();
                } else {
                    return false;
                }
                $this->savePaid($res, $appointment_id);
            }

            if ($response) {
                // return $res;
                return redirect('http://localhost:3000/payment-confirm');
            } else {
                return response([
                    'status' => "error",
                    'data' => []
                ], 500);
            }
        }
    }

    public function saveResponse($request)
    {
        $payment = Payment::create([
            'appointments_id' =>  $request->appointment_id,
            'amount' => $request->amount,
            'user_id' => auth()->user()->id,
            'paymentStatus' => "PENDING",
            'customer_name' => auth()->user()->firstname . ' ' . auth()->user()->lastname,
            'customer_email' => auth()->user()->email,
        ]);

        return $payment;
    }

    public function savePaid($res, $appointment_id)
    {
        $payment = Payment::where('appointments_id', $appointment_id)->first();
        // dd($payment);
        if ($payment) {
            // $payment->status = $res->data->status;
            $payment->paymentStatus = 'PAID';
            $payment->tx_ref = $res->data->tx_ref;
            $payment->transaction_id = $res->data->id;
            $payment->charged_amount = $res->data->charged_amount;
            $payment->processor_response = $res->data->processor_response;
            $payment->save();

            $user_id = $payment->user_id;
            $user = User::find($user_id);
            $payment->user()->associate($user);

            // dd($payment->user()->associate($user));

            // $payment->notify(new PaymentSuccessfulNotification($payment));
            // dd($payment);

            //save notification
            $notification = new Notification();
            $notification->user_id = auth()->user()->id ?? $user_id;
            $notification->user_type = 'Patient';
            $notification->title = 'Payment was Successful';
            $notification->message = 'Payment was Successful';
            $notification->save();
        }
    }

    public function saveNotPaid($data)
    {
        $payment = Payment::where('customer_email', auth()->user()->email)
            ->where('paymentStatus', 'PENDING')
            ->first();

        if ($payment) {
            $payment->status = $data->status;
            $payment->paymentStatus = 'cancelled';
            $payment->tx_ref = $data->tx_ref;
            $payment->save();
        }

        return $payment;
    }
}