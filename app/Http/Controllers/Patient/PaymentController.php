<?php

namespace App\Http\Controllers\Patient;

use App\Models\Payment;
use App\Facades\Monnify;
use Illuminate\Http\Request;
use App\Classes\StringGenerator;
use App\Actions\MakePaymentAction;
use App\Actions\UpdatePaymentAction;
use App\Http\Controllers\Controller;
use App\Events\NewWebHookCallReceived;
use App\Models\Payment as WebHookCall;


class PaymentController extends Controller
{

    protected $payload = [];

    // public function __construct()
    // {
    //     $this->middleware('auth:api');
    // }

    public function makePayment(Request $request)
    {
        $validatedPayload = $request->validate([
            'appointment_id' => 'required|integer',
            'amount' => 'required',
            'customerName' => 'required',
            'customerEmail' => 'required',
            'paymentDescription' => 'required',
        ]);

        $this->payload = $validatedPayload;
        $paymentReference = $this->generateRandomString();

        $this->payload['paymentReference'] = $paymentReference;

        $response = MakePaymentAction::run($this->payload);

        return $response;
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

    public function payment_status(Request $request)
    {

        $transaction = Payment::where('paymentReference', $request->query('paymentReference'))->first();

        if ($transaction) {
            return UpdatePaymentAction::run($transaction);
        } else {
            return response([
                'status' => "error",
            ], 500);
        }
    }


    public function payment_confirmation($txnReference)
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