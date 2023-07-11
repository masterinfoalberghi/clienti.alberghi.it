<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class HotelViewHandler extends Event
{
    use SerializesModels;
    public $dati;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(array $dati)
    {
        // rendo i dati visibili al listener
       $this->dati = $dati;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }






}
