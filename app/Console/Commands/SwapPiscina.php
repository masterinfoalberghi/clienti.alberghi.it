<?php

namespace App\Console\Commands;

use App\Hotel;
use App\ServizioLingua;
use Illuminate\Console\Command;

class SwapPiscina extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'swap:piscina';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Effettua lo swap tra i servizi "piscina" e "piscina fuori struttura" se esiste un servizio listing piscina';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
    $columns = ['note', 'note_en', 'note_fr', 'note_de'];

    $servizio_piscina_id = ServizioLingua::where('nome','piscina')
                                        ->whereHas('servizio', function($q){
                                          $q->where('categoria_id',2); // servizi in hotel
                                        })
                                        ->first()
                                        ->master_id;

    $servizio_piscina_esterno_id = ServizioLingua::where('nome','piscina fuori struttura')
                                        ->whereHas('servizio', function($q){
                                          $q->where('categoria_id',2);
                                        })
                                        ->first()
                                        ->master_id;
    
 		$hotel = Hotel::whereHas('servizi', function($q) {
					    				$q->where('categoria_id','=', 9); // "Listing Piscina"
									})
									// ->whereHas('localita', function($q) {
									//     $q->where('macrolocalita_id','=',1); // Rimini
									// })
 									->attivo()
 									//->where('id',262)
 									->get();
    
    
		foreach ($hotel as $h) 
			{
      $piscina = $h->servizi()->where('gruppo_id', 8)->first();
      $piscina_fuori = $h->servizi()->where('gruppo_id', 35)->first();

      if( !is_null($piscina) && is_null($piscina_fuori) )
        {
        $this->info($h->nome .'('. $h->id .')' . " con piscina interna");
        
        $pivot_piscina = $piscina->pivot;
        
        $pivot_array = []; 
        foreach ($columns as $col) 
          {
          $pivot_array[$col] = $pivot_piscina->$col;          
          }

      
        // associo la piscina esterna
        $h->servizi()->attach([$servizio_piscina_esterno_id => $pivot_array]);

        // tolgo la piscina interna
        $h->servizi()->detach($servizio_piscina_id);
        
        $this->info($h->nome .'('. $h->id .')' . " swap a piscina esterna");
        
        }
      elseif (!is_null($piscina_fuori)) 
        {
        $this->info($h->nome .'('. $h->id .')' . " ha già il check piscina fuori");
        }
        
      }
    }
}
