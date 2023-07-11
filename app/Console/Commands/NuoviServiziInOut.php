<?php

namespace App\Console\Commands;

use App\Hotel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class NuoviServiziInOut extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:serviziInOut';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Popola la tabella tblHoltelServiziInOut con i dati checkin_it, checkout_it, reception1_da, reception1_a, reception_24h della tabella tblHotel';

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
        /**
         * 
         * Check-In
         */
        DB::table('tblHoltelServiziInOut')->truncate();

        $checkInOut = Hotel::attivo()->get(['id', 'checkin_it', 'checkout_it']);

        foreach ($checkInOut as $r) {
            // tipo 12:00
            if ( (strlen($r->checkin_it) == 5 || strlen($r->checkin_it) == 4) &&  str_contains($r->checkin_it, ':')) {
                $data = [];
                // è ora inzio del checkin
                $data['hotel_id'] = $r->id;
                $data['servizio_id'] = 4;
                $data['valore_1'] = $r->checkin_it;
                DB::table('tblHoltelServiziInOut')->insert($data);
            }
            if( (strlen($r->checkout_it) == 5 || strlen($r->checkout_it) == 4) &&  str_contains($r->checkout_it, ':')) {
                $data = [];
                // è ora fine del checkout
                $data['hotel_id'] = $r->id;
                $data['servizio_id'] = 6;
                $data['valore_2'] = $r->checkout_it;
                DB::table('tblHoltelServiziInOut')->insert($data);

            }
            // 10:00 - 20:00
            if(str_contains($r->checkin_it, '-') && strlen($r->checkin_it) < 15) {
                
                list($in, $out) = explode('-', $r->checkin_it);
                $data = [];
                $data['hotel_id'] = $r->id;
                $data['servizio_id'] = 4;
                $data['valore_1'] = $in;
                DB::table('tblHoltelServiziInOut')->insert($data);

                $data = [];
                $data['hotel_id'] = $r->id;
                $data['servizio_id'] = 6;
                $data['valore_2'] = $out;
                DB::table('tblHoltelServiziInOut')->insert($data);

            }
        }

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
