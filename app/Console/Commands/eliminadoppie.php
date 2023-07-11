<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\MailDoppie;
use Carbon\Carbon;
use Config;

class eliminadoppie extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:eliminadoppie';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Elimina le email doppie più vecchie di 3 giorni';

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
		
		$temp_storage_email_duplicate = Config::get("mail.temp_storage_email_duplicate");
		MailDoppie::where('created_at', '<=', Carbon::now()->subDay($temp_storage_email_duplicate))->delete();
		
    }
}
