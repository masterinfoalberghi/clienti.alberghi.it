<?php

namespace App\Listeners;

use App\Events\MailSchedaInviataHandler;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogMailSchedaInviata
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
     * @param  MailSchedaInviataHandler  $event
     * @return void
     */
    public function handle(MailSchedaInviataHandler $event)
    {
        //
    }
}
