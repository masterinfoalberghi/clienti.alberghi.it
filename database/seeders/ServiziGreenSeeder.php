<?php

namespace Database\Seeders;

use App\Utility;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiziGreenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $servizi_it['1'] = [
            "Utilizzare dispenser ecologici",
            "Utilizzare carta riciclata (carta igienica, carta da stampa)",
            "Eliminare la plastica nelle camere e /o in cucina (usa bicchieri, cannucce e piatti riutilizzabili e/o compostabili; non utilizza confezioni monodose)",
            "Privilegiare il digitale (non stampa menù, rende disponibili quotidiani e riviste in formato digitale)",
            "Utilizzare prodotti per la pulizia ecologici e/o biodegradabili",
            "Privilegiare arredi ecocompatibili e artigianali",
        ];

        $servizi_it['2'] = [
            "Negli spazi comuni",
            "In camera",
        ];

        $servizi_it['3'] = [
            "Cibo biologico e prodotti km0 e/o ecosolidali",
            "Acqua microfiltrata",
            "Acqua h24 da distributore",
        ];

        $servizi_it['4'] = [
            "Uso di energia rinnovabile (pannelli solari, fornitori di energia pulita)",
            "Raccolta dell'acqua piovana",
            "Scelta di elettrodomestici a basso consumo",
            "Uso di riduttori di flusso per l'acqua",
            "Uso di lampadine a basso consumo",
            "Installazione infissi e finestre termoisolanti",
        ];

        $servizi_it['5'] = [
            "Scelte sostenibili (invita i suoi ospiti ad utilizzare la biancheria da camera e da bagno per più giorni)",
            "Una mobilità sostenibile (dispone di torrette per ricarica auto elettriche, servizio noleggio biciclette, servizio transfer)",
        ];




        foreach ($servizi_it as $gruppo_id => $value_array) {

            foreach ($value_array as $value) {
                $row['gruppo_id'] = $gruppo_id;
                $row['nome_it'] = $value;
                foreach (Utility::linguePossibili() as $lingua) {

                    if ($lingua != 'it') {
                        $col = 'nome_' . $lingua;
                        $row[$col] = Utility::translate($value, $lingua);
                    }
                }
                DB::table('tblServiziGreen')->insert($row);
            }
        }
    }
}
