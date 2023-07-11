<?php

namespace App\Console\Commands;

use App\Hotel;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use File;
use Response;

class ReportMailMultiple extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:mm';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calcola le mail multiple di Hotel random in determiani periodi dell\'anno corrente e di quello precedente';


    private $periodi = [
            '2022-01-01'  => '2022-01-30',
            '2021-01-01'  => '2021-01-30',

            '2022-01-28'  => '2022-01-30',
            '2021-01-28'  => '2021-01-30',
            ];


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {

        // ultima settimana ANNO CORRENTE
        $dal = Carbon::today()->sub(7, 'day')->format('Y-m-d');
        $al = Carbon::today()->format('Y-m-d');
        $this->periodi[$dal] = $al;


        // ultima settimana ANNO PRECEDENTE
        $dal = Carbon::today()->sub(1, 'year')->sub(7, 'day')->format('Y-m-d');
        $al = Carbon::today()->sub(1, 'year')->format('Y-m-d');        
        $this->periodi[$dal] = $al;

        parent::__construct();
    }





    private function getEmail($hotel_id, $dal, $al) {
        
        //ES:
        //select count(*) as aggregate 
        // from `tblStatsMailMultipleArchive` 
        //where `hotel_id` = 699 
        //and `data_invio` BETWEEN '2022-01-01 00:00:00' AND '2022-01-30 23:59:59'


        $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $dal . ' 00:00:00');
        $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $al . ' 23:59:59');

         $n = DB::connection("archive")       
                ->table("tblStatsMailMultipleArchive")
                ->where('hotel_id', $hotel_id)
                ->whereBetween('data_invio', [$startDate, $endDate])
                ->count();

        return $n;

    }


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        //dd($this->periodi);
        $numero = $this->ask('Inserisci il numero di hotel per cui cercare le mail multiple');

        $new_ids = [];
        
        while ( $this->confirm('Vuoi inserire altri ID?') ) {
            $nuovo_id = $this->ask('Inserisci ID');

            $new_ids[] = $nuovo_id;
        }
        

        // dd($new_ids);


        //? Seleziono 10 hotel random
        
        $all_ids = Hotel::attivo()->whereNotIn('id', $new_ids )->get()->pluck('id')->toArray();

        shuffle($all_ids);

        $hotel_ids_arr = array_slice($all_ids, 0, $numero);

        if (count($new_ids)) {
            $hotel_ids_arr = array_merge($hotel_ids_arr, $new_ids);
        }



        //! Force hotel ids ??

        $hotel_ids_arr = [699,1364,199,1488,379,471,17];


        //dd($hotel_ids_arr);


        //? CSV headers
        $headers = array(
            'Content-Type' => 'text/csv'
        );

        if (!File::exists(public_path()."/files")) {
            File::makeDirectory(public_path() . "/files");
        }

        $filename =  public_path("files/download.csv");
        $handle = fopen($filename, 'w');

        //? inserisco la prima riga del CSV

        $firstRow = ['ID','Nome','Località','Categoria'];

        foreach ($this->periodi as $dal => $al) {
            $firstRow[] = $dal.' => '. $al; 
        }

        fputcsv($handle, $firstRow);



        $bar = $this->output->createProgressBar(count($hotel_ids_arr));

        $bar->start();


        foreach ($hotel_ids_arr as $hotel_id) {

            $row_hotel = [];

            $hotel = Hotel::with(['localita','stelle'])->find($hotel_id);

            $row_hotel[] = $hotel_id;
            $row_hotel[] = $hotel->nome;
            $row_hotel[] = $hotel->localita->nome;
            $row_hotel[] = $hotel->stelle->nome;

            foreach ($this->periodi as $dal => $al) {
                
                $row_hotel[] = $this->getEmail($hotel_id, $dal, $al);
                
            }

            fputcsv($handle, $row_hotel);

            $this->info('Mail hotel ' .$hotel->nome . '(' . $hotel_id . ')');

            $bar->advance();
        }
            

        fclose($handle);

        //$this->getEmail(699);


        return 0;
    }
}
