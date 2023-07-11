<?php

namespace App\Console\Commands;

use Config;
use App\MailMultipla;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncApiMailMultipla extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:sync_mail_multipla';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica se ci sono dei record nella tabella tblMailMultiple che non sono stati scritti sulla tabella corrispondente del DB API e li sincronizza';

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

        $mm = MailMultipla::toSyncAPI()->get();
        $this->info('Mail multiple da sincronizzare ' . $mm->count());

        foreach ($mm as $mail) {

            $mail_arr = $mail->toArray();
            unset($mail_arr['api_sync']);
            
            if (Config::get("mail.send_to_api_db")) {
                DB::connection('api')->beginTransaction();
            }

            $this->info('Inizio transazione x mail ID ' . $mail->id);

            try
            {

                if (Config::get("mail.send_to_api_db")) {
                    DB::connection('api')->table('tblAPIMailMultipla')->insert($mail_arr);
                }

                $this->info('Inserimento tblAPIMailMultipla OK');
                $camere_aggiuntive = $mail->camereAggiuntive;

                if (!is_null($camere_aggiuntive) && $camere_aggiuntive->count()) {

                    foreach ($camere_aggiuntive as $row) {

                        $row_array = $row->toArray();

                        if (Config::get("mail.send_to_api_db")) {
                            DB::connection('api')->table('tblAPICamereAggiuntive')->insert($row_array);
                        }

                    }

                }

                $this->info('Inserimento ' . $camere_aggiuntive->count() . ' tblAPICamereAggiuntive OK');

                $clienti_mail_ids = $mail->clienti->pluck('id');
                if (!is_null($clienti_mail_ids) && $clienti_mail_ids->count()) {
                    foreach ($clienti_mail_ids as $hotel_id) {
                        if (Config::get("mail.send_to_api_db")) {
                            DB::connection('api')->table('tblAPIHotelMailMultiple')->insert(['mailMultipla_id' => $mail->id, 'hotel_id' => $hotel_id]);
                        }

                    }
                }

                $this->info('Associazione ' . $clienti_mail_ids->count() . ' tblAPIHotelMailMultiple OK');

                if (Config::get("mail.send_to_api_db")) {
                    DB::connection('api')->commit();
                }

                $this->info('Commit transazione x mail ID ' . $mail->id);

                $mail->api_sync = true;
                $mail->save();

                $this->info('api_sync = 1 x mail ID ' . $mail->id);

            } catch (\Exception $e) {

                if (Config::get("mail.send_to_api_db")) {
                    DB::connection('api')->rollback();
                }

                // something went wrong
                $this->info('Rollback transazione x mail ID ' . $mail->id . ' Errore: ' . $e->getMessage());
                config('app.debug_log') ? Log::emergency("\n" . '---> Errore SYNC MAIL MULTIPLA: ' . $e->getMessage() . ' <---' . "\n\n") : "";

            }

        } // end foreach
    } // END hanlde()
}
