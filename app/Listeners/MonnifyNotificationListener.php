<?php

namespace App\Listeners;

use App\Events\NewWebHookCallReceived;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Payment;
use App\Exceptions\MonnifyFailedRequestException;
use App\Facades\Monnify;
use Illuminate\Support\Facades\Log;


class MonnifyNotificationListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\NewWebHookCallReceived  $event
     * @return void
     */
    public function handle(NewWebHookCallReceived $event)
    {
        logger("i got to handle");
        $payload = $event->webHookCall;
        logger("i got to handle");
        logger($event->webhookType);

        switch ($event->webhookType) {
            case NewWebHookCallReceived::WEB_HOOK_EVENT_TXN_COMPLETION_CALL: {
                    if ($payload->paymentStatus == 'PAID') {
                        $payloadHash = $payload->transactionHash;
                        logger("inside switch case NewWebHookCallReceived");

                        $computedHash = Monnify::Transactions()->calculateHash($payload->paymentReference, $payload->amountPaid, $payload->paidOn, $payload->transactionReference);

                        logger($payload);

                        if ($payloadHash === $computedHash) { //Validate hash to make sure this call is from monnify server
                            try {
                                $transactionObject = Monnify::Transactions()->getTransactionStatus($payload->transactionReference);
                                logger("inside try case NewWebHookCallReceived");
                                //                    Confirm that this is a successfully paid transaction, or log other transactions in else block
                                if (($payload->paymentStatus == $transactionObject->paymentStatus) &&
                                    ($payload->amountPaid == $transactionObject->amountPaid)
                                ) {
                                    if ($payload->product['type'] == 'RESERVED_ACCOUNT') { //If payload transaction type is RESERVED_ACCOUNT then, one of your customer just transferred money to their reserved account
                                        //                            1. Extract Transaction Unique ID to confirm if you already credited this user for this transaction before
                                        $uniqueTxnID = $payload->transactionReference;
                                        //                            2. Check for any transaction within my application with this Unique ID $uniqueTxnID
                                        $txn = Payment::where('txn_ref', $uniqueTxnID)->get()->first();
                                        //                            3. If we haven't credited this user before then this transaction should not exist within our record, Only then should this transaction be attended to else just ignore
                                        if (!isset($txn->id)) {
                                            //                                4. Get amount user transferred in
                                            $amountReceived = $transactionObject->amountPaid;
                                            //                                5. (Optional) You can take out transaction fee base on your model here
                                            $amountToCreditUser = $amountReceived - config('my_site_config.reserved_account_txn_fee');

                                            //                                6. Get the user email and account reference that own this reserved account to identify user to credit
                                            $userEmail = $payload->customer['email'];
                                            $userReservedAccountReference = $payload->product['reference'];

                                            //                                7. Create a Transaction with $amountToCreditUser, $userEmail and $userReservedAccountReference
                                            //                                   NOTE: Transaction should have Observer to auto credit or debit user whenever a transaction is created


                                        }
                                    } else {
                                        //This successful payment is not a reserved account payment
                                        // Extract payment information and process, some of the information available on payload is as highlighted below:
                                        $payment = Payment::where('paymentReference', $payload->paymentReference)->get()->first();
                                        logger($payment);

                                        $payment->transactionReference = $payload->transactionReference;
                                        $payment->paymentReference = $payload->paymentReference;
                                        $payment->paymentStatus = $payload->paymentStatus;
                                        $payment->paymentMethod = $payload->paymentMethod;
                                        $payment->paidOn = $payload->paidOn;
                                        $payment->amountPaid = $payload->amountPaid;
                                        $payment->totalPayable = $payload->totalPayable;
                                        $payment->transactionHash = $payload->transactionHash;

                                        $payment->save();
                                    }
                                }
                            } catch (MonnifyFailedRequestException $exception) {
                                Log::channel('monnify')->error($exception->getMessage() . "\n\r" . $payload->transactionReference);
                            }
                        }
                    }
                    break;
                }
            case NewWebHookCallReceived::WEB_HOOK_EVENT_REFUND_COMPLETION_CALL: {
                    // Do Something
                    break;
                }
            case NewWebHookCallReceived::WEB_HOOK_EVENT_DISBURSEMENT_CALL: {
                    // Do Something
                    break;
                }
            case NewWebHookCallReceived::WEB_HOOK_EVENT_SETTLEMENT_CALL: {
                    // Do Something
                    break;
                }
        }
    }
}