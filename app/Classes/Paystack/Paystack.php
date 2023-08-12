<?php

namespace App\Classes\Paystack;

use Illuminate\Support\Facades\Http;

class Paystack
{
    protected $secretKey;
    protected $baseUrl;
    protected $secretHash;


    public function __construct()
    {
        $this->secretKey = config('paystack.secretKey');
        $this->baseUrl = 'https://api.paystack.co';
        $this->secretHash = config('paystack.secretHash');
    }

    public function verifyTransaction($reference)
    {
        $data =  Http::withToken($this->secretKey)
            ->get($this->baseUrl . "/transaction/verify/" . $reference)
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
        // Process Paystack Webhook. https://developer.paystack.com/reference#webhook
        if (request()->header('x-paystack-signature')) {
            // get input and verify paystack signature
            $paystackSignature = request()->header('x-paystack-signature');

            // confirm the signature is right
            if ($paystackSignature == $this->secretHash) {
                return true;
            }
        }
        return false;
    }
}
