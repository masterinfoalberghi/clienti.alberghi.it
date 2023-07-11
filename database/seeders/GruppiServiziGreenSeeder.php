<?php

namespace Database\Seeders;

use App\Utility;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GruppiServiziGreenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $gruppi_it = [
            'PACKAGING E MATERIALI - Questa struttura sceglie di...',
            'RACCOLTA DIFFERENZIATA - Questa struttura mette a disposizione contenitori per la raccolta differenziata...',
            'FOOD & BEVERAGE - Questa stuttura sceglie di servirti...',
            'RISPARMIO ENERGETICO - Questa struttura protegge l\'ambiente grazie a...',
            'COMUNICAZIONE - Questa struttura promuove...',
        ];

        foreach ($gruppi_it as $key => $value) {
            $row['id'] = $key + 1;
            $row['nome_it'] = $value;
            foreach (Utility::linguePossibili() as $lingua) {

                if ($lingua != 'it') {
                    $col = 'nome_' . $lingua;
                    $row[$col] = Utility::translate($value, $lingua);
                }
            }
            DB::table('tblGruppiServiziGreen')->insert($row);
        }
    }
}
