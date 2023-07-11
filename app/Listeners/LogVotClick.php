<?php

namespace App\Listeners;

use App\Events\VotClickHandler;
use App\StatVot;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogVotClick
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
     * @param  VotClickHandler  $event
     * @return void
     */
    public function handle(VotClickHandler $event)
    {   

        $stat = StatVot::create([
                    'hotel_id' => $event->dati['hotel_id'],
                    'pagina_id' => $event->dati['pagina_id'],
                    'referer' => (string)$event->dati['ref'],
                    'useragent' => (string)$event->dati['ua'],
                    'IP' => (string)$event->dati['ip']
                    ]);
    }
}
