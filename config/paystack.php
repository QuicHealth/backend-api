<?php

return [

    /**
     * Live Public Key From Paystack Dashboard
     *
     */
    'livePublicKey' => env('PS_LIVE_PUBLIC_KEY'),

    /**
     * Live Secret Key From Paystack Dashboard
     *
     */
    'liveSecretKey' => env('PS_LIVE_SECRET_KEY'),

    /**
     * Test Public Key From Paystack Dashboard
     *
     */
    'testPublicKey' => env('PS_TEST_PUBLIC_KEY'),

    /**
     * Test Secret Key From Paystack Dashboard
     *
     */
    'testSecretKey' => env('PS_TEST_SECRET_KEY'),

    /**
     * Paystack Payment URL
     *
     */
    'paymentUrl' => env('PAYSTACK_PAYMENT_URL'),

    /**
     * Optional email address of the merchant
     *
     */
    'merchantEmail' => env('MERCHANT_EMAIL'),

];
