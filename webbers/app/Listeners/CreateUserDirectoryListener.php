<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Storage;
use App\Events\CreateUserDirectoryEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;


class CreateUserDirectoryListener
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
     * @param  object  $event
     * @return void
     */
    public function handle(CreateUserDirectoryEvent $event)
    {
        Storage::disk('users')->makeDirectory($event->identifier);
    }
}