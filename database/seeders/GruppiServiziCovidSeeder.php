<?php

namespace Database\Seeders;

use App\Utility;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GruppiServiziCovidSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $gruppi_it = [
            'Regole generali',
            'Distanziamento fisico',
            'Stanze',
            'Ristorante'
        ];

        foreach ($gruppi_it as $value) {
            $row['nome_it'] = $value; 
            foreach (Utility::linguePossibili() as $lingua) {

                if ($lingua != 'it') {
                    $col = 'nome_' . $lingua;
                    $row[$col] = Utility::translate($value, $lingua);
                }
                
            }
            DB::table('tblGruppiServiziCovid')->insert($row);

        }

    }
}
