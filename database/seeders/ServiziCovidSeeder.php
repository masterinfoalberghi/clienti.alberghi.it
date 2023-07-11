<?php

namespace Database\Seeders;

use App\Utility;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiziCovidSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $servizi_it['1'] = [
            "0|Rispetto dei protocolli di sicurezza sanitaria",
            "0|Mascherina obbligatoria per staff e ospiti",
            "0|Gel disinfettante in aree comuni",
            "0|Termometro a disposizione",
            "0|Mascherine gratis per gli ospiti",
            "0|Purificatori d'aria installati",
            "0|Entrata e uscita separate",
            "0|Aree comuni sanificate",
            "1|Farmacia più vicina a metri",
            "1|Pronto soccorso più vicino a km",
        ];

        $servizi_it['2'] = [
            "0|Check-in e check-out in sicurezza senza contatto",
            "0|Possibilità di check-in on line",
            "0|Pagamenti in sicurezza senza contatto",
            "0|Plexiglass protettivo tra staff ed ospiti"
        ];

        $servizi_it['3'] = [
            "0|Uso di detergenti e disinfettanti specifici ",
            "0|Alloggi sanificati tra un soggiorno e l'altro",
            "0|Possibile pulizia stanza self-service ",
            "0|Biancheria sanificata con prodotti specifici",
            "0|Kit igienizzante stanza"
        ];

        $servizi_it['4'] = [
            "0|Distanziamento fisico zona ristorante",
            "0|Possibile doppio turno ai pasti",
            "0|Possibilità di usare tavoli all’aperto",
            "0|Sanificazione tavoli / sedie",
            "0|Consegna asporto consentito",
            "0|Stoviglie sterilizzate",
            "0|Colazione da asporto / in camera",
            "0|Pranzo da asporto / in camera",
            "0|Cena da asporto / in camera",
            "0|Buffet servito dallo staff"
        ];




        foreach ($servizi_it as $gruppo_id => $value_array) {

            foreach ($value_array as $value) {
                $row['gruppo_id'] = $gruppo_id;
                list($to_fill, $nome_it) = explode('|', $value);
                $row['nome_it'] = $nome_it;
                $row['to_fill'] = $to_fill;
                foreach (Utility::linguePossibili() as $lingua) {
    
                    if ($lingua != 'it') {
                        $col = 'nome_' . $lingua;
                        $row[$col] = Utility::translate($nome_it, $lingua);
                    }
                }
                DB::table('tblServiziCovid')->insert($row);
            }
        
        }


    }
}







