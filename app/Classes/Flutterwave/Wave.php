<?php

namespace App\Classes\Flutterwave;

use Illuminate\Support\Facades\Http;
use App\Classes\Flutterwave\Helpers\Banks;
use App\Classes\Flutterwave\Helpers\Payments;
use App\Classes\Flutterwave\Helpers\Transfers;

/**
 * Flutterwave's Rave payment class
 * @author Ozioma Dike <dikep15@gmail.com>
 * @version 1
 **/

class Wave
{
    protected $publicKey;
    protected $secretKey;
    protected $secretHash;
    protected $baseUrl;

    public function __construct()
    {
        $this->publicKey = config('flutterwave.publicKey');
        $this->secretKey = config('flutterwave.secretKey');
        $this->secretHash = config('flutterwave.secretHash');
        $this->baseUrl = 'https://api.flutterwave.com/v3';
    }


    public function verifyTransaction($id)
    {
        // dd($id);
        $data =  Http::withToken($this->secretKey)
            ->get($this->baseUrl . "/transactions/" . $id . '/verify')
            ->json();

        return $data;
    }

    /**
     * Confirms webhook `verifi-hash` is the same as the environment variable
     * @param $data
     * @return boolean
     */
    public function verifyWebhook()
    {
        // Process Paystack Webhook. https://developer.flutterwave.com/reference#webhook
        if (request()->header('verif-hash')) {
            // get input and verify paystack signature
            $flutterwaveSignature = request()->header('verif-hash');

            // confirm the signature is right
            if ($flutterwaveSignature == $this->secretHash) {
                return true;
            }
        }
        return false;
    }

    /**
     * Payments
     * @return Payments
     */
    public function payments()
    {
        $payments = new Payments($this->publicKey, $this->secretKey, $this->baseUrl);
        return $payments;
    }

    /**
     * Banks
     * @return Banks
     */
    public function banks()
    {
        $banks = new Banks($this->publicKey, $this->secretKey, $this->baseUrl);
        return $banks;
    }

    /**
     * Transfers
     * @return Transfers
     */
    public function transfers()
    {
        $transfers = new Transfers($this->publicKey, $this->secretKey, $this->baseUrl);
        return $transfers;
    }
}
