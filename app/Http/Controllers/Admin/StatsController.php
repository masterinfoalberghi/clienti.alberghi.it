<?php

/**
 * StatsController
 *
 * @author Info Alberghi Srl
 *
 */

namespace App\Http\Controllers\Admin;

use DB;
use Auth;
use Input;
use Response;
use App\Hotel;
use App\Utility;
use Carbon\Carbon;
use App\MailScheda;
use App\MailMultipla;
use Illuminate\Http\Request;
use SessionResponseMessages;
use App\Http\Controllers\Controller;

class StatsController extends Controller
{


    /* ------------------------------------------------------------------------------------
	 * METODI PUBBLICI ( VIEWS )
	 * ------------------------------------------------------------------------------------ */



    /**
     * Vista prinmcipale
     * 
     * @access public
     * @return view
     */

    public function index()
    {

        $groups = [];
        $base_link = ['nome' => '', 'route' => '', 'desc' => '', 'color' => '', 'icon' => ''];

        /**
         * Gruppo Hotel
         */

        $links = [];
        $links[] = ['nome' => 'Generali Hotel ', 'route' => 'admin/stats/hotels', 'desc' => 'Media visite, mail, telefonate, condivisioni whastapp per categoria / località', 'icon' => 'stats'];

        if (Auth::user()->hasRole(["admin", "operatore"])) :
            $links[] = ['nome' => 'Mail Schede Giornaliere totali', 'route' => 'admin/stats/stats-IA', 'desc' => 'Statistiche mail schede giornaliere totali', 'icon' => 'envelope'];
        endif;

        $links[] = ['nome' => 'Mail Scheda Località/Zone', 'route' => 'admin/stats/mail_scheda_localitazone', 'desc' => 'Statistiche mail schede totali annuali per Località/Zone', 'icon' => 'envelope'];
        $groups[] = ['nome' => 'Statistiche generali', 'links' => $links];

        $links = [];
        $links[] = ['nome' => 'Dettaglio Hotel', 'route' => 'admin/stats/hotel', 'desc' => 'Statistiche accessi alla scheda', 'icon' => 'stats'];
        $links[] = ['nome' => 'Link al sito ', 'route' => 'admin/stats/outbound-links', 'desc' => "Statistiche accessi dalla scheda IA al sito dell'hotel", 'icon' => 'link'];
        $links[] = ['nome' => 'Telefonate ', 'route' => 'admin/stats/hotelCalls', 'desc' => "Statistiche chiamate all'hotel dalla scheda mobile", 'icon' => 'phone'];
        $links[] = ['nome' => 'Messaggi Whatsapp ', 'route' => 'admin/stats/whatsapp', 'desc' => "Statistiche dei contatti su whatsapp", "icon" => "envelope"];
        $links[] = ['nome' => 'Condivisioni ', 'route' => 'admin/stats/share', 'desc' => "Statistiche delle condivisioni delle pagine su whatsapp", "icon" => "share-alt"];
        $links[] = ['nome' => 'Rating ', 'route' => 'admin/stats/hotels/rating', 'desc' => "Statistiche sull'andamento della reputazione online <sup style='color:#ff0000;'>NEW</sup>", "icon" => "star"];
        $groups[] = ['nome' => 'Hotel', 'links' => $links];

        $links = [];
        $links[] = ['nome' => 'Mail Scheda/Mobile ', 'route' => 'admin/stats/mail_scheda', 'desc' => 'Statistiche mail dirette da scheda', 'icon' => 'envelope'];
        $links[] = ['nome' => 'Mail Multiple/Wishlist/Mobile ', 'route' => 'admin/stats/mail_multiple', 'desc' => 'Statistiche mail multiple', 'icon' => 'envelope'];
        //$links[] = ['nome' => 'Mail Multiple/Wishlist/Mobile Giornaliere', 'route' => 'admin/stats/mail_multiple_giornaliere', 'desc' => 'Statistiche mail multiple giornaliere/wishlist/mobile'] + $base_link;	 
        $groups[] = ['nome' => 'Mail', 'links' => $links];

        /**
         * Vetrine
         */

        $links = [];
        /*$links[] = ['nome' => 'Vetrine fino al 28/01/2016', 'route' => 'admin/stats/vetrine/pre-laravel-era', 'desc' => 'Statistiche vetrine sito vecchio'] + $base_link;*/
        $links[] = ['nome' => 'Vetrine ', 'route' => 'admin/stats/vetrine/laravel-era', 'desc' => 'Statistiche vetrine sulle macrololcalitè ( es: rimini.php )', 'icon' => 'map-marker'];
        $groups[] = ['nome' => 'Vetrine', 'links' => $links];

        /**
         * Vetrine Top
         */

        $links = [];
        $links[] = ['nome' => 'Evidenze Offerte', 'route' => 'admin/stats/vetrine/vot', 'desc' => 'Statistiche evidenze sulle Offerte', 'icon' => 'heart'];
        $links[] = ['nome' => 'Evidenze Servizi', 'route' => 'admin/stats/vetrine/vst', 'desc' => 'Statistiche evidenze sui Servizi', 'icon' => 'heart'];
        $links[] = ['nome' => 'Evidenze Trattamenti', 'route' => 'admin/stats/vetrine/vtt', 'desc' => 'Statistiche evidenze sui Trattamenti', 'icon' => 'heart'];
        $links[] = ['nome' => 'Evidenze Bambini Gratis', 'route' => 'admin/stats/vetrine/vaat', 'desc' => 'Statistiche evidenze sui Bambini Gratis ', 'icon' => 'heart'];
        $groups[] = ['nome' => 'Evidenze <sup>Top</sup>', 'links' => $links];

        /**
         * Vetrine Top Simple
         */

        $links = [];
        $links[] = ['nome' => 'Evidenze Offerte Simple', 'route' => 'admin/stats/vetrine/votSimple', 'desc' => 'Statistiche evidenze sulle Offerte', 'icon' => 'star-empty'];
        $links[] = ['nome' => 'Evidenze Servizi Simple', 'route' => 'admin/stats/vetrine/vstSimple', 'desc' => 'Statistiche evidenze sui Servizi', 'icon' => 'star-empty'];
        $links[] = ['nome' => 'Evidenze Trattamenti Simple', 'route' => 'admin/stats/vetrine/vttSimple', 'desc' => 'Statistiche evidenze sui Servizi', 'icon' => 'star-empty'];
        $links[] = ['nome' => 'Evidenze Bambini Gratis Simple ', 'route' => 'admin/stats/vetrine/vaatSimple', 'desc' => 'Statistiche evidenze sui Bambini Gratis', 'icon' => 'star-empty'];
        $groups[] = ['nome' => 'Evidenze <b>Simple</b> <sup>Top</sup>', 'links' => $links];

        /**
         * Vetrine Top Ricerca per pagina
         */


        $links = [];
        $links[] = ['nome' => 'Evidenze Offerte Simple per pagina', 'route' => 'admin/stats/vetrine/votPage', 'desc' => 'Statistiche evidenze sulle Offerte', 'icon' => 'list-alt'];
        $links[] = ['nome' => 'Evidenze Servizi Simple per pagina ', 'route' => 'admin/stats/vetrine/vstPage', 'desc' => 'Statistiche evidenze sui Servizi', 'icon' => 'list-alt'];
        $links[] = ['nome' => 'Evidenze Trattamenti Simple per pagina', 'route' => 'admin/stats/vetrine/vttPage', 'desc' => 'Statistiche evidenze sui Trattamenti', 'icon' => 'list-alt'];
        $links[] = ['nome' => 'Evidenze Bambini Gratis Simple per pagina', 'route' => 'admin/stats/vetrine/vaatPage', 'desc' => 'Statistiche evidenze sui Bambini Gratis', 'icon' => 'list-alt'];
        $groups[] = ['nome' => 'Evidenze <b>Simple per pagina</b>  <sup>Top</sup>', 'links' => $links];

        return view('admin.stats', compact('groups'));
    }


