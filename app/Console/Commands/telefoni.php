<?php

namespace App\Console\Commands;

use App\Hotel;
use Illuminate\Console\Command;

class telefoni extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telefoni';

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
        $telefoni = Hotel::where("id", ">", "20000")->get();
    
        foreach($telefoni as $telefono):

            $phones = [];
            !is_null($telefono->telefono) && $telefono->telefono != "" ? $phones[] = $telefono->telefono : null;
            !is_null($telefono->cell)     && $telefono->cell != "" ? $phones[] = $telefono->cell : null;
            !is_null($telefono->whatsapp) && $telefono->whatsapp != "" ? $phones[] = $telefono->whatsapp : null;

            if (isset($phones[0])) {
                $phone = preg_replace('/[^0-9]/', '', $phones[0]);
                // dd(implode("",$phone));
                // if ($telefono->telefoni_mobile_call == "" || is_null($telefono->telefoni_mobile_call))
                    $telefono->telefoni_mobile_call = $phone;
                $telefono->save();
            }
        endforeach;
    }
}
