<?php

namespace App\Listeners;

use App\Events\NotificationReceived;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SendNotification
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
     * @param  \App\Events\NotificationReceived  $event
     * @return void
     */
    public function handle(NotificationReceived $event)
    {
        $userinfo = $event->user;

        $saveNotification = Notification::create(
            [
                'user_id' => '1',
                'user_type' => 'doctor',
                'title' => 'Fever',
                'message' => 'Message',
                'data' => '1'
            ]
        );
        return $saveNotification;
    }
}
