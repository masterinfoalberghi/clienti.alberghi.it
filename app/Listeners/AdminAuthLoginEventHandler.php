<?php

/**
 * Ogni volta che un utente accede all'admin, registro il fatto con alcuni dati annessi
 *
 * @author Luca Battarra
 */

namespace App\Listeners;

use App\User;
use App\UserAccessLog;
use App\Utility;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Request;

class AdminAuthLoginEventHandler
{
    /**
     * Create the event handler.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Events  $event
     * @return void
     */
    public function handle($user, $remember)
    {   
        $access_log = new UserAccessLog;
        $access_log->user_id = $user->id;
        $access_log->ip = (string) Utility::get_client_ip();
        $access_log->user_agent = (string)Request::header('User-Agent');
        $access_log->created_at = Carbon::now();;
        $access_log->save();

        /*
         * Faccio in modo che la tabella con lo storico di tutti gli accessi resti compatta
         * tengo solo le ultime UserAccessLog::MAX_LOG_ROWS_PER_USER righe per ogni utente
         */
        $log = UserAccessLog::where("user_id", $user->id)
          ->orderBy("id", "desc")
          ->skip(UserAccessLog::MAX_LOG_ROWS_PER_USER)
          ->take(1)
          ->first();

        if($log)
          {
          UserAccessLog::where("user_id", $user->id)
            ->where("id", "<=", $log->id)
            ->delete();
          }
    }
}
