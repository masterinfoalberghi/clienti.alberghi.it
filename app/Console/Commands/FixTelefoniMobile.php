<?php

namespace App\Console\Commands;

use App\Hotel;
use Illuminate\Console\Command;

class FixTelefoniMobile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:telefoni_call';

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
        $hotels = Hotel::where('localita_id', 52)->select("id", "telefono", "cell", "telefoni_mobile_call")->get();

        foreach($hotels as $hotel):
            if ($hotel->telefoni_mobile_call == "") {
               $hotel->telefoni_mobile_call =  preg_replace('/\D/', "", $hotel->telefono);

            //    $hotel->telefoni_mobile_call = preg_replace('/\D/', "", $hotel->telefono);

               $hotel->save();
              
            }
        endforeach;




        
        
        // $hotel["telefoni_mobile_call"] = preg_replace('/\s+/', "", $structure->phone_mobile);

        // dd($hotels);
    }
}
