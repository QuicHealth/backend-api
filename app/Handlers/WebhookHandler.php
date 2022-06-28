<?php

namespace App\Handlers;

class WebhookHandler
{
    public function handler()
    {
        logger('I was here');
        logger($this->WebhookCall);
    }
}