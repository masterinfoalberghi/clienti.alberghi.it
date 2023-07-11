<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParsingController extends Controller
  {

    function __construct()
      {
      $this->url = "http://api.iperbooking.net/v1/getpromotions.cfm?idhotel=";
      $this->username = "username=infoalberghi";
      $this->password = "password=a4jkh10ERewaf3";
      }
  

    private function _print_data($data = array())
      {
      echo "<br><br>===========================================";
       echo "===========================================<br>";
      foreach ($data as $key => $value) 
        {
       echo "$key = $value<br>";
        }
       echo "===========================================";
       echo "===========================================<br><br>";
      }

    // Tutte le date sono nel formato yyyymmdd
    private function _getTimestamp($str_data = "") 
      {
        if ( empty($str_data) ) return $str_data;

        $gg = substr($str_data,-2);
        $mm = substr($str_data,-4,2);
        $aa = substr($str_data,0,4);

        return mktime (0,0,0,$mm,$gg,$aa);

      }


    /////////////////////////////////////////////////////////////////////////////////
    // verifica se OGGI è nel periodo di validità dell'offerta e la mette ATTIVA!! //
    /////////////////////////////////////////////////////////////////////////////////
      // Tutte le date sono nel formato yyyymmdd
    private function _check_period($to, $from)
      {
      return ($this->_getTimestamp($from) <= time() && time() <= $this->_getTimestamp($to));
      }



    private function _getDiscountCode($condition)
      {
      if (in_array($condition->discountcode,['E','EG','EP'])) 
        {

        $sconto = "sconto di € $condition->discount";
        
        if ($condition->discountcode == 'EP') 
          {
          $sconto .= " a persona";
          } 
        elseif($condition->discountcode == 'EG') 
          {
          $sconto .= " al giorno";
          }
        
        } 
      else 
        {
        $sconto = "sconto del $condition->discount%";
        }
        
      return $sconto;
      }

    /**
     * [_getScontoLast sconto 'Early Booking','Last Minute','Lunghi Soggiorni]
     * @param  [type] $condition [description]
     * @return [type]            [description]
     */
    private function _getScontoLast($condition)
      {

      $sconto = "";
      
      $sconto .= $this->_getDiscountCode($condition);

      $sconto .= " per un soggiorno di almeno $condition->days gioni";

      return $sconto;
      
      }


    /**
     * [_getScontoPF Sconto Piano Famiglia]
     * @param  [type] $condition [description]
     * @return [type]            [description]
     */
    private function _getScontoPF($condition)
      {

      //dd($condition);
      $sconto = "";
      
      // Piano Famiglia ( sconto in base al numero di occupanti, Es. 6 occupanti pagano per 4 )
      if ( !empty($condition->guests) && !empty($condition->payingguests) ) 
        {
        $sconto = "$condition->guests occupanti pagano per $condition->payingguests";
        } 
      else 
        {
        //Piano Famiglia ( con gestione delle età dei bambini )
        
        $sconto .= $condition->adults . " adulti";  
        $sconto .= " e ". $condition->children. " bambini";  
        if (!is_null($condition->agefrom)) 
          {
          $sconto .= " a partire da ". $condition->agefrom;
          }
        if (!is_null($condition->ageto)) 
          {
          $sconto .= " fino a ". $condition->ageto." anni ";
          }

        $sconto .= $this->_getDiscountCode($condition);
          
        }
        

      return $sconto;
      
      }


    /**
     * [_getSconto Sconto]
     * @param  [type] $condition [description]
     * @return [type]            [description]
     */
    private function _getSconto($condition)
      {

      //dd($condition);
      $sconto = "";
      
      // Sconto
      if ($condition->applytowholestay == 'true') 
        {
        $sconto = $this->_getDiscountCode($condition);
        } 
      else 
        {
        // Sconto ( applicato a un numero limitato di notti )
        $sconto = $this->_getDiscountCode($condition);
        $sconto .= " per $condition->nights notti";
        }
        

      return $sconto;
      
      }



    public function index($hotel_id = 707)
      {


      $url = $this->url . "$hotel_id" . "&" . $this->username. "&" .$this->password;
    
      // Think of the variable $xml as if it was the <promotions> tag in our xml file.
      $promotions = simplexml_load_file($url);

      foreach ($promotions->promotion as $promotion) 
        {
        $data = [];
        $already_exists = false;
        
        !empty($promotion->id) ? $data['id_iper'] = $promotion->id : $data['id_iper'] = null;

        if(!empty($promotion->id))
          {

          // verifico se esiste_gia
          $check = DB::table('tblOfferteIpernet')->where('id_iper', '=', $promotion->id)->first();
          
          if(!is_null($check))
            {
            $already_exists = true;
            }

          /*1 LIVELLO NO FIGLI */
          !empty($promotion->minchildren) ? $data['minchildren'] = $promotion->minchildren : $data['minchildren'] = 0;
          !empty($promotion->minadults) ? $data['minadults'] = $promotion->minadults : $data['minadults'] = 0;
          !empty($promotion->maxchildren) ? $data['maxchildren'] = $promotion->maxchildren : $data['maxchildren'] = 0;
          !empty($promotion->maxadults) ? $data['maxadults'] = $promotion->maxadults : $data['maxadults'] = 0;
          !empty($promotion->maxlos) ? $data['maxlos'] = $promotion->maxlos : $data['maxlos'] = 0; // durata massima
          !empty($promotion->minlos) ? $data['minlos'] = $promotion->minlos : $data['minlos'] = 0; // durata minima
          !empty($promotion->type) ? $data['type'] = $promotion->type : $data['type'] = null; // tipologia
          /*1 LIVELLO NO FIGLI */



          /* CHECK ATTIVA O NO CON I BOOKING PERIODS */
          $attiva = false;
          // Tutte le date sono nel formato yyyymmdd
          if (!is_null($promotion->bookingperiods->children())) 
            {
            foreach ($promotion->bookingperiods->bookingperiod as $period) 
              {
              if ($this->_check_period($period->to,$period->from))
                {
                $attiva = true;
                break;
                }
              }
            }
          $data['attiva'] = $attiva;
          /* CHECK ATTIVA O NO CON I BOOKING PERIODS */




          /* VALIDA DAL/AL PRENDO SOLO IL PRIMO  */
          if (!is_null($promotion->stayperiods->children())) 
            {
            $period = $promotion->stayperiods->stayperiod[0];
            $data['valida_dal'] = date("Y-m-d",$this->_getTimestamp($period->from));
            $data['valida_al'] = date("Y-m-d",$this->_getTimestamp($period->to));
            }
          /* VALIDA DAL/AL PRENDO SOLO IL PRIMO  */
          


          /* trattamenti per cui è attiva l'offerta (AI, FB, HB, BB, B) */
          $boardtypes_arr = [];
          if (!is_null($promotion->boardtypes->children())) 
            {
            foreach ($promotion->boardtypes->boardtype as $trattamento) 
              {
              $boardtypes_arr[] = $trattamento;
              }
            }

          $data['boardtypes'] = implode(',',$boardtypes_arr);
          /* trattamenti per cui è attiva l'offerta (AI, FB, HB, BB, B) */


          /* TESTI IN LINGUA*/
          if (!is_null($promotion->descriptions->children())) 
            {
            foreach ($promotion->descriptions->description as $testo)
              {
              $lang = strtoupper($testo->language);
              empty($testo->extended) ? $desc = $testo->brief : $desc = $testo->extended;
              $title = $testo->title;

              $data['titolo_'.$lang] = $title;
              $data['descrizione_'.$lang] = $desc;
              }
            }
          /* TESTI IN LINGUA*/



          /* CAMPO CONFIG */
          $configurazione = "";
          
          if (!is_null($promotion->config->children())) 
            {
            if(in_array($promotion->type, ['Early Booking','Last Minute','Lunghi Soggiorni']))
              {
              
              foreach ($promotion->config->conditions->condition as $condition)
                {
                $sconto = $this->_getScontoLast($condition);
                $configurazione = $configurazione . "<br/>". $sconto;
                }
              }

            elseif (in_array($promotion->type, ['Piano Famiglia']))
              {
              
              foreach ($promotion->config->conditions->condition as $condition)
                {
                $sconto = $this->_getScontoPF($condition);
                $configurazione = $configurazione . "<br/>". $sconto;
                }

              }
            elseif (in_array($promotion->type, ['Sconto']))
              {
              ///////////////////////////////////////////////////////
              // ATTENZIONE LO SCONTO NON HA conditions->condition //
              ///////////////////////////////////////////////////////
              // i tag dello sconto sono FIGLI DIRETTI DI <config>
              $condition = $promotion->config; 

              $sconto = $this->_getSconto($condition);
              
              $configurazione = $configurazione . "<br/>". $sconto;
              
              }
            else 
              {
              $configurazione = $promotion->type;
              }

            }

          $data['configurazione'] = $configurazione;


          /* CAMPO CONFIG */

          
          if($already_exists)
            {
            // se aggiorno la rimetto da processare
            $data['processata'] = false;
            $data['updated_at'] = date("Y-m-d H:i:s");
            $check = DB::table('tblOfferteIpernet')->where('id_iper', '=', $promotion->id)->update($data);  
            echo "<br><br><br><br>aggiornato <br>";
            }
          else
            {
            $data['created_at'] = date("Y-m-d H:i:s");
            $check = DB::table('tblOfferteIpernet')->insert($data);  
            echo "<br><br><br><br>inserito <br>";
            }
          
          $this->_print_data($data);
          

          } // /*END if NOT already_exists*/
        else
          {
          echo "<br><br><br><br>NON HO L'ID !!!!<br>";
          }

        
        }



    }

   
  }
