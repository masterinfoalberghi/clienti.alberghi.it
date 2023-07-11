<?php

namespace App\Http\Controllers;
use App\Hotel;
use App\Http\Controllers\CmsPagineController;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\MailUpsellingQueue;
use App\StatUpselling;
use App\Utility;
use App\ImmagineGallery;
use App\CmsPagina;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\Facades\Image;

class MailUpsellingController extends Controller {

  const MS_RETURN_PATH = "richiesta@info-alberghi.com";

  private function _indexSimili($filters = [], $locale = 'it', $limit = 5)
  {

    $id_hotel = $filters['id'];
    $original_hotel = Hotel::find($id_hotel);

    $clienti =
      Hotel::withListingSimiliEagerLoading($locale)
        ->attivo()
        ->exclude($filters)
        ->listingLocalita($original_hotel->localita_id)
        ->listingTipologie($original_hotel->tipologia_id)
        ->listingCategorie($original_hotel->categoria_id)
        ->raggio($filters)
        ->take($limit)
        ->get();

    $clienti = $clienti->shuffle();
    return $clienti;

  }


  private function _getOggettoMail($dal = "",$al = "")
  {

      $oggetto_data = "";
      if ($dal != "")
          $oggetto_data .= " DAL $dal";

      if ($al != "")
          $oggetto_data .= " AL $al";

      $oggetto = "PREVENTIVO $oggetto_data DA INFO-ALBERGHI.COM";
      return $oggetto;

  }

  public function index($hotel_to, $queue_id)
  {

      $data = ['queue_id' => $queue_id,'hotel_id' => $hotel_to];
      StatUpselling::create($data);
      return redirect(url('hotel.php?id='.$hotel_to));

  }



  public function cron()
  {

      /**
       * ogni ora le svuoto tutte !!!!
       */

      $queue = MailUpsellingQueue::where('inviata', 0)->limit(150)->get();

      if($queue->count() > 0)
      {

        /**
         * ricavo gli hotel simili
         */

        foreach ($queue as $q)
        {

          $ref = $q->referer;

          /**
           * hotel a cui è stata spedita la mail dal modulo del "richiedi preventivo"
           */

          $cliente = Hotel::find($q->hotel_id);

          if (!is_null($cliente))
          {

            /**
             * nel trovare gli hotel simili devo scartare quello corrente e trovare un intorno di raggio $raggio
             * che dipende dalla lat e dalla long di quello corrente
             */

            $raggio = 0.8;
            $filters = ['id' => $cliente->id,'lat' => $cliente->mappa_latitudine,'long' => $cliente->mappa_longitudine,'raggio' => $raggio];


            /* Tra gli hotel simili devo selezionare SOLO QUELLI CHE VOGLIONO la MAIL DI UPSELLING */
            $hotel_simili = $this->_indexSimili($filters, $locale = 'it', $limit = 4);


            if ($hotel_simili->count())
            {

              /*
               * Invio la mail di upselling a $q->email proponendo gli hotel simili
               *
               * Ricavo i dai da inserire nell'email
               *
               */

              /**
               * Dati mappa
               */

              $markers  = "&zoom=16&size=600x400";
              $markers .= "&center=" . $cliente->mappa_latitudine .",". $cliente->mappa_longitudine;
              $markers .= "&markers=icon:https://static.info-alberghi.com/images/markers/red.png|" . $cliente->mappa_latitudine . "," . $cliente->mappa_longitudine;

              $hotels = [];
              $t=0;
              foreach ($hotel_simili as $hotel)
              {

                $img = ImmagineGallery::where("hotel_id", $hotel->id)
                  ->orderBy('position', 'asc')
                  ->take(1)
                  ->first();
                $img_list = $img->getImg("800x538",true);


                $hotels[$t] = [];
                $hotels[$t][0] = url("/upselling/hotel/").$hotel->id."/from/".$q->id;
                $hotels[$t][1] = Utility::asset($img_list);
                $hotels[$t][2] = $hotel->nome;
                $hotels[$t][3] = $hotel->stelle->nome;
                $hotels[$t][4] = $hotel->indirizzo;
                $hotels[$t][5] = $hotel->localita->nome;

                $markers .= "&markers=icon:https://static.info-alberghi.com/images/markers/blue.png|" . $hotel->mappa_latitudine . "," . $hotel->mappa_longitudine;
                $t++;

              }

              /*
               * Dati hotel contattato
               */

              $hotel_name = $cliente->nome;
              $localita = $cliente->localita->macrolocalita->nome;


              /*
               * Dati destinatario
               */

              $nome_cliente = ($q->nominativo != '') ? $q->nominativo : 'cliente';
              $from = 'no-reply@info-alberghi.com';


              if (App::environment() == "local") {
                $to = 'testing.infoalberghi@gmail.com';
                $bcc = "";
              } else {
                $to = $q->email;
                $bcc = ""; // 'testing.infoalberghi@gmail.com';
              }

              $oggetto  = $this->_getOggettoMail($q->dal, $q->al);

              Utility::swapToSendGrid();

              try
              {
                Mail::send('emails.mail_upselling', compact('nome_cliente', 'hotel_name','localita','hotels','markers'), function ($message) use ($from, $to, &$bcc, $oggetto) {

                  $message->from(Self::MS_RETURN_PATH,'no-reply@info-alberghi.com');
                  $message->returnPath(Self::MS_RETURN_PATH)->sender(Self::MS_RETURN_PATH)->to($to);
                  if ($bcc != "")
                    $message->bcc($bcc);
                  $message->subject($oggetto);

                });
              }
              catch (\Exception $e) { 
                echo "Error " . $e->getMessage(); 
                config('app.debug_log') ? Log::emergency("\n".'---> Errore invio MAILUPSELLING: '.$e->getMessage().' <---'."\n\n") : "";
              }

            } // se ci sono hotel simili mando la mail
          } // se il cliente NON viene trovato

        $q->update(['inviata' => 1]);

      } // end foreach
    } // end if

  }
}
