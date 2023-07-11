<?php

namespace App\Console\Commands;

use App\Utility;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AggiustaServiziInOut extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:aggiustaServiziInOut';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Modifica i servizi del gruppo Checkin Checkout senza rilanciare le migrazioni';

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
        // cancello tutti i servzi con id > 6 (che non sono reception, checkin, checkout)
        DB::table('tblServiziInOut')->whereIn('gruppo_id', [2,3])->delete();

        $servizi_it['2'] = [
            "early check-in se disponibile|0|0|;gratis;a pagamento",
            "lasciare i bagagli in custodia|0|0|;gratis;a pagamento",
            "fare colazione (entro le ore)|1|0|;gratis;a pagamento",
            "fare pranzo (entro le ore)|1|0|;gratis;a pagamento",
            "usufruire del servizio spiaggia|0|0|;gratis;a pagamento",
            "utilizzare i servizi comuni|0|0|",
            "parcheggiare l'auto|0|0|;gratis;a pagamento",
            "usare la piscina|0|0|",
        ];


        $servizi_it['3'] = [
            "late check-out se disponibile |0|0|;gratis;a pagamento",
            "lasciare i bagagli in custodia (fino alle ore)|1|0|;gratis;a pagamento",
            "fare colazione (entro le ore)|1|0|;gratis;a pagamento",
            "fare il pranzo|0|0|;gratis;a pagamento",
            "recuperare pranzo se non fatto il giorno d'arrivo|0|0|",
            "usufruire del servizio spiaggia|0|0|;gratis;a pagamento",
            "fare una doccia|0|0|;gratis;a pagamento",
            "utilizzare i servizi comuni|0|0|",
            "usare parcheggio auto (fino alle ore)|1|0|;gratis;a pagamento",
            "usare la piscina|0|0|",
        ];


        foreach ($servizi_it as $gruppo_id => $value_array) {

            $ordering = 10;
            foreach ($value_array as $value) {
                $row['ordering'] = $ordering;
                $row['gruppo_id'] = $gruppo_id;
                list($nome_it, $to_fill_1, $to_fill_2, $options) = explode('|', $value);
                $row['nome_it'] = $nome_it;
                $row['to_fill_1'] = $to_fill_1;
                $row['to_fill_2'] = $to_fill_2;
                $row['options'] = $options;
                foreach (Utility::linguePossibili() as $lingua) {

                    if ($lingua != 'it'
                    ) {
                        $col = 'nome_' . $lingua;
                        $row[$col] = Utility::translate($nome_it, $lingua);
                    }
                }
                DB::table('tblServiziInOut')->insert($row);
                $ordering = $ordering + 10;
            }
        }
        

    }
}
