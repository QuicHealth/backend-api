<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Appointment;
use App\Classes\Flutterwave\Wave;
use App\Actions\UpdateTimeslotStatus;

class PaymentServices
{

    public function savePayment($data)
    {
        // check if appointment exist in the database
        $appointment = Appointment::find($data['customer']['appointments_id']);

        if (!$appointment) {
            return response()->json([
                'status' => 'error',
                'message' => 'Appointment not found',
            ], 404);
        }

        // check payment status
        $verification =  $this->verifyPayment($data['transaction_id'], $data['payment_gateway_type'], $appointment);

        if ($verification['status'] === 'success') {

            // update timeslot
            $this->updateTimeslot($appointment);

            $payload = [
                'user_id' => $data['customer']['user_id'],
                'appointments_id' => $data['customer']['appointments_id'],
                'amount' => $data['amount'],
                'paymentStatus' => $verification['data']['status'],
                'tx_ref' => $data['tx_ref'],
                'transaction_id' => $data['transaction_id'],
                'charged_amount' => $data['charged_amount'],
                'currency' => $data['currency'],
                'processor_response' => $data['charge_response_message'],
                'payment_gateway_type' =>  $data['payment_gateway_type'],
            ];


            $payment = Payment::create($payload);

            if ($payment) {
                // send email to patient
                // $payment->user->notify(new \App\Notifications\PaymentNotification($payment));

                // send email to doctor
                // $payment->appointment->doctor->notify(new \App\Notifications\PaymentNotification($payment));

                // send email to admin
                // $payment->appointment->doctor->notify(new \App\Notifications\PaymentNotification($payment));


                // update payment status
                // $payment->paymentStatus = $verification['data']['status'];
                // $payment->save();

                // update appointment payment status
                $appointment->payment_status = 'PAID';
                $appointment->save();


                // create zoom meeting.
                $zoomMeeting = new ZoomServices();

                $data = [
                    'appointment_id' => $appointment->id,
                    'topic' => 'Appointment with ' . $appointment->doctor->name,
                    'agenda' => $appointment->user->name . ' will be have a consultions with ' . $appointment->doctor->name,
                    'duration' => 30,

                ];

                $meeting = $zoomMeeting->create($data);

                return $meeting;
            }
        } else {
            return $verification;
        }
    }

    protected function verifyPayment($tx_id, $payment_gateway, $appointment)
    {
        if ($payment_gateway == 'flutterwave') {

            $response = $this->verifyFlutterwavePayment($tx_id);

            return $response;
        }
    }

    public function updateTimeslot($appointment)
    {
        $doctor_id = $appointment->doctor_id;
        $date = $appointment->date;
        $time_slots = [
            "start" =>  $appointment->start_time,
            "end" =>  $appointment->end_time,
        ];
        return UpdateTimeslotStatus::run($doctor_id, $date, $time_slots);
    }

    public function verifyFlutterwavePayment($tx_id)
    {
        // verify flutterwave payment in laravel
        $wave = new Wave();
        $response = $wave->verifyTransaction($tx_id);

        return $response;
    }
}
