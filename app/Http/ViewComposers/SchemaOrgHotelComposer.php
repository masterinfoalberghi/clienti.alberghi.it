<?php

/**
 *
 * View composer per render schema.org dell'hotel:
 * @parameters: cliente
 *
 *
 *
 */

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use App\Utility;

class SchemaOrgHotelComposer
  {


    private function _getFromParagrafoWithSubTitle($paragrafi, $nome) 
      {
      foreach ($paragrafi as $paragrafo) 
        {
        if (isset($paragrafo->subtitle) && $paragrafo->subtitle == $nome) 
          {
          //return addslashes($paragrafo->testo); 
          return str_replace('"', "'", $paragrafo->testo);
          } 
        }

      return "";
      }



  
  /**
   * Bind data to the view.
   *
   * @param  View  $view
   * @return void
   */
  public function compose(View $view) 
    {
    
    $viewdata = $view->getData();
    
    $cliente = $viewdata['cliente'];
    $paragrafi = $viewdata['paragrafi'];

    $mylocale = 'it';

    /* Date apertura */
    $apertura = array();

    if ($cliente->annuale)  
      {
      $apertura[] = 'Annuale';
      } 
    else 
      {
      $cliente->aperto_dal->toDateString() !=  '-0001-11-30' ? $apertura[] = trans('labels.apertura') .': <strong>' . Utility::myFormatLocalized($cliente->aperto_dal, '%d %B', $mylocale) .'</strong>' : '';
      $cliente->aperto_al->toDateString() !=  '-0001-11-30' ?  $apertura[] = trans('labels.chiusura') .': <strong>' . Utility::myFormatLocalized($cliente->aperto_al, '%d %B', $mylocale)  .'</strong>' : '';
      }
    /*================================================================================================================================*/


    /* Orari */
    
    $orari = ""; // variabile che contiene tutto il testo degli orari 

    $campo_checkin = 'checkin_'.$mylocale;
    $campo_checkout = 'checkout_'.$mylocale;

    if ($cliente->reception_24h ||
        $cliente->reception1_da != '0:0' ||
        $cliente->reception1_a != '0:0' ||
        $cliente->reception2_da != '0:0' ||
        $cliente->reception2_a != '0:0' ||

        $cliente->$campo_checkin != '' ||
        $cliente->$campo_checkout != '' ||
        
        $cliente->colazione_da != '' ||
        $cliente->colazione_a != '' ||
        $cliente->pranzo_da != '' ||
        $cliente->pranzo_a != '' ||
        $cliente->cena_da != '' ||
        $cliente->cena_a != ''
    ) 
      {
        $reception = "";
        $checkin = "";
        $checkout = "";
        $colazione = "";
        $pranzo = "";
        $cena = "";

        /*  RECEPTION */
        $reception = "<b>Reception:</b> ";

        if ($cliente->reception_24h) 
          {
          $reception .= "24/24 h";
          }
        else
          {
            $reception .= $cliente->writeReception();
          }

        /*CHECKIN CHECKOUT */
        if($cliente->$campo_checkin != '')
          {
          $checkin = "<b>Check in:</b> ".$cliente->$campo_checkin;          
          }

        /*CHECKIN CHECKOUT */
        if($cliente->$campo_checkout != '')
          {
          $checkout = "<b>Check out:</b> ".$cliente->$campo_checkout;         
          }

        
        /* COLAZIONE */
        if ($cliente->colazione_da != '' && $cliente->colazione_a != '') 
        
          {
          $colazione .="<b>".trans('hotel.colazione').":</b> $cliente->colazione_da  - $cliente->colazione_a";
          }
        elseif ($cliente->colazione_da == '' && $cliente->colazione_a != '') 
          {
          $colazione .="<b>".trans('hotel.colazione').":</b> $cliente->colazione_a";
          }
        elseif ($cliente->colazione_da != '' && $cliente->colazione_a == '') 
          {
          $colazione .="<b>".trans('hotel.colazione').":</b> $cliente->colazione_da";
          }

        /* PRANZO */
        if ($cliente->pranzo_da != '' && $cliente->pranzo_a != '') 
          {
          $pranzo .="<b>".trans('hotel.pranzo').":</b> $cliente->pranzo_da  - $cliente->pranzo_a";
          }
        elseif ($cliente->pranzo_da == '' && $cliente->pranzo_a != '') 
          {
          $pranzo .="<b>".trans('hotel.pranzo').":</b> $cliente->pranzo_a";
          }
        elseif ($cliente->pranzo_da != '' && $cliente->pranzo_a == '') 
          {
          $pranzo .="<b>".trans('hotel.pranzo').":</b> $cliente->pranzo_da";
          }
        
        /* CENA */
        if ($cliente->cena_da != '' && $cliente->cena_a != '') 
          {
          $cena .="<b>".trans('hotel.cena').":</b> $cliente->cena_da  - $cliente->cena_a";
          }
        elseif ($cliente->cena_da == '' && $cliente->cena_a != '') 
          {
          $cena .="<b>".trans('hotel.cena').":</b> $cliente->cena_a";
          }
        elseif ($cliente->cena_da != '' && $cliente->cena_a == '') 
          {
          $cena .="<b>".trans('hotel.cena').":</b> $cliente->cena_da";
          }


        $reception  == "" ? $orari .= "" : $orari .= $reception . '<br>';
        $checkin  ==  "" ? $orari .= "" : $orari .= $checkin . '|';
        $checkout   ==  "" ? $orari .= "" : $orari .= $checkout .'<br>';

        $colazione  == "" ? $orari .= "" : $orari .= $colazione;
        $pranzo   == "" ? $orari .= "" : $orari .= '|' . $pranzo;
        $cena     == "" ? $orari .= "" : $orari .= '|' . $cena;


      } 

    /*================================================================================================================================*/




    /*Paragrafo Posizione */
    $posizione = $this->_getFromParagrafoWithSubTitle($paragrafi,"POSIZIONE");
    /*================================================================================================================================*/


    /*Paragrafo Camere */
    $camere = $this->_getFromParagrafoWithSubTitle($paragrafi,"CAMERE");
    /*================================================================================================================================*/


    /*Paragrafo Spiaggia */
    $spiaggia = $this->_getFromParagrafoWithSubTitle($paragrafi,"SPIAGGIA");
    /*================================================================================================================================*/



    // Siccome nella view composer.schemaOrgHotel ho bisogno di dati che vengono giàù reovati in altri Composer
    // chiamo questi composer prima di renderizzare la view composer.schemaOrgHotel in modo da avere i dati che mi servono 
    // questi dati sono disponibili nella vista
    
    $view->with([
                'cliente' => $cliente,
                'posizione' => $posizione,
                'apertura' => $apertura,
                'camere' => $camere,
                'spiaggia' => $spiaggia,
                'orari' => $orari
                ]);
    }
    
  }
