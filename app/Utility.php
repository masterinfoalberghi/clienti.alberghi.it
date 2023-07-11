<?php
namespace App;

use Auth;
use Langs;
use Request;
use DateTime;
use Exception;
use Validator;
use App\CookieIA;
use App\Localita;
use App\CmsPagina;
use Carbon\Carbon;
use App\GruppoServizi;
use App\Macrolocalita;
use App\ImmagineGallery;
use Illuminate\Support\Str;
use SessionResponseMessages;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use App\library\ImageVersionHandler;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class Utility extends Model
{

    /**
     * Indice
     *
     * 1.  Get var
     * 2.  Utility
     * 3.  Detect
     * 4.  Id servizi
     * 5.  Ip
     * 6.  Preferiti
     * 7.  Email
     * 8.  LINGUE
     * 9.  TRATTAMENTI
     * 10. URL
     * 11. Chiamate esterne ( google trans, email subscribe etc. ...)
     * 12. Excerpt, stringe etc...
     * 13. DATE
     * 14. Offerte
     * 15. Menu Verde
     * 16. Stradario, punti forza etc ...
     * 17. Geo
     * 18. Filtri
     * 19. Località
     * 20. Immagini
     * 21. Altro
     */
    
    const SPOT_PATH = "spothome";
    const EVIDENZA_PATH = "pagine";

    /**
     * MAILUP console URL Read more at http://help.mailup.com/display/mailupUserGuide/Connecting+to+MailUp).
     */

    const CONSOLE_URL = 'http://a4g6g.s21.it/frontend/xmlsubscribe.aspx';

    /**
     * E-MAIL VERIFICATION emailhippo.com
     */

    const EV_URI = 'https://api1.27hub.com/api/emh/a/v2?k=';
    const EV_KEY = 'A4496729';
    

    private static $options_cancellazione_gratuita = [1,2,7,3,4,9];




    /**
     * 
     * Icone fontello da associate ai POI
     * vedi il la sezione "Customize Codes" su fontello per associazione
     * 
     */

    private static $fontello_icons = [
        'F1AE' => 'icon-child',
        'F0F5' => 'icon-food',
        'E82C' => 'icon-basket',
        'F279' => 'icon-map',
        'F0F4' => 'icon-coffee',
        'E82A' => 'icon-monument',
        'E825' => 'icon-commerical-building',
        'E803' => 'icon-road',
        'E82F' => 'icon-religious-christian',
        'E830' => 'icon-book',
        'E833' => 'icon-smiley',
        'F19D' => 'icon-graduation-cap',
        'F1E3' => 'icon-soccer-ball',
        'F286' => 'icon-fort-awesome',
        'F19C' => 'icon-bank',
        'E82D' => 'icon-music',
        'F0F9' => 'icon-ambulance',
        'F238' => 'icon-train',
        'E835' => 'icon-waves',
        'F1CD' => 'icon-lifebuoy',
        'F21A' => 'icon-ship',
        'E836' => 'icon-flight',
        'E837' => 'icon-water',
        'E838' => 'icon-pin',
        'E83B' => 'icon-flag-filled',
        'E809' => 'icon-heart',
        'E813' => 'icon-camera',
        'F140' => 'icon-bullseye',
        'F1BB' => 'icon-tree',
        'F29A' => 'icon-universal-access',
        'E826' => 'icon-star',
        'F0E0' => 'icon-mail-alt',
        'F17C' => 'icon-linux',
        'F1D6' => 'icon-qq',
        'F2B2' => 'icon-themeisle',
        'E804' => 'icon-location',
        'F11D' => 'icon-flag-empty',
        'F192' => 'icon-dot-circled',
        'F1E7' => 'icon-slideshare',
        'E82E' => 'icon-info-1',
        'E834' => 'location-1',
        'F1BA' => 'icon-taxi',
        'F276' => 'map-pin',
        'E842' => 'icon-warehouse',
        'F21C' => 'icon-motorcycle',
        'E840' => 'icon-fire',
        'E841' => 'icon-key',
        'E843' => 'icon-town-hall',
        'E844' => 'icon-museum',
        'E845' => 'icon-garden',
        'E846' => 'icon-school',
        'E847' => 'icon-swimming',
        'E848' => 'icon-basketball',
        'E849' => 'icon-home-1',
        'E84A' => 'icon-videocam',
        'E84B' => 'icon-video-alt',
        'E84C' => 'icon-video',
        'E84D' => 'icon-suitcase',
        'E84E' => 'icon-briefcase',
        'E84F' => 'icon-book-1',
        'E850' => 'icon-users',
        'F1AD' => 'icon-building-filled',
        'F18C' => 'icon-pagelines',
        'F291' => 'icon-shopping-basket',
        'E83A' => 'icon-religious-christian',
    ];



    /**
     * Lingue
     */
    
    private static $lang = ['it', 'en', 'fr', 'de'];
    
    private static $language = [
        "it" => ["italiano" ,"it_IT"	,"it"],
        "en" => ["inglese"	,"en_GB"	,"ing"],
        "fr" => ["francese"	,"fr_FR"	,"fr"],
        "de" => ["tedesco"	,"de_DE"	,"ted"]
    ];	

    private static $url_locale_to_app_locale = [
        "it" => "it",
        "ing" => "en",
        "fr" => "fr",
        "ted" => "de"
    ];


    private static $app_locale_to_url_locale = [
        "it" => "",
        "en" => "ing",
        "fr" => "fr",
        "de" => "ted"
    ];

    

    private static $app_locale_to_set_locale = [
        "it" => "it_IT.utf8",
        "en" => "en_GB.utf8",
        "fr" => "fr_FR.utf8",
        "de" => "de_DE.utf8"
    ];



    /**
     * IP Interni
     */

    private static $IP = [
        '::1',
        '127.0.0.1', // loopback
        '192.168.1.68', // my IP
        '192.168.1.236', // Lucio IP
        '188.10.53.211', // vecchio Telecom Alice che ogni tanto rimettiamo 
        '2.224.168.43', // ns FASTWEB ULTRAFIBRA
        '2.39.213.143',
        '45.135.232.236',
        '185.19.184.170', // alpha.info-alberghi.com
        '151.69.4.20',
        '185.37.116.8' // NUOVO IP UFFICO (TELEIMPIANTI)
  ];

    /**
     * Virtual Host
     */

    private static $VH = [
        "www.info-alberghi.com"
    ];
    

    /**
     * Trattamenti
     */
     
    private static $trattamenti_nomi_arr = [
        "trattamento_ai" => "All inclusive",
        "trattamento_pc" => "Pensione completa",
        "trattamento_mp" => "Mezza pensione",
        "trattamento_mp_spiaggia" => "Mezza pensione + Spiaggia",
        "trattamento_bb" => "Bed &amp; breakfast",
        "trattamento_bb_spiaggia" => "Bed &amp; breakfast + Spiaggia",
        "trattamento_sd" => "Solo dormire",
        "trattamento_sd_spiaggia" => "Solo dormire + Spiaggia"
    ];
    
    private static $trattamenti_arr = [
        "trattamento_ai" => ["listing.ai","AI","All inclusive"],
        "trattamento_pc" => ["listing.pc","FB","Full board"],
        "trattamento_mp" => ["listing.mp","HB","Half board"],
        "trattamento_mp_spiaggia" => ["listing.mp_s","MP_S","Half board and beach"],
        "trattamento_bb" => ["listing.bb","BB","Bed & breakfast"],
        "trattamento_bb_spiaggia" => ["listing.bb_s","BB_S","Bed & breakfast and beach"],
        "trattamento_sd" => ['listing.sd','SD','Room only'],
        "trattamento_sd_spiaggia" => ['listing.sd_s','SD_S','Room only and beach'],
    ];

    private static $trattamenti_json_arr = [
        "AI" => "trattamento_ai",
        "FB" => "trattamento_pc",
        "HB" => "trattamento_mp",
        "BB" => "trattamento_bb",
        "SD" => "trattamento_sd",
        "MP_S" => "trattamento_mp_spiaggia",
        "BB_S" => "trattamento_bb_spiaggia",
        "SD_S" => "trattamento_sd_spiaggia",

    ];

    private static $trattamentiOfferte_arr = [
        "sd" => 'listing.sd',
        "bb" => "listing.bb",
        "hb" => "listing.mp",
        "fb" => "listing.pc",
        "ai" => "listing.ai",
        "mp_s" => "listing.mp_s",
        "bb_s" => "listing.bb_s",
        "sd_s" => "listing.sd_s",
        "tt" => "listing.tt"
    ];


    private static  $trattamenti_con_spiaggia = ['trattamento_mp', 'trattamento_bb', 'trattamento_sd'];

    private static $offerte_numero_persone = [
        '1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5',
        '6'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'10',
        '15'=>'15','20'=>'20','25'=>'25','30'=>'30','35'=>'35',
        '40' => '40', '45' => '45', '50' => '50',
    ];

    private static $max_wishlist = 50;
    private static $max_newsletterlinks = 10;
    

    private static $sender_blacklist = array(
        'info@piadinaromagnolarimini.it',
        'info@dollyservice.it',
        'laurastre965@hotmail.it',
        'vcn.pignatiello@libero.it',
        'tipolitogullotta@tiscali.it',
        'hobbyfilms1982@gmail.com',
        'gala.sas@alice.it',
        'kseniyaebjfed@mail.ru',
        'leone.paolo@gmail.com',
        'leonepaolo@gmail.com',
        'sase27@outlook.it',
        'cridis5@gmail.com',
        'Ciprian.curiman@gmail.com',
        'nistorg26@gmail.com',
        'matchia77@yahoo.com',
        'Khadijabourjaa@gmail.com',
        'lucianoattrezzature@outlook.it',
        'marcobelleri@gmail.com', // Bloccata perchè usata per sbaglio da un altro utente
        'lorenzi.ale85@outlook.it',
        'ginoanna7070@gmail.com',
        'arachni@email.gr'
    );

    private static  $mesi = [
      '1' => 'Gennaio',
      '2' => 'Febbraio',
      '3' => 'Marzo',
      '4' => 'Aprile',
      '5' => 'Maggio',
      '6' => 'Giugno',
      '7' => 'Luglio',
      '8' => 'Agosto',
      '9' => 'Settembre',
      '10' => 'Ottobre',
      '11' => 'Novembre',
      '12' => 'Dicembre',
     ];
     
    private static $ventiquattro = [
        '0'=>'0',
        '1'=>'1',
        '2'=>'2',
        '3'=>'3',
        '4'=>'4',
        '5'=>'5',
        '6'=>'6',
        '7'=>'7',
        '8'=>'8',
        '9'=>'9',
        '10'=>'10',
        '11'=>'11',
        '12'=>'12',
        '13'=>'13',
        '14'=>'14',
        '15'=>'15',
        '16'=>'16',
        '17'=>'17',
        '18'=>'18',
        '19'=>'19',
        '20'=>'20',
        '21'=>'21',
        '22'=>'22',
        '23'=>'23',
        '24'=>'24'
    ];

    private static $offerteInEvidenza = [

        // Inizio, fine, ricorsivo, idParolaChiave, descrizione

        /*["2018-12-17","2018-1-1",1,45,"Capodanno"],
        ["2018-11-5","2018-11-22",0,21,"FIERA Gluten free expo"],
        ["2018-10-15","2018-11-1",1,24,"Halloween"],
        ["2018-10-15","2018-11-10",0,40,"FIERA Ecomondo "],	
        ["2018-10-1","2018-10-13",0,59,"FIERA Siaguest"],
        ["2018-10-1","2018-10-13",0,61,"FIERA Sun"], 
        ["2018-9-25","2018-10-13",0,54,"FIERA TTG Incontri"],
        ["2018-8-25","2018-9-11",0,42,"FIERA Gelato world tour"], 
        ["2018-8-25","2018-9-10",0,1,"Moto GP"],
        ["2018-8-1","2018-8-16",1,25,"Ferragosto"],
        ["2017-6-25","2017-7-8",0,26,"Notte rosa"],
        ["2017-6-10","2017-6-24",0,23,"Molo street parade"],
        ["2018-5-15","2018-6-3",1,4,"2 Giugno"],
        ["2018-5-15","2018-6-4",0,51,"FIERA Rimini Wellness"],
        ["2017-5-5","2017-5-21",0,14,"9 Colli"],
        ["2018-5-1","2018-5-19",0,53,"FIERA Expodental"], 
        ["2018-4-15","2018-5-2",1,6,"Primo Maggio"],
        ["2018-4-10","2018-4-26",1,4,"25 Aprile"],
        ["2018-3-1","2018-3-17",0,63,"FIERA Enada"],
        ["2018-2-1","2018-2-16",1,17,"San Valentino"],
        ["2018-2-1","2018-2-18",0,15,"FIERA Beer Attraction (fiera della birra)"],
        ["2018-2-1","2018-4-2",0,68,"Pasqua"],
        ["2018-1-4","2018-1-24",0,58,"FIERA Sigep"]*/
    ];




    public static $mappaServizi =
        // Inizio, fine ,descrizione)
        array(
                    "trattamenti" => ["2018-2-1", "2018-9-30", ""],
                        "4" => ["2018-2-1", "2018-9-30","2 Giugno"],
                      "6" => ["2018-2-1", "2018-9-30","1 Maggio"],
                      "7" => ["2018-5-1", "2018-9-30","Agosto"],
                      "8" => ["2018-5-1", "2018-9-30","Luglio"],
                      "9" => ["2018-2-1", "2018-9-30","Giugno"],
                      "10" =>["2018-2-1", "2018-9-30", "Maggio"],
                      "11" =>["2018-2-1", "2018-4-25", "25 Aprile"],
                      "22" =>["2018-2-1", "2018-9-30", "Terme"],
                      "25" =>["2018-5-1", "2018-9-30", "Ferragosto"],
                      "26" =>["2018-3-1", "2018-9-30", "Notte rosa"],
                      "64" =>["2018-2-1", "2018-9-30", "Fiera Rimini"],
                      "68" =>["2018-2-1", "2018-4-1", "Pasqua"],
                    );


    public static $orderParams = [
        '0', 'nome', 'categoria_asc', 'categoria_desc', 'prezzo_min', 'prezzo_max'
    ];

    public static $filterParams = [
        '0', 'annuale', 'aperto_capodanno', 'aperto_pasqua', 'aperto_eventi_e_fiere'
    ];

    public static $cms_pagine_gia_mostrate = [];
    public static $debugTagString = "";




    /* 
    @Lucio vuole che dal 15/12/2020 appaia già 2021 in alcuni punti del sito (title/description, scheda e pagine ) 
    questa funzione restituisce 1 (aumenta l'anno) prima del 31/12/2020 e restituisce 0 dopo il 01/01/2021 
    */
    public static function fakeNewYear()
      {
      $comparison_date_string = "2020-12-31";
      //$comparison_date_string = "2020-12-18";

      $comparison_date = Carbon::createFromFormat('Y-m-d', $comparison_date_string);
      $today = Carbon::today();

      if( $today->greaterThanOrEqualTo($comparison_date) )
        {
        return 0;
        } 
      else
        {
        return 1;
        }
      }




    public static function isValidMappaServizio($servizio = "")
    {

    if ($servizio == '' || !array_key_exists($servizio, self::$mappaServizi)) 
        {
        return false;
        } 
    else 
        {
        list($dal, $al, $descrizione) = self::$mappaServizi[$servizio];
        
        $dal_carbon = Carbon::createFromFormat('Y-m-d H:i:s', $dal.' 00:00:00');
        $al_carbon = Carbon::createFromFormat('Y-m-d H:i:s', $al.' 23:59:59');

        return Carbon::now()->between($dal_carbon, $al_carbon);
        }
    
    }



    /* -------------------------------------------------------------------------------------
     * 0. Fontello Icons (POI)
     * ------------------------------------------------------------------------------------- */
    public static function fontelloIcons()
    {
        return self::$fontello_icons;
    }



    /* -------------------------------------------------------------------------------------
     * 1. Get var
     * ------------------------------------------------------------------------------------- */
    
    public static function offerte_numero_persone_select() { return self::$offerte_numero_persone; }

    public static function mesi() { return self::$mesi; }

    public static function getMaxWishlist() { return self::$max_wishlist; }
    public static function getMaxNewsletterLinks() { return self::$max_newsletterlinks; }

    public static function VentiQuattro() { return self::$ventiquattro; }
    
    public static function getFeaturedPage() { return DB::table('tblCmsPagine')->where('uri', 'rimini.php')->first(); }

    static public function getUiAvailableRoles() { return [ "operatore" => "operatore", "commerciale" => "commerciale", "hotel" => "hotel"]; }




    /* -------------------------------------------------------------------------------------
     * 2. Utility
     * ------------------------------------------------------------------------------------- */


     public static function echoDebug( $txt,  $var ) {
         if ($txt != "")
             return $txt . ": <pre>" . print_r($var, 1) . "</pre>";
         
         else 
             return "<pre>" . print_r($var, 1) . "</pre>";
     }

    /**
     * Dato un valore, ne fa il casting a boolean, per poi tornarlo nel formato "si" / "no"
     * @param  mixed $value  il valore da mostrare come "si" / "no"
     * @param  boolean $hilite quale valore evidenziare. Null per non evidenziare nulla
     * @return string "si" / "no"
     */
     
    public static function viewBool($value, $hilite = null)
    {
        $value = (bool)$value;

        $cast = $value ? "si" : "no";

        if ($hilite === $value)
            return '<strong class="text-warning">'.$cast.'</strong>';

        return $cast;
    }
    
    /**
     * Toglie i -1 dall'array
     */

    public static function purgeMenoUnoOld ($eta, $bambini) {
          
          $eta = explode("," , $eta);
          for ($t = 0; $t< 6; $t++):
              if ($t>=$bambini)
              unset($eta[$t]);
          endfor;
          return implode("," , $eta);
        
    }
    

        public static function purgeMenoUno ($eta, $bambini) {
          
        $eta = explode("," , $eta);
           
        return implode("," , array_slice($eta, 0, $bambini));
      

      }

      public static function purgeMenoUnoArray ($eta) {
          
          for ($t = 0; $t< 6; $t++):
              if (isset($eta[$t]) && $eta[$t] == "-1")
                  unset($eta[$t]);
          endfor;
          return $eta;
          
      }

    /**
     * Scriveun un numero di telefono come link
     */
    
     public static function telephoneLink ($num_tel) {
    
        $int = filter_var($num_tel, FILTER_SANITIZE_NUMBER_INT);
        return "+39" . $int;
    
    }

    public static function getPhoneWa ($prefix, $number) {
        $number = str_replace("+" . $prefix, "", $number);
        return $prefix . $number;
    }
    
    
    

    
    

    /* -------------------------------------------------------------------------------------
     * 3. Detect
     * ------------------------------------------------------------------------------------- */


     
     
     
     
    /**
     * Detect del dispositivo .
     * 
     * @access public
     * @static
     * @return void
     */
     
    public static function DetectMobile()
    {

        $detect = new \Detection\MobileDetect;
        $deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
        return $deviceType;

    }
    
    /**
     * verifica se è stato compilato il campo esca
     * questo campo viene compilato solo da un robot è settatto style="display:none;"!!!!
     * 
     * @access public
     * @static
     * @param string $esca (default: "")
     * @return void
     */
     
    public static function checkSpider($esca = "")
    {
        if (trim($esca) != "") {
            return 2;
        }
        else {
            return 0;
        }
    }



    

    /* -------------------------------------------------------------------------------------
     * 4. Id servizi
     * ------------------------------------------------------------------------------------- */


    /**
    * 
    */

    static public function getIdMicroPesaro()
    {
        return 50;
    }

    static public function getIdMacroPesaro()
    {
        return 12;
    }

    //? ID dei POI "Ruota Panoramica" e "Fiera Rimini"
    //? da togliere se l'hotel è di PESARO
    static public function getIdPoiNotPesaro()
    {
        return [8,9];
    }

    
    /**
     * Ritorna l'ID di RR.
     * 
     * @access public
     * @static
     * @return void
     */
     
    static public function getMacroRR() { return 11; }

    static public function getMacroBellaria() { return 6; }
    
    static public function getGruppiServizi($locale) {
        return GruppoServizi::where("ricerca_mappa", 1)->pluck("nome_" . $locale,"id")->toArray();
    }

    /**
     * Ritorna gli ID delle localita di RR
     * 
     * @access public
     * @static
     * @return void
     */
     
    static public function getMicroRR() {return Macrolocalita::find(self::getMacroRR())->localita()->first()->id; }


    /**
     * I listing di tipo piscina (tipo servizio) hanno un listing_gruppo_servizio_id che è l'id della tabella tblGruppoServizi con nome 'piscina'
     *
     * @return String ID
     */
     
    static public function getGruppoPiscina() { return GruppoServizi::tipoPiscina()->first()->id; }


    static public function getGruppoPiscinaFuori() { return GruppoServizi::tipoPiscinaFuori()->first()->id; }



    /**
     * I listing di tipo benessere (tipo servizio) hanno un listing_gruppo_servizio_id che è l'id della tabella tblGruppoServizi con nome 'benessere'
     *
     * @return String ID
     * 
     * Per il momento, per non escludere gli hotel che non hanno compilato la scheda benessere faccio ritornare da 
     * Utility::getGruppoBenessere() un valore farlocco in modo che NON SI ESEGUA MAI IL CODICE del gruppo benessere
     * SARA' DA SCOMMENTARE !!!!!
     */
     
    static public function getGruppoBenessere() { return GruppoServizi::tipoBenessere()->first()->id; /* return -50; */}	



    /* -------------------------------------------------------------------------------------
     * 5. Ip
     * ------------------------------------------------------------------------------------- */


    
    /**
     * Prende l'id del visitatore
     * 
     * @access public
     * @static
     * @return void
     */
     
    public static function get_client_ip()
    {
        
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
            
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
            
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
            
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
            
        else if(getenv('HTTP_FORWARDED'))
           $ipaddress = getenv('HTTP_FORWARDED');
           
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
            
        else
            $ipaddress = 'UNKNOWN';
            
        return $ipaddress;
         
    }
    
    /**
     * Maschera l'ip nelle email
     * @param  String $ip il numero ip del cliente
     * @return String $ip il numero ip del cliente mascherato
     */
    
    public static function maskIP($ip) {
        
        return preg_replace('!(\d+).(\d+).\d+.\d+!s', '${1}.${2}.***.***', $ip);
            
    }
    
    /**
     * Cambia l'IP di MAMP in un ip vero
     * @param  String $ip il numero ip del cliente
     * @return String $ip il numero ip del corretto se sono in locale
     */
    
    public static function localIpToNumberIp ($ip) {
        
        if ($ip == "::1")
            return "127.0.0.1";
        
        return $ip;
        
    }
    
    /**
     * Ritorno l'array con i valori validi per gli IP.
     * 
     * @access public
     * @static
     * @return void
     */
     
    public static function validIP()
    {
        return self::$IP;
    }


    public static function validVH()
    {
        return self::$VH;
    }

    public static function isValidIP() 
    {
        return in_array(self::get_client_ip() , self::$IP);
    }


    /* -------------------------------------------------------------------------------------
     * 6. Cache
     * ------------------------------------------------------------------------------------- */

    public static function checkLoginCookie()
    {

        $login_cookie = Cookie::get('login_cookie');

        return $login_cookie;
    }


        
    /**
     * Debugga la cache
     *
     * @access public
     * @static
     * @param string $key
     * @param string $testo
     * @return void
     */
    
    public static function debugTag($key, $testo )
    {

        if (Config::get("cache.debug_cache") ) {

            if (is_null(Self::checkLoginCookie())):
                if (Cache::has($key) && Config::get("cache.active_cache"))
                    Self::$debugTagString .= "<span style=\"color:#8BC34A\">". $testo ."<br /><small style=\"color:#fff; opacity:0.8\">". $key . "</small></span><br /><br />";
                else
                    Self::$debugTagString .= "<span style=\"color:#B20000\">". $testo ."<br /><small style=\"color:#fff; opacity:0.8\">". $key . "</b></small><br /><br />";
                else:
                    Self::$debugTagString .= "<span style=\"color:#FF9326\">". $testo ."<br /><small style=\"color:#fff; opacity:0.8\">". $key . "</b></small><br /><br />";
                endif;


        }

    }


    /**
     * Metto l'oggetto in cache a meno che non sia loggato, allora non faccio niente
     *
     * @access public
     * @static
     * @param string $key
     * @param mixed $object
     * @param Carbon $time (default: null)
     * @return void
     */

    public static function putCache($key, $object, $time = null)
    {
        
        $notTime = false;
        
        // Attenzione se ho tempo 0 allora bypasso la cache
    
        if ((is_string($time) && $time == "0"))
            $notTime = true;
            
        else {
            
            if ($time == null)
                $time = Config::get("cache.other");
                
            elseif ( is_string($time))
                $time = intval($time);
        
        }
        
        if (Config::get("cache.active_cache") && is_null(Self::checkLoginCookie()) && !$notTime)
            Cache::put($key, $object, $time);

    }

      /**
     * Metto l'oggetto in cache SOLO SE SONO loggato
     *
     * @access public
     * @static
     * @param string $key
     * @param mixed $object
     * @param Carbon $time (default: null)
     * @return void
     */

    public static function putCacheAdmin($key, $object, $time = null)
    {
        
        $notTime = false;
        
        // Attenzione se ho tempo 0 allora bypasso la cache
    
        if ((is_string($time) && $time == "0"))
            $notTime = true;
            
        else {
            
            if ($time == null) {
                
                $time = intval(Config::get("cache.other"));
                $time = Carbon::now()->addSeconds($time);
                
            } elseif ( is_string($time)) {
                
                $time = Carbon::now()->addSeconds(intval($time));
        
            }
        
        }
        
        //dump ($time, $notTime );
        
        if (!is_null(Self::checkLoginCookie()) && !$notTime)
            Cache::put($key, $object, $time);

    }


    /**
     * Controlla se la chiave è in cache
     * 
     * @access public
     * @static
     * @param string $key
     * @param Object $testo
     * @param bool $forceCache (default: false)
     * @param bool $forceDebug (default: false)
     * @return Cache
     */
    
    public static function activeCache($key, $testo, $forceCache = false, $forceDebug = false) 
    {
        
        $return = null;		
        Self::debugTag($key, $testo);
        
        if ($forceCache)
            return null;
        
        if (Config::get("cache.active_cache") && Cache::has($key) && is_null(Self::checkLoginCookie()))
            return Cache::get($key);
            
        return null;
        
    }


    //? Cache SOLO SE SEI LOGATO
    public static function activeCacheAdmin($key, $testo, $forceCache = false, $forceDebug = false) 
    {
        
        $return = null;     
        Self::debugTag($key, $testo);
        
        if ($forceCache)
            return null;
        
        if (Config::get("cache.active_cache") && Cache::has($key) && !is_null(Self::checkLoginCookie()))
            return Cache::get($key);
            
        return null;
        
    }


    /**
     * DEPRECATA
     * Elimina la cache.
     *
     * @access public
     * @static
     * @param int $id (default: 0)
     * @param mixed $cache_item_key (default: [])
     * @param string $tipo (default: "hotel")
     * @return void
     */

    public static function removeCache($id = 0, $cache_item_key = [], $tipo = "hotel") { }
    /**
     * Elimina la cache.
     * 
     * @access public
     * @static
     * @param int $id (default: 0)
     */
    
    public static function clearCacheHotel($id) {
        
        foreach (Langs::getAll() as $l) {
        
            Cache::forget('hotel_' . $id . "_" . $l); // Cache hotel
            Cache::forget('hotel_extra_' . $id . "_" . $l); // Cache hotel ( complementi )
            Cache::forget('gallery_items_desktop_' . $id . "_" . $l); // Cache Gallery Items
            Cache::forget('CategoriaServizi_' . $id . "_" . $l); // Cache Categoria Servizi
            Cache::forget('listino_strandard_' . $id . "_" . $l); // Cache Listino
            Cache::forget('listino_minmax_' . $id . "_" . $l); // Cache MinMax
            Cache::forget('listino_custom_' . $id . "_" . $l); // Cache Custom
            Cache::forget('bambini_items_' . $id . "_" . $l); // CacheBambini Items

        }

    }


    /* -------------------------------------------------------------------------------------
     * 7. Preferiti
     * ------------------------------------------------------------------------------------- */
     
     
     
     
    /**
     * Prende i preferiti da mettere sul link dell'header
     * 
     * @access public
     * @static
     * @return void
     */
     
    public static function getPreferitiCount() 
    {
        
        $favoriti = CookieIA::getFavourite();
        $favoriti = explode("," , $favoriti);
        $n = count($favoriti) - 2; //$preferiti->count();
        if ($n<0)
            $n = 0;
        return $n;
        
    }
    
    
    
    
    
    /* -------------------------------------------------------------------------------------
     * 8. Email
     * ------------------------------------------------------------------------------------- */
    
    
    
     
    
    /**
     * Verifica delle email.
     * Contruzione dell' url da chiamare
     * 
     * @access private
     * @static
     * @param mixed $email
     * @return void
     */
     
    private static function _emailVerificationUrl($email) { return self::EV_URI.self::EV_KEY.'&e='.$email; }
    
    /**
     * Verifica delle email.
     * 
     * @access public
     * @static
     * @param string $email (default: "")
     * @return void
     */
     
    public static function EmailVerification($email = "")
    {
        if ($email == "") return "Bad|EmailEmpty";

        // Initializing curl
        $ch = curl_init( self::_emailVerificationUrl($email) );

        if($ch == false) {
            
             die ("Curl failed!");
             
        } else {
         
          // Configuring curl options
          $options = array(
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_HTTPHEADER => array('Content-type: application/json')
          );
           
          // Setting curl options
          curl_setopt_array( $ch, $options );
           
          // Getting results
          $result = curl_exec($ch); // Getting jSON result string
           
          // display JSON data
          /*{"result":"Bad","reason":"NoMxServersFound","role":false,"free":false,"disposable":false,"email":"marta.monaco93@yhaoo.it","domain":"yhaoo.it","user":"marta.monaco93","mailServerLocation":null,"duration":157}"*/

          $risp = json_decode($result);


          /*
          Main Status Response Codes
          Ok:	Verification passes all checks including Syntax, DNS, MX, Mailbox, Deep Server Configuration, Grey Listing
          Bad:	Verification fails checks for definitive reasons (e.g. mailbox does not exist)
          RetryLater:	Conclusive verification result cannot be achieved at this time. Please try again later.
          Unverifiable:	Conclusive verification result cannot be achieved due to mail server configuration or anti-spam measures. See table “Additional Status Codes”. See also What Verification Results Mean to Your Email Campaign .
          */

          return $risp->{'result'}."|".$risp->{'reason'}; // "Bad|NoMxServersFound"
           
        }

    }

    
    /**
     * Cambio i dati al volo per spedire con Sendgrid.
     * 
     * @access public
     * @static
     * @return void
     */
     
    public static function swapToSendGrid()
    {        
        if ( App::environment() == 'production') {

            Config::set('mail.username', env('SENDGRID_USERNAME', "infoalberghi.com"));
            Config::set('mail.password', env('SENDGRID_PASSWORD', "yKW7KCYz"));
            Config::set('mail.host', env('SENDGRID_HOST', 'smtp.sendgrid.net'));
            Config::set('mail.port', env('SENDGRID_PORT', 587));
            Config::set('mail.encryption', env('SENDGRID_ENCRYPTION', "tls"));

        } else {

            if (Config::get("mail.use_mailtrap")) {

                Config::set('mail.username', env('MAILTRAP_USERNAME', "674ed9cee13308")); 
                Config::set('mail.password', env('MAILTRAP_PASSWORD', "eed2bdb912bd40")); 
                Config::set('mail.host', env('MAILTRAP_HOST', "smtp.mailtrap.io")); 
                Config::set('mail.port', env('MAILTRAP_PORT', 2525)); 

            } 

        }

    }


    public static function swapToMailUp()
    {
        
        Config::set('mail.username', env('MAILUP_USERNAME'));
        Config::set('mail.password', env('MAILUP_PASSWORD'));
        Config::set('mail.host', env('MAILUP_HOST'));
        Config::set('mail.port', env('MAILUP_PORT'));
        Config::set('mail.encryption', env('MAILUP_ENCRYPTION'));

        /*if ( App::environment() == 'production' || true) {

            Config::set('mail.username', env('MAILUP_USERNAME',"s14767_9"));
            Config::set('mail.password', env('MAILUP_PASSWORD', "4lg2W?71Tr"));
            Config::set('mail.host', env('MAILUP_HOST', "fast.smtpok.com"));
            Config::set('mail.port', env('MAILUP_PORT', 1025));
            Config::set('mail.encryption', env('MAILUP_ENCRYPTION', "STARTTLS"));

        } else {

            if (Config::get("mail.use_mailtrap")) {

                Config::set('mail.username', env('MAILTRAP_USERNAME', "a63713caa52aa5")); 
                Config::set('mail.password', env('MAILTRAP_PASSWORD', "abc94884c33690")); 
                Config::set('mail.host', env('MAILTRAP_HOST', "smtp.mailtrap.io")); 
                Config::set('mail.port', env('MAILTRAP_PORT', 2525)); 

            } 

        }
    */
    }




    
    public static function controlloLogMailDoppie ( $id, $codice_cookie, $prefill ) {
        
        $controllo = MailDoppie::where("codice_cookie", $codice_cookie)
                        ->where('ids_send_mail', $idArray)
                        ->where('prefill', $prefill)
                        ->where('created_at', '>' , Carbon::now()->subDay(3))
                        ->orderBy('created_at', 'desc')
                        ->first();
                            
        return $controllo;
        
    }
    
    
    
    
    public static function scrittoDiRecente($email, $id) 
    {
        
        $scritto = false;
        $confronto = MailScheda::where("email", $email)
                        ->where('hotel_id', $id)
                        ->where('created_at' ,'>', Carbon::now()->subDay(3))
                        ->orderBy('created_at', 'desc')
                        ->first();
        
        return $confronto;

    }

    /**
     * Iscrizione alla newsletter di mailup
     * 
     * @access public
     * @static
     * @param String $email (default: "")
     * @return void
     */

    public static function mailUpSubscribe($email = "")
    {

        if ($email != "") {

            $Email = $email;
            $IDList = "1";
            $IDGroup = "31,50";
            $RequiredConfirmation = false;
            $ReturnCode = 0;
            return self::_CallXmlSubscribeConsole($Email, $IDList, $IDGroup, $RequiredConfirmation, $ReturnCode);

        } else 
        
            return "";

    }


    /**
     * Prepara i dati del soggiorno da spedire nell'email
     * 
     * DEPRECATA
     * 
     * @access public
     * @static
     * @param array $dati_mail (default: array())
     * @param bool $table (default: false)
     * @param bool $style (default: false)
     * @return String
     */

    // public static function preparaSoggiorni($dati_soggiorni) 
    // {
    // 
    // 
    // 	$msg_dati = "";
    // 	foreach($dati_soggiorni as $ds) {
    // 
    // 		foreach($ds as $key => $value) {
    // 
    // 			if ($value != "" ) {
    // 				if ($key != 'Vuoto'){
    // 					if (strpos($key, '2_cols') !== false) {
    // 
    // 						$msg_dati .= '<tr>' .
    // 										 '<td colspan="2" align="center" style="background: none; padding:5px; margin: 2px 0; font-size:14px; text-align:left;">' .
    // 											 '<span style="color:#666;"><b>' . 
    // 											 	$value[3] . 
    // 											 '</b></span>' .
    // 										 '</td>' . 
    // 									 '</tr>';
    // 
    // 					} else {
    // 
    // 						$msg_dati .= '<tr>' .
    // 										'<td style="width:150px; background: #fff; padding:5px; margin: 2px 0; font-size:14px;">' .
    // 											'<span style="color:#666;"><b>' . 
    // 												$key . 
    // 											'</b></span>' .
    // 										'</td>' .
    // 										'<td  style="background: #fff; padding:5px; margin: 2px 0;font-size:14px;">' .
    // 											'<span style="color:#222;">' . 
    // 												$value[3] . 
    // 											'</span>' .
    // 										'</td>' . 
    // 									'</tr>';
    // 					}
    // 
    // 				} else {
    // 
    // 					$msg_dati .= '<tr>' .
    // 									'<td style="width:150px; background: none; padding:5px; margin: 2px 0; font-size:14px;" colspan="2">&nbsp;</td>' . 
    // 								'</tr>';
    // 
    // 				}
    // 
    // 			}
    // 
    // 		}
    // 	}
    // 
    // 	return $msg_dati;
    // 
    // } 
    
    
    
    /**
     * Prepara i dati da spedire nell'email.
     * 
     * DEPRECATA
     * 
     * @access public
     * @static
     * @param array $dati_mail (default: array())
     * @param bool $table (default: false)
     * @param bool $style (default: false)
     * @return String
     */
     
    // public static function preparaDati($dati_mail = array(), $table = false, $style= false)
    // {	
    // 
    // 	if (!$table)
    // 		$msg_dati = "";
    // 	else
    // 		$msg_dati = "<table width='700'>";
    // 
    // 	foreach ($dati_mail as $key => $value) {
    // 
    // 		if ($value != "" ) {
    // 
    // 			if ($key != 'Vuoto'){
    // 
    // 				if (strpos($key, '2_cols') !== false) {
    // 
    // 					$msg_dati .= '<tr>' .
    // 									'<td colspan="2" align="center" style="background: none; padding:5px; margin: 2px 0; font-size:14px; text-align:left;">' .
    // 										'<span style="color:#666;"><b>' . 
    // 											$value[3] . 
    // 										'</b></span>' . 
    // 									'</td>' .
    // 								'</tr>';
    // 
    // 				} else {
    // 
    // 					if ($key == "Soggiorni") {
    // 
    // 						$msg_dati .= Self::preparaSoggiorni($value);
    // 
    // 					} else if ($key == "Codice email") {
    // 
    // 						$msg_dati .= '<tr>' . 
    // 										'<td valign="top" style="width:150px; background: #fff; padding:5px; margin: 2px 0; font-size:14px;">' . 
    // 											'<span style="color:#666;"><b>' . 
    // 												$key . 
    // 											'</b></span>' .
    // 										'</td>' . 
    // 										'<td style="padding:5px; background: #fff; margin: 2px 0; overflow-wrap: break-word; word-wrap: break-word;-ms-word-break: break-all; word-break: break-all; word-break: break-word; -ms-hyphens: auto; -moz-hyphens: auto; -webkit-hyphens: auto; hyphens: auto;">' .
    // 											'<span style="color:#222; display: block; font-size:10px; line-height:12px; color:#666; ">' . 
    // 												$value[3] . 
    // 											'</span>' . 
    // 										'</td>' . 
    // 									'</tr>';
    // 
    // 					} else {
    // 
    // 						$msg_dati .= '<tr>' . 
    // 										'<td style="width:150px; background: #fff; padding:5px; margin: 2px 0; font-size:14px;">' . 
    // 											'<span style="color:#666;"><b>'. $key . '</b></span>' . 
    // 										'</td>' . 
    // 										'<td  style=" background: #fff; padding:5px; margin: 2px 0;font-size:14px;">' . 
    // 											'<span style="color:#222;">' . 
    // 												$value[3] .
    // 											'</span>' . 
    // 										'</td>' . 
    // 									'</tr>';
    // 
    // 					}
    // 				}
    // 
    // 
    // 
    // 			} else {
    // 
    // 				$msg_dati .= '<tr><td style="width:150px; background: none; padding:5px; margin: 2px 0; font-size:14px;" colspan="2">&nbsp;</td></tr>';
    // 
    // 			}
    // 
    // 
    // 		}
    // 
    // 	} 
    // 
    // 	if ($table)
    // 		$msg_dati .= "</table>";
    // 
    // 	return $msg_dati;
    // 
    // }
    
        
    
    /**
     * verifica se il mittente di una mail è tra gli indirizzi blacklistati
     * utilizzata da mail_scheda, mail_multipla e mail_wishlist
     * 
     * @access public
     * @static
     * @param string $from (default: "")
     * @return void
     */
     
    public static function checkSenderMailBlacklisted($from = "") { return in_array(strtolower($from), self::$sender_blacklist); }
    
    
    /**
     * Tolgo tutti campi non necessari per il confronto tra il veccio prefill e il nuovo
     * 
     * @access public
     * @static
     * @param mixed $prefill
     * @param mixed $type
     * @param bool $isMultipla (default: false)
     * @return void
     */
     
    public static  function unsetEmailPrefill($prefill, $type, $isMultipla = false)
    {
          
          unset($prefill["ids_send_mail"]);
          unset($prefill["tag"]);
          
          if ($type == "NEW") {
          
              unset($prefill["type"]);
              unset($prefill["sender"]);
            
              /*if ($isMultipla)
                   unset($prefill["phone"]);*/
              
          } else {
              
            unset($prefill["codice_cookie"]);
            unset($prefill["servizi"]);
            unset($prefill["categoria"]);
            unset($prefill["localita_multi"]);
            unset($prefill["cat_1"]);
            unset($prefill["cat_2"]);
            unset($prefill["cat_3"]);
            unset($prefill["cat_4"]);
            unset($prefill["cat_5"]);
            unset($prefill["cat_6"]);
            unset($prefill["f_prezzo_real"]);
            unset($prefill["multiple_loc_single"]);
            unset($prefill["distanza_centro_real"]);
            unset($prefill["distanza_stazione_real"]);
            unset($prefill["distanza_spiaggia_real"]);
            unset($prefill["newsletter"]);
            unset($prefill["a_partire_da"]);
            unset($prefill["flex_date_value"]);
            unset($prefill["type"]);
            unset($prefill["sender"]);
            unset($prefill["whatsapp"]);
            
            /*if ($isMultipla)
                  unset($prefill["phone"]);*/
            
        }

        ksort($prefill);
          return $prefill; 
          
      }


    /**
     * TCreo le due variabili con gli ID a cui spedire e a cui non spedire.
     * 
     * @access public
     * @static
     * @param mixed $ids_send_mail_old
     * @param mixed $ids_send_mail_new
     * @param mixed $old_prefill
     * @param mixed $new_prefill
     * @param mixed $clienti_ids_arr
     * @param mixed $clienti_ids_arr_not_sent
     * @return void
     */
     
    
    public static function getIdNotDuplicate( $ids_send_mail_old, $ids_send_mail_new, $old_prefill, $new_prefill, $clienti_ids_arr, $clienti_ids_arr_not_sent)
    {
                                            
        // Controllo gli id per la spedizione e creo una lista unica
        foreach($ids_send_mail_new as $ids_new):
            
            if ($ids_new != 0)
                if (in_array($ids_new, $ids_send_mail_old) && base64_encode(json_encode($old_prefill)) == base64_encode(json_encode($new_prefill)))
                    array_push($clienti_ids_arr_not_sent, $ids_new);					
                    
                else
                    array_push($clienti_ids_arr, $ids_new);
                    
        endforeach;
        
        return ["clienti_ids_arr" => $clienti_ids_arr, "clienti_ids_arr_not_sent" => $clienti_ids_arr_not_sent];
        
    }
    
    /**
     * getHotelsRapportoMail function.
     * 
     * @access public
     * @static
     * @return void
     */
     
    public static function getHotelsRapportoMail()
    {

        $hotels = Hotel::attivo()->get();
        $retval = [];

        foreach ($hotels as $hotel)
            $retval[] = $hotel->getIdNomeLoc();

        return $retval;
        
    }
    
    
    /**
     * Spedisce l'email con l'errore.
     * 
     * @access public
     * @static
     * @param mixed $subject
     * @param mixed $body
     * @param mixed $server
     * @return void
     */
    
    public static function sendMeEmailError( $subject, $body, $server) {
        
        if ( App::environment() == 'local') 
            {
            $to = env('ERROR_LOCAL_MAIL','luigi@info-alberghi.com');
            $bcc = "";
            }
        else
            {
            $to = "testing.infoalberghi@gmail.com";
            $bcc = "luigi@info-alberghi.com";
            }
            
                    
        try {
            Mail::send('emails.error_notification', compact('body'), function ($message) use ($to, $bcc, $subject, $server)
            {
                $message->from('richiesta@info-alberghi.com', $server);
                $message->returnPath('richiesta@info-alberghi.com')->sender('richiesta@info-alberghi.com')->to($to);

                if ($bcc != "")
                    $message->bcc($bcc);
                    
                $message->subject($subject);
                
            });
                
        } catch (\Exception $e) {
            
            config('app.debug_log') ? Log::emergency("\n".'---> Errore invio MAIL NOTIFICA ERRORE <---'."\n\n") : "";
            
        }
        


    }

    /**
     * Scrive il json nella forma corretta e traduce i trattamenti per le API
     * 
     * @access public
     * @static
     * @param Array $dati_json
     * @return String
     */

    public static function putJsonMail( $dati_json = array(), $force = false ) 
    {
        
        

        if (config('mail.footer_json_mail') || $force == true) {

            /**
             * Correggo i trattamenti
             */

            $t = 0;
            foreach( $dati_json["rooms"] as $room) {
                if (strpos($room["meal_plan"], ",")) {
                    $trattamenti = [];
                    $items = explode(",",  $room["meal_plan"]);
                    foreach($items as $item):
                        $trattamenti[] = Self::$trattamenti_json_arr[trim($item)];
                    endforeach;
                    $dati_json["rooms"][$t]["meal_plan"] = implode(",", $trattamenti );
                } else {
                    $dati_json["rooms"][$t]["meal_plan"] = Self::$trattamenti_json_arr[$room["meal_plan"]];
                }
                $t++;
            }
            
            /**
             * Ritormo il JSON
             */
            return base64_encode(json_encode($dati_json));
            
        } else 
            return "";

    }


    /* -------------------------------------------------------------------------------------
     * 8. Lingue
     * ------------------------------------------------------------------------------------- */	
    
    
    public static function getLanguage($locale = 'it') { return Self::$language[$locale]; }
    
    public static function getLanguageIso($locale = 'it') {	return Self::$language[$locale][1];	}
    
    public static function exposeTrattamentiOggettoMail() { return self::$trattamenti_arr; }

    public static function getTrattamentiNomi() { return self::$trattamenti_nomi_arr; }
    
    public static function trattamentoShort($trattamento) { 
        
        if (strpos($trattamento,",") === false) {
            if (isset(self::$trattamenti_arr[$trattamento][1]))
                return self::$trattamenti_arr[$trattamento][1]; 
            else 
                return $trattamento;

        } else {

            $meal_plans = explode(",", $trattamento);
            $return = [];
            foreach($meal_plans as $mp) {
                $return[] = self::$trattamenti_arr[$mp][1]; 
            }

            return implode(",", $return);

        }
            
    
    }
    
    public static function getAppLocaleFromUrlLocale($url_locale = 'it') { return Self::$url_locale_to_app_locale[$url_locale];	}
    
    public static function getUrlLocaleFromAppLocale($locale = 'it') {return Self::$app_locale_to_url_locale[$locale]; }

    public static function linguePossibili() { return self::$lang; }
    
 
    public static function removeLangFromUrl ($uri, $lang) { 
        
        $uri = str_replace(self::$app_locale_to_url_locale[$lang], "" , $uri ); 
        $uri = str_replace(".php", "", $uri ); 
        return $uri; 
    
    }
    
    /**
     * Prendo la lingua in base all'URL
     * @param  [String] $uri
     * @return [String] $lang
     */
    
    public static function getLangFromUri( $uri ) {
        
        foreach (self::$url_locale_to_app_locale as $key_lang_uri => $lang_uri):
            
            if (strpos($uri, $key_lang_uri . "/") === 0) 
                return $lang_uri;
                
        endforeach;
                
        return "it";
        
    }
    
    

    /**
     * localeToSet trasforma il locale della app in una strinaga da passare alla funzione PHP setlocale
     * es: 'it' ====> 'it_IT.utf8'
     * @param  string $locale [description]
     * @return [string]         [description]
     */
    
    public static function _localeToSet($locale = 'it') { return $locale.'_'.strtoupper($locale).'.utf8'; }
    
    public static function getLocaleUrl($locale) {

        $url_locale = array_search($locale, Self::$url_locale_to_app_locale);

        if ($url_locale == 'it') {
            return '';
        } else {
            return $url_locale . '/';
        }

    }


    public static function isInUrl($url)
    {

        if ($url == "fr")
            return true;

        if ($url == "ing")
            return true;

        if ($url == "ted")
            return true;

        return false;

    }

    public static function getLocaleByUrl(  )
    {
        
        if (Request::segment(1) == "ing")
            return 'en';
            
        if (Request::segment(1) == "fr")
            return 'fr';
        
        if (Request::segment(1) == "ted")
            return 'de';
            
        return "it";
        
    }


    
    
    /* -------------------------------------------------------------------------------------
     * 9. Trattamenti
     * ------------------------------------------------------------------------------------- */

    
     public static function getTrattamentiSpiaggia() { return self::$trattamenti_con_spiaggia; }


    /**
     * Ritorna array dei trattamenti IN LINGUA SE ESISTE LA TRADUZIONE
     * 
     * @access public
     * @static
     * @param string $select (default: "")
     * @return void
     */
     
    public static function Trattamenti($select = "")
    {
    
        $trattamenti_arr = Self::$trattamenti_arr;

        foreach ($trattamenti_arr as $key => $value) {
            
            $lang_key = $value[0];
            
            if (Lang::has($lang_key))
                $trattamenti_arr[$key] = Lang::get($lang_key);
                
        }

        if ($select == "no_select")

            return "Trattamento da definire con l'utente";

        else if ($select != "")
        
            array_unshift($trattamenti_arr, $select);
            
        return $trattamenti_arr;

    }

    /**
     * Ritorna JSON dei trattamenti IN LINGUA SE ESISTE LA TRADUZIONE
     * 
     * @access public
     * @static
     * @param String $select
     * @return String
     */
    
    public static function Trattamenti_json($select = "")
    {
        
        $trattamenti_for_json = Self::$trattamenti_arr;

        if (strpos($select , ",")) {
            
            $new_value_select = [];
            $new_array_select = explode(",", $select);

            foreach($new_array_select as $select_item) {

                if (array_key_exists(trim($select_item), $trattamenti_for_json))
                    $new_value_select[] = $trattamenti_for_json[$select_item][1];

            }
            
            return implode(", ", $new_value_select);

        } else {
            
            if (array_key_exists($select, $trattamenti_for_json)) 
                return $trattamenti_for_json[$select][1];

        }
        
        return "";
    }
    
    /**
     * ritorna il trattamento come valore dell'array Self::$trattamenti_arr
     * OPPURE il corrispondente in lingua se esiste
     * 
     * @access public
     * @static
     * @param string $trattamento (default: "trattamento_sd")
     * @return void
     */
     
    public static function getTrattamento($trattamento = "trattamento_sd")
    {
        $trattamenti_arr = Self::$trattamenti_arr;
        $t = $trattamenti_arr[$trattamento];

        $lang_key = 'listing.'.$t;
        
        if (Lang::has($lang_key)) 

            return Lang::get($lang_key);

        else 
            
            return $t;
            
    }
    
    /**
     * Ritorna tutti i trattamenti
     * checkboxs
     * @access public
     * @static
     * 
     * @return Array
     */

    public static function getAllMealPlan() 
    {
        $new_trattamenti_arr = [];

        foreach(Self::$trattamenti_arr as $key => $trat): 
            $new_trattamenti_arr[$key] = Lang::get($trat[0]);
        endforeach;

        return $new_trattamenti_arr;
    }

    /**
     * Prende la prima key dell'array
     * 
     * @access public
     * @static
     * @param Array $array
     * @return String
     */

    public static function _array_key_first($array) {

        return array_keys($array)[0];

    }
    
    /**
     * Riordina corretamente i trattamenti per il form e restituisce i valori
     * 
     * @access public
     * @static
     * @param String
     * @return String
     */

    public static function getMultiMealPlan($meal_plan, $list_meal_plan)
    {

        $trattamentiOfferte_arr = Self::$trattamenti_arr;
        $new_meal_plan = "";

        /**
         * Se non esiste trattamwnto prendo quello di default
         */
        if (!isset($meal_plan) || $meal_plan == "")
            $new_meal_plan = $list_meal_plan[Self::_array_key_first($list_meal_plan)];

        else {
            
            /**
             * Se sono una lista
             */

            if (strpos($meal_plan,",")) {
                
                $new_trattamento_value = [];
                $new_meal_plan_items = explode(",", $meal_plan);

                /**
                 * Ciclo per trovare tutte le coincidenze
                 */

                foreach($new_meal_plan_items as $item) {

                    $lang_key = $trattamentiOfferte_arr[$item][0];
                    
                    if (Lang::has($lang_key)) {

                        /** 
                          * Se sono nell'array lo scrivo bene altrimenti lo segno marcato
                          */
                          
                        if (in_array($item, array_flip($list_meal_plan)))
                            $new_trattamento_value[] = Lang::get($lang_key);
                        else
                            $new_trattamento_value[] = "<strike>" . Lang::get($lang_key) ."</strike>";

                    } else
                        $new_trattamento_value[] = $trattamentiOfferte_arr[$item][0];

                }

                /**
                 * Se non ho neanche una coincidenza allora il trattamento di default
                 */

                if (count($new_trattamento_value) == 0)
                    $new_meal_plan = $list_meal_plan[Self::_array_key_first($list_meal_plan)];
                else
                    $new_meal_plan = implode(", ", $new_trattamento_value);

            } else {

                /**
                 * Se non ho una lista ma un solo trattamento o prendo lui o quello di defualt
                 */

                if (in_array($meal_plan, array_flip($list_meal_plan)))
                    $new_meal_plan =  $list_meal_plan[$meal_plan];
                else 
                    $new_meal_plan = $list_meal_plan[Self::_array_key_first($list_meal_plan)];

            }

        }

        return $new_meal_plan;
        
    }

    /**
     * Prende il codice trattamento 
     * 
     * @access public
     * @static
     * @param String
     * @return String
     */

    public static function getMultiMealPlanCode($meal_plan, $list_meal_plan)
    {

        if (!isset($meal_plan) || $meal_plan == "")
            return Self::_array_key_first($list_meal_plan);
        
        if (strpos($meal_plan, ",")) {
            
            $new_meal_plan_items = [];
            $meal_plan_items = explode("," , $meal_plan);
            
            foreach($meal_plan_items as $items) {
                if (in_array($items, $meal_plan_items)) 
                    $new_meal_plan_items[] = $items;
            }
            
            return implode(",", $new_meal_plan_items);

        } else
            if (in_array($meal_plan, $list_meal_plan))
                return $meal_plan;

        return $meal_plan;

    }
    

    /**
     * ritorna il trattamento come valore dell'array Self::$trattamentiOfferte_arr
     * OPPURE il corrispondente in lingua se esiste
     * 
     * @access public
     * @static
     * @param string $trattamento (default: "sd")
     * @return void
     */
     
    public static function getTrattamentoOfferte($trattamento = "sd", $beforeIn = true)
    {	
        
        $trattamentiOfferte_arr = Self::$trattamentiOfferte_arr;

        if ($trattamento == "")
            return "";
        
        if (strpos($trattamento , ",")) {

            $new_trattamento_value = [];
            $trattamenti = explode(",", $trattamento);

            foreach($trattamenti as $trattamento_item):

                $t = $trattamentiOfferte_arr[strtolower(trim($trattamento_item))];
                $lang_key = $t;

                if ($beforeIn) 
                    if (Lang::has($lang_key))
                        $new_trattamento_value[] = '<b class="rosso">' . Lang::get($lang_key) . '</b>';
                    else
                        $new_trattamento_value[] = '<b class="rosso">' . $t . '</b>';

                else 
                    if (Lang::has($lang_key))
                        $new_trattamento_value[] = '<b class="rosso">' . Lang::get($lang_key) . '</b>';
                    else
                        $new_trattamento_value[] = '<b class="rosso">' . $t . '</b>';

            endforeach;

            return implode(", ", $new_trattamento_value);

        } else {

            $t = $trattamentiOfferte_arr[$trattamento];
            $lang_key = $t;

            if ($beforeIn) 
                if (Lang::has($lang_key))
                    return Lang::get('listing.in') . ' <b class="rosso">' . Lang::get($lang_key) . '</b>';
                else
                    return Lang::get('listing.in') . ' <b class="rosso">' . $t . '</b>';

            else 
                if (Lang::has($lang_key))
                    return '<b class="rosso">' . Lang::get($lang_key) . '</b>';
                else
                    return '<b class="rosso">' . $t . '</b>';

        }
            

    }




    /* -------------------------------------------------------------------------------------
     * 10. Url
     * ------------------------------------------------------------------------------------- */




    private static function _translationUrl() { return 'https://www.googleapis.com/language/translate/v2?key=' . Config::get("google.googlekey") . '&q='; }

    
    /**
     * Controlla che la var $url sia uguale all'URI attuale.
     * 
     * @access private
     * @static
     * @param mixed $url
     * @param bool $query (default: false)
     * @return void
     */
     
    private static function matchUrl ( $url, $query = false) {
        
        if ($query)
            $uri = Request::fullUrl();
        else
            $uri = Request::url();
            
        return $url == $uri;
                
    }
    
    public static function getConsoleUrl() { return self::CONSOLE_URL; }

    public static function getCurrentUri() { return ( (isset($_SERVER['HTTPS']) || Config::get("app.ssl")) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; }
    
    public static function stripProtocol($url) { return str_replace(['http://', 'https://'], '', $url); }
    
    public static function urlWithoutParams($url) { 

        if (strpos($url, "?")) 
            $url = explode("?", $url)[0];
        
        if(strpos($url, "/") == 0)
            $url = substr($url,1);
        
        return $url;
        
    }
    
    public static function uriToTitle($uri) { return str_replace(['-', '/','.php'], [' ',' ',''], $uri); }
    
    /**
     * Dato un url trova l'oggetto pagina relativo
     * 
     * @access public
     * @param String $uri
     * @return Object 
     */

    public static function urlToPage($uri = null) {

        /**
         * Esempio url
         * http://www.info-alberghi.com/hotel-4-quattro-stelle/pinarella-di-cervia.php?gclid=EAIaIQobChMIw4md99Pw2QIVCxQbCh1kMwlxEAAYASAAEgKfXvD_BwE
         */

        $domain = Config::get("app.url");

        if (!$uri)
            $uri = url()->current(); // -> /hotel-4-quattro-stelle/pinarella-di-cervia.php

        /**
         * Tolgo eventuali querystring
         */

        if (strpos($uri, "?") !== false)
            $uri = explode("?", $uri)[0]; // -> http://www.info-alberghi.com/hotel-4-quattro-stelle/pinarella-di-cervia.php

        /**
         * Tolgo il dominio
         */

        $uri = self::stripProtocol($uri); // -> www.info-alberghi.com/hotel-4-quattro-stelle/pinarella-di-cervia.php
        $uri = str_replace($_SERVER['HTTP_HOST'], "", $uri); // -> /hotel-4-quattro-stelle/pinarella-di-cervia.php

        if (substr($uri, 0, 1) === '/')
            $uri = substr($uri, 1);

        /**
         * Ora dovrei avere un link di questo tipo:
         * hotel-4-quattro-stelle/pinarella-di-cervia.php
         */

        $match = CmsPagina::where("uri", $uri)->first();
        
        if ($match)
            return [
                $match->ancora, 
                $domain . "/" . $match->uri, 
                $uri,
                $match->menu_macrolocalita_id
            ];
        else
            return ["", "#", "", 11];

    }

    /**
     * Controlla che sia un url filtro e prende i parametri 
     * 
     * @access public
     * @static
     * @param String $url
     * @return 
     */

    public static function filterUriToParams ($uri)
    {

        $uri = Self::stripProtocol($uri);                    // -> www.info-alberghi.com/hotel-4-quattro-stelle/pinarella-di-cervia.php?p=dgfsgs
        $uri = str_replace($_SERVER['HTTP_HOST'], "", $uri); // -> /hotel-4-quattro-stelle/pinarella-di-cervia.php?p=dgfsgs
        $uri = Self::urlWithoutParams($uri);                 // -> hotel-4-quattro-stelle/pinarella-di-cervia.php

        if (strpos( $uri, "filter-listing") === 0)
            return str_replace("filter-listing" , "/filter" , $uri);

        else {

            $uridefault = "/filter/11/*/*/0/*/*/*/*";
            $localita = CmsPagina::where("uri", $uri)->first();
            
            if ($localita)
                $uri = "/filter/" . $localita->listing_macrolocalita_id . "/*/*/0/*/*/*/*";
            else
                $uri = $uridefault;

            return $uri;

        }

    }

    /**
     * Serve per cambiare al volo i Javascript e i Css in cache.
     * 
     * @access public
     * @static
     * @param mixed $url
     * @return void
     */
     
    public static function assetNew($hotel, $type, $forceImage = NULL) { 
        
        $path = "/images/gallery";
            $asset = Config::get("app.cdn_online") . $path . "/" . $type . "/" .  $hotel->id ;

        if( $forceImage != NULL)
            return $asset . "/" . $forceImage;
        else
            return $asset . "/" . $hotel->listing_img;

    }
    
    // public static function asset($url , $protocol = false, $gallery_cdn = false) { 

    //     $arrayImgFile = array("jpg", "peg", "png" , "gif", "svg", "ebp", "JPG");

    //     if ($gallery_cdn) {
    //         $string = Config::get("app.gallery_cdn");
    //     } else {
    //         $string = Config::get("app.cdn");
    //     }

    //     $version = "";

    //     if (substr($url,0,1) == '/') 

    //         $url = ltrim($url,'/');

    //     if (!in_array(substr($url, -3), $arrayImgFile))  
    //         $version = "?" . env('VERSIONCSS', '10'); 

    //     if ($protocol)
    //         $string = "https:" . $string;
            
    //     return $string ."/" . $url . $version;
    // }

    // public static function asset($url , $protocol = false, $romagna = true) { 

    //     $version = "";
    //     $cdn =  $romagna == false ? config("app.cdn") : config("app.cdn") . "/romagna";
    //     $arrayImgFile = array("jpg", "peg", "png" , "gif", "svg", "ebp", "JPG", "css", "js");

    //     /** Evito di mettere un doppio url */
    //     $url = str_replace(["https:", "http:"], "", $url);
    //     $url = str_replace($cdn, "", $url);
        
    //     /** Se il primo carattere è lo slash lo tolgo */
    //     if (substr($url,0,1) == '/') $url = ltrim($url,'/');

    //     /** Se non sono una immagine aggiungo anche la cartella sito di riferimento*/ 
    //     if (!in_array(substr($url, -3), $arrayImgFile)) {
    //         $version = "?" . config('cssversion.VERSION');
    //     }

    //     /** Se devo forzare il protocollo */
    //     if ($protocol) $cdn = "https:" . $cdn;
        
    //     /** Torno l'url */
    //     return $cdn ."/" . $url . $version;

    // }
    
    public static function assets($url, $without_version = false) { 

        
        if (substr($url,0,1) == '/') $url = ltrim($url,'/'); /** Se il primo carattere è lo slash lo tolgo */
        $version = $url;
        $cdn = config("app.cdn") . "/css-js/romagna/";
        if($without_version)
            $version = str_replace(".min.", "." . config('cssversion.VERSION') . ".min.", $url); /** Se non sono una immagine aggiungo anche la cartella sito di riferimento*/ 
            
        return $cdn . $version; /** Torno l'url */

    }

    public static function assetsImage($url) { 

        $version = "";
        $cdn = config("app.cdn") . "/assets/romagna/";
        if (substr($url,0,1) == '/') $url = ltrim($url,'/'); /** Se il primo carattere è lo slash lo tolgo */

        $version = str_replace(
            [
                ".png", 
                ".jpg", 
                ".svg",
                ".gif"
            ], [
                "." . config('cssversion.VERSION') . ".png",
                "." . config('cssversion.VERSION') . ".jpg",
                "." . config('cssversion.VERSION') . ".svg",
                "." . config('cssversion.VERSION') . ".gif",
            ], $url); /** Se non sono una immagine aggiungo anche la cartella sito di riferimento*/ 

        return $cdn . $version; /** Torno l'url */

    }

    public static function assetsLoaded($url, $online = true, $truncateimage = false ) { 

        $version = "";
        if (!$online)
            $cdn = config("app.cdn") . ($truncateimage == false ? "/images/" : "/");
        else 
            $cdn = config("app.cdn_online") . ($truncateimage == false ? "/images/" : "/");

        if (substr($url,0,1) == '/') $url = ltrim($url,'/'); /** Se il primo carattere è lo slash lo tolgo */
        return $cdn . $url; /** Torno l'url */
        
    }

    public static function assetsHotel($url) { 

        $version = "";
        $cdn = config("app.cdn_online") . "/images/";
        if (substr($url,0,1) == '/') $url = ltrim($url,'/'); /** Se il primo carattere è lo slash lo tolgo */
        return $cdn . $url; /** Torno l'url */
        
    }
    
    /**
     * reindirizza le pagine che hanno un placeholder nell'url come estate-{CURRENT_YEAR} 
     * 
     * @access public
     * @static
     * @param string $uri (default: '')
     * @return void
     */
     
    public static function getPublicUri($uri = '')
    {

        if (strpos( $uri, '{CURRENT_YEAR}') !== false || strpos( $uri, '{CURRENT-YEAR}') !== false) 
            return url( str_replace(['{CURRENT_YEAR}','{CURRENT-YEAR}'],date("Y"),$uri) );

        else
            return url($uri);
        
    }

    
    /**
     * Verifica che un URL completo sia interno (localhost, www.info-alberghi.com, sandbox.info-alberghi.com)
     * 
     * @access public
     * @static
     * @param string $url (default: '')
     * @return void
     */
     
    public static function isInternallUrl($url = '')
    {

        if ($url == '')
            return false;

        $host = parse_url($url, PHP_URL_HOST);

        return  $host == 'develop.info-alberghi.com' ||
                $host == Config::get('app.url')      ||
                $host == 'alpha.info-alberghi.com';   

    }
    
    /**
     * Se è un url di debug mostra tutto il log 
     * 
     * @param  string  $url (default: '')
     * @return void
     */
    
    public static function isDebuglUrl($url = '')
    {

        if ($url == '')
            return false;

        //$host = parse_url($url, PHP_URL_HOST);
        $host = $url;
        
        return $host == "::1" || 
            $host == 'develop.info-alberghi.com' ||
            $host == 'www.info-alberghi.ssl'     ||
            $host == 'www.info-alberghi.xxx'     ||
            $host == 'alpha.info-alberghi.com'   ||
            $host == 'alpha.info-alberghi.com';

    }
    

    /**
     * Costruisce l'url 
     * 
     * @access public
     * @static
     * @param mixed $locale
     * @param mixed $url (default: null)
     * @param bool $withDomain (default: false)
     * @param bool $stripped (default: false)
     * @return void
     */
     
    public static function getUrlWithLang( $locale, $url = null, $withDomain = false, $stripped = true)
    {

        $dm = "";
        $url_locale = array_search($locale, Self::$url_locale_to_app_locale);
        
        $domain = Request::server("HTTP_HOST");
        $domain_raplace = explode(".", $domain);
        
        if( $domain_raplace[0] == "fierarimini" || $domain_raplace[0] == "hotelperdisabili") {
            
            $withDomain = true;
            $domain_raplace[0] = "www";
            
        }
        
        //echo $domain . "-" . $withDomain . "-" .$domain_raplace[0]; die();
        
        // Se non è impostato
        if (!$url) {

            $url = "$_SERVER[REQUEST_URI]";
            $url = str_replace($url_locale, "" , $url);

            // Se non comincia con /
        } else {

            if (strpos("/", $url) > 0)
                $url = "/". $url;

        }

        if ($withDomain == true) {

            if ($domain_raplace[0] != "www" && $domain_raplace[0] != "develop" && $domain_raplace[0] != "alpha") {
                $domain_raplace[0] = "www";
            }

            $domain = join(".", $domain_raplace);
            $dm = "//" .  $domain;
            
            if (!$stripped)
                $dm = "http:" . $dm; 

        }

        if ($url_locale == 'it') {
            return $dm . $url;
        } else {
            return $dm . "/". $url_locale . $url;
        }

    }
    
    /**
     * Verifica che il paramentro ORDER sia corretto
     * Non lo facciamo nella Route perchè qui siamo nel caso in cui passo tutti i controlli 
     * al controller
     * 
     * @param String $order
     * @return void Boolean
     */	

    public static function verifyOrderParams($order) {
        
        if ($order == "")
            $order = 0;
        
        if (in_array($order, Self::$orderParams))
            return true;

        return false; 

    }

    /**
     * Verifica che il paramentro FILTER sia corretto
     * Non lo facciamo nella Route perchè qui siamo nel caso in cui passo tutti i controlli 
     * al controller
     * 
     * @param String $order
     * @return void Boolean
     */	

    public static function verifyFilterParams($filter) {
        
        if ($filter == "")
            $filter = 0;
        
        if (in_array($filter, Self::$filterParams))
            return true;

        return false; 

    }
    



    /* -------------------------------------------------------------------------------------
     * CHIAMATE ESTERNE ( GOOGLE TRANS, EMAIL SUBSCRIBE ETC. ...)
     * ------------------------------------------------------------------------------------- */




    /**
     * Chiama l'iscrizione alla newsletter 
     * 
     * @access private
     * @static
     * @param String $Email
     * @param String $IDList
     * @param String $IDGroup
     * @param String $RequiredConfirmation
     * @param String $ReturnCode
     * @return void
     */
     
    private static function _CallXmlSubscribeConsole($Email, $IDList, $IDGroup, $RequiredConfirmation, $ReturnCode)
    {
        
        $server_response = '';
        try
        {
            $console_url = Utility::getConsoleUrl().'?email='.urldecode($Email).'&list='.$IDList.'&group='.$IDGroup.'&confirm='.$RequiredConfirmation.'&retCode='.$ReturnCode;
            $server_request = curl_init($console_url);
            curl_setopt($server_request, CURLOPT_RETURNTRANSFER, 1);
            $server_response = curl_exec($server_request);
            curl_close($server_request);
            return $server_response;

        } catch (Exception $ex) {

            return $server_response;
            
        }


    }
    
    
    /**
     * Chiama google translate.
     * 
     * @access public
     * @static
     * @param string $text (default: "")
     * @param string $lingua (default: "en")
     * @return void
     */
     
    public static function translate($text = "", $lingua = "en")
    {
        
        if (trim($text) != '' && array_key_exists($lingua, self::$language)) {

            $myurl = self::_translationUrl(). rawurlencode($text) . '&target='.$lingua;
            $handle = curl_init($myurl);
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($handle);
            $responseDecoded = json_decode($response, TRUE);
            curl_close($handle);

            try {
                $nome = $responseDecoded['data']['translations']['0']['translatedText'];
                
            } catch (\Exception $e) {
                
                return $text;
                config('app.debug_log') ? Log::emergency("\n".'---> Errore TRADUZIONE GOOGLE TRANSLATE: '. $ip . ' --- '.$e->getMessage().' <---'."\n\n") : "";
                
            }

            return $nome;

        } else
            return '';
            
    }






    /* -------------------------------------------------------------------------------------
     * 12. Excerpt, stringe etc...
     * ------------------------------------------------------------------------------------- */



    public static function addParag ($testo)
    {

        $testo = str_replace("<p>" , "<span>", $testo);
        $testo = str_replace("</p>" , "</span>", $testo);
        return "<p>" . $testo . "</p>";

    }

    public static function confrontaStringa($s1, $s2) { return strtoupper(preg_replace('/\s+/', '', $s1)) == strtoupper(preg_replace('/\s+/', '', $s2)); }
    
    public static function strongToH3($testo = "") { return preg_replace(array('#<strong>#i', '#</strong>#i'),  array("<h3>", "</h3>"), $testo); }

    public static function stripLettereAccentate($string) { return str_replace(array("è", "à", "ò", "ì", "ù"), array("e","a","o","i","u"), $string);}
    
    public static function setPriceFormat ( $euro ) { if ($euro != "" && $euro != "/") return $euro . " &euro;"; else return ""; }
    
    public static function echoFMYO($string) { if ( in_array(self::get_client_ip(), self::$IP) ) echo $string; }
    
    public static function replacePlaceholderHotelCounter($source, $n) { return str_replace("{HOTEL_COUNT}", $n, $source); }

    public static function replacePlaceholder($placeholder = [], $source, $n) { array_walk($n, 'self::cleanNumber'); if (!empty($placeholder)) return str_replace($placeholder, $n, $source); else return $source; }

    public static function getTasseLabel () { }
    
    public static function cleanNumber(&$item1, $key) { 
        if (is_numeric($item1) && $item1 == 0) 
            {
            $item1 = "";
            } else {
            if (is_numeric($item1)) 
                    $item1 = preg_replace("/\.0*$/", '', $item1); 
        }
        
    }
    
    /*
     * Crea excerpt per i listing e i titoli offerti mobile
     * @param int $limit
     * @param string $end
     * @return string
     */
    
    public static function getExcerpt($text = "", $limit = 100, $strip = true)
    {
        
        
        $str = Str::words($text, $limit);
        if (strlen($text) > $limit) $str = $str . "... ";
        $str = str_replace(array("<br>", "<br/>", "<br />"), " ", $str);
        return ($strip) ? strip_tags($str) : $str;

    }

    
    /**
     * Valida un JSON.
     *
     * @access public
     * @static
     * @param String $string
     * @return
     */

    public static function is_JSON($string)
    {

        $result = json_decode($string);

        // switch and check possible JSON errors
        switch (json_last_error()) {

            case JSON_ERROR_NONE:
                $error = ''; // JSON is valid // No error has occurred
                break;
            case JSON_ERROR_DEPTH:
                $error = 'The maximum stack depth has been exceeded.';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                $error = 'Invalid or malformed JSON.';
                break;
            case JSON_ERROR_CTRL_CHAR:
                $error = 'Control character error, possibly incorrectly encoded.';
                break;
            case JSON_ERROR_SYNTAX:
                $error = 'Syntax error, malformed JSON.';
                break;
            case JSON_ERROR_UTF8:
                $error = 'Malformed UTF-8 characters, possibly incorrectly encoded.';
                break;
            case JSON_ERROR_RECURSION:
                $error = 'One or more recursive references in the value to be encoded.';
                break;
            case JSON_ERROR_INF_OR_NAN:
                $error = 'One or more NAN or INF values in the value to be encoded.';
                break;
            case JSON_ERROR_UNSUPPORTED_TYPE:
                $error = 'A value of a type that cannot be encoded was given.';
                break;
            default:
                $error = 'Unknown JSON error occured.';
                break;
                
        }

        if ($error !== '')
            return false;

        return true;

    }


    /**
     * Genera una stringa casuale
     * 
     * @access public
     * @static
     * @param int $length (default: 10)
     * @return void
     */
     
    public static function generateRandomString($length = 10) 
    {
        
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        
        for ($i = 0; $i < $length; $i++) 
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        
        return $randomString;
    }
        
    
    /**
     * Verifica se la stringa che passo è il nome di una macrolocalità
     * 
     * @access public
     * @static
     * @param mixed $nome
     * @return void
     */
     
    public static function isStringaMacro($nome)
    {
        $macro_array = DB::table('tblMacrolocalita')->pluck('nome', 'id');
        foreach ($macro_array as $id => $macro_nome) {
            if (Utility::confrontaStringa($nome, $macro_nome)) {
                return $id;
            }
        }

        return false;
    }


    /**
     * Verifica se la stringa che passo è il nome di una località
     * utilizzando il metodo confrontaStringa
     * @param  [type]  $nome [description]
     * @return $id_localita or false        [description]
     */
    
    public static function isStringaLocalita($nome)
    {
        $loc_array = DB::table('tblLocalita')->pluck('nome', 'id');
        foreach ($loc_array as $id => $localita_nome) {
            if (Utility::confrontaStringa($nome, $localita_nome)) {
                return $id;
            }
        }

        return false;
    }


    /**
     * Verifica se la stringa che passo è il nome di una parolachiave
     * utilizzando il metodo confrontaStringa
     * @param  [type]  $nome [description]
     * @return $id_parloachiave or false        [description]
     */
    
    public static function isStringaParolaChiave($nome)
    {
        $chiave_array = DB::table('tblParoleChiave')->pluck('chiave', 'id');
        foreach ($chiave_array as $id => $chiave) {
            if (Utility::confrontaStringa($nome, $chiave)) {
                return $id;
            }
        }

        return false;
    }






    /* -------------------------------------------------------------------------------------
     * 13. DATE
     * ------------------------------------------------------------------------------------- */

     public static function formatRangeDateWithYear($arrivo, $partenza, $format = "d/m/Y") {

        if (is_string($arrivo)) $arrivo = Carbon::createFromFormat($format, $arrivo);
        if (is_string($partenza)) $partenza = Carbon::createFromFormat($format, $partenza);

        if ($arrivo->format("Y") != $partenza->format("Y")) 
            return [$arrivo->format("d/m/Y"), $partenza->format("d/m/Y")];
        else
            return [$arrivo->format("d/m"), $partenza->format("d/m")];

     }

     public static function getDateEasy($date) {
         
         $today = Carbon::now();

         if (is_string($date)) 
             $date = Carbon::createFromFormat("Y-m-d H:i:s", $date);
        
        $lengthOfAd = $today->diffInHours($date);
        
        if ($date->format("Y-m-d") == $today->format("Y-m-d"))
            return "Oggi<br />Ore " . $date->format("H:i");
    
        else
            return $date->format("l, j F Y") . "<br/>Ore " . $date->format("H:i");
            
        
     }



    /**
     * Trova la differenza notti tra 2 date
     */

    public static function night( $arrivo, $partenza) {

        if (!Utility::isValidDate($arrivo) || !Utility::isValidDate($partenza))
            return 1;

        $arrivo = Carbon::createFromFormat('d/m/Y', $arrivo);
        $partenza = Carbon::createFromFormat('d/m/Y', $partenza);
        
        return $partenza->diffInDays($arrivo);

    }

    public static function isValidYear($data_str = "") { if ($data_str == "-0001") return false; else return true; }


    public static function isValidDate($data_str = "") {

        if ($data_str  == '0000-00-00' || $data_str == "2000-01-01")
            return false;
    
        return true;

    }
    
    /**
     * Se la data e prima di oggi allra la cambio con oggi
     * 
     * @access public
     * @static
     * @param String $data_str
     * @param String $giorni
     * @return void
     */
    
    public static function ePrimaDiOggi($data_str = "", $giorni = 0) { 

        $today = Carbon::today();

        if ($giorni > 0)
            $today->addDays($giorni);  
            
        if (is_array($data_str))
            $data_str = $data_str[0];

        $data  = Carbon::createFromFormat('d/m/Y', $data_str);

        if ($today->gte($data))
            return $today->format('d/m/Y');

        return $data->format('d/m/Y');
        
    }

    public static function ePrimaDiOttobre($data_str = "", $giorni = 0) { 

        $today = new Carbon("first day of October " . date("Y"));

        if ($giorni > 0)
            $today->addDays($giorni);  

        $data  = Carbon::createFromFormat('d/m/Y', $data_str);

        if ($today->gte($data))
            return $today->format('d/m/Y');

        return $data->format('d/m/Y');
        
    }
        
    /**
     * Formatta e localizza una data che è un'istanza di Carbon, oppure restituisce stringa vuota se la data è null
     * 
     * @access public
     * @static
     * @param Carbon $date
     * @param String $mask
     * @param string $locale (default: 'it')
     * @return void
     */
     
    public static function getLocalDate($date, $mask, $locale = 'it')
    {

        // 0000-00-00, senza minuti e secondi, viene vista da Carbon come '-0001-11-30' 
        // Nota 4 giugno 2020
        // In seguito ad un aggiornamento di mysql la data 0000-00-00 non viene più accettata da MySql
        // Quindi le ho cambiate tutte in 2000-01-01 che considero come data nulla

        if (is_null($date) || $date == '' || $date->toDateString() == '-0001-11-30' || $date->toDateString() == "2000-01-01")
            return '';

        else { 
            
            //$loc = setlocale(LC_ALL, Self::_localeToSet($locale));
      setlocale(LC_TIME, self::$app_locale_to_set_locale[$locale]);
      return $date->formatLocalized($mask);
        }
        
    }


    /**
     * [myFormatLocalized description]
     * @param  [type] $date   [Carbon/Carbon date]
     * @param  [type] $mask   [utilizza quella di formatLocalized di Carbon e quindi  strftime di PHP ]
     * @param  string $locale [description]
     * @return [type]         [description]
     */
    public static function myFormatLocalized($date = null, $mask = '%d %B %Y', $locale = 'it')
        {
            if (is_null($date)) 
                {
                $date = Carbon::today();
                }

            setlocale(LC_TIME, self::$app_locale_to_set_locale[$locale]);
            
            return $date->formatLocalized($mask);
        }

    
    /**
     * Accetta una strina nel formato dd/mm/yyyy e la trasforma in un oggetto data Carbon; se la stringa è vuota o malformata restituisce l'oggetto Carbon da $y=0-$m=0-$d=0.
     * 
     * @access public
     * @static
     * @param string $data_str (default: "")
     * @return void
     */
     
    public static function getCarbonDate($data_str = "")
    {
        try {

            $data_str = trim($data_str);
            if ($data_str == '') {
                $data_carbon = Carbon::createFromDate(0, 0, 0);
            }
            else {
                list($d, $m, $y) = explode('/', $data_str);
                $data_carbon = Carbon::createFromDate($y, $m, $d);
            }


            return $data_carbon;

        } catch (\Exception $e) {

            return Carbon::now();

        }

    }
    

    /**
     * [isValidMenuAsCarbon description]
     * @param  [type]  $dal [Carbon]
     * @param  [type]  $al  [Carbon]
     * @return boolean      [description]
     */
    static public function isValidMenuAsCarbonv2($dal, $al, $chi, $menu_auto_annuale = false)
    {

          

            if( $dal->timestamp <= 0 || $al->timestamp <= 0 )
                return true;

            $return = false;
            $today = Carbon::today()->timestamp;
            
            if ($menu_auto_annuale) {

                $yearDal = $dal->year;
                $yearAl = $al->year;

                $from = $dal->subYears(20)->format("Y");
                $to = $al->addYears(20)->format("Y");

                for($t=$from;$t<=$to;$t++) {

                    if ($yearDal == $yearAl) {

                        $dateDal = Carbon::create($t, $dal->month, $dal->day,0,0,0)->timestamp;
                        $dateAl = Carbon::create($t, $al->month, $al->day,0,0,0)->timestamp;

                    } else {

                        $dateDal = Carbon::create($t, $dal->month, $dal->day,0,0,0)->timestamp;
                        $dateAl = Carbon::create($t+1, $al->month, $al->day,0,0,0)->timestamp;

                    }

                    if (($today > $dateDal && $today < $dateAl) || $today == $dateDal || $today == $dateAl)
                        $return = true;

                }
                

            } else {

                $dateDal = $dal->timestamp;
                $dateAl = $al->timestamp;

                if (($today > $dateDal && $today < $dateAl) || $today == $dateDal || $today == $dateAl)
                    $return = true;

            }


            return $return;

        }

        static public function isValidMenuAsCarbon($dal, $al, $chi, $menu_auto_annuale = false)
        {
            

            // se sono la stessa data con al differenza di 1 anno 

            if( $dal->addYear() == $al )
                return true;
            
            $annodal = $dal->year;
            $annoal  = $al->year;
            $today = Carbon::today();

            if ($menu_auto_annuale) {
                
                if ($annodal == $annoal) {
                    
                    $dal  = Carbon::create($today->year, $dal->month, $dal->day,0,0,0);
                    $al  = Carbon::create($today->year, $al->month, $al->day,0,0,0);

                } else if ($annodal != $annoal) {
                    
                    // L'anno di partenza è uguale all'attule
                    if ($annodal == $today->year) {
                        
                        $dal  = Carbon::create($today->year, $dal->month, $dal->day,0,0,0);
                        $al  = Carbon::create(($today->year+1), $al->month, $al->day,0,0,0);

                    // L'anno di partenza è minore dell'attuale e la data di oggi minore o uguale alla data di fine
                    } else if ( $annodal < $today->year && ($today->lte($al) || $today->eq($al))) {
                        
                        $dal  = Carbon::create(($today->year-1), $dal->month, $dal->day,0,0,0);
                        $al  = Carbon::create($today->year, $al->month, $al->day,0,0,0);

                    // L'anno di partenza è minore dell'attuale e la data di oggi maggiore alla data di fine
                    } else if ( $annodal < $today->year && $today->gte($al)) {
                        
                        $dal  = Carbon::create($today->year, $dal->month, $dal->day,0,0,0);
                        $al  = Carbon::create(($today->year+1), $al->month, $al->day,0,0,0);

                    }

                }
                
                // Provo l'anno corrente
                return ($today->gte($dal) && $today->lte($al)) || $today->eq($dal) || $today->eq($al);

            } else {

                
                $dal  = Carbon::create($dal->year, $dal->month, $dal->day,0,0,0);
                $al  = Carbon::create($al->year, $al->month, $al->day,0,0,0);
                
                return ($today->gte($dal) && $today->lte($al)) || $today->eq($dal) || $today->eq($al);

            } 
            

        }
    
    
    /**
     * Verifica se la voce di menu deve essere mostata in base ai campi menu_dal, menu_al
     * 
     * @access public
     * @static
     * @param Carbon $dal
     * @param Carbon $al
     * @param bool $menu_auto_annuale (default: false)
     * @return void
     */
     
    static public function isValidMenu($dal, $al, $chi, $menu_auto_annuale = false)
    {
        $al_old = $al;

        if ($dal == '0000-00-00' || $al == '0000-00-00' || $dal == "2000-01-01" || $al == "2000-01-01" )
            return true;

        try 
            {
            $dal = 	Carbon::createFromFormat('Y-m-d', $dal);
            $al = 	Carbon::createFromFormat('Y-m-d', $al);		
            }
        catch (\Exception $e) 
            {
            return false;
            }

        // if($al_old == '2018-06-03') 
        // {
        //     dd('al_carbon = '.$al. ' dal_carbon = ' . $dal . ' menu_auto_annuale = '. $menu_auto_annuale);
        // }

        return self::isValidMenuAsCarbonv2($dal, $al, $chi, $menu_auto_annuale);

    }
 
    /**
     * Controlla che oggi sia nell'intervallo di tempo tra inizio e fine
     *
     * @access private
     * @param string $inizio
     * @param string $fine
     * @return Boolean
     */

    /*public static function controlloDate( $inizio, $fine, $ricorsivo )
    {

        $oggi = mktime(0, 0, 0, date("m")  , date("d"), date("Y"));

        if (!$ricorsivo) {

            $inizio = Carbon::parse( $inizio )->timestamp;
            $fine =   Carbon::parse( $fine )->timestamp;

        } else {

            /**
             * Controllo se $inizio e fine hanno lo stesso anno
             * /

            if (Carbon::parse( $inizio )->year == Carbon::parse( $fine )->year) {

                $newYear = Carbon::now()->format("Y");
                $inizio = Carbon::parse( $inizio )->year($newYear)->timestamp;
                $fine  = Carbon::parse( $fine )->year($newYear)->timestamp;

            } else {

                /**
                 * Se l'anno non è uguale allora sommo 1 all'anno corrente
                 * /

                $newYearInizio = Carbon::now()->format("Y");
                $newYearFine = Carbon::now()->addYear()->format("Y");
                $inizio = Carbon::parse( $inizio )->year($newYearInizio)->timestamp;
                $fine  = Carbon::parse( $fine )->year($newYearFine)->timestamp;

            }

        }

        /**
         * Se è tutto ok allora costruisco l'oggetto
         * /

        if ($oggi >= $inizio && $oggi <=$fine)
            return true;

        return false;

    } */

    


    /* -------------------------------------------------------------------------------------
     * 14. Offerte
     * ------------------------------------------------------------------------------------- */




    public static function NotifyOfferta($request, $offerta, $session_id, $tipo)
    {
        
            /////////////////////////////////////////////////////////////////////////
            // ATTENZIONE: l'offerta non è dell'hotel che sto impersonificando !!! //
            /////////////////////////////////////////////////////////////////////////
            
            

            ////////////////////////////////
            // INVIO UNA MAIL DI NOTIFICA //
            ////////////////////////////////
            $body = "
            Tentativo di salvare ".$tipo." che non apprtiene all'hotel che si sta impersonificando.<br>
            Alle:". Carbon::now() .
            "<br>Untente loggato:" . Auth::user()->username . ', ruolo '.Auth::user()->role.
            "<br><br> ".$tipo." che si cerca di salvare id = " . $offerta->id ." appartenente all'hotel ".$offerta->hotel_id . " con hotel impersonificato id = ".$session_id;

            Mail::send([], [], function ($message) use ($body, $tipo)
            {
              $message->from('richiesta@info-alberghi.com');
              $message->returnPath('richiesta@info-alberghi.com')->sender('richiesta@info-alberghi.com')->to('luigi@info-alberghi.com');   
              $message->subject('ATTENZIONE: Alert modifica '.$tipo.' da approvare!!!');
              $message->setBody($body, 'text/html');
                 $message->bcc('giovanni@info-alberghi.com');
            });


        
    }


    /**
     * Prende il cliente in base all'ID offerta
     * 
     * @String $id_offerta
     * 
     * return @Hotel
     */

    public static function getHotelFromOfferta($id_offerta)
    {
        $offerta = Offerta::find($id_offerta);

        if(!is_null($offerta))
            {
            return $offerta->cliente;
            }
        else
            {
            return null;
            }

    }

    /**
     * Controlla in base ad un tabella di dati se ci sono offerte in evidenza nei 
     * listing 
     * 
     * @String $lang
     * 
     * return @String
     */

    //public static function checkOfferteCapodannoTime($lang_id = null)
    public static function checkOfferteInEvidenza($lang_id = null)
    {

        $listing_parolaChiave_id = false;

        foreach(Self::$offerteInEvidenza as $offertatime):

            if (Self::isValidMenu($offertatime[0],$offertatime[1], $offertatime[4], $offertatime[2]) && $lang_id == 'it')
                $listing_parolaChiave_id = $offertatime[3];

        endforeach;

        return $listing_parolaChiave_id;

    }

    /**
     * Manda in cima gli hotel con l'offera in evidenza
     * 
     * return @Boolean
     */

    //public static function checkOrderCapodannoTime()
    public static function checkOrderOfferteInEvidenza()
    {
    
        foreach(Self::$offerteInEvidenza as $offertatime)
            if (Self::isValidMenu($offertatime[0],$offertatime[1], $offertatime[4], $offertatime[2]) )
                return true;

        return false;

    }

    /**
     * Manda in cima gli hotel con l'offera in evidenza
     * 
     * return @Boolean
     */

    //public static function checkOrderCapodannoTime()
    public static function descrizioneOfferteInEvidenza($id_parola)
    {
    
        foreach(Self::$offerteInEvidenza as $offertatime)
            if ( $offertatime[3] == $id_parola )
                return $offertatime[4];

        return false;

    }


      /**
     * Prende il listino di un hotel con tutte le offerte disponibili
     * NOTA per ora non mi serve metterlo in cache in quanto è chiamato già
     * dentro una oggetto cachato.(\app\http\ViewComposers\ListinoComposer)
       * 
       * @access public
       * @static
       * @param mixed $hotel_id
       * @param mixed $locale
       * @return void
       */
       
      public static function getListinoWithOfferte($hotel_id, $locale)
      {
          
          $listini = Hotel::with([
                
            'listini' => function($query) use ($locale)
            {
                $query
                ->attivo()
                ->nonNullo()
                ->orderByRaw('periodo_dal asc');
            },
            
            'listiniMinMax' => function($query) use ($locale)
            {
                $query
                ->attivo()
                ->nonNullo()
                ->orderByRaw('periodo_dal asc');
            },

            'offerte'  => function($query)
            {
                $query
                ->attiva()
                ->orderBy('valido_dal', 'asc');
            },
            
            'offerte.offerte_lingua' => function($query) use ($locale)
            {
                $query
                ->where('lang_id', '=', $locale);
            },
            
            'last'  => function($query)
            {
                $query
                ->attiva()
                ->orderBy('valido_dal', 'asc');
            },
            
            'last.offerte_lingua' => function($query) use ($locale)
            {
                $query
                ->where('lang_id', '=', $locale);
            },
            
            'bambiniGratisAttivi',
            
            'offerteBambiniGratisTop'  => function($query)
            {
                $query
                ->attiva();
            },

            'offerteBambiniGratisTop.offerte_lingua' => function($query) use ($locale)
            {
                $query
                ->where('lang_id', '=', $locale);
            },

            'offertePrenotaPrima'  => function($query)
            {
                $query
                ->attiva()
                ->orderBy('prenota_entro', 'asc');
            },

            'offertePrenotaPrima.offerte_lingua' => function($query) use ($locale)
            {
                $query
                ->where('lang_id', '=', $locale);
            },

            'offerteTop'  => function($query)
            {
                $query
                ->visibileInScheda()
                ->attiva();
            },

            'offerteTop.offerte_lingua' => function($query) use ($locale)
            {
                $query
                ->where('lang_id', '=', $locale);
            }
            
            ])->find($hotel_id);
        
        return $listini;
          
      }

    
      /**
       * Fa il match tra offerte e listino per le offerte Top
       * 
       * @access public
       * @static
       * @param mixed $offerta
       * @param mixed $trattamento
       * @param mixed $periodo_dal
       * @param mixed $periodo_al
       * @return void
       */
       
      public static function getOfferteMatchTop($offerta, $trattamento, $periodo_dal, $periodo_al)
      {
        $nofferte = 0;
        
        if ($offerta) {	
            
            if ($offerta->formula == "*" || $offerta->formula == "tt" || $trattamento == "prezzo_" . $offerta->formula || $trattamento == "prezzo_" . $offerta->formula . "_min") {
                                                
                if (
                            ($offerta->valido_dal->timestamp > $periodo_al->timestamp ) || 
                            ($offerta->valido_al->timestamp < $periodo_dal->timestamp ) 
                            
                        ) {
                            
                        // Nessun match tra offerta e  listino 
                        
                    } else {
                        
                        $nofferte++;
                        
                    } 
                
            }
        }							
        
        return $nofferte;
        
    }
      

      /**
       *  Fa il match tra offerte e listino
       * 
       * @access public
       * @static
       * @param mixed $offerte
       * @param mixed $trattamento
       * @param mixed $periodo_dal
       * @param mixed $periodo_al
       * @return void
       */
       
      public static function getOffertematch($offerte, $trattamento, $periodo_dal, $periodo_al)
      {

    $nofferte = 0;
                                    
        foreach($offerte as $offerta) {

            try {
                if ($offerta->formula == "*" || $offerta->formula == "tt" || $trattamento == "prezzo_" . $offerta->formula || $trattamento == "prezzo_" . $offerta->formula . "_min") {
                    
                    if (
                            ($offerta->valido_dal->timestamp > $periodo_al->timestamp ) || 
                            ($offerta->valido_al->timestamp < $periodo_dal->timestamp ) 
                            
                        ) {
                            
                        // Nessun match tra offerta e  listino 
                        
                    } else {
                        
                        $nofferte++;
                        
                    } 
                    
                }
            } catch (\Exception $e) {
                Log::info($e->getMessage());
            }
                        
        
                                        
        }
        
        return $nofferte;
        
    }
      

    /**
     * Fa il match tra offerte e listino PER LE OFFERTE CHE NON HANNO UNA FORMULA
     * 
     * @access public
     * @static
     * @param mixed $offerte
     * @param mixed $trattamento
     * @param mixed $periodo_dal
     * @param mixed $periodo_al
     * @return void
     */
     
    public static function getOfferteMatchNoFormula($offerte, $trattamento, $periodo_dal, $periodo_al)
    {
        
        $nofferte = 0;
                                    
        foreach($offerte as $offerta) {
            
            try {

                if (
                        ($offerta->valido_dal->timestamp > $periodo_al->timestamp ) || 
                        ($offerta->valido_al->timestamp < $periodo_dal->timestamp ) 
                        
                    ) {
                        
                    // Nessun match tra offerta e  listino 
                    
                } else {
                    
                    $nofferte++;
                    
                } 
                
            } catch (\Exception $e) {

                //dd($offerta);
                Log::info($e->getMessage());
                
            }

                
                                        
        }
        
        return $nofferte;
        
    }





    /* -------------------------------------------------------------------------------------
     * 15. Menu verde, azzurro etc ...
     * ------------------------------------------------------------------------------------- */





    /**
     *  Metodi privati per disegnare il menu localita mobile
     * 
     * @access private
     * @static
     * @param mixed $menu (default: null)
     * @param string $key (default: 'menu')
     * @return void
     */
     
    private static function _draw_menu_no_macrolocalita($menu = null, $key = 'menu')
    {

        if (!$menu = Utility::activeCache($key, "Cache Menu No Macrolocalita")) {

            $macro = Macrolocalita::real()
            ->orderBy('id', 'asc')
            ->get();

            $menu .= '<ul id="menu-macrolocalita">';

            foreach ($macro as $m) {
                $menu .= '<li><a href="'.url('/'.$m->linked_file).'">'.strtoupper($m->nome).'</a></li>';
            }

            $menu .= '</ul>';

            Utility::putCache($key, $menu);

        }

        return $menu;

    } 

    private static function _draw_menu_macrolocalita($menu = null, $key = 'menu', $current_macro_id = 0, $current_lang = 'it')
    {

        if (!$menu = Utility::activeCache($key, "Cache Menu  Macrolocalita")) {

            $macro = Macrolocalita::with(["localita" => function($query)
                {
                    $query->where("macrolocalita_id", ">", 0)->orderBy('id', 'asc');
                }])
            ->orderBy('ordering')
            ->get();

            $loc_pagine = CmsPagina
            ::attiva()
            ->where("lang_id", $current_lang)
            ->where("template", "localita")
            ->where("menu_localita_id", ">", "0")
            ->get();


            $_loc_pagine = [];
            foreach ($loc_pagine as $cms_pagina)
                $_loc_pagine[$cms_pagina->menu_localita_id] = Utility::getPublicUri($cms_pagina->uri);
            $count = 0;
            $menu = '<ul id="menu-macrolocalita">';

            foreach ($macro as $m) {


                $url = Utility::getUrlWithLang($current_lang, '/'.$m->linked_file);
                
                //////////////////////////////////////
                // ATTENZIONE RR ha un link a parte //
                //////////////////////////////////////

                if ($m->id == 11) {
                    $url = Utility::getUrlWithLang($current_lang, "/italia/hotel_riviera_romagnola.html");
                }


                $menu .= '<li id="ajax-'.$m->id.'_0" data-localita="'.$m->id.'" class="home_'.$m->id.'"><a href="'.$url.'"><span>'. $m->nome.'</span></a>';

                if ($m->localita && count($m->localita) > 1) {

                    $menu .= '<div class="badge"><div data-txt="'.count($m->localita).' '.trans("listing.localita") .' &#10095;" data-id="ul-'.$count.'">'.count($m->localita).' '.trans("listing.localita") .' &#10095;</div></div>';
                    $menu .= '<ul id="ul-'.$count.'" class="menu-microlocalita">';

                    //$menu .= '<li class="no-select">'.trans("listing.localita").'&#8594;</li>';

                    foreach ($m->localita as $l) {

                        $url = isset($_loc_pagine[$l->id]) ? url($_loc_pagine[$l->id]) : false;

                        if ($url) {
                            $menu .= '<li id="ajax-'.$m->id .'_'. $l->id.'" data-localita="'.$l->id.'" ><a href="'.$url.'">'.$l->nome.'</a></li>';
                        }

                    }
                    $menu .= "</ul></li>";
                } else {
                    $menu .= "</li>";
                }

                $count++;
            }

            $menu .= '</ul>';

            Utility::putCache($key, $menu);

        }

        return $menu;

    }
    
    
    /**
     * I menu che sono "out of range" vengono messi di tipo visibilità per visualizzarli nel barusso
     * 
     * @access private
     * @static
     * @param mixed &$tblMenuTematico
     * @return void
     */
     
    private static function _swapScadutiToBarusso(&$tblMenuTematico)
    {
        
        foreach ($tblMenuTematico as $item)
            if (!self::isValidMenu($item->menu_dal, $item->menu_al, null,  $item->menu_auto_annuale))
                $item->type = 'visibilita';
                
    }
    
    /**
     * Crea il menu macrolocalita.
     * 
     * @access public
     * @static
     * @return void
     */
     
    public static function getMenuMacroLocalita( )
    {

        $macro =  Macrolocalita::real()
        ->orderBy('id', 'asc')
        ->get();

        $return = '<nav id="menu-macrolocalita" class="menu-macrolocalita">';
        foreach ($macro as $m):
            $return .= '<div class="home_'.$m->id.'"><a href="#"><span class="tover">' . $m->nome .'</span></a></div>';
        endforeach;

        $return .= '<div class="igeamarina"><a href="#"><span class="tover">Igea marina</span></a></div>';
        $return .= '<div class="rivieraromagnola"><a href="#"><span class="tover">Riviera romagnola</span></a></div>';
        $return .= '</nav>';

        echo $return;

    }


    /**
     * Crea il menu localita
     * 
     * @access public
     * @static
     * @param string $locale (default: 'it')
     * @param int $listing_macrolocalita_id (default: 0)
     * @return void
     */
    public static function getMenuLocalita($locale = 'it', $listing_macrolocalita_id = 0, $listing_localita_id = 0)
    {

        $listing_macrolocalita_id == 0 ? $listing_macrolocalita_id = Utility::getMacroRR() : "";
        $listing_localita_id == 0 ? $listing_localita_id = Utility::getMicroRR() : "";

        $menu = null;
        $key = "menu_localita_" . $locale . "_". $listing_macrolocalita_id . "_"  . $listing_localita_id;

        if (!$menu = Utility::activeCache($key, "Cache Menu Localita")) {

            $macrolocalita = Macrolocalita::with(["localita" => function($query)
                {
                    $query->where("macrolocalita_id", ">", 0);
                }])
            ->orderBy("ordering")
            ->get();

            //dd($macrolocalita);

            $macro_pagine = CmsPagina
                ::attiva()
                ->where("lang_id", $locale)
                ->where("template", "localita")
                ->where("menu_macrolocalita_id", ">", "0")
                ->where("menu_localita_id", "0")
                ->get();

            $loc_pagine = CmsPagina
                ::attiva()
                ->where("lang_id", $locale)
                ->where("template", "localita")
                ->where("menu_localita_id", ">", "0")
                ->get();

            $_macro_pagine = [];
            foreach ($macro_pagine as $cms_pagina) {

                $_macro_pagine[$cms_pagina->menu_macrolocalita_id] = array();
                $_macro_pagine[$cms_pagina->menu_macrolocalita_id]["uri"] = Utility::getPublicUri($cms_pagina->uri);
                $_macro_pagine[$cms_pagina->menu_macrolocalita_id]["hotel_count"] = $cms_pagina->listing_count;
                $_macro_pagine[$cms_pagina->menu_macrolocalita_id]["offers_count"] = $cms_pagina->n_offerte;
                $_macro_pagine[$cms_pagina->menu_macrolocalita_id]["prezzo_minimo"] = $cms_pagina->prezzo_minimo;
                $_macro_pagine[$cms_pagina->menu_macrolocalita_id]["prezzo_massimo"] = $cms_pagina->prezzo_massimo;

            }


            $_loc_pagine = [];
            foreach ($loc_pagine as $cms_pagina) {

                $_loc_pagine[$cms_pagina->menu_localita_id] = array();
                $_loc_pagine[$cms_pagina->menu_localita_id]["uri"] = Utility::getPublicUri($cms_pagina->uri);
                $_loc_pagine[$cms_pagina->menu_localita_id]["hotel_count"] = $cms_pagina->listing_count;
                $_loc_pagine[$cms_pagina->menu_localita_id]["offers_count"] = $cms_pagina->n_offerte;
                $_loc_pagine[$cms_pagina->menu_localita_id]["prezzo_minimo"] = $cms_pagina->prezzo_minimo;
                $_loc_pagine[$cms_pagina->menu_localita_id]["prezzo_massimo"] = $cms_pagina->prezzo_massimo;

            }

            $menu = '<ul id="menu-macrolocalita" class="menu-macrolocalita">';

            foreach ($macrolocalita as $m) {

                $url = isset($_macro_pagine[$m->id]["uri"]) ? url($_macro_pagine[$m->id]["uri"]) : false;
                $hotel_count = isset($_macro_pagine[$m->id]["hotel_count"]) ? $_macro_pagine[$m->id]["hotel_count"] : 0;
                $prezzo_minimo = isset($_macro_pagine[$m->id]["prezzo_minimo"]) ? $_macro_pagine[$m->id]["prezzo_minimo"] : 0;
                $class="";

                if ($listing_macrolocalita_id == $m->id)
                    $class="current";

                if ($url) {

                    $class="";

                    if ($listing_macrolocalita_id == $m->id)
                        $class="current";

                    $menu .= "<li class='".$class."'>";
                        $menu .= "<a href=\"$url\">";
                        $menu .= 	"{$m->nome}";
                        $menu .= "</a>";
                    $sottomenu = "";

                    foreach ($m->localita as $l) {

                        $url = isset($_loc_pagine[$l->id]) ? url($_loc_pagine[$l->id]["uri"]) : false;
                        $hotel_count = isset($_loc_pagine[$l->id]["hotel_count"]) ? $_loc_pagine[$l->id]["hotel_count"] : 0;
                        $current = Self::matchUrl($url);
                                                
                        if ( $url ) {
                            
                            if ($current)
                                $sottomenu .= "<li class='current-micro'>";
                            else
                                $sottomenu .= "<li>";
                            
                            $sottomenu .= "<a href=\"$url\">";
                            $sottomenu .= "{$l->nome}";

                            if ($hotel_count > 0)
                                $sottomenu .= "<span class='badge'>$hotel_count</span>";

                            $sottomenu .= "</a>";
                            $sottomenu .= "</li>";

                        }

                    }
                    
                    if ($sottomenu != "" )
                        if (\Browser::isTablet())			
                            $menu .= '<i class="icon-down-open"></i><ul class="menu-microlocalita">' . $sottomenu . "</ul>";
                        else
                            $menu .= '<ul class="menu-microlocalita">' . $sottomenu . "</ul>";

                    $menu .= "</li>";
                }
            }
            $menu .= "</ul>";

            Utility::putCache($key, $menu);

        }

        return $menu;
    }


    /**
     * Costrisce il menu verde di navigazione
     * 
     * @access public
     * @static
     * @param string $locale (default: 'it')
     * @param int $id_macrolocalita (default: 0)
     * @param int $id_localita (default: 0)
     * @param int $getMenuVisibilita (default: 0)
     * @return void
     */
    
    public static function getMenuTematico($locale = 'it', $id_macrolocalita=0, $id_localita=0, $getMenuVisibilita = 0)
    {

        $key = "menu_tematico_desktop_" .$locale ."_".$id_macrolocalita."_".$id_localita."_".$getMenuVisibilita;
        $visibilita = null;
        $menu = null;
        
        $class = "badge";
        if ($getMenuVisibilita)
            $class="badge reverse";
        
        if (!$menu_array = Utility::activeCache($key, "Cache Menu Tematico")) {
            
            //////////////////////////////////////////////////////////////////////////////////////////
            // 09/01/18 Voglio aggiungere il link ai trattamenti b&b nella sezione categoria @lucio //
            //////////////////////////////////////////////////////////////////////////////////////////
            $cms_pagine = CmsPagina::getCategorieWithBB($id_macrolocalita, $id_localita, $locale);
            // dd($cms_pagine);

            /*
             * Prima voglo le stelle, dalla 5 alla 1
             * poi voglio il resto (residence, ecc...)
             */
            
            $tmp = [];

         
            foreach ($cms_pagine as $cms_pagina) {

                if ($cms_pagina->listing_macrolocalita_id == 9 && $cms_pagina->listing_categorie == Categoria::ID_STELLE_3S) {

                    /**
                     * @2021-02-15 09:47:10 
                     * @Lucio, @Elena, @Sacco
                     * Su gabicce non esistono 3 stelle superiori 
                     * In linea di massima su tutte le marche i 3 stelle sup non ci sono, controllare in caso di apertura di nuove zone
                     * 
                     */

                } else {

                    $ord = 0;
                    if ($cms_pagina->listing_categorie == Categoria::ID_STELLE_1)
                        $ord = 1;
                    if ($cms_pagina->listing_categorie == Categoria::ID_STELLE_2)
                        $ord = 2;
                    if ($cms_pagina->listing_categorie == Categoria::ID_STELLE_3)
                        $ord = 3;
                    if ($cms_pagina->listing_categorie == Categoria::ID_STELLE_3S)
                        $ord = 4;
                    if ($cms_pagina->listing_categorie == Categoria::ID_STELLE_4)
                        $ord = 5;
                    if ($cms_pagina->listing_categorie == Categoria::ID_STELLE_5)
                        $ord = 6;
                    if ($cms_pagina->listing_trattamento == 'bb')
                        $ord = -1;

                    $url = url(Utility::getPublicUri($cms_pagina->uri));

               
                    $tmp[$ord] = '<li><a href="'.$url.'"><span>' . $cms_pagina->ancora . '</span> <span class="'.$class.'"">' . $cms_pagina->listing_count . '</span></a></li>';
        
                    self::$cms_pagine_gia_mostrate[] = $cms_pagina->id;

                }
                
            }
    
            krsort($tmp);


            /**
             * Per il 2° e 3° blocco bisogna sempre mostrare il contesto relativo alla macrolocalità
             */
            
            if ($id_localita != 0)
                $id_macrolocalita = Localita::find($id_localita)->macrolocalita_id;
                

            $tblMenuTematico = DB::table("tblMenuTematico")
                ->join('tblCmsPagine', 'tblCmsPagine.id', '=', 'tblMenuTematico.cms_pagine_id')
                ->select("tblCmsPagine.ancora", "tblCmsPagine.uri" , "tblCmsPagine.menu_evidenza" , "tblCmsPagine.menu_dal", "tblCmsPagine.menu_al" , "tblCmsPagine.menu_auto_annuale", "tblCmsPagine.listing_count", "tblMenuTematico.*")
                ->where("tblMenuTematico.lang_id", $locale)
                ->whereIn("tblMenuTematico.macrolocalita_id", [$id_macrolocalita,0])
                ->get();

            //Utility::_swapScadutiToBarusso($tblMenuTematico);

            /**
             * Trovo il massimo valore dell'ordinamento per ciascuna tipologia di link (E' il valore che assegno in caso di CONFLITTO!!!)
             */
             
            $max['servizi'] = 0;
            $max['offerte'] = 0;
            $max['trattamenti'] = 0;
            $max['parchi'] = 0;
    
            $max['visibilita'] = 0;
            $max['famiglia'] = 0;
    
            foreach ($tblMenuTematico as $row) 
                if ($row->ord > $max[$row->type]) 
                    $max[$row->type] = $row->ord;

            $servizi = [];
            $offerte = [];
            $trattamenti = [];
            $parchi = [];
            $famiglia = [];
    
            if ($getMenuVisibilita)
                $visibilita = [];

            //dd($tblMenuTematico);

            foreach ($tblMenuTematico as $row) {
            

                $ancora = (empty($row->ancora) || $row->ancora == '') ? substr($uri, 0, strpos($uri, "/")) : $row->ancora;

                if (Utility::isValidMenu($row->menu_dal, $row->menu_al, $ancora , $row->menu_auto_annuale)) {
                    
                    $uri = Utility::getPublicUri($row->uri);
                    $url = url($uri);
                    
                    $li_class = "";
                    // if ( $url == Self::getCurrentUri() )
                    // 	$li_class = "current";

                    /*
                     * E LA PAGINA NON HA ANCORA
                     * PROVO A CREARLA DALL'URI "on the fly"
                     */
                     
                    if ($row->menu_evidenza)
                        $li_class .= "evidenziato";
            
                    $tag = '<li class="'.$li_class.'">';
                    $tag2 = "</li>";
                    
                    if ($row->type !== 'visibilita')
                        $li = $tag ."<a href=\"{$url}\"><span>{$ancora}</span><span class='".$class."'>{$row->listing_count}</span></a>" . $tag2;
                    else
                        $li = $tag ."<a href=\"{$url}\"><span>{$ancora}</span></a>" . $tag2;
                    
                    /**
                     * ATTENZIONE: per qualche motivo ci sono dei link della tabella tblMenuTematico che hanno, per la stessa lingua, lo stesso "type" e lo stesso "ord"
                     * QUINDI ad esempio per il type = "offerte"
                     * $offerte[$row->ord] = $li;
                     * verrà sovrascritto e prenderà SOLO l'ULTIMO VALORE, ecco perché alcuni link SPARISCONO !!!!
                     *
                     * QUI FACCIO COMUNQUE VISUALIZZARE LE VOCI IN ULTIMA POSIZIONE,
                     * DAL BACK END LE VOCI VERRANNO GESTITE MODIFICANDONE L'ORDINE AUTOMATICAMENTE ENTRANDO NEL MENU !!!
                     */

                    if ($row->type === 'servizi') {

                        if (!empty($servizi[$row->ord])) {
                            $max_val = $max[$row->type]+1;
                            $max[$row->type] = $max_val;
                            $servizi[$max_val] = $li;
                        }
                        else 
                            $servizi[$row->ord] = $li;
                            
                        self::$cms_pagine_gia_mostrate[] = $row->id;
                        
                    }
                    
                    if ($row->type === 'offerte') {

                        if (!empty($offerte[$row->ord])) {
                            $max_val = $max[$row->type]+1;
                            $max[$row->type] = $max_val;
                            $offerte[$max_val] = $li;
                        }
                        else 
                            $offerte[$row->ord] = $li;
    
                        self::$cms_pagine_gia_mostrate[] = $row->id;
                        
                    }
                    
                    if ($row->type === 'trattamenti') {
                        
                        if (!empty($trattamenti[$row->ord])) {
                            $max_val = $max[$row->type]+1;
                            $max[$row->type] = $max_val;
                            $trattamenti[$max_val] = $li;
                        }
                        else 
                            $trattamenti[$row->ord] = $li;
    
                        self::$cms_pagine_gia_mostrate[] = $row->id;
                        
                    }
    
                    if ($row->type === 'parchi') {

                        if (!empty($parchi[$row->ord])) {
                            $max_val = $max[$row->type]+1;
                            $max[$row->type] = $max_val;
                            $parchi[$max_val] = $li;
    
                        }
                        else
                            $parchi[$row->ord] = $li;
    
                        self::$cms_pagine_gia_mostrate[] = $row->id;
                        
                    }
    
                    if ($row->type === 'famiglia') {

                        if (!empty($famiglia[$row->ord])) {
                            $max_val = $max[$row->type]+1;
                            $max[$row->type] = $max_val;
                            $famiglia[$max_val] = $li;
    
                        }
                        else
                            $famiglia[$row->ord] = $li;
    
                        self::$cms_pagine_gia_mostrate[] = $row->id;
                        
                    }
    
                    if ($getMenuVisibilita) {
                        if ($row->type === 'visibilita') {

                            if (!empty($visibilita[$row->ord])) {
                                $max_val = $max[$row->type]+1;
                                $max[$row->type] = $max_val;
                                $visibilita[$max_val] = $li;
                            }
                            else
                                $visibilita[$row->ord] = $li;
    
                            self::$cms_pagine_gia_mostrate[] = $row->id;
                            
                        }
                    }
                
                
                    
                } /* / if isValidMenu */
                else {
                    
                    if ($getMenuVisibilita) {
                        
                        if ($row->type === 'visibilita') {
    
                            $uri = Utility::getPublicUri($row->uri);
                            $url = url($uri);
                            $ancora = (empty($row->ancora) || $row->ancora == '') ? substr($uri, 0, strpos($uri, "/")) : $row->ancora;

                            $li_class = "";
                            // if ( $url == Self::getCurrentUri() )
                            // 	$li_class = "current";

                            if ($row->menu_evidenza)
                                $li_class .= "evidenziato";
            
                                $tag = '<li class="'.$li_class.'">';
                                $tag2 = "</li>";
                                    
                            $li = $tag . "<a href=\"{$url}\"><span>{$ancora}</span><span class='".$class."'>{$row->listing_count}</span></a>". $tag2;
                            
                            if (!empty($visibilita[$row->ord])) {
                                $max_val = $max[$row->type]+1;
                                $max[$row->type] = $max_val;
                                $visibilita[$max_val] = $li;
                            }
                            else
                                $visibilita[$row->ord] = $li;

                            self::$cms_pagine_gia_mostrate[] = $row->id;
    
                        }
                    }
                }
            } /* / foreach tblMenuTematico */
            

            ksort($servizi);
            ksort($offerte);
            ksort($trattamenti);
            ksort($parchi);
            ksort($famiglia);

            if ($getMenuVisibilita)
                ksort($visibilita);

            $block1 = '<nav><header><h3>'.Lang::get('labels.menu_cat').'</h3></header><ul>'.implode("", $tmp)."</ul></nav>";

            

            $menu .= $block1;
    
            /**
             * Link alle OFFERTE ed ai LAST 
             * se c'è una localita provo a prendere la pagina che filtra sulla localita se esiste
             * SE SONO IN UNA PAGINA "Riviera Romagnola" ho i link alle offerte ed ai last della RR
             */

            if ($id_macrolocalita == Utility::getMacroRR()) {
    
                $offer_page = CmsPagina::attiva()
                    ->where('uri', 'like', '%/offerte_speciali%')
                    ->where('listing_macrolocalita_id', Utility::getMacroRR())
                    ->where('listing_localita_id', Utility::getMicroRR())
                    ->where('template', 'listing')
                    ->where("lang_id", $locale)
                    ->get();
    
                $last_page = CmsPagina::attiva()
                    ->where('uri', 'like', '%/last_minute%')
                    ->where('listing_macrolocalita_id', Utility::getMacroRR())
                    ->where('listing_localita_id', Utility::getMicroRR())
                    ->where('template', 'listing')
                    ->where("lang_id", $locale)
                    ->get();
            }
            else {

                if ($id_localita) {
    
                    $offer_page = CmsPagina::attiva()
                        ->where('uri', 'like', '%/offerte_speciali.php')
                        ->where('listing_localita_id', $id_localita)
                        ->where('template', 'listing')
                        ->where("lang_id", $locale)
                        ->get();
    
                    $last_page = CmsPagina::attiva()
                        ->where('uri', 'like', '%/last_minute.php')
                        ->where('listing_localita_id', $id_localita)
                        ->where('template', 'listing')
                        ->where("lang_id", $locale)
                        ->get();
    
                    if (!$offer_page->count() && $id_macrolocalita) {
                        $offer_page = CmsPagina::attiva()
                            ->where('uri', 'like', '%/offerte_speciali.php')
                            ->where('listing_macrolocalita_id', $id_macrolocalita)
                            ->where('listing_localita_id', 0)
                            ->where('template', 'listing')
                            ->where("lang_id", $locale)
                            ->get();
                    }
    
                    if (!$last_page->count() && $id_macrolocalita) {
                        $last_page = CmsPagina::attiva()
                            ->where('uri', 'like', '%/last_minute.php')
                            ->where('listing_macrolocalita_id', $id_macrolocalita)
                            ->where('listing_localita_id', 0)
                            ->where('template', 'listing')
                            ->where("lang_id", $locale)
                            ->get();
                    }
    
    
                }
                elseif ($id_macrolocalita) {
    
                    $offer_page = CmsPagina::attiva()
                        ->where('uri', 'like', '%/offerte_speciali.php')
                        ->where('listing_macrolocalita_id', $id_macrolocalita)
                        ->where('listing_localita_id', 0)
                        ->where('template', 'listing')
                        ->where("lang_id", $locale)
                        ->get();
    
                    $last_page = CmsPagina::attiva()
                        ->where('uri', 'like', '%/last_minute.php')
                        ->where('listing_macrolocalita_id', $id_macrolocalita)
                        ->where('listing_localita_id', 0)
                        ->where('template', 'listing')
                        ->where("lang_id", $locale)
                        ->get();
    
                }
                else {
    
                    $offer_page = CmsPagina::attiva()
                        ->where('uri', 'like', '%/offerte_speciali.php')
                        ->where('listing_macrolocalita_id', 0)
                        ->where('listing_localita_id', 0)
                        ->where('template', 'listing')
                        ->where("lang_id", $locale)
                        ->get();
        
                    $last_page = CmsPagina::attiva()
                        ->where('uri', 'like', '%/last_minute.php')
                        ->where('listing_macrolocalita_id', 0)
                        ->where('listing_localita_id', 0)
                        ->where('template', 'listing')
                        ->where("lang_id", $locale)
                        ->get();
    
                }

            }
            
            if ($servizi)
                $menu .= '<nav><header><h3>'.Lang::get('labels.menu_serv').'</h3></header><ul>'.implode("", $servizi)."</ul></nav>";
                
            if ($offerte) {
                
                $menu .= '<nav><header><h3>'.Lang::get('labels.menu_off').'</h3></header>';
            
                if ($last_page->count() || $offer_page->count()):
                    
                    $menu .= '<ul style="margin-bottom:15px;">';
                    
                    $li_class = "";
                    // if ($last_page->count() && "/" .$last_page->first()->uri == Self::getCurrentUri() )
                    // 	$li_class = "current";

                    if ($last_page->count()){
                        $menu .= '<li class="'. $li_class.' evidenziato lastMinute"><a  href="/' .$last_page->first()->uri.'"><span>'.Lang::get('labels.menu_last').'</span></a></li>';
                    }
                
                    // $li_class = "";
                    // if ($offer_page->count() && "/" .$offer_page->first()->uri == Self::getCurrentUri() )
                    // 	$li_class = "current";

                    if ($offer_page->count())
                        $menu .= '<li class="'. $li_class.' evidenziato offerteSpeciali"><a  href="/' . $offer_page->first()->uri.'"><span>'.Lang::get('labels.menu_offer').'</span></a></li>';
                        
                    $menu .= '</ul>';
                
                endif;
            
                $menu .= '<ul>'.implode("", $offerte)."</ul></nav>";
            }
                
            if ($famiglia)
                $menu .= '<nav><header><h3>'.Lang::get('labels.menu_fam').'</h3></header><ul>'.implode("", $famiglia)."</ul></nav>";
            if ($trattamenti)
                $menu .= '<nav><header><h3>'.Lang::get('labels.menu_trat').'</h3></header><ul>'.implode("", $trattamenti)."</ul></nav>";
            if ($parchi)
                $menu .= '<nav><header><h3>'.Lang::get('labels.menu_par').'</h3></header><ul  class="no-margin">'.implode("", $parchi)."</ul></nav>";
                

            
            $menu_array = [];
            $menu_array[0] = $menu;
            $menu_array[1] = $visibilita;
            
            Utility::putCache($key, $menu_array);

        }
        
        /**
         * Se sono i il barusso torno il barusso 
         */
         
        if ($getMenuVisibilita && count($menu_array[1]))
            return '<h3>'.Lang::get('labels.menu_barusso').'</h3><ul>'.implode("", $menu_array[1])."</ul>";
        
        /**
         * Altrimento il menu tematico
         */
         
        return $menu_array[0];

    }	
    
    
    /* ----- MOBILE ----- */
    
    
    /**
     * Crea il menu localita MOBILE.
     * 
     * @access public
     * @static
     * @param mixed $cms_pagina (default: null)
     * @return void
     */
     
    public static function getMenuLocalitaMobile($cms_pagina = null)
    {

        $menu = null;
        $current_lang = \App::getLocale();

        /*
         * Se ho passato la pagina allora devo verificare in quale macro sono ed aprire le sottolocalita
         * Se sono in una pagina "trasversale" LASCIO solo le macro
         */

        // se non passo la pagina
        // oppure è una pagina RR (sia listing che localita)
        // oppure è statica
        if (
            is_null($cms_pagina)
            || (!$cms_pagina->menu_macrolocalita_id && !$cms_pagina->menu_localita_id && $cms_pagina->template == 'localita')
            || (!$cms_pagina->listing_macrolocalita_id && !$cms_pagina->listing_localita_id && $cms_pagina->template == 'listing')
            || ($cms_pagina->template == 'statica')
        ) {

            $key = "menumobile_macrolocalita_$current_lang";
            $menu = self::_draw_menu_macrolocalita($menu, $key, 1);


        }
        else {

            if ($cms_pagina->template == 'localita') {
                
                $current_macro_id = $cms_pagina->menu_macrolocalita_id;
                $key = "menumobile_localita_{$current_lang}_{$current_macro_id}";

            } elseif ($cms_pagina->template == 'listing') {
                
                $current_macro_id = $cms_pagina->listing_macrolocalita_id;
                $key = "menumobile_localita_{$current_lang}_{$current_macro_id}";

            }

            $menu = self::_draw_menu_macrolocalita($menu, $key, $current_macro_id, $current_lang);

        }

        return $menu;
    }

    /**
     * Crea il menu localita MOBILE x le pagine hotel.
     * 
     * @access public
     * @static
     * @param mixed $cms_pagina (default: null)
     * @return void
     */
         
    public static function getMenuLocalitaMobileHotel($cliente = null)
    {

        $menu = null;
        $current_lang = \App::getLocale();

        if (is_null($cliente) || is_null($cliente->localita->macrolocalita)) {

            $key = "menumobile_macrolocalita_$current_lang";
            $menu = self::_draw_menu_no_macrolocalita($menu, $key);

        } else {

            $current_macro_id = $cliente->localita->macrolocalita->id;
            $key = "menumobile_localita_{$current_lang}_{$current_macro_id}";
            $menu = self::_draw_menu_macrolocalita($menu, $key, $current_macro_id, $current_lang);

        }

        return $menu;
        
    }
     
    /**
     * Versione mobile del menu tematico]
     * 
     * @access public
     * @static
     * @param string $locale (default: 'it')
     * @param int $id_macrolocalita (default: 0)
     * @param int $id_localita (default: 0)
     * @param int $getMenuVisibilita (default: 0)
     * @return void
     */
     
    public static function getMenuTematicoMobile( $locale = 'it', $id_macrolocalita=0, $id_localita=0, $getMenuVisibilita = 0)
    {
        
        $visibilita = null;
        $key = "menu_tematico_mobile_" . $locale ."_".$id_macrolocalita."_".$id_localita."_".$getMenuVisibilita;
                
        if (!$menu_array = Utility::activeCache($key, "Cache Menu Tematico")) {
            
            /**
             * se la macro ha MOLTE micro, allora prendo setto id_localita = 0 e filtro solo per la macro,
             * altrimenti se la macro ha 1! micro filtro per quella
             */

            $menu = '';
            $macro = Macrolocalita::find($id_macrolocalita);
                
            //////////////////////////////////////////////////////////////////////////////////////////
            // 09/01/18 Voglio aggiungere il link ai trattamenti b&b nella sezione categoria @lucio //
            //////////////////////////////////////////////////////////////////////////////////////////
            $cms_pagine = CmsPagina::getCategorieWithBB($id_macrolocalita, $id_localita, $locale);

            
            /**
             * Prima voglo le stelle, dalla 5 alla 1
             * poi voglio il resto (residence, ecc...)
               */
            $tmp = [];
            foreach ($cms_pagine as $cms_pagina) {
                
                if ($cms_pagina->listing_macrolocalita_id == 9 && $cms_pagina->listing_categorie == Categoria::ID_STELLE_3S) {

                    /**
                     * @2021-02-15 09:47:10 
                     * @Lucio, @Elena, @Sacco
                     * Su gabicce non esistono 3 stelle superiori 
                     * In linea di massima su tutte le marche i 3 stelle sup non ci sono, controllare in caso di apertura di nuove zone
                     * 
                     */

                } else {

                    $ord = 0;
                    if ($cms_pagina->listing_categorie == Categoria::ID_STELLE_1)
                        $ord = 1;
                    if ($cms_pagina->listing_categorie == Categoria::ID_STELLE_2)
                        $ord = 2;
                    if ($cms_pagina->listing_categorie == Categoria::ID_STELLE_3)
                        $ord = 3;
                    if ($cms_pagina->listing_categorie == Categoria::ID_STELLE_3S)
                        $ord = 4;
                    if ($cms_pagina->listing_categorie == Categoria::ID_STELLE_4)
                        $ord = 5;
                    if ($cms_pagina->listing_categorie == Categoria::ID_STELLE_5)
                        $ord = 6;
                    if ($cms_pagina->listing_trattamento == 'bb')
                        $ord = -1;

                    $url = url(Utility::getPublicUri($cms_pagina->uri));
                    $tmp[$ord] = "<li><a href=\"{$url}\">{$cms_pagina->ancora}<div class=\"badge\"><div>{$cms_pagina->listing_count}</div></div></a></li>";

                    self::$cms_pagine_gia_mostrate[] = $cms_pagina->id;

                }
                
            }

            krsort($tmp);
            $block1 = implode("", $tmp);

            /**
             * Per il 2° e 3° blocco bisogna sempre mostrare il contesto relativo alla macrolocalità
             */
                    
            if ($id_localita > 1)
                $id_macrolocalita = Localita::find($id_localita)->macrolocalita_id;
                        
            $tblMenuTematico = DB::table("tblMenuTematico")
                ->join('tblCmsPagine', 'tblCmsPagine.id', '=', 'tblMenuTematico.cms_pagine_id')
                ->select("tblCmsPagine.ancora", "tblCmsPagine.uri" , "tblCmsPagine.menu_evidenza" , "tblCmsPagine.menu_dal", "tblCmsPagine.menu_al" , "tblCmsPagine.menu_auto_annuale", "tblCmsPagine.listing_count", "tblMenuTematico.*")
                ->where("tblMenuTematico.lang_id", $locale)
                ->whereIn("tblMenuTematico.macrolocalita_id", [$id_macrolocalita, 0])
                ->get();


            //Utility::_swapScadutiToBarusso($tblMenuTematico);

            /**
             * Trovo il massimo valore dell'ordinamento per ciascuna tipologia di link (E' il valore che assegno in caso di CONFLITTO!!!)
             */
             
            $max['servizi'] = 0;
            $max['offerte'] = 0;
            $max['trattamenti'] = 0;
            $max['parchi'] = 0;

            $max['visibilita'] = 0;
            $max['famiglia'] = 0;

            foreach ($tblMenuTematico as $row)
                if ($row->ord > $max[$row->type])
                    $max[$row->type] = $row->ord;
            
            $servizi = [];
            $offerte = [];
            $trattamenti = [];
            $parchi = [];
            $famiglia = [];

            if ($getMenuVisibilita)
                $visibilita = [];

            foreach ($tblMenuTematico as $row) {

                $ancora = (empty($row->ancora) || $row->ancora == '') ? substr($uri, 0, strpos($uri, "/")) : $row->ancora;
                
                if (Utility::isValidMenu($row->menu_dal, $row->menu_al, $ancora, $row->menu_auto_annuale)) {

                    $uri = Utility::getPublicUri($row->uri);
                    $url = url($uri);

                    /**
                     * SE LA PAGINA NON HA ANCORA
                     * PROVO A CREARLA DALL'URI "on the fly"
                     */
                     

                    $li = "<li><a href=\"{$url}\">{$ancora}</a></li>";

                    if ($row->type === 'servizi') {
                        
                        /**
                         * Se questa posizione è già occupata
                         */
                         
                        if (!empty($servizi[$row->ord])) {
                            
                            $max_val = $max[$row->type]+1;
                            $max[$row->type] = $max_val;
                            $servizi[$max_val] = $li;
                            
                        } else 
                            $servizi[$row->ord] = $li;

                        self::$cms_pagine_gia_mostrate[] = $row->id;

                    }
                    
                    if ($row->type === 'offerte') {
                        
                        /**
                         * se questa posizione è già occupata
                         */
                         
                        if (!empty($offerte[$row->ord])) {
                            
                            $max_val = $max[$row->type]+1;
                            $max[$row->type] = $max_val;
                            $offerte[$max_val] = $li;
                            
                        } else
                            $offerte[$row->ord] = $li;

                        self::$cms_pagine_gia_mostrate[] = $row->id;
                    }
                    
                    if ($row->type === 'trattamenti') {
                        /**
                         * se questa posizione è già occupata
                         */
                         
                        if (!empty($trattamenti[$row->ord])) {
                            
                            $max_val = $max[$row->type]+1;
                            $max[$row->type] = $max_val;
                            $trattamenti[$max_val] = $li;
                            
                        } else
                            $trattamenti[$row->ord] = $li;

                        self::$cms_pagine_gia_mostrate[] = $row->id;
                    }
                    
                    if ($row->type === 'parchi') {
                        
                        /**
                         * Se questa posizione è già occupata
                         */
                         
                        if (!empty($parchi[$row->ord])) {
                            
                            $max_val = $max[$row->type]+1;
                            $max[$row->type] = $max_val;
                            $parchi[$max_val] = $li;
                            
                        } else 
                            $parchi[$row->ord] = $li;

                        self::$cms_pagine_gia_mostrate[] = $row->id;
                    }
                    
                    if ($row->type === 'famiglia') {
                        
                        /**
                         * Se questa posizione è già occupata
                         */
                         
                        if (!empty($famiglia[$row->ord])) {
                        
                            $max_val = $max[$row->type]+1;
                            $max[$row->type] = $max_val;
                            $famiglia[$max_val] = $li;

                        } else
                            $famiglia[$row->ord] = $li;

                        self::$cms_pagine_gia_mostrate[] = $row->id;
                    }

                    if ($getMenuVisibilita) {
                        
                        if ($row->type === 'visibilita') {
                            
                            /**
                             * se questa posizione è già occupata
                             */
                             
                            if (!empty($visibilita[$row->ord])) {
                                
                                $max_val = $max[$row->type]+1;
                                $max[$row->type] = $max_val;
                                $visibilita[$max_val] = $li;
                                
                            } else
                                $visibilita[$row->ord] = $li;

                            self::$cms_pagine_gia_mostrate[] = $row->id;
                        }
                        
                    }

                } /* if isValidMenu */ 				 
                else {

                    /*if ($getMenuVisibilita) {

                        if ($row->type === 'visibilita') {
        
                            $uri = Utility::getPublicUri($row->uri);
                            $url = url($uri);
                            $ancora = (empty($row->ancora) || $row->ancora == '') ? substr($uri, 0, strpos($uri, "/")) : $row->ancora;

                            $li_class = "";
                            if ( $url == Self::getCurrentUri() )
                                $li_class = "current";

                            if ($row->menu_evidenza)
                                $li_class .= "evidenziato";
            
                                $tag = '<li class="'.$li_class.'">';
                                $tag2 = "</li>";
                                    
                            $li = $tag . "<a href=\"{$url}\"><span>{$ancora}</span><span class='".$class."'>{$row->listing_count}</span></a>". $tag2;
                            
                            if (!empty($visibilita[$row->ord])) {
                                $max_val = $max[$row->type]+1;
                                $max[$row->type] = $max_val;
                                $visibilita[$max_val] = $li;
                            }
                            else
                                $visibilita[$row->ord] = $li;

                            self::$cms_pagine_gia_mostrate[] = $row->id;

                        }
                    }*/

                }
            }
            
            ksort($servizi);
            ksort($offerte);
            ksort($trattamenti);
            ksort($parchi);
            ksort($famiglia);

            if ($getMenuVisibilita)
                ksort($visibilita);

            $menu .= '<ul class="menu-tematico"><li><span>'.Lang::get('labels.menu_cat').'</span><ul>'.$block1. '</ul><div class="clear"></div>';

            if ($servizi)
                $menu .= '<li><span>'.Lang::get('labels.menu_serv').'</span><ul>'.implode("", $servizi) . '</ul><div class="clear"></div>';
            if ($offerte)
                $menu .= '<li><span>'.Lang::get('labels.menu_off').'</span><ul>'.implode("", $offerte) . '</ul><div class="clear"></div>';
            if ($famiglia)
                $menu .= '<li><span>'.Lang::get('labels.menu_fam').'</span><ul>'.implode("", $famiglia) . '</ul><div class="clear"></div>';
            if ($trattamenti)
                $menu .= '<li><span>'.Lang::get('labels.menu_trat').'</span><ul>'.implode("", $trattamenti) . '</ul><div class="clear"></div>';
            if ($parchi)
                $menu .= '<li><span>'.Lang::get('labels.menu_par').'</span><ul>'.implode("", $parchi) . '</ul><div class="clear"></div>';

            $menu .= '</ul><div class="clear"></div>';

            /**
             * Link alle OFFERTE ed ai LAST
             * se c'è una localita provo a prendere la pagina che filtra sulla localita se esiste
             */

            if ($id_localita) {

                $offer_page = CmsPagina::attiva()
                    ->where('uri', 'like', '%/offerte_speciali.php')
                    ->where('listing_localita_id', $id_localita)
                    ->where('template', 'listing')
                    ->where("lang_id", $locale)
                    ->get();

                $last_page = CmsPagina::attiva()
                    ->where('uri', 'like', '%/last_minute.php')
                    ->where('listing_localita_id', $id_localita)
                    ->where('template', 'listing')
                    ->where("lang_id", $locale)
                    ->get();

                if (!$offer_page->count() && $id_macrolocalita)
                    $offer_page = CmsPagina::attiva()
                        ->where('uri', 'like', '%/offerte_speciali.php')
                        ->where('listing_macrolocalita_id', $id_macrolocalita)
                        ->where('listing_localita_id', 0)
                        ->where('template', 'listing')
                        ->where("lang_id", $locale)
                        ->get();

                if (!$last_page->count() && $id_macrolocalita)
                    $last_page = CmsPagina::attiva()
                        ->where('uri', 'like', '%/last_minute.php')
                        ->where('listing_macrolocalita_id', $id_macrolocalita)
                        ->where('listing_localita_id', 0)
                        ->where('template', 'listing')
                        ->where("lang_id", $locale)
                        ->get();

            } elseif ($id_macrolocalita) {

                $offer_page = CmsPagina::attiva()
                    ->where('uri', 'like', '%/offerte_speciali.php')
                    ->where('listing_macrolocalita_id', $id_macrolocalita)
                    ->where('listing_localita_id', 0)
                    ->where('template', 'listing')
                    ->where("lang_id", $locale)
                    ->get();

                $last_page = CmsPagina::attiva()
                    ->where('uri', 'like', '%/last_minute.php')
                    ->where('listing_macrolocalita_id', $id_macrolocalita)
                    ->where('listing_localita_id', 0)
                    ->where('template', 'listing')
                    ->where("lang_id", $locale)
                    ->get();

            } else {

                $offer_page = CmsPagina::attiva()
                    ->where('uri', 'like', '%/offerte_speciali.php')
                    ->where('listing_macrolocalita_id', 0)
                    ->where('listing_macrolocalita_id', 0)
                    ->where('template', 'listing')
                    ->where("lang_id", $locale)
                    ->get();

                $last_page = CmsPagina::attiva()
                    ->where('uri', 'like', '%/last_minute.php')
                    ->where('listing_macrolocalita_id', 0)
                    ->where('listing_macrolocalita_id', 0)
                    ->where('template', 'listing')
                    ->where("lang_id", $locale)
                    ->get();

            }


            if (!isset($offer_page))
                $offer_page = CmsPagina::attiva()
                    ->where('uri', 'like', '%/offerte_speciali.php')
                    ->where('listing_macrolocalita_id', 0)
                    ->where('listing_macrolocalita_id', 0)
                    ->where('template', 'listing')
                    ->where("lang_id", $locale)
                    ->get();
        


            if (!isset($last_page))
                $last_page = CmsPagina::attiva()
                    ->where('uri', 'like', '%/last_minute.php')
                    ->where('listing_macrolocalita_id', 0)
                    ->where('listing_macrolocalita_id', 0)
                    ->where('template', 'listing')
                    ->where("lang_id", $locale)
                    ->get();

            $menu_offerte = "";

            //if ($last_page->count() || $offer_page->count())
                $menu_offerte = '<ul class="menu-offerte">';

            if ($last_page->count()) {
                $menu_offerte .= '<li><a class="ico lastMinute" href="'.url(Utility::getPublicUri($last_page->first()->uri)).'"><span><i class="icon-clock"></i>'.Lang::get('labels.menu_last').'</span></a></li>';
            }

            if ($offer_page->count()) {
                $menu_offerte .= '<li><a class="ico offerteSpeciali" href="'.url(Utility::getPublicUri($offer_page->first()->uri)).'"><span><i class="icon-gift"></i>'.Lang::get('labels.menu_offer').'</span></a></li>';
            }

            //if ($last_page->count() || $offer_page->count())
                $menu_offerte .= '<li><a class="ico favorites" href="'.url("/preferiti").'"><i class="icon-heart"></i><span>'.Lang::get('labels.preferiti').'</span></a></li></ul><div class="clear"></div>';

            $menu = $menu_offerte . $menu;
            $menu_array = [];
            $menu_array[] = $menu;
            $menu_array[] = $visibilita;

            Utility::putCache($key, $menu_array);

        }

        if ($getMenuVisibilita)
            if (count($menu_array[1]))
                return '<h3>'.Lang::get('labels.menu_barusso').'</h3><ul>'.implode("", $menu_array[1])."</ul>";
            else
                return '';
            else
                return $menu_array[0];

    }





    /* -------------------------------------------------------------------------------------
     * STRADARIO, PUNTI FORZA ETC ...
     * ------------------------------------------------------------------------------------- */

    
    
    
    /**
     * Trova le strade partendo dalla pagina
     * 
     * @access private
     * @static
     * @param String $locale
     * @param Localita $localita (default: null)
     * @param int $macro_localita (default: 11)
     * @return CmsPagine
     */
     
    public static function getStradeFromCmsPagine( $locale,  $localita = null , $macro_localita = 11 )
    {

        /**
         * Se sono RR allora imposto la localita
         */
        
        $key = "strade_" . $macro_localita . "_" . $localita . "_" . $locale;
            
        if (!$strade = Utility::activeCache($key, "Strade per la macro/loc:" . $macro_localita . "/" . $localita)) {
        
            if ($macro_localita == 11)
                $localita = 49;

            $strade = CmsPagina::where('indirizzo_stradario', '!=', '')
                ->where('lang_id', $locale)
                ->where('macrolocalita_id_stradario', $macro_localita);
    
            if ($localita != null)
                $strade = $strade->where('localita_id_stradario', $localita);

            $strade = $strade->get();

            Utility::putCache($key, $strade, 1296000); // !5 giorni
        
        }

        return $strade;

    }	

    
    
    /* ---- Cron Function ---- */
    
    
    
    /**
     * Inserisce le nuove pagine dello stradario
     * 
     * @access public
     * @static
     * @param mixed &$new_pages
     * @param mixed $strada
     * @return void
     */
     
    public static function insertStadaAsPage(&$new_pages, $strada)
      {
          
          $page = [];
          $page['lang_id'] = 'it';							
          $page['attiva'] = 0;			
          $page['template'] = 'listing';							
          $page['listing_attivo'] = 1;

        if ($strada->localita == $strada->macrolocalita) {
            
              $page['listing_macrolocalita_id'] = $strada->id_macrolocalita;
              $page['listing_localita_id'] = 0;
              $page['uri'] = Str::slug($strada->macrolocalita,'-'). '/' . Str::slug($strada->indirizzo,'-');
              
          } else {

              $page['listing_macrolocalita_id'] = $strada->id_macrolocalita;
              $page['listing_localita_id'] = $strada->id_localita;
              $page['uri'] = Str::slug($strada->macrolocalita,'-'). '/' . Str::slug($strada->localita,'-'). '/' .Str::slug($strada->indirizzo,'-');
              
          }
          
          $page['ancora'] = $strada->indirizzo. ', ' .$strada->localita;
          $page['indirizzo_stradario'] = $strada->indirizzo;
          $page['localita_id_stradario'] = $strada->id_localita;
          $page['macrolocalita_id_stradario'] = $strada->id_macrolocalita;
          

          $new_pages[] = $page;
    
    }	
            
                                    
      /**
       * Trove le candidate per essere inserite come nuove pagine.
       * 
       * @access public
       * @static
       * @param mixed $localita (default: null)
       * @param mixed $macro_localita (default: null)
       * @return void
       */
       
      public static function getStradeStradario( $localita = null , $macro_localita = null )
    {

        $strade =  DB::table('tblHotel')
        ->select(
            DB::raw("SUBSTRING_INDEX(tblHotel.indirizzo, ', ', 1) AS `indirizzo`,
                                    tblLocalita.nome as localita,
                                    tblLocalita.id as id_localita,
                                    tblMacrolocalita.nome as macrolocalita,
                                    tblMacrolocalita.id as id_macrolocalita,
                                    COUNT(*) as numero")
        )
        ->join('tblLocalita', function($join)
            {
                $join->on('tblHotel.localita_id', '=', 'tblLocalita.id');
            })
        ->join('tblMacrolocalita', function($join2)
            {
                $join2->on('tblLocalita.macrolocalita_id', '=', 'tblMacrolocalita.id');
            })
        ->where('tblHotel.attivo', 1);
        
        if ($macro_localita != null)
            $strade = $strade->where('tblLocalita.macrolocalita_id', $macro_localita);

        if ($localita != null)
            $strade = $strade->where('tblLocalita.id', $localita);

        $strade = $strade->groupBy(DB::raw("SUBSTRING_INDEX(tblHotel.indirizzo, ', ', 1),
                                    tblLocalita.id,
                                    tblMacrolocalita.id"))
        ->havingRaw("COUNT(*) >  4")
        ->get();
                
        return $strade;
        
    }
    
    
      /**
       * Trove i candidati per essere inseriti come nuove pagine.
       * 
       * @access public
       * @static
       * @param mixed $localita (default: null)
       * @param mixed $macro_localita (default: null)
       * @return void
       */

    public static function getPtiForzaGrouped($limit = 10)
    {
        $pdf_count = DB::table('tblPuntiForza')
            ->join('tblPuntiForzaLang', 'tblPuntiForza.id', '=', 'tblPuntiForzaLang.master_id')
            ->select('tblPuntiForzaLang.nome', 'tblPuntiForzaLang.lang_id', DB::raw('count(*) as n') )
            ->where('tblPuntiForzaLang.nome', '!=', '')
            
                                    //->where('tblPuntiForzaLang.lang_id', '=', 'it')
            
            ->groupBy('tblPuntiForzaLang.nome', 'tblPuntiForzaLang.lang_id')
            ->havingRaw('count(*) > '.$limit)
            ->orderBy('lang_id', 'desc')
            ->orderBy('n', 'desc')
            ->get();

        return $pdf_count;
    }




    /* -------------------------------------------------------------------------------------
     * GEO
     * ------------------------------------------------------------------------------------- */


     
     
    /**
     * Trova le distanze tra due punti.
     * 
     * @access public
     * @static
     * @param mixed $lat1
     * @param mixed $lng1
     * @param mixed $lat2
     * @param mixed $lng2
     * @return void
     */
     
    public static function getGeoDistance($lat1, $lng1, $lat2, $lng2)
    {
        // calculates the distance between two lat, long coordinate pairs
        $R = 6371000; // radius of earth in m
        $lat1rads = deg2rad($lat1);
        $lat2rads = deg2rad($lat2);
        $deltaLat = deg2rad($lat2-$lat1);
        $deltaLng = deg2rad($lng2-$lng1);
        $a = sin($deltaLat/2) * sin($deltaLat/2) + cos($lat1rads) * cos($lat2rads) * sin($deltaLng/2) * sin($deltaLng/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        $d = $R * $c;

        return $d;
    }


    /**
     * In genere le coordinate vengono trovate in base alla localita oppure alla macro
     * nelle pagine in cui non ho né macro né micro ottengo delle coordinate generiche
     * 
     * @access public
     * @static
     * @return void
     */
     
    public static function getGenericGeoCoords()
    {
        $coordinate = array();

        $lat_fallback = "44.063409";
        $long_fallback = "12.585280";
        $zoom_fallback = 9;


        // ci sono località che hanno come coordinate 0.0000
        $coordinate['lat'] = $lat_fallback;
        $coordinate['long'] = $long_fallback;
        $coordinate['zoom'] = $zoom_fallback;

        return $coordinate;
    }
    
    
    /**
     * Ritorna la distanza dal centro.
     * 
     * @access public
     * @param mixed $cliente
     * @return void
     */
     
    public static function getDistanzaDalCentroPoi( $cliente ) {
        
        $centro = $cliente->getDistanzaDalCentroPoi($cliente->distanza_centro,$cliente->localita->centro_raggio);
        $misura = " km";
        
        if ($centro == "0.0") 
            {
            return Lang::get('labels.in_centro');
            } 
        else 
            {

            if ($centro < 1) {
                $misura = " m";
                $centro *= 1000; 
                $centro = number_format($centro,1 , ".", "");
            }
                
            return $centro . $misura ; 			
            
            }
        
        
    }
    
    
    /**
     * Restituisce le coordinate in base all'id.
     * 
     * @access public
     * @static
     * @param mixed $macro
     * @param int $micro (default: 0)
     * @return void
     */
     
    public static function getCoordsByLocalitaID ( $macro, $micro = 0) {
        
        if ($micro > 0) 
            return Localita::select("latitudine", "longitudine")->where("id", $micro)->first();
            
        else
            return Macrolocalita::select("latitudine", "longitudine")->where("id", $macro)->first();
        
        
    } 



    /* -------------------------------------------------------------------------------------
     * FILTRI
     * ------------------------------------------------------------------------------------- */


     

    public static function filterOrderFieldHotel($field)
    {
        if (!in_array($field, array('id', 'localita_id', 'nome', 'attivo','chiuso_temp','annuale')))
            $field = 'id';

        return $field;
    }


    public static function filterOrderFieldPages($field)
    {
        if (!in_array($field, array('id', 'attiva', 'lang_id', 'template', 'uri', 'updated_at', 'menu_dal')))
            $field = 'id';

        return $field;
    }


    public static function filterOrderDirection($direction)
    {
        if (!in_array($direction, array('asc', 'desc')))
            $direction = 'asc';

        return $direction;
    }


    public static function viewThOrderBy($human_name, $ord)
    {
        $request = Request::capture();

        $ord_curr = self::filterOrderFieldHotel($request->query->get('ord'));
        $dir_curr = self::filterOrderDirection($request->query->get('dir'));

        if ($dir_curr == 'asc')
            $dir = 'desc';
        else
            $dir = 'asc';

        $request_clone = $request->duplicate();

        $request_clone->query->set('ord', $ord);
        $request_clone->query->set('dir', $dir);

        $params = $request_clone->query->all();

        $url = $request_clone->getSchemeAndHttpHost().$request_clone->getBaseUrl().$request_clone->getPathInfo();

        $url .= '?'.http_build_query($params);

        $css_class = null;
        if ($ord_curr == $ord)
            $css_class = "nm-order-$dir_curr";

        return '<th class="'.$css_class.'" onclick="window.location.href = \''.$url.'\'">'.$human_name.'</th>';
    }

    static public function getUsersHotels($without_user_account = false)
    {
        if ($without_user_account) {
            $ids = User::where("hotel_id", ">", 0)->pluck('hotel_id');
            $hotels = Hotel::whereNotIn("id", $ids)->get();
        }
        else {
            
            /*
           * Queste query sicuramente vanno cachate...
           * altrimenti ogni pagina visitata in admin da un operatore scatena questa table scan
           */
            // $key = "total_hotels_admin";
            
            // if (!$hotels = Self::activeCacheAdmin($key, "Cache Hotel admin")) {
               
            //     $hotels = Hotel::with(['localita' => function($query) {
            //         $query->select('id', 'nome'); 
            //     }])
            //     ->get(['id','nome', 'localita_id']);

            //     Self::putCacheAdmin($key, $hotels);

            // }

            // if (Session::has('total_hotels_admin')) {
                
            //     $hotels = Session::get('total_hotels_admin');
                
                
            // } else {
               
                
                // }
                
            $hotels = Hotel::with(['localita' => function ($query) {
                $query->select('id', 'nome');
            }])
            ->get(['id', 'nome', 'localita_id']);

            Session::put('total_hotels_admin', $hotels);

            
            
        }


        $retval = [];

        foreach ($hotels as $hotel) {
            //$retval[] = $hotel->getIdNomeLoc();
            if ($hotel->localita->id >= 52) {
                $retval[] = $hotel->id." ".str_replace("'", "", $hotel->nome)." ".json_decode($hotel->localita->nome)->it;
            } else {
                $retval[] = $hotel->id." ".str_replace("'", "", $hotel->nome)." ".$hotel->localita->nome;
            }
        }


        return $retval;
    }
    
    
    /* -------------------------------------------------------------------------------------
     * 19. Località
     * ------------------------------------------------------------------------------------- */

    public static function getLocalitaChainedForView()
    {

        $localita = Localita
            ::orderBy("nome", "asc")
            ->get();

        $retval = [];
        foreach ($localita as $loc) {
            $retval[$loc->macrolocalita_id][$loc->id] = $loc->nome;
        }

        return json_encode($retval);

    }
    
    /* -------------------------------------------------------------------------------------
     * 20. Immagini
     * ------------------------------------------------------------------------------------- */

        /**
     * Esegue il resize delle foto in base a ImmagineGallery::getImagesVersions()
     *
     * @access private
     * @param Request $request
     * @param String $path
     * @return void
     */

    public static function getResizedImages($request, $path = "")
    {

        if ($path == "")
            $path = Self::SPOT_PATH;

        if ($path == Self::SPOT_PATH) 
            $versions = ImmagineGallery::getImagesVersionsSpot();
        else
            $versions = ImmagineGallery::getImagesVersionsEvidenza();

        /**
         * Validazione
         */

        $rules = array('file' => 'image|mimes:jpeg,png,jpg,gif,svg|max:3000');

        $messages = [
            'image' => 'Il file selezionato deve essere un\'immagine!\n',
            'max' => 'La dimensione massima del file deve essere :max KB\n',
        ];

        $validation = Validator::make($request->all(), $rules, $messages);

        /**
         * Restituisco un errore in caso di fallimento
         */

        if ($validation->fails()) {

            $errors = $validation->errors()->all();
            $msg = "";

            foreach ($errors as $error) {
                $msg .= $error . "<br>";
            }

            return ['msg' => rtrim($msg, '<br>')];

        }

        if (is_null($request->file('file'))) {
            return ['msg' => 'file nullo'];
        }

        /**
         * Eseguo il resize
         */

        try
        {

            $imagev = new ImageVersionHandler;

            if ($file = $request->file('file')) {

                $uploaded_filename = File::name($file->getClientOriginalName());
                $imagev->setImageBasename(str_replace([' ', '\''], '_', "{$uploaded_filename}_" . uniqid()));
                //$backup_path = storage_path('original_images/' . $path);
                
                $backup_path = "/original_images/spothome/";
                $imagev->enableOriginalBackup($backup_path);
                $imagev->loadOriginalFromUpload($request->file('file'));

                /**
                 * Processo le immagini
                 */

                foreach ($versions as $v) {

                    $imagev->process($v["mode"], $v["basedir"], $v["width"], $v["height"]);
                    SessionResponseMessages::add("info", "Creata variante immagine {$v["width"]}x{$v["height"]} ({$v["mode"]})");

                }

                return $imagev->getImageFilename();

            }

        } catch (\Exception $e) {

            config('app.debug_log') ? Log::emergency("\n" . '---> Errore UPLOAD IMMAGINI GALLERY: ' . $e->getMessage() . ' <---' . "\n\n") : "";
            return ['msg' => 'Errore upload del file ' . $e->getMessage() ];

        }

    }

    /* -------------------------------------------------------------------------------------
     * 21. Altro
     * ------------------------------------------------------------------------------------- */
    
    /**
     * Prende i commerciali del CRM di IA.
     * 
     * @access public
     * @static
     * @param int $hotel_id (default: 0)
     * @return void
     */
    
    public static function getCommercialeFromCrm($hotel_id = 0) 
    {

        if (!$hotel_id) 
            return [];

        if (App::environment() == "local")
            $webServiceHost = 'http://localhost/crm2/';

        else
            $webServiceHost = 'https://alpha.info-alberghi.com/';

        $url = $webServiceHost . 'admin/cron/get_commerciale_cliente/' . $hotel_id;

        //  Initiate curl
        $ch = curl_init();
        // Disable SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Set the url
        curl_setopt($ch, CURLOPT_URL,$url);
        // Execute
        $result=curl_exec($ch);
        // Closing
        curl_close($ch);

        // array degli id dei servizi associati a a questo hotel su IA
        $commerciale_array = json_decode($result, true);

        return $commerciale_array;

    }

    
    
    /**
     * Torna le condizioni di un'offerta.
     * 
     * @access public
     * @param mixed $offers
     * @return void
     */
     
    public static function getConditions ($offers, $locale = 'it') {
        
        if (Self::DetectMobile() != "computer")
            $formatDate = '%e %b %Y';
            
        else
            $formatDate = '%e %B';
        
        $html = ucfirst(Lang::get('listing.per_soggiorni_dal')) .'<b> ' . Self::myFormatLocalized($offers->valido_dal, $formatDate, $locale) . '</b> ' . Lang::get('listing.fino_al') . '<b> ' . Self::myFormatLocalized($offers->valido_al,  $formatDate, $locale) . ' </b>';
    
        $lbl_pp = Lang::get('hotel.valido_da_al_4');
        $lbl_pg = Lang::get('hotel.valido_notte');
                

        if ($offers->per_persone || $offers->per_giorni != 0) 

            if ($offers->per_persone == 1) 
                {
                $lbl_pp = Lang::get('hotel.valido_da_al_4');
                }
            else 
                {
                $lbl_pp = Lang::get('hotel.valido_da_al_4s'); 
                }

            if ($offers->per_giorni == 1) 
                {
                $lbl_pg = Lang::get('hotel.valido_notte');
                } 
            else 
                {
                $lbl_pg = Lang::get('hotel.valido_notti');
                }

             $html .= '<br />' . Lang::get('hotel.valido_da_al_3') . ' <b>' .  $offers->per_persone . ' ' . $lbl_pp . '</b> ' .
             Lang::get('hotel.valido_da_al_4_1') . ' <b>' . $offers->per_giorni . ' ' . $lbl_pg . '</b>';
         
        return $html;
        
    }


        /**
     * Torna le condizioni di un'offerta Prenota Prima.
     * 
     * @access public
     * @param mixed $offers
     * @return void
     */
     
    public static function getConditionsPP ($offerta_vot, $locale = 'it') {
        
        if (Self::DetectMobile() != "computer")
            $formatDate = '%e %b %Y';
            
        else
            $formatDate = '%e %B';
        
        
        $html = ucfirst(Lang::get('listing.prenota_entro_il')) . " <b>". Utility::myFormatLocalized($offerta_vot->prenota_entro, '%e %B', $locale) . "</b> " . Lang::get('listing.ottieni') . ' <b>' . $offerta_vot->perc_sconto . "%</b> " .Lang::get('listing.di_sconto') ;
        $html .= "<br>";
        $html .= Lang::get('listing.per_soggiorni_dal') .'<b> ' . Self::myFormatLocalized($offerta_vot->valido_dal, $formatDate, $locale) . '</b> ' . Lang::get('listing.fino_al') . '<b> ' . Self::myFormatLocalized($offerta_vot->valido_al,  $formatDate, $locale) . ' </b>';
    
        
         
        return $html;
        
    }
    
    
    /*
     * UTILIZZATO come titolo dell'elenco degli hotel simili nella mail di upselling e nella scheda hotel
     * SOSTANZIALMENTE leggo il campo ancora della tabella e se è vuoto restituisco la stringa "hotel simili"
     * 
     * @access public
     * @param string $ref (default: '')
     * @return void
     */
     
    public static function getCausaleSimili($ref = '')
    {
        
        $void_return = Lang::get('labels.hotel_simili');
        
        if (App::environment() == "local")
            //$uri = substr($ref, strpos($ref, "public") + 7);
            $uri = substr($ref, strpos($ref, ".ssl") + 5);
            
        else
            $uri = substr($ref, strpos($ref, ".com") + 5);
        

        $referer_page = CmsPagina::where('uri', $uri)->attiva()->first();
        
        if (!$referer_page)
            return $void_return;
            
        return ($referer_page->ancora == '') ? '"'. $void_return . '"' : '"'.strtolower($referer_page->ancora).'"';
        
    }

    /**
     * Prende la localita in base al listing (utilitzzato nella ricerca)
     * 
     * @paran String $page
     * @return String
     */

    public static function getLocalitaFromPage($page)
    {

        $macro_localita_seo = "Riviera Romagnola";
        
        switch ($page->template) {
        case "listing":

            if ($page->listing_macrolocalita_id > 0 && $page->listing_localita_id == 0) {
                
                $macroloc = Macrolocalita::find($page->listing_macrolocalita_id);
                $macro_localita_seo = $macroloc->nome;
                
            }
            
            if ($page->listing_macrolocalita_id > 0 && $page->listing_localita_id > 0) {
                
                $macroloc = Macrolocalita::find($page->listing_macrolocalita_id);
                $macro_localita_seo = $macroloc->nome;					
            
            }

            break;
        
        case "localita":

            if ($page->menu_macrolocalita_id > 0 && $page->menu_localita_id == 0) 
                {				
                $macroloc = Macrolocalita::find($page->menu_macrolocalita_id);
                $macro_localita_seo = $macroloc->nome;
                }
            
            if ($page->menu_localita_id > 0) 
                {
                $macroloc = Macrolocalita::find($page->menu_macrolocalita_id);	
                $macro_localita_seo = $macroloc->nome;
                }	

            break;
            
        }

        return $macro_localita_seo;

    }


    public static function getOggettoMail($camere = 1, $prefill=array(), $mobile = false) 
    {

        $oggetto_data = "Info-Alberghi.com - ";

        if ($mobile)
            $oggetto_data = "Info-Alberghi.com mobile - ";

        $oggetto_data .= "(";

        $oggetto_data .= Carbon::createFromFormat('d/m/Y', $prefill["rooms"][0]["checkin"])->format('j/n') . " - ";
        $oggetto_data .= Carbon::createFromFormat('d/m/Y', $prefill["rooms"][0]["checkout"])->format('j/n') . " - ";

        if ($prefill["rooms"][0]["flex_date"])
            $oggetto_data .= "flex - ";

        // $oggetto_data .= Utility::exposeTrattamentiOggettoMail()[$prefill["rooms"][0]["meal_plan"]][1] . " - ";

        $adulti = $prefill["rooms"][0]["adult"];
        $oggetto_data .= "$adulti Pax - ";

        $bambini = $prefill["rooms"][0]["children"];

        if ($bambini) {
            $eta = $prefill["rooms"][0]["age_children"];
            $oggetto_data .= "$bambini bimbi  - ";
        }

        if ($prefill["information"]) {

            $messaggio = substr($prefill["information"], 0, 20);

            if (strlen($prefill["information"]) > 20)
                $messaggio .= "...";

            $oggetto_data .= $messaggio;

        }

        $oggetto_data .= ")";

        if ($camere>1) {
            $oggetto = $oggetto_data . " ... Richiesta per $camere preventivi ";

        } else {
            $oggetto = $oggetto_data . " - Richiesta Preventivo";
        }

        return $oggetto;

    }

    public static function postData($dati_json)
        {
        try 
            {

            $dati_json["username"] = 'infoalberghi';
            $dati_json["password"] = 'a4jkh10ERewaf3';
            $dati_json = json_encode($dati_json);
            $url = 'http://api.iperbooking.net/custom/infoalberghi.cfm';//create a new cURL resource
            $ch = curl_init($url);//setup request to send json via POST
            
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dati_json);//set the content type to application/json
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));//return response instead of outputting
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);//execute the POST request
            $result = curl_exec($ch);//close cURL resource
            if ($result != "OK") 
                {
                config('app.debug_log') ? Log::emergency("\n".'---> Errore RISPOSTA postData iperbooking: ---'."\n\n") : "";
                }
            curl_close($ch);
            
            return $result;
            
            } 
        catch (\Exception $e) 
            {
                config('app.debug_log') ? Log::emergency("\n".'---> Errore postData iperbooking: --- '.$e->getMessage().' <---'."\n\n") : "";
            }

        }



    public static function _getYears()
    {
        $current_year = Carbon::now()->year;
        return array($current_year-1,$current_year,$current_year+1,$current_year+2);
    }


    public static function _getMesi($anni = [])
    {
        $mesi = [];
        foreach ($anni as $anno) 
            {
            unset($mesi_anno);
            foreach (self::$mesi as $count => $mese) 
                {
                $mesi_anno[$count .'-'.$anno] = $mese;	
                }
                $mesi[$anno] = $mesi_anno;
        }	

        return $mesi;

    }



 public static function conta($source)
     {
    if(is_array($source)) 
        {
            return count($source);
        }
    else
        {
        return $source->count(); 
        }
   }
   


   /**
     * Fake hotel
   * hotel 4 stelle che devono apparire come 4 stelle SUP
     */

    private static $fakeHotel = [
    '1583' => '1583',
    '1506' => '1506',
  ];

  
  
  
  public static function fakeHotel()
    {
        return self::$fakeHotel;
    }


    /**
     * Gli hotel chiusiTemp RICEVON MAIL SOLO PER DATA DI ARRIVO > 30/09/2020
     */
    public static function getArrvioSeChiuso()
        {
        return Carbon::createFromDate(2020, 9, 30);
        }

    public static function getFirstPlaceHolder($placeholders) 
    {	
        //dd($placeholders);
        if(!is_null($placeholders))
          {
          foreach($placeholders as $key=> $placeholder):

              if (!is_null($placeholder) && $placeholder["nome"] !== NULL && trim($placeholder["nome"])!= "")
                  return "(traduzione mancante) - (" . strtoupper($key) . ": " . $placeholder["nome"] . ")";
          endforeach;
          }

        return "Inserire la descrizione del servizio";
    }



    public static function optionsCancellazioneGratuita() { return self::$options_cancellazione_gratuita; }

   
    private static function validateDate($date)
    {
       
        try {

            if (is_numeric($date) || $date == "")
                return $date;

            if (strlen($date) > 6 )
                $format = "d-m-Y";
            else
                $format = "H:i";

            $carbonInstance = Carbon::parse($date)->format($format);

            return $carbonInstance;

        } catch (\Exception $e) {

            return $date;

        }
    }

    public static function revisionData ($param, $label, $values = [], $current, $revisions) {

        $changes = [];
        $colors = ["#ffe8e7", "#ffe8e7"];
        $t = 0;
        $c = 0;

        $precedent = "";
        foreach( $revisions as $revision):

            /** prendo i dati della revisione */
            $revision_data = json_decode( $revision->data);
            
            /** Se i dato corrente è diverso da quello della revisione */
            if (   
                $param != "cir" &&
                $param != "slug_it" &&
                $param != "slug_en" &&
                $param != "slug_fr" &&
                $param != "slug_de" &&
                $param != "notewa_it" &&
                $param != "notewa_en" &&
                $param != "notewa_fr" &&
                $param != "notewa_de" &&
                $param != "cell" &&
                $param != "telefono" &&
                $param != "fax" &&
                $param != "telefoni_mobile_call" &&
                $param != "whatsapp" &&
                $param != "email" &&
                $param != "email_multipla" &&
                $param != "email_risponditori" &&
                $param != "email_secondaria" && 
                $param != "note_organizzazione_matrimoni" &&
                $param != "altra_lingua_it" &&
                $param != "altra_lingua_en" &&
                $param != "altra_lingua_fr" &&
                $param != "altra_lingua_de" &&
                $param != "aperto_altro_it" &&
                $param != "aperto_altro_en" &&
                $param != "aperto_altro_fr" &&
                $param != "aperto_altro_de" &&
                $param != "note_contanti_it" &&
                $param != "note_contanti_en" &&
                $param != "note_contanti_fr" &&
                $param != "note_contanti_de" &&
                $param != "note_assegno_it" &&
                $param != "note_assegno_en" &&
                $param != "note_assegno_fr" &&
                $param != "note_assegno_de" &&
                $param != "note_carta_credito_it" &&
                $param != "note_carta_credito_en" &&
                $param != "note_carta_credito_fr" &&
                $param != "note_carta_credito_de" &&
                $param != "note_bonifico_it" &&
                $param != "note_bonifico_en" &&
                $param != "note_bonifico_fr" &&
                $param != "note_bonifico_de" &&
                $param != "note_paypal_it" &&
                $param != "note_paypal_en" &&
                $param != "note_paypal_fr" &&
                $param != "note_paypal_de" &&
                $param != "note_bancomat_it" &&
                $param != "note_bancomat_en" &&
                $param != "note_bancomat_fr" &&
                $param != "note_bancomat_de" &&
                $param != "note_altro_pagamento_it" &&
                $param != "note_altro_pagamento_en" &&
                $param != "note_altro_pagamento_fr" &&
                $param != "note_altro_pagamento_de"
                
                ) {
                    $revision_data->{$param} == "" ? $revision_data->{$param} = 0 : '&quote;&quote;';
                }

                $text_current = $current[$param];
                $text_revision = $revision_data->{$param};

                if (   
                    $param != "cell" &&
                    $param != "telefono" &&
                    $param != "fax" &&
                    $param != "telefoni_mobile_call" &&
                    $param != "whatsapp") 
                {
                    $text_current = Self::validateDate($text_current);
                    $text_revision = Self::validateDate($text_revision);
                }
            
            if ($text_current != $text_revision) {

                /** Scrivo il log */
                $change1 = "<tr style='background:" . $colors[$t] . "; border-bottom:10px solid white'><th></th>";
                
                if (empty($values)) {
                    $change1 .= "<td><small>" . $text_revision  . "</small></td>";
                } else {
                    $chiave = $revision_data->{$param} == "" ? 0 : $revision_data->{$param};
                    $change1 .= "<td><small>" . $values[$chiave] . "</small></td>";
                }

                $change2 = "<td><small><b>" . Carbon::parse($revision["created_at"])->format("j F Y H:i:s"). "</b> | <b>" . $revision->editors->name . "</b></small></td></tr>";

                if ($precedent != $change1) {
                    $changes[] = $change1 . $change2;
                    $precedent = $change1;
                }

                $t == 0 ? $t = 1: $t = 0;
                $c++;

                if ($c == 3)
                    break;

            }
        endforeach;
        
        !empty($changes) ? $color = "#eafbe8" : $color ="#fff";

        $row = '<tr style="background: ' . $color . '">
                    <th  style="width:250px;">' . $label . '</th>';
        
        if (empty($values)) {

            $row .= '<td colspan="2">' .  $text_current . '</td></tr>' . implode("", $changes);
        
        } else {
            $chiave = $current[$param] == "" ? 0 : $current[$param];
            $row .= '<td colspan="2">' . $values[$chiave] . ' ' .'</td></tr>' . implode("", $changes);
        }
        
        return $row;
      
    }

} // end Class Utility



?>
