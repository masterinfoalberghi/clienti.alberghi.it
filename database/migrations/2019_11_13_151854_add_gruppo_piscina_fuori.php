<?php

use App\Servizio;
use App\GruppoServizi;
use App\ServizioLingua;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGruppoPiscinaFuori extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    
    $nome['it'] = "Piscina fuori struttura";
    $nome['en'] = "Off-site swimming pool";
    $nome['fr'] = "Piscine hors site";
    $nome['de'] = "Off-Site-Pool";

    $gruppo = new GruppoServizi;

    $gruppo->nome = "Piscina fuori struttura";
    
    foreach (Utility::linguePossibili() as $lingua) {
      $column = "nome_".$lingua;
      $gruppo->$column = $nome[$lingua];  
    }

    $gruppo->save();


    // creo il servizio "Piscina fuori struttura" con categoria "Servizi in hotel" e gruppo "Piscina fuori struttura"

    $categoria_id = 2; // Servizi in hotel
    
    $servizio = Servizio::create(['gruppo_id' => $gruppo->id, 'categoria_id' => $categoria_id ]);

    foreach (Utility::linguePossibili() as $lingua) 
      {
      $servizio_lingua[] = ["master_id" => $servizio->id, "nome" => strtolower($nome[$lingua]), "lang_id" => $lingua];
      }
    
    ServizioLingua::insert($servizio_lingua);


    //**ATTENZIONE** affinché le label si vedano negli hotel con piscina fuori struttura devo assegnare ai servizi della categoria "Listing Piscina" il gruppo_id 35  (listing_gruppo_piscina_fuori) e non 8 (listing_gruppo_piscina)

    Servizio::where('categoria_id','9')
            ->update(['gruppo_id' => 0]);


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $sl = ServizioLingua::where('nome','Piscina fuori struttura')->where('lang_id','it')->first();

        if (!is_null($sl)) 
          {
          $id = $sl->master_id;
          DB::table('tblHotelServizi')->where('servizio_id', $id)->delete();
          ServizioLingua::where('master_id', $id)->delete();
          Servizio::destroy($id);
          }

        DB::table('tblGruppoServizi')->where('nome', 'Piscina fuori struttura')->delete();

        Servizio::where('categoria_id','9')
            ->update(['gruppo_id' => 8]);

    }
}
