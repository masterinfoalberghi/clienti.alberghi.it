<?php

namespace Database\Seeders;

use App\Utility;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GruppiServiziAltriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $gruppi_it = [
            'Orari...',
            'Al check-in puoi anche...',
            'Al check-out puoi ancora...',
        ];

        foreach ($gruppi_it as $key => $value) {
            $row['id'] = $key+1;
            $row['nome_it'] = $value;
            foreach (Utility::linguePossibili() as $lingua) {

                if ($lingua != 'it') {
                    $col = 'nome_' . $lingua;
                    $row[$col] = Utility::translate($value, $lingua);
                }
            }
            DB::table('tblGruppiServiziInOut')->insert($row);
        }
    }
}
