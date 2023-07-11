<?php

namespace App\Console\Commands;

use Config;
use App\MailScheda;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncApiMailScheda extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:sync_mail_scheda';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica se ci sono dei record nella tabella tblMailScheda che non sono stati scritti sulla tabella corrispondente del DB API e li sincronizza';

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
        $ms = MailScheda::toSyncAPI()->get();

        $this->info('Mail scheda da sincronizzare ' . $ms->count());

        foreach ($ms as $mail) {

            $mail_arr = $mail->toArray();

            unset($mail_arr['api_sync']);

            if (Config::get("mail.send_to_api_db")) {
                DB::connection('api')->beginTransaction();
            }

            $this->info('Inizio transazione x mail ID ' . $mail->id);

            try
            {

                if (Config::get("mail.send_to_api_db")) DB::connection('api')->table('tblAPIMailScheda')->insert($mail_arr);

                $this->info('Inserimento tblAPIMailScheda OK');

                $camere_aggiuntive = $mail->camereAggiuntive;

                if (!is_null($camere_aggiuntive)) {

                    foreach ($camere_aggiuntive as $row) {

                        $row_array = $row->toArray();

                        if (Config::get("mail.send_to_api_db")) DB::connection('api')->table('tblAPICamereAggiuntive')->insert($row_array);
                        
                    }

                }

                $this->info('Inserimento ' . $camere_aggiuntive->count() . ' tblAPICamereAggiuntive OK');

                if (Config::get("mail.send_to_api_db")) DB::connection('api')->commit();

                $this->info('Commit transazione x mail ID ' . $mail->id);

                $mail->api_sync = true;
                $mail->save();

                $this->info('api_sync = 1 x mail ID ' . $mail->id);

            } catch (\Exception $e) {

                if (Config::get("mail.send_to_api_db")) DB::connection('api')->rollback();
                $this->info('Rollback transazione x mail ID ' . $mail->id . ' Errore: ' . $e->getMessage());
                config('app.debug_log') ? Log::emergency("\n" . '---> Errore SYNC MAIL SCHEDA: ' . $e->getMessage() . ' <---' . "\n\n") : "";

            }

        } // foreach
    } // END hanlde()
}
