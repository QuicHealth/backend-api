<?php

namespace App\Handlers;

use Spatie\WebhookClient\Jobs\ProcessWebhookJob;


class WebhookHandler extends ProcessWebhookJob
{
    public function handler()
    {
        logger('I was here');
        logger($this->WebhookCall);
    }
}