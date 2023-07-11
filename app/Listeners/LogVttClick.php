<?php

namespace App\Listeners;

use App\Events\VttClickHandler;
use App\StatVtt;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogVttClick
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
     * @param  VttClickHandler  $event
     * @return void
     */
    public function handle(VttClickHandler $event)
    {
        $stat = StatVtt::create([
                    'hotel_id' => $event->dati['hotel_id'],
                    'pagina_id' => $event->dati['pagina_id'],
                    'referer' => (string)$event->dati['ref'],
                    'useragent' => (string)$event->dati['ua'],
                    'IP' => (string)$event->dati['ip']
                    ]);
    }
}
