<?php
namespace App\Helpers;

use App\Hotel;
use App\Utility;
use App\CmsPagina;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\App;

class NuovaLocalita
{   

    public static function getSlugStruttura($id, $lang, $title, $localita) {

        $slug = Str::slug($title);
        
        $geoslug = [

                2 => [
                    "it" => "emilia-romagna/rimini",
                    "en" => "en/emilia-romagna/rimini",
                    "fr" => "fr/emilie-romagne/rimini", 
                    "de" => "de/emilia-romagna/rimini",
                ],  

                3 => [
                    "it" => "emilia-romagna/rimini",
                    "en" => "en/emilia-romagna/rimini",
                    "fr" => "fr/emilie-romagne/rimini", 
                    "de" => "de/emilia-romagna/rimini",
                ], 

                4 => [
                    "it" => "emilia-romagna/riccione",
                    "en" => "en/emilia-romagna/riccione",
                    "fr" => "fr/emilie-romagne/riccione", 
                    "de" => "de/emilia-romagna/riccione",
                ],  

                5 => [
                    "it" => "emilia-romagna/cattolica",
                    "en" => "en/emilia-romagna/cattolica",
                    "fr" => "fr/emilie-romagne/cattolica", 
                    "de" => "de/emilia-romagna/cattolica",
                ],

                6 => [
                    "it" => "emilia-romagna/misano-adriatico",
                    "en" => "en/emilia-romagna/misano-adriatico",
                    "fr" => "fr/emilie-romagne/misano-adriatico", 
                    "de" => "de/emilia-romagna/misano-adriatico",
                ],

                7 => [
                    "it" => "marche/gabicce-mare",
                    "en" => "en/marche/gabicce-mare",
                    "fr" => "fr/marche/gabicce-mare",
                    "de" => "de/marche/gabicce-mare",
                ],

                8 => [
                    "it" => "emilia-romagna/rimini",
                    "en" => "en/emilia-romagna/rimini",
                    "fr" => "fr/emilie-romagne/rimini", 
                    "de" => "de/emilia-romagna/rimini",
                ],  

                9 => [
                    "it" => "emilia-romagna/rimini",
                    "en" => "en/emilia-romagna/rimini",
                    "fr" => "fr/emilie-romagne/rimini", 
                    "de" => "de/emilia-romagna/rimini",
                ],

                11 => [
                    "it" => "emilia-romagna/rimini",
                    "en" => "en/emilia-romagna/rimini",
                    "fr" => "fr/emilie-romagne/rimini", 
                    "de" => "de/emilia-romagna/rimini",
                ],

                12 => [
                    "it" => "emilia-romagna/san-mauro-pascoli/",
                    "en" => "en/emilia-romagna/san-mauro-pascoli/",
                    "fr" => "fr/emilie-romagne/san-mauro-pascoli/",
                    "de" => "de/emilia-romagna/san-mauro-pascoli/",
                ],

                14 => [
                    "it" => "emilia-romagna/rimini",
                    "en" => "en/emilia-romagna/rimini",
                    "fr" => "fr/emilie-romagne/rimini", 
                    "de" => "de/emilia-romagna/rimini",
                ], 

                15 => [
                    "it" => "emilia-romagna/rimini",
                    "en" => "en/emilia-romagna/rimini",
                    "fr" => "fr/emilie-romagne/rimini", 
                    "de" => "de/emilia-romagna/rimini",
                ], 

                16 => [
                    "it" => "emilia-romagna/rimini",
                    "en" => "en/emilia-romagna/rimini",
                    "fr" => "fr/emilie-romagne/rimini", 
                    "de" => "de/emilia-romagna/rimini",
                ], 

                17 => [
                    "it" => "emilia-romagna/rimini",
                    "en" => "en/emilia-romagna/rimini",
                    "fr" => "fr/emilie-romagne/rimini", 
                    "de" => "de/emilia-romagna/rimini",
                ], 

                18 => [
                    "it" => "emilia-romagna/bellaria-igea-marina",
                    "en" => "en/emilia-romagna/bellaria-igea-marina",
                    "fr" => "fr/emilie-romagne/bellaria-igea-marina", 
                    "de" => "de/emilia-romagna/bellaria-igea-marina",
                ], 

                19 => [
                    "it" => "emilia-romagna/bellaria-igea-marina",
                    "en" => "en/emilia-romagna/bellaria-igea-marina",
                    "fr" => "fr/emilie-romagne/bellaria-igea-marina", 
                    "de" => "de/emilia-romagna/bellaria-igea-marina",
                ], 

                20 => [
                    "it" => "emilia-romagna/cesenatico",
                    "en" => "en/emilia-romagna/cesenatico",
                    "fr" => "fr/emilie-romagne/cesenatico", 
                    "de" => "de/emilia-romagna/cesenatico",
                ], 

                21 => [
                    "it" => "emilia-romagna/cesenatico",
                    "en" => "en/emilia-romagna/cesenatico",
                    "fr" => "fr/emilie-romagne/cesenatico", 
                    "de" => "de/emilia-romagna/cesenatico",
                ],

                22 => [
                    "it" => "emilia-romagna/gatteo",
                    "en" => "en/emilia-romagna/gatteo",
                    "fr" => "fr/emilie-romagne/gatteo", 
                    "de" => "de/emilia-romagna/gatteo",
                ],

                23 => [
                    "it" => "emilia-romagna/ravenna",
                    "en" => "en/emilia-romagna/ravenna",
                    "fr" => "fr/emilie-romagne/ravenna", 
                    "de" => "de/emilia-romagna/ravenna",
                ],

                24 => [
                    "it" => "emilia-romagna/cervia",
                    "en" => "en/emilia-romagna/cervia",
                    "fr" => "fr/emilie-romagne/cervia", 
                    "de" => "de/emilia-romagna/cervia",
                ],

                25 => [
                    "it" => "emilia-romagna/cervia",
                    "en" => "en/emilia-romagna/cervia",
                    "fr" => "fr/emilie-romagne/cervia", 
                    "de" => "de/emilia-romagna/cervia",
                ],

                26 => [
                    "it" => "emilia-romagna/ravenna",
                    "en" => "en/emilia-romagna/ravenna",
                    "fr" => "fr/emilie-romagne/ravenna", 
                    "de" => "de/emilia-romagna/ravenna",
                ],

                27 => [
                    "it" => "emilia-romagna/ravenna",
                    "en" => "en/emilia-romagna/ravenna",
                    "fr" => "fr/emilie-romagne/ravenna", 
                    "de" => "de/emilia-romagna/ravenna",
                ],

                28 => [
                    "it" => "emilia-romagna/cervia",
                    "en" => "en/emilia-romagna/cervia",
                    "fr" => "fr/emilie-romagne/cervia", 
                    "de" => "de/emilia-romagna/cervia",
                ],

                29 => [
                    "it" => "emilia-romagna/ravenna",
                    "en" => "en/emilia-romagna/ravenna",
                    "fr" => "fr/emilie-romagne/ravenna", 
                    "de" => "de/emilia-romagna/ravenna",
                ],

                30 => [
                    "it" => "emilia-romagna/cesenatico",
                    "en" => "en/emilia-romagna/cesenatico",
                    "fr" => "fr/emilie-romagne/cesenatico", 
                    "de" => "de/emilia-romagna/cesenatico",
                ], 

                31 => [
                    "it" => "emilia-romagna/ravenna",
                    "en" => "en/emilia-romagna/ravenna",
                    "fr" => "fr/emilie-romagne/ravenna", 
                    "de" => "de/emilia-romagna/ravenna",
                ], 

                32 => [
                    "it" => "emilia-romagna/santarcangelo-di-romagna",
                    "en" => "en/emilia-romagna/santarcangelo-of-romagna",
                    "fr" => "fr/emilie-romagne/santarcangelo-fr-romagne", 
                    "de" => "de/emilia-romagna/santarcangelo-in-der-romagna",
                ], 

                34 => [
                    "it" => "emilia-romagna/cervia",
                    "en" => "en/emilia-romagna/cervia",
                    "fr" => "fr/emilie-romagne/cervia", 
                    "de" => "de/emilia-romagna/cervia",
                ], 
                
                39 => [
                    "it" => "emilia-romagna/rimini",
                    "en" => "en/emilia-romagna/rimini",
                    "fr" => "fr/emilie-romagne/rimini", 
                    "de" => "de/emilia-romagna/rimini",
                ],

                40 => [
                    "it" => "emilia-romagna/ravenna",
                    "en" => "en/emilia-romagna/ravenna",
                    "fr" => "fr/emilie-romagne/ravenna", 
                    "de" => "de/emilia-romagna/ravenna",
                ],

                41 => [
                    "it" => "emilia-romagna/ravenna",
                    "en" => "en/emilia-romagna/ravenna",
                    "fr" => "fr/emilie-romagne/ravenna", 
                    "de" => "de/emilia-romagna/ravenna",
                ],

                43 => [
                    "it" => "emilia-romagna/cesenatico",
                    "en" => "en/emilia-romagna/cesenatico",
                    "fr" => "fr/emilie-romagne/cesenatico", 
                    "de" => "de/emilia-romagna/cesenatico",
                ], 
                
                50 => [
                    "it" => "marche/pesaro",
                    "en" => "en/marche/pesaro",
                    "fr" => "fr/marche/pesaro",
                    "de" => "de/marche/pesaro",
                ],

                51 => [
                    "it" => "emilia-romagna/bologna",
                    "en" => "en/emilia-romagna/bologna",
                    "fr" => "fr/emilie-romagne/bologne",
                    "de" => "de/emilia-romagna/bologna",
                ],

                52 => [
                    "it" => "toscana/firenze",
                    "en" => "en/tuscany/florence",
                    "fr" => "fr/toscane/florence",
                    "de" => "de/toskana/florenz",
                ],

                53 => [
                    "it" => "toscana/siena",
                    "en" => "en/tuscany/siena",
                    "fr" => "fr/toscane/siena",
                    "de" => "de/toskana/siena",
                ],

                54 => [
                    "it" => "toscana/firenze",
                    "en" => "en/tuscany/florence",
                    "fr" => "fr/toscane/florence",
                    "de" => "de/toskana/florenz",
                ],

                55 => [
                    "it" => "toscana/firenze",
                    "en" => "en/tuscany/florence",
                    "fr" => "fr/toscane/florence",
                    "de" => "de/toskana/florenz",
                ],

                56 => [
                    "it" => "toscana/firenze",
                    "en" => "en/tuscany/florence",
                    "fr" => "fr/toscane/florence",
                    "de" => "de/toskana/florenz",
                ],

                57 => [
                    "it" => "toscana/siena",
                    "en" => "en/tuscany/siena",
                    "fr" => "fr/toscane/siena",
                    "de" => "de/toskana/siena",
                ],

                58 => [
                    "it" => "toscana/siena",
                    "en" => "en/tuscany/siena",
                    "fr" => "fr/toscane/siena",
                    "de" => "de/toskana/siena",
                ],

                59 => [
                    "it" => "toscana/siena",
                    "en" => "en/tuscany/siena",
                    "fr" => "fr/toscane/siena",
                    "de" => "de/toskana/siena",
                ],

                60 => [
                    "it" => "toscana/siena",
                    "en" => "en/tuscany/siena",
                    "fr" => "fr/toscane/siena",
                    "de" => "de/toskana/siena",
                ],

                61 => [
                    "it" => "toscana/siena",
                    "en" => "en/tuscany/siena",
                    "fr" => "fr/toscane/siena",
                    "de" => "de/toskana/siena",
                ],

                62 => [
                    "it" => "toscana/siena",
                    "en" => "en/tuscany/siena",
                    "fr" => "fr/toscane/siena",
                    "de" => "de/toskana/siena",
                ],
                
            
            ];
        
            
        if ($id >= 2000)
            return $geoslug[$localita][$lang] . "/" . $slug;
        else 
            return NULL;

    }

}