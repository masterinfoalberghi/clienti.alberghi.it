<?php

namespace App\Console\Commands;

use App\Hotel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class NuoviServiziInOutImportaReception extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:serviziInOutImportaReception';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ripopola la tabella tblHoltelServiziInOut con i dati reception1_da, reception1_a, reception_24h della tabella tblHotel';

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

        // cancello tutti i servizi reception degli hotel
        DB::table('tblHoltelServiziInOut')->whereIn('servizio_id',[1,2])->delete();


        $reception = Hotel::attivo()->get(['id', 'reception1_da', 'reception1_a', 'reception_24h']);

        foreach ($reception as $r) {
            // tipo 12:00
            if ($r->reception_24h == 1) {
                $data = [];
                // è servizio reception_24
                $data['hotel_id'] = $r->id;
                $data['servizio_id'] = 1;
                $data['valore_1'] = null;
                $data['valore_2'] = null;
                DB::table('tblHoltelServiziInOut')->insert($data);
            } else {
                $data = [];
                $data['hotel_id'] = $r->id;
                $data['servizio_id'] = 2;
                
                list($h,$m) = explode(':', $r->reception1_da);
                if($m == '0') {
                    $data['valore_1'] = $r->reception1_da.'0';
                } else {
                    $data['valore_1'] = $r->reception1_da;
                }

                list($h, $m) = explode(':', $r->reception1_a);
                if ($m == '0') {
                    $data['valore_2'] = $r->reception1_a.'0';
                } else {
                    $data['valore_2'] = $r->reception1_a;
                }

                DB::table('tblHoltelServiziInOut')->insert($data);
            }
        }
        
    }
}
