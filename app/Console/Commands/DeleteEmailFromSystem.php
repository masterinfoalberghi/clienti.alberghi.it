<?php

namespace App\Console\Commands;

use App\MailMultipla;
use App\MailScheda;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteEmailFromSystem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:delete {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancella la mail passata dai sistemi di infoalberghi';

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
        $email = $this->argument('email');
        
        $this->info("------------------");
        $toDeletedMS = MailScheda::with('camereAggiuntive')->where('email', $email)->get();
        foreach ($toDeletedMS as $ms) 
          {
          $ms->camereAggiuntive()->delete();
          $ms->delete();
          }
        $this->info("Cancellate ". $toDeletedMS->count() . " mail scheda (con relative camere aggiuntive)");
        $this->info("------------------");
        

        $toDeletedMM = MailMultipla::where('email', $email)->get();
        foreach ($toDeletedMM as $mm)
          {
          $mm->clienti()->detach();
          $mm->camereAggiuntive()->delete();
          $mm->delete();
          } 
        $this->info("Cancellate ". $toDeletedMM->count() . " mail multiple (con relative camere aggiuntive e tabella pivot hotel)");
        $this->info("------------------");

        $deletedMSArchive = DB::table("tblMailSchedaArchive")->where('email', $email)->update(['email' => ""]);
        
        $this->info("Azzerate ". $deletedMSArchive . " mail scheda ARCHIVE");
        $this->info("------------------");

        $deletedMMArchive = DB::table("tblStatsMailMultipleArchive")->where('email', $email)->update(['email' => ""]);
        
        $this->info("Azzerate ". $deletedMMArchive . " mail multiple ARCHIVE");
        $this->info("------------------");        

    }
}
