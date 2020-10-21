<?php

namespace App\Listeners;

use App\Events\AnswerAccepted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AnswerAcceptedListener
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
    public function handle(AnswerAccepted $event)
    {
        DB::table('posts')->where('user_id', Auth::id())->where('id', $event->post->id)->update(['post_status' => 'answered']);
    }
}