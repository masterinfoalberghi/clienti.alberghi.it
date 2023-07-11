<?php

namespace App\Listeners;

use App\Events\VetrinaClickHandler;
use App\StatVetrine;
use App\SlotVetrina;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogVetrinaClick
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
     * @param  VetrinaClickHandler  $event
     * @return void
     */
    public function handle(VetrinaClickHandler $event)
    {

        /*
         * Controllo che i parametri presentati abbiano una corrispondenza,
         * questo perchè abbiamo visto che ci sono bot che provano a valorizzare casualmente i parametri
         * e non vogliamo che questo rumore sporchi le statistiche
         * 
         * @author Luca Battarra
         */
        $ok = SlotVetrina::where("id", $event->dati['slot_id'])
            ->where("hotel_id", $event->dati['hotel_id'])            
            ->where("vetrina_id", $event->dati['vetrina_id'])
            ->first();

        if($ok)
        {

         $stat = StatVetrine::create([
                                'hotel_id' => $event->dati['hotel_id'],
                                'slot_id' => $event->dati['slot_id'],
                                'vetrina_id' => $event->dati['vetrina_id'],
                                'referer' => (string)$event->dati['ref'],
                                'useragent' => (string)$event->dati['ua'],
                                'IP' => (string)$event->dati['ip']
                                ]);
         }
    }
}
