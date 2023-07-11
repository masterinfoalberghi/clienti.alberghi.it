<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CheckAdminAccess extends Command
{

    // gg. dopo i quali viene inviaa una mail se l'hotel non ha acceduto il backend
    private $delay_days;


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alert:check_admin_access';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica l\'ultimo accesso al backend da parte degli hotel ed eventualmente invia una mail';



    const MS_RETURN_PATH = "richiesta@info-alberghi.com";


    public $email_mittente;
    public $nome_mittente;
    public $oggetto;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
      {
      $this->email_mittente = "info@info-alberghi.com";
      $this->nome_mittente = "Info Alberghi";
      $this->oggetto = "Collegati alla tua area riservata su info-alberghi.com!";
      parent::__construct();
      }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
      {
      $users = User::whereHas('hotel', function($query){
                            $query->where('attivo',1);
                          })
                    ->withRole('hotel')
                    ->accessWithDelay()
                    ->get();

      $oggetto = $this->oggetto;

      $this->info($users->count() . " utenti che non accedono da tanto al nostro backend");
      $this->info("------------------");
      foreach ($users as $user) 
        {
        Mail::send(
          'emails.accesso_admin', 
          compact(
            'user','oggetto'
          ), 
          function ($message) use ($user, $oggetto) {
            
            $message->from(Self::MS_RETURN_PATH, $this->nome_mittente);
            $message->replyTo($this->email_mittente);
            $message->returnPath(Self::MS_RETURN_PATH)->sender(Self::MS_RETURN_PATH)->to($user->email);
        
            if (isset($this->bcc) && $this->bcc != "")
              $message->bcc($this->bcc);
        
            $message->subject($oggetto);
            
          }
        );
        $this->info("Inviata mail notifica a ".$user->email);
        
        }

      }

}
