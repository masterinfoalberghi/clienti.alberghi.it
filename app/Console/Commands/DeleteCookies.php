<?php

namespace App\Console\Commands;

use App\CookieDB;
use Illuminate\Console\Command;

class DeleteCookies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cookies:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancella i record con data di modifica più vecchia di 30 gg dalla tabella tblCookies';

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
        $deletedCookies = CookieDB::modifyWithDelay()->delete();
         $this->info("Cancellati ".$deletedCookies. " cookies");
    }
}
