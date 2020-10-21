<?php

namespace App\Providers;

use App\Events\AnswerAccepted;
use App\Events\CreateUserDirectoryEvent;
use App\Events\OAuthenticated;
use App\Listeners\AnswerAcceptedListener;
use App\Listeners\CreateUserDirectoryListener;
use App\Listeners\OAuthenticatedListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        CreateUserDirectoryEvent::class => [CreateUserDirectoryListener::class],
        OAuthenticated::class => [OAuthenticatedListener::class,],
        AnswerAccepted::class => [
            AnswerAcceptedListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}