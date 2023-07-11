<?php

namespace App\Console\Commands;

use App\Hotel;
use Illuminate\Support\Str;
use Illuminate\Console\Command;

class addSlugHotelBologna extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'slug:bologna';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $hotels = Hotel::where("id", ">", 5000)->get();
        foreach($hotels as $hotel):
            $slug = Str::slug($hotel->nome);
            $hotel->slug_it = "emilia-romagna/bologna/" . $slug;
            $hotel->slug_en = "en/emilia-romagna/bologna/" . $slug;
            $hotel->slug_fr = "fr/emilie-romagne/bologne/" . $slug;
            $hotel->slug_de = "de/emilia-romagna/bologna/" . $slug;
            $hotel->save();
        endforeach;
        
    }
}
