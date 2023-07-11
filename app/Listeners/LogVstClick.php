<?php

namespace App\Listeners;

use App\Events\VstClickHandler;
use App\StatVst;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogVstClick
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
     * @param  VstClickHandler  $event
     * @return void
     */
    public function handle(VstClickHandler $event)
    {
        $stat = StatVst::create([
                    'hotel_id' => $event->dati['hotel_id'],
                    'pagina_id' => $event->dati['pagina_id'],
                    'referer' => (string)$event->dati['ref'],
                    'useragent' => (string)$event->dati['ua'],
                    'IP' => (string)$event->dati['ip']
                    ]);
    }
}