    /**
     * View share.
     * 
     * @access public
     * @return void
     */

    public function share(Request $request)
    {

        /**
         * Prendo i dati in POST
         */

        $filtro_request = $request->input("f");
        $where_raw = "";

        if (!$filtro_request) $filtro_request = "";

        /**
         * Faccio la query con i parmetri
         */

        $share_data = DB::table("tblStatsHotelShareRead")
            ->select("*")
            ->where("uri", "<>", "");

        if (isset($filtro_request) && $filtro_request != "")
            $share_data = $share_data->whereRaw(" uri LIKE '%" . $filtro_request . "%' ");

        $share_data = $share_data->orderBy("count", "DESC")->get();

        return view('admin.share', compact('share_data', 'filtro_request'));
    }


    protected static function getMesi()
    {
        return [1 => "Gen", "Feb", "Mar", "Apr", "Mag", "Giu", "Lug", "Ago", "Set", "Ott", "Nov", "Dic"];
    }

    public static function statsIA(Request $request)
    {
        $stats_dirette = [];
        $stats_multiple = [];
        $email_dirette_totali_this_year = [];
        $email_dirette_totali_last_year = [];
        $email_multiple_totali_this_year = [];
        $email_multiple_totali_last_year = [];
        return view('admin.stats-IA', compact('stats_dirette', 'stats_multiple', 'email_dirette_totali_this_year', 'email_dirette_totali_last_year', 'email_multiple_totali_this_year', 'email_multiple_totali_last_year'));
    }


