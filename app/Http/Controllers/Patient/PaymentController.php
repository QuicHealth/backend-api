<?php

namespace App\Http\Controllers\Patient;

use App\Models\Payment;
use App\Facades\Monnify;
use Illuminate\Http\Request;
use App\Classes\StringGenerator;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Events\NewWebHookCallReceived;
use App\Models\Payment as WebHookCall;
use App\Classes\Funtions\MonnifyPaymentMethod;
use App\Classes\Funtions\MonnifyPaymentMethods;


class PaymentController extends Controller
{

    public function makePayment(Request $request)
    {
        // dd($this->generateRandomString());
        $validatedPayload = $request->validate([
            'amount' => 'required',
            'customerName' => 'required',
            'customerEmail' => 'required',
            'paymentDescription' => 'required',
            // 'redirectUrl' => 'required',
        ]);
        $paymentReference = $this->generateRandomString();

        $response = Monnify::Transactions()->initializeTransaction(
            $validatedPayload['amount'],
            $validatedPayload['customerName'],
            $validatedPayload['customerEmail'],
            $paymentReference,
            $validatedPayload['paymentDescription'],
            $validatedPayload['redirectUrl'] = 'http:127.0.0.1:8000/api/v1/webhook/transaction-completion',
            new MonnifyPaymentMethods(MonnifyPaymentMethod::CARD(), MonnifyPaymentMethod::ACCOUNT_TRANSFER()),
        );

        $payment = Payment::create([
            'transactionReference' => "",
            'paymentReference' => $paymentReference,
            'amountPaid' => $validatedPayload['amount'],
            'totalPayable' => "",
            'paymentStatus' => "Not Paid",
            'paymentDescription' => $validatedPayload['paymentDescription'],
            'transactionHash' => "",
            'currency' => "",
            'paymentMethod' => "",
            'paidOn' => "",
        ]);

        if ($payment) {
            return response([
                'status' => $response,
            ], 200);
        } else {
            return response([
                'status' => "error",
            ], 500);
        }
    }

    public function txnCompletion(Request $request)
    {
        $request->validate([
            'eventData.transactionReference' => 'required',
            'eventData.paymentReference' => 'required',
            'eventData.amountPaid' => 'required',
            'eventData.totalPayable' => 'required',
            'eventData.paidOn' => 'required',
            'eventData.paymentStatus' => 'required',
            'eventData.paymentDescription' => 'required',
            'eventData.currency' => 'required',
            'eventData.paymentMethod' => 'required',
        ]);

        $isValidHash = false;
        $webHookCall = $this->initRequest($request, $isValidHash);
        event(new NewWebHookCallReceived($webHookCall, $isValidHash, NewWebHookCallReceived::WEB_HOOK_EVENT_TXN_COMPLETION_CALL));
    }

    public function payment_status(Request $request, $txnReference)
    {
        $response = Monnify::Transactions()->getTransactionStatus($txnReference);
        return $response;
    }

    private function initRequest($request, &$isValidHash)
    {
        $monnifySignature = $request->header('monnify-signature');

        $stringifiedData = json_encode($request->all());
        $payload = $request->input('eventData');

        $webHookCall = new WebHookCall($payload);
        $webHookCall->transactionHash = $monnifySignature;
        $webHookCall->stringifiedData = $stringifiedData;

        $calculatedHash = Monnify::computeRequestValidationHash($stringifiedData);
        //    Log::info("$transactionHash\n\r{$webHookCall->stringifiedData}\n\r$calculatedHash");
        $isValidHash = $calculatedHash == $monnifySignature;
        return $webHookCall;
    }

    private function generateRandomString()
    {

        $customAlphabet = '0123456789ABCDEF';

        // Create new instance of generator class.
        // Set initial alphabet.
        $generator = new StringGenerator($customAlphabet);

        // Set token length.
        $tokenLength = 15;

        // Call method to generate random string.
        $token = $generator->generate($tokenLength);

        return $token;
    }
}