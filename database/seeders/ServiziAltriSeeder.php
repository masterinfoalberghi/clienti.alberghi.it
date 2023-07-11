<?php

namespace Database\Seeders;

use App\Utility;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiziAltriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // LA RECEPTION E' APERTA....
        $servizi_it['1'] = [
            "La receprion è aperta H 24 |0|0|",
            "La receprion è aperta |1|1|",
            "È possibile fare il check-in H 24|0|0|",
            "È possibile fare il fare il check-in|1|1|",
            "È possibile fare il fare il check-out H 24|0|0|",
            "È possibile fare il fare il check-out|1|1|",
        ];

        // AL TUO ARRIVO PUOI SUBITO....
        // formato dati: nome|to_fill_1|to_fill_2|option1;option2;...;option
        $servizi_it['2'] = [
            "entrare in camera se già disponibile|0|0|",
            "lasciare i bagagli in custodia|0|0|;gratis;a pagamento",
            "fare colazione (entro le ore)|1|0|;gratis;a pagamento",
            "fare pranzo (entro le ore)|1|0|;gratis;a pagamento",
            "usufruire del servizio spiaggia|0|0|;gratis;a pagamento",
            "utilizzare i servizi comuni|0|0|",
            "parcheggiare l'auto|0|0|;gratis;a pagamento",
            "usare la piscina|0|0|",
        ];


        $servizi_it['3'] = [
            "lasciare i bagagli in custodia (fino alle ore)|1|0|;gratis;a pagamento",
            "fare colazione (entro le ore)|1|0|;gratis;a pagamento",
            "fare il pranzo|1|0|;gratis;a pagamento",
            "recuperare pranzo se non fatto il giorno d'arrivo|0|0|",
            "usufruire del servizio spiaggia|0|0|;gratis;a pagamento",
            "fare una doccia|0|0|;gratis;a pagamento",
            "utilizzare i servizi comuni|0|0|",
            "usare parcheggio auto (fino alle ore)|1|0|;gratis;a pagamento",
            "usare la piscina|0|0|",
        ];


        foreach ($servizi_it as $gruppo_id => $value_array) {

            foreach ($value_array as $value) {
                $row['gruppo_id'] = $gruppo_id;
                list($nome_it, $to_fill_1, $to_fill_2, $options) = explode('|', $value);
                $row['nome_it'] = $nome_it;
                $row['to_fill_1'] = $to_fill_1;
                $row['to_fill_2'] = $to_fill_2;
                $row['options'] = $options;
                foreach (Utility::linguePossibili() as $lingua) {

                    if ($lingua != 'it') {
                        $col = 'nome_' . $lingua;
                        $row[$col] = Utility::translate($nome_it, $lingua);
                    }
                }
                DB::table('tblServiziInOut')->insert($row);
            }
        }


    }
}
