<?php

namespace App\Listeners;

use App\Events\VaatClickHandler;
use App\StatVaat;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogVaatClick
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
     * @param  VaatClickHandler  $event
     * @return void
     */
    public function handle(VaatClickHandler $event)
    {
        $stat = StatVaat::create([
                    'hotel_id' => $event->dati['hotel_id'],
                    'pagina_id' => $event->dati['pagina_id'],
                    'referer' => (string)$event->dati['ref'],
                    'useragent' => (string)$event->dati['ua'],
                    'IP' => (string)$event->dati['ip']
                    ]);
    }
}
