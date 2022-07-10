<?php

namespace App\Actions;

use App\Models\Payment;
use App\Facades\Monnify;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Classes\Funtions\MonnifyPaymentMethod;
use App\Classes\Funtions\MonnifyPaymentMethods;

class MakePaymentAction
{
    use AsAction;

    public function handle($payload)
    {

        $reponse = $this->makePayment($payload);

        if ($reponse) {
            $savePayment = $this->saveResponse($reponse, $payload['appointment_id']);
            if ($savePayment) {
                return $reponse;
            } else {
                return false;
            }
            return $reponse;
        } else {
            return response([
                'status' => 'failed',
            ]);
        }
    }

    public function makePayment($payload)
    {

        $response = Monnify::Transactions()->initializeTransaction(
            $payload['amount'],
            $payload['customerName'],
            $payload['customerEmail'],
            $payload['paymentReference'],
            $payload['paymentDescription'],
            $payload['redirectUrl'] = route('payment.status'),
            new MonnifyPaymentMethods(MonnifyPaymentMethod::CARD(), MonnifyPaymentMethod::ACCOUNT_TRANSFER()),
        );

        return $response;
    }

    public function saveResponse($response, $appointments_id)
    {
        $payment = Payment::create([
            'appointments_id' =>  $appointments_id,
            'transactionReference' => $response->transactionReference,
            'paymentReference' => $response->paymentReference,
            'amountPaid' => "",
            'totalPayable' => "",
            'settlementAmount' => "",
            'paymentStatus' => "PENDING",
            'paymentDescription' => "",
            'transactionHash' => "",
            'currency' => "",
            'paymentMethod' => "",
            'paidOn' => "",
        ]);

        return $payment;
    }
}
