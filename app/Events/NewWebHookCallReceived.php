<?php

namespace App\Events;

use Illuminate\Support\Facades\Log;
use App\Models\Payment as WebHookCall;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class NewWebHookCallReceived
{
    use Dispatchable, SerializesModels;

    public const WEB_HOOK_LEGACY_CALL = "legacy";
    public const WEB_HOOK_EVENT_TXN_COMPLETION_CALL = "txn_completion";
    public const WEB_HOOK_EVENT_REFUND_COMPLETION_CALL = "refund_completion";
    public const WEB_HOOK_EVENT_DISBURSEMENT_CALL = "disbursement";
    public const WEB_HOOK_EVENT_SETTLEMENT_CALL = "settlement";

    public $webHookCall;
    public $isValidTransactionHash;
    public $webhookType;

    public function __construct(WebHookCall $webHookCall, bool $isValidTransactionHash, string $webhookType = self::WEB_HOOK_LEGACY_CALL)
    {
        $this->webHookCall = $webHookCall;
        $this->isValidTransactionHash = $isValidTransactionHash;
        $this->webhookType = $webhookType;
    }
}