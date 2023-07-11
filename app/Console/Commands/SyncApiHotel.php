<?php

namespace App\Console\Commands;

use App\ApiHotel;
use App\Hotel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncApiHotel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:sync_hotel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincronizza gli hotel di IA con quelli del DB API';

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
     * [import_from_ia_to_api POTREBBE ESSERE UN NUOVO RECORD OPPURE UNA ATTIVAZIONE! ]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    private function import_from_ia_to_api($id)
      {

      // verifico se in API questo $id è tra i softDeleted
      $trashed = ApiHotel::withTrashed()->where('id', $id)->first();

      if (is_null($trashed)) 
        {
        
        $hotel_ia  =  DB::table('tblHotel')
                        ->select(DB::raw('tblHotel.id, tblHotel.nome, tblHotel.indirizzo, tblLocalita.nome as localita, tblHotel.cap, tblHotel.email'))
                        ->leftJoin('tblLocalita', 'tblLocalita.id' , '=', 'tblHotel.localita_id' )
                        ->where('tblHotel.id', $id)
                        ->first();
                        
        $hotel_ia_arr = (array) $hotel_ia;

        if(Config::get("mail.send_to_api_db"))          DB::connection('api')->table('tblAPIHotel')->insert($hotel_ia_arr);
        if(Config::get("mail.send_to_api_db_matteo"))   DB::connection('api_matteo')->table('tblAPIHotel')->insert($hotel_ia_arr);

        $hotel = $hotel_ia->nome . ' ' . $hotel_ia->localita;
        
        $this->info('Aggiunto su API hotel '. $hotel .' (ID = '.$id .')');
        
        return $hotel;

        } 
      else 
        {
        $trashed->restore();

        $trashed->save();
        
        $hotel = $trashed->nome . ' ' . $trashed->localita;

        $this->info('Ripristinato su API hotel '. $hotel .' (ID = '.$id .')');

        return $hotel;

        }



      }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        
        $hotel_ids_ia = Hotel::attivoWithDemo()->pluck('id');

        $hotel_ids_api = ApiHotel::pluck('id');

        $ia_not_api = $hotel_ids_ia->diff($hotel_ids_api);


        // hotel aggiunti su IA che non esistono sul API (NUOVO OPPURE ATTIVAZIONE)
        $this->info('Hotel su IA che non esistono sul API '.$ia_not_api->count());
        
        if($ia_not_api->count())
          {
          foreach ($ia_not_api as $id) 
            {

            try 
              {
              // POTREBBE ESSERE UN NUOVO RECORD OPPURE UNA ATTIVAZIONE! 
              $hotel = self::import_from_ia_to_api($id);
              
              } 
            catch (\Exception $e) 
              {
              $this->error('Errore nell\'aggiungere su API hotel ID = '.$id . ': '.$e->getMessage());
              }

            }
          }

        // Hotel cancellati su IA (il cui record non eiste più) che esistono ancora su API
        $api_not_ia = $hotel_ids_api->diff($hotel_ids_ia);

        $this->info('Hotel cancellati/disattivati su IA che esistono ancora su API '.$api_not_ia->count());

        if($api_not_ia->count())
          {
          foreach ($api_not_ia as $id) 
            {
            try 
              {
              // tblAPIHotel implementa softDelete
              ApiHotel::where('id', $id)->delete();

              $this->info('Cancellato (softDelete) su API hotel ID = '.$id);
              } 
            catch (\Exception $e) 
              {
              $this->error('Errore nel cancellare (softDelete) su API hotel ID = '.$id . ': '.$e->getMessage());
              }
            }
          }


        
    }
}
