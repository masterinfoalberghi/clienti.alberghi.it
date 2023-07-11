<?php

namespace Database\Seeders;

use App\Utility;
use App\CategoriaPoi;
use Illuminate\Database\Seeder;

class CategoriePoiSeeder extends Seeder
{
    private static $categorie = ['Distanze', 'Come raggiungere la struttura', 'Trasporti locali', 'In zona trovi..', 'Parcheggi'];
    private static $lang = ['en', 'fr', 'de'];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (self::$categorie as $cat) {
            $categoria = new CategoriaPoi;
            $categoria->nome_it = $cat;
            foreach (self::$lang as $lang) {
                $col = 'nome_' . $lang;
                $categoria->$col = Utility::translate($cat, $lang);
            }
            $categoria->save();
        }
    }
}
