<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Hotel;
use DB;

class RegenNewletterAddress extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'regen:newsletter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Rigenera l'indirizzario newsletter";

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
        $hotel = Hotel::select("id", "nome", "email",  "email_secondaria")->attivo()->get();

        echo "ID,Nome,Email" . PHP_EOL;

        foreach ($hotel as $e) {

            $email = $e->email;
            if ($e->email_secondaria != "")
                $email = $e->email_secondaria;

            $pos = strpos($email, ",");

            if ($pos === false) {
                echo "\"" . $e->id . "\",\"" . $e->nome . "\",\"" . $email . "\"" . PHP_EOL;
            } else {

                $emails = explode(",", $email);
                foreach ($emails as $em) { 
                    echo "\"" . $e->id . "\",\"" . $e->nome . "\",\"" . trim($em) . "\"" .  PHP_EOL;
                }

            }

        }

    }
}
