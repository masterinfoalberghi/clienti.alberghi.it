<?php

namespace App\Console\Commands;

use App\Utility;
use App\Servizio;
use App\ServizioLingua;
use App\CategoriaServizi;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class NuoviServiziCRM extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:nuovi-servizi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crea/aggiorna i servizi con i nuovi del CRM';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    private function _elimina_servizio($id)
      {
      if( !is_null(Servizio::find($id)) )
        {
        DB::table('tblHotelServizi')->where('servizio_id', $id)->delete();
        ServizioLingua::where('master_id', $id)->delete();
        Servizio::destroy($id);
        }
      else 
        {
        $this->error("Servizio già eliminato!" ."\n\t");
        }
      }
    
    private function _rinomina_servizio($id, $nome)
      {
      $servizi_lingua_par = ServizioLingua::where('master_id', $id)->get();
      foreach ($servizi_lingua_par as $sl) 
        {
        if ($sl->lang_id == 'it') 
          {
          $sl->nome = $nome;
          }
        else 
          {
          $sl->nome = Utility::translate($nome, $sl->lang_id);
          }
        $sl->save();
        }
      }

    private function _inserisci_servizio($gruppo_id, $categoria_id, $nome)
      {
        
      $servizio_exists = ServizioLingua::whereHas('servizio', function ($query) use ($categoria_id) {
                              $query->where('categoria_id',$categoria_id);
                          })
                          ->where('nome',$nome)
                          ->count();

      if(!$servizio_exists)
        {
        $servizio = Servizio::create(['gruppo_id' => $gruppo_id, 'categoria_id' => $categoria_id ]);

        $servizio_lingua[] = ["master_id" => $servizio->id, "nome" => $nome, "lang_id" => 'it'];

        foreach (Utility::linguePossibili() as $lingua) {
          if ($lingua != 'it') {
            $servizio_lingua[] = ["master_id" => $servizio->id, "nome" => Utility::translate($nome, $lingua), "lang_id" => $lingua];
          }
        }

        ServizioLingua::insert($servizio_lingua);
        }
      else 
        {
        $this->error("Servizio '$nome' già inserito! (gruppo = $gruppo_id, ctaegoria = $categoria_id)" ."\n\t");
        }
      
      }


    

    private function _manage_servizi_gratuiti()
      {
      /**
      * SERVIZI GRATUITI: parcheggio coperto, parcheggio scoperto => parcheggio
      */
      
      if(Servizio::whereIn('id',[222,224])->count() > 1)
        {

        // tutti gli hotel che hanno parcheggio_coperto (224) li metto a parcheggio_scoperto(222)
        $this->line("tutti gli hotel che hanno parcheggio_coperto (224) li metto a parcheggio_scoperto(222)" . "\n\t");
        DB::table('tblHotelServizi')->where('servizio_id', 224)->update(['servizio_id' => 222]);
          
        // elimino parcheggio_coperto
        $this->line("elimino parcheggio_coperto" . "\n\t");
        $this->_elimina_servizio(224);
    
        // rinomino parcheggio_scoperto(222) in parcheggio
        $this->line("rinomino parcheggio_scoperto(222) in parcheggio" . "\n\t");
        $this->_rinomina_servizio(222, 'parcheggio');

        }
        /***********************************************************************
         * *********************************************************************
         ***********************************************************************/
      

      /**
       * ELIMINO garage
       */
      // elimino garage (226)
      
      // tutti gli hotel che hanno garage (226) li metto a garage in hotel()
      $this->line("tutti gli hotel che hanno garage (224) li metto a garage in hotel" . "\n\t");
      $garage_in_hotel = DB::table('tblServiziLang')->where('lang_id','it')->where('nome','garage in hotel')->first();
      DB::table('tblHotelServizi')->where('servizio_id', 226)->update(['servizio_id' => $garage_in_hotel->id]);

      $this->line("elimino garage (226)" . "\n\t");
      $this->_elimina_servizio(226);
      
      /***********************************************************************
        * *********************************************************************
      ***********************************************************************/

      /**
        * Rinomina spa => centro benessere / spa
        */ 
      $this->line("rinomino spa" . "\n\t");
      $this->_rinomina_servizio(134, "centro benessere / spa");
      
      
      /**
        * Rinomina wifi => centro benessere / spa
        e altro...
        */ 
      
      $this->line("inserisci 'WI-FI adsl'" . "\n\t");
      $this->_inserisci_servizio($gruppo_id = 0, $categoria_id = 1, "WI-FI adsl");
      
      $this->line("rinomino wifi" . "\n\t");
      $this->_rinomina_servizio(132, "WI-FI fibra 50/100");

      $this->line("rinomino checkout" . "\n\t");
      $this->_rinomina_servizio(140, "late check out chiedi sempre la disponibilità alla reception");

      $this->line("rinomino bici" . "\n\t");
      $this->_rinomina_servizio(139, "bici a disposizione");

      $this->line("rinomino sky" . "\n\t");
      $this->_rinomina_servizio(144, "sky TV");

      $this->line("rinomino mediaset" . "\n\t");    
      $this->_rinomina_servizio(218, "smart TV");


      /**
       * nuovo servizio
       */
      
      $this->line("inserisci 'deposito valori'" . "\n\t");
      $this->_inserisci_servizio($gruppo_id = 0, $categoria_id = 1, "deposito valori");

      $this->line("rinomino navetta" . "\n\t");        
      $this->_rinomina_servizio(136, "servizio navetta da/a");
      }
    

    
    private function _manage_servizi_hotel()
      {
      
      /**
      * SERVIZI IN HOTEL: parcheggio coperto, parcheggio scoperto => parcheggio
      */
      
      if(Servizio::whereIn('id',[223,225])->count() > 1)
        {

        // tutti gli hotel che hanno parcheggio_coperto (225) li metto a parcheggio_scoperto(223)
        $this->line("tutti gli hotel che hanno parcheggio_coperto (225) li metto a parcheggio_scoperto(223)" . "\n\t");
        DB::table('tblHotelServizi')->where('servizio_id', 225)->update(['servizio_id' => 223]);
          
        // elimino parcheggio_coperto
        $this->line("elimino parcheggio_coperto" . "\n\t");
        $this->_elimina_servizio(225);
    
        // rinomino parcheggio_scoperto(223) in parcheggio
        $this->line("rinomino parcheggio_scoperto(223) in parcheggio" . "\n\t");
        $this->_rinomina_servizio(223, 'parcheggio');

        }
      
      $this->line("rinomino idromassaggio" . "\n\t");
      $this->_rinomina_servizio(205, "idromassaggio esterno alla piscina");

      $this->line("rinomino giardino" . "\n\t");
      $this->_rinomina_servizio(148, "area giardino");

      // tutti gli hotel che hanno garage (227) li metto a garage in hotel
      $this->line("tutti gli hotel che hanno garage (227) li metto a garage in hotel" . "\n\t");
      $garage_in_hotel = DB::table('tblServiziLang')->where('lang_id','it')->where('nome','garage in hotel')->first();
      
      DB::table('tblHotelServizi')->where('servizio_id', 227)->update(['servizio_id' => $garage_in_hotel->id]);
      $this->line("elimino garage" . "\n\t");
      $this->_elimina_servizio(227);
      
      $this->line("inserisci 'WI-FI adsl'" . "\n\t");
      $this->_inserisci_servizio($gruppo_id = 0, $categoria_id = 2, "WI-FI adsl");

      $this->line("rinomino wifi" . "\n\t");
      $this->_rinomina_servizio(159, "WI-FI fibra 50/100");

      $this->line("rinomino animali" . "\n\t");
      $this->_rinomina_servizio(150, "animali");

      $this->line("aria condizionata" . "\n\t");
      $this->_rinomina_servizio(149, "aria condizionata aree comuni");

      $this->line("rinomino riscaldamento" . "\n\t");
      $this->_rinomina_servizio(146, "riscaldamento aree comuni");

      $this->line("rinomino bici a pagamento" . "\n\t");
      $this->_rinomina_servizio(155, "bici a pagamento");

      $this->line("rinomino attrezzato ciclisti" . "\n\t");
      $this->_rinomina_servizio(162, "servizi bike hotel");

      }

    private function _manage_servizi_bambini()
      {
      $this->line("rinomino piscina" . "\n\t");
      $this->_rinomina_servizio(173, "piscina");

      $this->line("inserisci 'spondine'" . "\n\t");
      $this->_inserisci_servizio($gruppo_id = 0, $categoria_id = 3, "spondine anticaduta");

      $this->line("rinomino seggioloni" . "\n\t");
      $this->_rinomina_servizio(168, "seggioloni a tavola");

      $this->line("elimino baby club (232)" . "\n\t");
      $this->_elimina_servizio(232);

      $this->line("elimino mini club (233)" . "\n\t");
      $this->_elimina_servizio(233);

      $this->line("elimino junior club (234)" . "\n\t");
      $this->_elimina_servizio(234);

      $this->line("inserisci seggiolino e casco" . "\n\t");
      $this->_inserisci_servizio($gruppo_id = 0, $categoria_id = 3, "seggiolino e casco per la bicicletta");

      $this->line("inserisci animazione durante i pasti" . "\n\t");
      $this->_inserisci_servizio($gruppo_id = 0, $categoria_id = 3, "animazione durante i pasti");

      }

    private function _manage_ristorazione()
      {
        $this->line("inserisci brunch" . "\n\t");
        $this->_inserisci_servizio($gruppo_id = 0, $categoria_id = 5, "brunch");

        $this->line("inserisci pasti presso ristorante convenzionato" . "\n\t");
        $this->_inserisci_servizio($gruppo_id = 0, $categoria_id = 5, "pasti presso ristorante convenzionato");

        $this->line("inserisci pranzo al sacco" . "\n\t");
        $this->_inserisci_servizio($gruppo_id = 0, $categoria_id = 5, "pranzo al sacco");


      }

    private function _manage_servizi_camera()
      {
       $this->line("inserisci 'WI-FI adsl'" . "\n\t");
      $this->_inserisci_servizio($gruppo_id = 0, $categoria_id = 6, "WI-FI adsl");

      $this->line("rinomino wifi" . "\n\t");
      $this->_rinomina_servizio(188, "WI-FI fibra 50/100");
      
      $this->line("elimino tv digitale" . "\n\t");
      $this->_elimina_servizio(221);

      $this->line("rinomino mediaset" . "\n\t");    
      $this->_rinomina_servizio(196, "smart TV");

      $this->line("rinomino sky" . "\n\t");    
      $this->_rinomina_servizio(197, "sky TV");
      
      $this->line("inserisci vasca idromassaggio" . "\n\t");
      $this->_inserisci_servizio($gruppo_id = 0, $categoria_id = 6, "vasca idromassaggio");

      $this->line("rinomino colazione in camera" . "\n\t");    
      $this->_rinomina_servizio(243, "colazione in camera / room service a pagamento");
    
      }

    private function _manage_convenzioni()
      {
        $this->line("elimino covenzione INAL" . "\n\t");
        $this->_elimina_servizio(245);

        $this->line("elimino convenzione forze dell'ordine" . "\n\t");
        $this->_elimina_servizio(246);

        $this->line("inserisci tour serali organizzati" . "\n\t");
        $this->_inserisci_servizio($gruppo_id = 0, $categoria_id = 8, "tour serali organizzati");

      }

    private function _new_categorie()
      {
      $categorie = CategoriaServizi::all();

      foreach ($categorie as $cat) 
        {
        $cat->position = $cat->position*10;
        $cat->save();
        }
      
      $cat_parcheggio = CategoriaServizi::create(['nome' => 'Parcheggio', 'position' => 22, 'created_at' => Carbon::now()->toDateTimeString(), 'updated_at' => Carbon::now()->toDateTimeString()]);
      $data_p = [];
      $data_p[] = 'numero posti disponibili';
      $data_p[] = 'sorveglianza o videosorveglianza';
      $data_p[] = 'servizio navetta a/r parcheggio';
      $data_p[] = "parcheggio coperto a metri dall'hotel";
      $data_p[] = 'parcheggio coperto interno alla proprietà';
      $data_p[] = "parcheggio scoperto a metri dall'hotel";
      $data_p[] = 'parcheggio scoperto interno alla proprietà';
      $data_p[] = 'garage in hotel';
      $data_p[] = "garage a metri dall'hotel";
      $data_p[] = "parcheggio pubblico gratuito a metri dall'hotel";
      $data_p[] = "parcheggio pubblico a pagamento a metri dall'hotel";

      foreach ($data_p as $nome) 
        {
        $this->line("inserisci $nome" . "\n\t");
        $this->_inserisci_servizio($gruppo_id = 23, $categoria_id = $cat_parcheggio->id, $nome);
        }

      $cat_bike = CategoriaServizi::create(['nome' => 'Bike Hotel', 'position' => 24, 'created_at' => Carbon::now()->toDateTimeString(), 'updated_at' => Carbon::now()->toDateTimeString()]);

      $data_bike = [];
      $data_bike[] = 'guide cicloturistiche (e bike manager) e/o info point con bike manager';
      $data_bike[] = 'percorsi e mappe';
      $data_bike[] = 'bike room con chiusura a lucchetto e/o videosorveglianza';
      $data_bike[] = 'possibilità di portare la bici in camera';
      $data_bike[] = 'area lavaggio bici';
      $data_bike[] = 'pasti dedicati agli atleti';
      $data_bike[] = 'colazioni energetiche';
      $data_bike[] = 'lavanderia per abbigliamento tecnico';
      $data_bike[] = 'furgone per assistenza sulle strade';
      $data_bike[] = 'officina dedicata alla riparazione e manutenzione della bicicletta';
      $data_bike[] = 'assistenza medica e fisioterapica';
      $data_bike[] = 'noleggio bici da corsa o MTB';
      $data_bike[] = 'organizzazione di bike tour';
      $data_bike[] = 'abbigliamento tecnico e integratori in vendita';
      $data_bike[] = 'programma di svago e intrattenimento per gli accompagnatori e le famiglie dei ciclisti';
      $data_bike[] = 'KIT gare ciclistiche';

      foreach ($data_bike as $nome) 
        {
        $this->line("inserisci $nome" . "\n\t");
        $this->_inserisci_servizio($gruppo_id = 34, $categoria_id = $cat_bike->id, $nome);
        }


      $cat_animali = CategoriaServizi::create(['nome' => 'Animali', 'position' => 26, 'created_at' => Carbon::now()->toDateTimeString(), 'updated_at' => Carbon::now()->toDateTimeString()]);

      $data_animali = [];
      $data_animali[] = 'educazione cinofila';
      $data_animali[] = 'animazione per cani';
      $data_animali[] = 'sgambatoio';
      $data_animali[] = 'convenzione spiaggia pet friendly';
      $data_animali[] = 'veterinario';
      $data_animali[] = 'pet kit (ciotola, cuccia, lettiera, ecc.)';
      $data_animali[] = 'pet menù';
      $data_animali[] = 'dog sitter';
      $data_animali[] = 'accesso alla sala da pranzo';
      $data_animali[] = 'toelettatore convenzionato';
      
      foreach ($data_animali as $nome) 
        {
        $this->line("inserisci $nome" . "\n\t");
        $this->_inserisci_servizio($gruppo_id = 4, $categoria_id = $cat_animali->id, $nome);
        }


      
      }
    
    
    
      /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
    // $this->_visualizza_servizi();
    // die();

    // $servizi_gratis = Servizio::with('translate_it')->where('categoria_id', 1)->orderBy('admin_position')->get();
    // dd($servizi_gratis);


    /**
     * 
      SELECT sl.* 
      FROM tblServizi s join tblServiziLang sl ON s.id = sl.master_id 
      WHERE sl.master_id IN (SELECT id FROM tblServizi WHERE categoria_id = 1) 
      AND lang_id = 'it'
      ORDER BY admin_position
     */
    


      $this->info('Nuove categorie Servizi');
      $this->_new_categorie();

      $this->info('Servizi Gratuiti');
      $this->_manage_servizi_gratuiti();

      $this->info('Servizi In Hotel');
      $this->_manage_servizi_hotel();

      $this->info('Servizi per bambini');
      $this->_manage_servizi_bambini();

      $this->info('Ristorazione');
      $this->_manage_ristorazione();

      $this->info('Servizi in camera');
      $this->_manage_servizi_camera();

      $this->info('Convenzioni');
      $this->_manage_convenzioni();    
    
    } // end hanlde
}


/**
 * 

 -- tutti i baby club --
SELECT DISTINCT hs.hotel_id 
FROM tblHotelServizi hs JOIN tblHotel h ON hs.hotel_id = h.id
where hs.servizio_id = 232
AND h.attivo = 1

-- tutti i baby club che contengono spiaggia --
SELECT DISTINCT hs.hotel_id 
FROM tblHotelServizi hs JOIN tblHotel h ON hs.hotel_id = h.id
where hs.servizio_id = 232
AND hs.note LIKE '%spiaggia%'
AND h.attivo = 1

 * 
 * 
 */