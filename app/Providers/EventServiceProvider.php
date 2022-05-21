<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use App\Events\NewWebHookCallReceived;
use Illuminate\Auth\Events\Registered;
use App\Listeners\MonnifyNotificationListener;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return true;
    }

    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        NewWebHookCallReceived::class => [
            MonnifyNotificationListener::class,
            //    ... Other Listener you wish to also receive the WebHook call event
        ],

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}