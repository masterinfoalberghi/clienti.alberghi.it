<?php

/**
* @author Luca Battarra
*/

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Config;
use Carbon\Carbon;

class ImportStatsVetrine extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'import:stats-vetrine';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = 'Processa le stats vetrine del giorno precedente ad "oggi" importandole nella tabella d\'archivio e nella tabella statistica';

    /**
     * Create a new command instance.
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

    public function handle() {

       /**
        * Con questo script travaso i dati più vecchi di "oggi" da tblStatsHotel a tblStatsHotelArchive
        * nel fare questo aggiorno il dato aggragato in tblStatsHotelRead
        *
        * lavoro per chunks, in questo modo se ho un boom di accessi lo script non "patisce"
        */

        $term_storage_archived_data = Config::get("privacy.term_storage_archived_data");

        $vetrine = ['tblStatsVetrine', 'tblStatsVaat', 'tblStatsVot', 'tblStatsVst', 'tblStatsVtt'];
        $detect = new \Detection\MobileDetect;

        foreach ($vetrine as $tbl) {

            $classname = str_replace("tblStats", "App\Stat", $tbl);

            /** Main function */
            $n_rows = $classname::beforeToday()->count();
            $rows_per_chunk = 5000;

            $t = 0;
            for ($i = 0; $i < $n_rows; $i += $rows_per_chunk) {

            /** Query */
            $data = $classname::beforeToday()
                ->skip($i)
                ->take($rows_per_chunk)
                ->get();

            $data_read = [];
            $data_archivies = [];

             /** Ciclo */
            foreach ($data as $r) {

                /** Elimino i bot dai dati  */
                if(strpos($r->useragent, 'bot') === false) {

                    /** Eccezione vetrine */
                    if ($tbl == 'tblStatsVetrine') {
                        $data_archive = array(
                            'vetrina_id' => $r->vetrina_id,
                            'slot_id' => $r->slot_id,
                            'referer' => $r->referer
                        );
                    } else {
                        $data_archive = array(
                            'pagina_id' => $r->pagina_id,
                            'referer' => $r->referer
                        );
                    }

                    $data_archive['hotel_id'] = $r->hotel_id;
                    $data_archive['created_at'] = $r->created_at;
                    $data_archive['useragent'] = $r->useragent;

                    $data_archivies[] = $data_archive;

                    /** Tabella Read / Inserisci o aggiorna */
                    $deviceType = ($detect->isMobile($r->useragent) ? ($detect->isTablet($r->useragent) ? 'tablet' : 'phone') : 'desktop');
                    $dt = Carbon::createFromFormat('Y-m-d H:i:s', $r->created_at);
                    if ($tbl == 'tblStatsVetrine') {
                        $vetrina_id_fld = 'vetrina_id';
                        $vetrina_id_fld_value = $r->vetrina_id;
                    } else {
                        $vetrina_id_fld = 'pagina_id';
                        $vetrina_id_fld_value = $r->pagina_id;
                    }

                    DB::statement("INSERT INTO {$tbl}Read (created_at, $vetrina_id_fld, hotel_id, visits)
                    VALUES ('".$dt->format("Y-m-d")."', ". $vetrina_id_fld_value . ", " . $r->hotel_id .", 1)
                    ON DUPLICATE KEY UPDATE visits = visits + 1");

                    DB::statement("INSERT INTO {$tbl}ReadV2 (created_at, $vetrina_id_fld, hotel_id, visits, device)
                    VALUES ('".$dt->format("Y-m-d")."', ". $vetrina_id_fld_value . ", " . $r->hotel_id .", 1, '". $deviceType. "')
                    ON DUPLICATE KEY UPDATE visits = visits + 1");

                    $t++;

                } // endif not bot

            } // end for chunk data

             /** Inserisci i dati nella tabella archivio */
             DB::connection("archive")
                ->table("{$tbl}Archive")
                ->insert($data_archivies);

            $this->info("{$tbl} - Record processati $t / $n_rows");

        } // end for numrows

        /**
         * Cancello tutti i beforeToday
         * perchè non lo posso fare offset per offset con i chunk!
         * controllando le query nel general log le query in delete le fa senza offset!
         */

        $classname::beforeToday()->delete();

        /** Cancello i dati più vecchi di xx anni */
        DB::connection("archive")
            ->table($tbl . "Archive")
            ->where("created_at", "<", Carbon::now()->subMonths($term_storage_archived_data))
            ->delete();

        } // endfor tables

    }

}