    public static function statsIAResult(Request $request)
    {

        $stats = [];
        $mesi = self::getMesi();
        $tipologie = array("normale", "mobile", "doppie", "doppie-parziali", "wishlist");

        if (!empty($request->input('date_range'))) {

            $date_range         = explode(" - ", $request->input('date_range'));
            $date_from             = Carbon::createFromFormat("d/m/Y", $date_range[0]);
            $date_to             = Carbon::createFromFormat("d/m/Y", $date_range[1]);
            $date_last_from     = Carbon::createFromFormat("d/m/Y", $date_range[0]);
            $date_to_from         = Carbon::createFromFormat("d/m/Y", $date_range[1]);
            $date_from_2         = Carbon::createFromFormat("d/m/Y", $date_range[0]);
            $date_to_2            = Carbon::createFromFormat("d/m/Y", $date_range[1]);

            $date_last_from = $date_last_from->subYears(1)->toDateString();
            $date_to_from = $date_to_from->subYears(1)->toDateString();

            $date_from_js = $date_from->format('d/m/Y');
            $date_to_js = $date_to->format('d/m/Y');

            $date_from = $date_from->toDateString();
            $date_to = $date_to->toDateString();
        } else {

            SessionResponseMessages::add('error', 'Entrambe le date devono essere compilate');
            return SessionResponseMessages::redirect($request->getUri(), $request);
        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // se il rabge è anno corrente oppure anno passato, oppure anno -2 vado con la select sul campo YEAR altrimenti ho errore OUT OF MEMORY //
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $query_for_year = "";
        $current_year = date('Y');
        foreach ([$current_year - 2, $current_year - 1, $current_year] as $y) {
            if ($date_from == Carbon::create($y, 01, 01)->toDateString() && $date_to == Carbon::create($y, 12, 31)->toDateString()) {
                $query_for_year = $y;
                break;
            }
        }




        if (!empty($query_for_year)) {
            $email_dirette_this_year = DB::table("tblMailSchedaRead")
                ->selectRaw('anno,mese,giorno,tipologia,hotel_id,conteggio')
                ->where('anno', $query_for_year);

            $email_dirette_last_year = DB::table("tblMailSchedaRead")
                ->selectRaw('anno,mese,giorno,tipologia,hotel_id,conteggio')
                ->where('anno', $query_for_year - 1);
        } else {
            $email_dirette_this_year = DB::table("tblMailSchedaRead")
                ->selectRaw('anno,mese,giorno,tipologia,hotel_id,conteggio')
                ->whereRaw('DATE(CONCAT(anno,"-",mese,"-",giorno)) >=  "' . $date_from . '"')
                ->whereRaw('DATE(CONCAT(anno,"-",mese,"-",giorno)) <=  "' . $date_to . '"');

            $email_dirette_last_year = DB::table("tblMailSchedaRead")
                ->selectRaw('anno,mese,giorno,tipologia,hotel_id,conteggio')
                ->whereRaw('DATE(CONCAT(anno,"-",mese,"-",giorno)) >=  "' . $date_last_from . '"')
                ->whereRaw('DATE(CONCAT(anno,"-",mese,"-",giorno)) <=  "' . $date_to_from . '"');
        }


        $email_dirette_totali = [];
        $email_dirette_this_year_divise = [];
        $email_dirette_last_year_divise = [];

        $n_rows_this_year = $email_dirette_this_year->count();
        $rows_per_chunk = 10000;

        for ($i = 0; $i < $n_rows_this_year; $i += $rows_per_chunk) {

            $data = $email_dirette_this_year
                ->skip($i)
                ->take($rows_per_chunk)
                ->get();

            foreach ($data as $email) :

                $anno = $email->anno;
                $mese = $email->mese;
                $giorno = $email->giorno;

                if (!isset($email_dirette_this_year_divise[$anno][$mese][$giorno][$email->tipologia]))
                    $email_dirette_this_year_divise[$anno][$mese][$giorno][$email->tipologia] = 0;

                $email_dirette_this_year_divise[$anno][$mese][$giorno][$email->tipologia] += $email->conteggio;

            endforeach;
        }


        $n_rows_last_year = $email_dirette_last_year->count();
        $rows_per_chunk = 10000;

        for ($i = 0; $i < $n_rows_last_year; $i += $rows_per_chunk) {

            $data = $email_dirette_last_year
                ->skip($i)
                ->take($rows_per_chunk)
                ->get();

            foreach ($data as $email) :

                $anno = $email->anno;
                $mese = $email->mese;
                $giorno = $email->giorno;

                if ($email->tipologia == "doppia parziale")
                    $email->tipologia = "doppia-parziale";

                if (!isset($email_dirette_last_year_divise[$anno][$mese][$giorno][$email->tipologia]))
                    $email_dirette_last_year_divise[$anno][$mese][$giorno][$email->tipologia] = 0;

                $email_dirette_last_year_divise[$anno][$mese][$giorno][$email->tipologia] += $email->conteggio;

            endforeach;
        }



        // -- Multiple -- //


        if (!empty($query_for_year)) {
            $email_multiple_this_year = DB::table("tblStatsMailMultipleRead")
                ->selectRaw('anno,mese,giorno,tipologia,hotel_id,conteggio')
                ->where('anno', $query_for_year);

            $email_multiple_last_year = DB::table("tblStatsMailMultipleRead")
                ->selectRaw('anno,mese,giorno,tipologia,hotel_id,conteggio')
                ->where('anno', $query_for_year - 1);
        } else {
            $email_multiple_this_year = DB::table("tblStatsMailMultipleRead")
                ->selectRaw('anno,mese,giorno,tipologia,hotel_id,conteggio')
                ->whereRaw('DATE(CONCAT(anno,"-",mese,"-",giorno)) >=  "' . $date_from . '"')
                ->whereRaw('DATE(CONCAT(anno,"-",mese,"-",giorno)) <=  "' . $date_to . '"');

            $email_multiple_last_year = DB::table("tblStatsMailMultipleRead")
                ->selectRaw('anno,mese,giorno,tipologia,hotel_id,conteggio')
                ->whereRaw('DATE(CONCAT(anno,"-",mese,"-",giorno)) >=  "' . $date_last_from . '"')
                ->whereRaw('DATE(CONCAT(anno,"-",mese,"-",giorno)) <=  "' . $date_to_from . '"');
        }



        $email_multiple_this_year_divise = [];
        $email_multiple_last_year_divise = [];



        $n_rows_this_year = $email_multiple_this_year->count();
        $rows_per_chunk = 10000;

        for ($i = 0; $i < $n_rows_this_year; $i += $rows_per_chunk) {

            $data = $email_multiple_this_year
                ->skip($i)
                ->take($rows_per_chunk)
                ->get();

            foreach ($data as $email) :

                $anno = $email->anno;
                $mese = $email->mese;
                $giorno = $email->giorno;

                if (!isset($email_multiple_this_year_divise[$anno][$mese][$giorno][$email->tipologia]))
                    $email_multiple_this_year_divise[$anno][$mese][$giorno][$email->tipologia] = 0;

                $email_multiple_this_year_divise[$anno][$mese][$giorno][$email->tipologia] +=  $email->conteggio;

            endforeach;
        }



        $n_rows_last_year = $email_multiple_last_year->count();
        $rows_per_chunk = 10000;

        for ($i = 0; $i < $n_rows_last_year; $i += $rows_per_chunk) {

            $data = $email_multiple_last_year
                ->skip($i)
                ->take($rows_per_chunk)
                ->get();


            foreach ($data as $email) :

                $anno = $email->anno;
                $mese = $email->mese;
                $giorno = $email->giorno;

                if ($email->tipologia == "doppia par")
                    $email->tipologia = "doppia";

                if (!isset($email_multiple_last_year_divise[$anno][$mese][$giorno][$email->tipologia]))
                    $email_multiple_last_year_divise[$anno][$mese][$giorno][$email->tipologia] = 0;

                $email_multiple_last_year_divise[$anno][$mese][$giorno][$email->tipologia] += $email->conteggio;

            endforeach;
        }



        $stats_multiple = array($email_multiple_this_year_divise, $email_multiple_last_year_divise);

        // ---- riferimenti

        $email_dirette_totali_this_year = [];
        $email_dirette_totali_last_year = [];

        $email_multiple_totali_this_year = [];
        $email_multiple_totali_last_year = [];

        for ($i = $date_from_2; $i <= $date_to_2; $i->addDay(1)) {

            $anno = $i->format("Y");
            $mese = $i->format("m");
            $giorno = $i->format("d");

            foreach ($tipologie as $tipologia) :

                if (!isset($email_dirette_this_year_divise[$anno][$mese][$giorno]))
                    $email_dirette_this_year_divise[$anno][$mese][$giorno] = [];

                if (!isset($email_dirette_last_year_divise[$anno][$mese][$giorno]))
                    $email_dirette_last_year_divise[$anno][$mese][$giorno] = [];

                if (!isset($email_multiple_this_year_divise[$anno][$mese][$giorno]))
                    $email_multiple_this_year_divise[$anno][$mese][$giorno] = [];

                if (!isset($email_multiple_last_year_divise[$anno][$mese][$giorno]))
                    $email_multiple_last_year_divise[$anno][$mese][$giorno] = [];

                ksort($email_dirette_this_year_divise[$anno]);
                ksort($email_dirette_last_year_divise[$anno]);
                ksort($email_multiple_this_year_divise[$anno]);
                ksort($email_multiple_last_year_divise[$anno]);

                ksort($email_dirette_this_year_divise[$anno][$mese]);
                ksort($email_dirette_last_year_divise[$anno][$mese]);
                ksort($email_multiple_this_year_divise[$anno][$mese]);
                ksort($email_multiple_last_year_divise[$anno][$mese]);

            endforeach;



            if (!isset($email_dirette_totali_this_year[$anno][$mese][$giorno]))
                $email_dirette_totali_this_year[$anno][$mese][$giorno] = 0;

            if (!isset($email_dirette_totali_last_year[(int)($anno - 1)][$mese][$giorno]))
                $email_dirette_totali_last_year[(int)($anno - 1)][$mese][$giorno] = 0;

            if (isset($email_dirette_this_year_divise[$anno][$mese][$giorno]))
                foreach ($email_dirette_this_year_divise[$anno][$mese][$giorno] as $totale)
                    $email_dirette_totali_this_year[$anno][$mese][$giorno] += (int)$totale;

            if (isset($email_dirette_last_year_divise[(int)($anno - 1)][$mese][$giorno]))
                foreach ($email_dirette_last_year_divise[(int)($anno - 1)][$mese][$giorno] as $totale)
                    $email_dirette_totali_last_year[(int)($anno - 1)][$mese][$giorno] += (int)$totale;

            if (!isset($email_multiple_totali_this_year[$anno][$mese][$giorno]))
                $email_multiple_totali_this_year[$anno][$mese][$giorno] = 0;

            if (!isset($email_multiple_totali_last_year[(int)($anno - 1)][$mese][$giorno]))
                $email_multiple_totali_last_year[(int)($anno - 1)][$mese][$giorno] = 0;

            if (isset($email_multiple_this_year_divise[$anno][$mese][$giorno]))
                foreach ($email_multiple_this_year_divise[$anno][$mese][$giorno] as $totale)
                    $email_multiple_totali_this_year[$anno][$mese][$giorno] += (int)$totale;

            if (isset($email_multiple_last_year_divise[(int)($anno - 1)][$mese][$giorno]))
                foreach ($email_multiple_last_year_divise[(int)($anno - 1)][$mese][$giorno] as $totale)
                    $email_multiple_totali_last_year[(int)($anno - 1)][$mese][$giorno] += (int)$totale;
        }

        $stats_dirette = array($email_dirette_this_year_divise, $email_dirette_last_year_divise);
        $stats_multiple = array($email_multiple_this_year_divise, $email_multiple_last_year_divise);

        return view(
            'admin.stats-IA',
            compact(
                'stats_dirette',
                'stats_multiple',
                'email_dirette_totali_this_year',
                'email_dirette_totali_last_year',
                'email_multiple_totali_this_year',
                'email_multiple_totali_last_year',
                'mesi',
                'date_from_js',
            )
        );
    }


    /**
     * Selezione hotel e data
     * 
     * @access public
     * @return void
     */

    public function rapportoMail()
    {
        $listaEmail = [];
        $listaTelefonate = [];
        $rapporto = 0;
        $tipocontatto = "";

        return view('admin.rapporto-mail', compact("tipocontatto", "rapporto", "listaEmail",  "listaTelefonate"));
    }


    /**
     * Mostra le email ricevute da un hotel in un periodo
     * 
     * @access public
     * @param Request $request
     * @return view
     */

    public function rapportoMailResult(Request $request)
    {

        $listaEmail = [];
        $listaTelefonate = [];
        $request->flash();
        $rapporto = 1;
        $today = Carbon::now()->format("Y-m-d");
        $hotel_id = (int)$request->input("hotel");
        $tipocontatto = $request->input("tipocontatto");

        if (!$hotel_id) {
            SessionResponseMessages::add('error', 'Devi selezionare almeno un hotel');
            return SessionResponseMessages::redirect($request->getUri(), $request);
        }

        if (!empty($request->input('date_range'))) {

            $date_range = explode(" - ", $request->input('date_range'));
            $date_from = Carbon::createFromFormat("d/m/Y", $date_range[0]);
            $date_to = Carbon::createFromFormat("d/m/Y", $date_range[1]);
        } else {

            SessionResponseMessages::add('error', 'Entrambe le date devono essere compilate');
            return SessionResponseMessages::redirect($request->getUri(), $request);
        }

        /*
		 * Email dirette
		 */

        $listaEmail["oggi"] = [];
        $listaEmail["archivio"] = [];
        $listaEmailMultiple["oggi"] = [];
        $listaEmailMultiple["archivio"] = [];
        $listaTelefonate["oggi"] = [];
        $listaTelefonate["archivio"] = [];
        $date_from = $date_from->format("Y-m-d");
        $date_to = $date_to->format("Y-m-d");

        if ($tipocontatto == "dirette") {

            if (($today > $date_from && $today < $date_to) || $today == $date_from || $today == $date_to) {

                $listaEmail["oggi"] = MailScheda::with(["camereAggiuntive"])
                    ->where("hotel_id", $hotel_id)
                    ->orderBy("data_invio", "desc")
                    ->get();
            }

            $listaEmail["archivio"] = DB::connection("archive")
                ->table("tblMailSchedaArchive")
                ->where("hotel_id", $hotel_id)
                ->where("data_invio", ">=", $date_from)
                ->where("data_invio", "<=", $date_to)
                ->orderBy("data_invio", "desc")
                ->get();
        }

        if ($tipocontatto == "multiple") {

            if (($today > $date_from && $today < $date_to) || $today == $date_from || $today == $date_to) {

                $listaEmailMultiple["oggi"] = MailMultipla::with(["clienti", "camereAggiuntive"])
                    ->whereHas('clienti', function ($q) use ($hotel_id) {
                        $q->where('tblHotel.id',  $hotel_id);
                    })
                    ->orderBy("data_invio", "desc")
                    ->get();

                //	dd($listaEmailMultiple["oggi"]);

            }

            $listaEmailMultiple["archivio"] = DB::connection("archive")->table("tblStatsMailMultipleArchive")
                ->where("hotel_id", $hotel_id)
                ->where("data_invio", ">=", $date_from)
                ->where("data_invio", "<=", $date_to)
                ->orderBy("data_invio", "desc")
                ->get();
        }

        if ($tipocontatto == "chiamate") {

            //tblStatsHotelCallRead

            if (($today > $date_from && $today < $date_to) || $today == $date_from || $today == $date_to) {

                $listaTelefonate["oggi"] = DB::table("tblStatsHotelCall")
                    ->where("hotel_id", $hotel_id)
                    ->orderBy("created_at", "desc")
                    ->get();
            }

            $listaTelefonate["archivio"] = DB::connection("archive")->table("tblStatsHotelCallArchive")
                ->where("hotel_id", $hotel_id)
                ->where("created_at", ">=", $date_from)
                ->where("created_at", "<=", $date_to)
                ->orderBy("created_at", "desc")
                ->get();
        }

        $request->session()->put('listaEmail', $listaEmail);
        $request->session()->put('listaEmailMultiple', $listaEmailMultiple);

        return view('admin.rapporto-mail', compact("tipocontatto", "rapporto", "date_range", "listaEmail", "listaEmailMultiple", "listaTelefonate"));
    }


    private function _fill_oggi($oggi, $fields, $trattamenti_arr, &$tabella)
    {

        foreach ($oggi as $elem_arr) {
            foreach ($fields as $f) {
                if ($f == 'camere') {
                    $multicamera = [];

                    if (count(explode(',', $elem_arr['trattamento'])) > 1) {
                        $meal_plan_arr = explode(',', $elem_arr['trattamento']);
                        $trattamenti = [];
                        foreach ($meal_plan_arr as $meal_plan) {
                            $trattamenti[] = $trattamenti_arr[$meal_plan];
                        }
                        $multicamera_str = 'camera 1: dal ' . date("d/m/Y", strtotime($elem_arr['arrivo'])) . ' al ' . date("d/m/Y", strtotime($elem_arr['partenza'])) . ' in ' . implode(',', $trattamenti) . ' adulti:' . $elem_arr['adulti'] . ' adulti:' . $elem_arr['adulti'];
                    } else {
                        $multicamera_str = 'camera 1: dal ' . date("d/m/Y", strtotime($elem_arr['arrivo'])) . ' al ' . date("d/m/Y", strtotime($elem_arr['partenza'])) . ' in ' . $trattamenti_arr[$elem_arr['trattamento']] . ' adulti:' . $elem_arr['adulti'] . ' adulti:' . $elem_arr['adulti'];
                    }

                    if ($elem_arr['bambini'] > 0) {
                        $multicamera_str .= ' bambini: ' . $elem_arr['bambini'] . ' (' . $elem_arr['eta_bambini'] . ')';
                    }

                    if ($elem_arr['date_flessibili']) {
                        $date_flessibili = "'Sì";
                    } else {
                        $date_flessibili = "'No";
                    }

                    $multicamera_str .= ' date flessibili ' . $date_flessibili;
                    $multicamera[] = $multicamera_str;

                    if (count($elem_arr['camere_aggiuntive'])) {
                        foreach ($elem_arr['camere_aggiuntive'] as $key => $camera) {

                            $num = $key + 2;
                            $multicamera_str = 'camera ' . $num . ': dal ' . $camera['arrivo'] . ' al ' . $camera['partenza'];
                            if (count(explode(',', $camera['trattamento'])) > 1) {

                                $meal_plan_arr = explode(',', $camera['trattamento']);
                                $trattamenti = [];
                                foreach ($meal_plan_arr as $meal_plan) {
                                    $trattamenti[] = $trattamenti_arr[$meal_plan];
                                }
                                $multicamera_str .= ' in ' . implode(',', $trattamenti) . ' adulti:' . $camera['adulti'];

                            } else {
                                $multicamera_str .= ' in ' . $trattamenti_arr[$camera['trattamento']] . ' adulti:' . $camera['adulti'];
                            }

                            if ($camera['bambini'] > 0) {
                                $multicamera_str .= ' bambini: ' . $camera['bambini'] . ' (' . $camera['eta_bambini'] . ')';
                            }

                            if ($camera['date_flessibili']) {
                                $date_flessibili = "'Sì";
                            } else {
                                $date_flessibili = "'No";
                            }

                            $multicamera_str .= ' date flessibili ' . $date_flessibili;
                            $multicamera[] =  $multicamera_str;

                        }
                    }
                    $row[$f] = implode("\n", $multicamera);

                } elseif ($f == 'data_invio') {

                    $row[$f] =     date("d/m/Y H:i:s", strtotime($elem_arr[$f]));

                } else {

                    if (array_key_exists($f, $elem_arr)) {
                        $row[$f] = $elem_arr[$f];
                    } else {
                        $row[$f] = "";
                    }

                }
            }
            $tabella[] = $row;
        }
    }

    private function _fill_archvio($archivio, $fields, $trattamenti_arr, &$tabella)
    {

        foreach ($archivio as $elem) {

            foreach ($fields as $f) {

                if ($f == 'camere') {

                    try {

                        $camere_arr = json_decode($elem->camere);
                        $multicamera = [];

                        foreach ($camere_arr as $key => $camera) {

                            $num = $key + 1;
                            $multicamera_str = 'camera ' . $num . ': dal ' . date("d/m/Y", strtotime(optional($camera)->arrivo)) . ' al ' . date("d/m/Y", strtotime(optional($camera)->partenza));
                            $multicamera_str .= ' in ' . $trattamenti_arr[$camera->trattamento] . ' adulti:' . optional($camera)->adulti;

                            if ($camera->bambini > 0) {
                                $multicamera_str .= ' bambini: ' . optional($camera)->bambini . ' (' . optional($camera)->eta_bambini . ')';
                            }

                            if ($camera->date_flessibili) {
                                $date_flessibili = "'Sì";
                            } else {
                                $date_flessibili = "'No";
                            }

                            $multicamera_str .= ' date flessibili ' . $date_flessibili;

                            $multicamera[] =  $multicamera_str;
                        }
                    } catch (\Exception $e) {
                        $camere_arr = json_decode($elem->camere);

                        $multicamera = [];
                        foreach ($camere_arr as $key => $camera) {

                            $num = $key + 1;

                            $multicamera_str = 'camera ' . $num . ': dal ' . date("d/m/Y", strtotime(optional($camera)->checkin)) . ' al ' . date("d/m/Y", strtotime(optional($camera)->checkout));

                            //? ATTENZIONE $camera->meal_plan può essere separato da virgola

                            if (count(explode(',', $camera->meal_plan)) > 1) {

                                $meal_plan_arr = explode(',', $camera->meal_plan);
                                $trattamenti = [];
                                foreach ($meal_plan_arr as $meal_plan) {
                                    $trattamenti[] = $trattamenti_arr[$meal_plan];
                                }
                                $multicamera_str .= ' in ' . implode(',', $trattamenti) . ' adulti:' . optional($camera)->adult;
                            } else {

                                $multicamera_str .= ' in ' . $trattamenti_arr[$camera->meal_plan] . ' adulti:' . optional($camera)->adult;
                            }

                            if (isset($camera->children)) {
                                if (is_array($camera->children) && count($camera->children) > 0) {

                                    $multicamera_str .= ' bambini: ' . implode(',', $camera->children);
                                }
                            }

                            if ($camera->flex_date) {
                                $date_flessibili = "'Sì";
                            } else {
                                $date_flessibili = "'No";
                            }

                            $multicamera_str .= ' date flessibili ' . $date_flessibili;

                            $multicamera[] =  $multicamera_str;
                        }
                    }


                    $row[$f] = implode("\n", $multicamera);
                } elseif ($f == 'data_invio') {
                    $row[$f] =     date("d/m/Y H:i:s", strtotime($elem->$f));
                } else {

                    $row[$f] = $elem->$f;
                }
            }

            $tabella[] = $row;
        }
    }

    public function exportRapportoMailMultipla(Request $request)
    {
        
        $trattamenti_arr = Utility::getTrattamentiNomi();

        // $trattamenti_arr = [
        // "trattamento_ai" => "All inclusive",
        // "trattamento_pc" => "Full board",
        // "trattamento_mp" => "Half board",
        // "trattamento_bb" => "Bed & breakfast",
        // "trattamento_sd" => "Room only",
        // ];

        $listaEmailMultiple = $request->session()->get('listaEmailMultiple');
        $columns = array('Hotel ID', 'Nome', 'Email', 'Richiesta', 'Camere', 'Data invio');
        $fields = array('hotel_id', 'nome', 'email', 'richiesta', 'camere', 'data_invio');
        $tabella = [];

        /** Oggi */
        if (count($listaEmailMultiple['oggi'])) {
            $oggi = $listaEmailMultiple['oggi']->toArray();
            $this->_fill_oggi($oggi, $fields, $trattamenti_arr, $tabella);
        }

        /** Archivio */
        if (count($listaEmailMultiple['archivio'])) {
            $archivio = $listaEmailMultiple['archivio']->toArray();
            $this->_fill_archvio($archivio, $fields, $trattamenti_arr, $tabella);
        }

        $filename = storage_path("mail_multiple.csv");
        $handle = fopen($filename, 'w+');
        fputcsv($handle, $fields);
        foreach ($tabella as $row) {
            fputcsv($handle, $row);
        }
        fclose($handle);
        $headers = array(
            'Content-Type' => 'text/csv',
        );
        return response()->download($filename, "mail_multiple.csv", $headers);

    }



    public function exportRapportoMailDiretta(Request $request)
    {

        $trattamenti_arr = Utility::getTrattamentiNomi();

        // $trattamenti_arr = [
        // "trattamento_ai" => "All inclusive",
        // "trattamento_pc" => "Full board",
        // "trattamento_mp" => "Half board",
        // "trattamento_bb" => "Bed & breakfast",
        // "trattamento_sd" => "Room only",
        // ];

        $listaEmail = $request->session()->get('listaEmail');




        $columns = array('Hotel ID', 'Nome', 'Email', 'Telefono', 'Richiesta', 'Camere', 'Data invio');
        $fields = array('hotel_id', 'nome', 'email', 'telefono', 'richiesta', 'camere', 'data_invio');

        $tabella = [];


        //////////
        // OGGI //
        //////////
        if (count($listaEmail['oggi'])) {
            $oggi = $listaEmail['oggi']->toArray();
            $this->_fill_oggi($oggi, $fields, $trattamenti_arr, $tabella);
        }

        ///////////////
        // FINE OGGI //
        ///////////////




        //////////////
        // ARCHIVIO //
        //////////////
        if (count($listaEmail['archivio'])) {
            $archivio = $listaEmail['archivio']->toArray();
            $this->_fill_archvio($archivio, $fields, $trattamenti_arr, $tabella);
        }

        ///////////////////
        // FINE ARCHIVIO //
        ///////////////////



        //dd($oggi);
        //dd($tabella);

        $filename = storage_path("mail_dirette.csv");

        $handle = fopen($filename, 'w+');

        fputcsv($handle, $fields);

        foreach ($tabella as $row) {
            fputcsv($handle, $row);
        }

        fclose($handle);

        $headers = array(
            'Content-Type' => 'text/csv',
        );

        return response()->download($filename, "mail_dirette.csv", $headers);
    }
}
