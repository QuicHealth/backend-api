<?php

namespace App\Actions;

use App\Models\Payment;
use App\Facades\Monnify;
use App\Models\Appointment;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdatePaymentAction
{
    use AsAction;

    protected $timeslots = [];

    public function handle($transaction)
    {

        $confirmation = $this->paymentConfirmation($transaction->transactionReference);

        if ($confirmation->paymentStatus === 'PAID') {

            $this->runUpdates($confirmation, $transaction);

            return response([
                'status' => 'payment was successfully processed',
            ], 200);
        } else {
            return response([
                'status' => 'payment was not successful',
            ], 500);
        }
    }

    public function runUpdates($confirmation, $transaction)
    {
        // $this->updatePayment($confirmation);


        dd($this->updatePayment($confirmation));
        $appointment =  $this->updateAppointment($transaction->appointments_id);

        $this->timeslots['start'] = $appointment->start;
        $this->timeslots['end'] = $appointment->end;

        UpdateTimeslotStatus::run($appointment->doctor_id, $appointment->date, $this->timeslots);
    }

    public function paymentConfirmation($txnReference)
    {

        $response = Monnify::Transactions()->getTransactionStatus($txnReference);

        return $response;
    }

    public function updatePayment($response)
    {
        Payment::updateOrCreate(
            [
                'paymentReference' => $response->paymentReference,
                'transactionReference' => $response->transactionReference
            ],
            [
                'amountPaid' => $response->amountPaid,
                'totalPayable' => $response->totalPayable,
                'settlementAmount' => $response->settlementAmount,
                'paymentStatus' => $response->paymentStatus,
                'paymentDescription' => $response->paymentDescription,
                'currency' => $response->currency,
                'paymentMethod' => $response->paymentMethod,
                'paidOn' => $response->paidOn,

            ]
        );
    }

    public function updateAppointment($appointment_id)
    {
        $appointment = Appointment::find($appointment_id);

        if ($appointment) {
            $appointment->payment_status = 'PAID';
            $appointment->save();

            return $appointment;
        } else {
            return false;
        }
    }
}