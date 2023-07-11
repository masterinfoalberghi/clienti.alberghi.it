<?php

namespace App\Http\Controllers\Admin;

use DB;
use Auth;
use SessionResponseMessages;
use Carbon\Carbon;
use App\Categoria;
use App\Hotel;
use App\Localita;
use App\Macrolocalita;
use App\MailMultipleRead;
use App\MailSchedaRead;
use App\StatHotelRead;
use App\StatsHotelCallRead;
use App\StatsHotelWhatsappRead;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StatsHotelController extends AdminBaseController
{

    private static $anni = [];
    protected $lowest_year = 0;

    public function __construct()
    {
        for ($i = 0; $i < 4; $i++) {
            $anno = date('Y') + 1 - $i;
            self::$anni[] = $anno;
        }
    }

    /** -------------------------------------------------------- 
     *  PRIVATE
     * --------------------------------------------------------- */

    private function getMesi()
    {
        return [1 => "Gen", "Feb", "Mar", "Apr", "Mag", "Giu", "Lug", "Ago", "Set", "Ott", "Nov", "Dic"];
    }

    private function getAllMonth($with_zero = false)
    {
        $mesi = [];

        if ($with_zero) {
            $mesi[0] = 'Tutti';
        }

        $date = new Carbon();

        foreach (range(1, 12) as $monthNumber) {
            $date->setDate(2015, $monthNumber, 1);
            $mesi[$monthNumber] = ucfirst($date->formatLocalized("%B"));
        }
        return $mesi;
    }

    private function calculateGeneralStats($data_inizio, $data_fine, $categoria, $macrolocalita, $localita, $tipo = 'visite')
    {

        $data_inizio_dt = Carbon::createFromFormat("d/m/Y", $data_inizio);
        $data_fine_dt = Carbon::createFromFormat("d/m/Y", $data_fine);

        if ($data_fine_dt < $data_inizio_dt) {
            SessionResponseMessages::add('error', "La prima data deve essere maggiore della seconda.");
        }

        if (SessionResponseMessages::hasErrors()) {
            return false;
        }

        $ids = null;

        if ($categoria || $macrolocalita || $localita) {
            // Ignoro il fatto che la macrolocalità e la località mi vengano passate entrambe,
            // Se così succede al massimo non trovo risultati
            $q_hotel = Hotel::categoria($categoria)
                ->macrolocalita($macrolocalita)
                ->localita($localita);

            $ids = $q_hotel->pluck("id");

        }

        switch ($tipo) {
            case 'visite':
                $totalStats[$tipo] = $this->_getStatsForVisite($data_inizio, $data_inizio_dt, $data_fine, $data_fine_dt, $ids);
                break;
            case 'mail':
                $totalStats[$tipo] = $this->_getStatsForMail($data_inizio, $data_inizio_dt, $data_fine, $data_fine_dt, $ids);
                break;
            case 'telefonate':
                $totalStats[$tipo] = $this->_getStatsForTelefonate($data_inizio, $data_inizio_dt, $data_fine, $data_fine_dt, $ids);
                break;
            case 'whatsapp':
                $totalStats[$tipo] = $this->_getStatsForWhatsapp($data_inizio, $data_inizio_dt, $data_fine, $data_fine_dt, $ids);
                break;
        }

        return $totalStats;

    }

    private function _getStatsForWhatsapp($data_inizio, $data_inizio_dt, $data_fine, $data_fine_dt, $ids)
    {
        // SELECT utilizzata per estrarre i dati, la media non la inserisco nella query perché devo calcolarla a mano dopo
        $q_select = "COUNT(DISTINCT hotel_id) as n_hotel, SUM(calls) as n_click";

        $stats = [];
        $res_start = StatsHotelWhatsappRead::hotelIds($ids)
            ->whereRaw('DATE(CONCAT(anno,"-",mese,"-",giorno)) >=  "' . $data_inizio_dt->toDateString() . '"')
            ->whereRaw('DATE(CONCAT(anno,"-",mese,"-",giorno)) <=  "' . $data_fine_dt->toDateString() . '"')
            ->orderByRaw('DATE(CONCAT(anno,"-",mese,"-",giorno))')
            ->select(DB::raw($q_select))
            ->first();

        $stats['n_hotel'] = $res_start['n_hotel'];
        $stats['n_click'] = $res_start['n_click'];

        if ((int) $stats['n_click'] > 0 && (int) $stats['n_hotel'] > 0) {
            $stats['media'] = round((int) $res_start['n_click'] / (int) $stats['n_hotel']);
        } else {
            $stats['media'] = "NA";
        }

        return $stats;
    }

    private function _getStatsForTelefonate($data_inizio, $data_inizio_dt, $data_fine, $data_fine_dt, $ids)
    {
        // SELECT utilizzata per estrarre i dati, la media non la inserisco nella query perché devo calcolarla a mano dopo
        $q_select = "COUNT(DISTINCT hotel_id) as n_hotel, SUM(calls) as n_click";

        $stats = [];
        $res_start = StatsHotelCallRead::hotelIds($ids)
            ->whereRaw('DATE(CONCAT(anno,"-",mese,"-",giorno)) >=  "' . $data_inizio_dt->toDateString() . '"')
            ->whereRaw('DATE(CONCAT(anno,"-",mese,"-",giorno)) <=  "' . $data_fine_dt->toDateString() . '"')
            ->orderByRaw('DATE(CONCAT(anno,"-",mese,"-",giorno))')
            ->select(DB::raw($q_select))
            ->first();

        $stats['n_hotel'] = $res_start['n_hotel'];
        $stats['n_click'] = $res_start['n_click'];

        if ((int) $stats['n_click'] > 0 && (int) $stats['n_hotel'] > 0) {
            $stats['media'] = round((int) $res_start['n_click'] / (int) $stats['n_hotel']);
        } else {
            $stats['media'] = "NA";
        }

        return $stats;
    }

    private function _getStatsForVisite($data_inizio, $data_inizio_dt, $data_fine, $data_fine_dt, $ids)
    {
        // SELECT utilizzata per estrarre i dati, la media non la inserisco nella query perché devo calcolarla a mano dopo
        $q_select = "COUNT(DISTINCT hotel_id) as n_hotel, SUM(visits) as n_click";

        $stats = [];
        $res_start = StatHotelRead::hotelIds($ids)
            ->whereRaw('DATE(CONCAT(anno,"-",mese,"-",giorno)) >=  "' . $data_inizio_dt->toDateString() . '"')
            ->whereRaw('DATE(CONCAT(anno,"-",mese,"-",giorno)) <=  "' . $data_fine_dt->toDateString() . '"')
            ->orderByRaw('DATE(CONCAT(anno,"-",mese,"-",giorno))')
            ->select(DB::raw($q_select))
            ->first();

        $stats['n_hotel'] = $res_start['n_hotel'];
        $stats['n_click'] = $res_start['n_click'];

        if ((int) $stats['n_click'] > 0 && (int) $stats['n_hotel'] > 0) {
            $stats['media'] = round((int) $res_start['n_click'] / (int) $stats['n_hotel']);
        } else {
            $stats['media'] = "NA";
        }

        return $stats;
    }

    private function _getStatsForMail($data_inizio, $data_inizio_dt, $data_fine, $data_fine_dt, $ids)
    {
        $mail_stats = [];

        // MAIL SCHEDA
        $stats = [];

        // SELECT utilizzata per estrarre i dati, la media non la inserisco nella query perché devo calcolarla a mano dopo
        $q_select = "COUNT(DISTINCT hotel_id) as n_hotel, SUM(conteggio) as n_click";

        $res_start = MailSchedaRead::hotelIds($ids)
            ->whereRaw('DATE(CONCAT(anno,"-",mese,"-",giorno)) >=  "' . $data_inizio_dt->toDateString() . '"')
            ->whereRaw('DATE(CONCAT(anno,"-",mese,"-",giorno)) <=  "' . $data_fine_dt->toDateString() . '"')
            ->orderByRaw('DATE(CONCAT(anno,"-",mese,"-",giorno))')
            ->select(DB::raw($q_select))
            ->first();

        $stats['n_hotel'] = $res_start['n_hotel'];
        $stats['n_click'] = $res_start['n_click'];

        if ((int) $stats['n_click'] > 0 && (int) $stats['n_hotel'] > 0) {
            $stats['media'] = round((int) $res_start['n_click'] / (int) $stats['n_hotel']);
        } else {
            $stats['media'] = "NA";
        }

        $mail_stats['Scheda'] = $stats;

        // MAIL MULTIPLA
        $stats = [];

        // SELECT utilizzata per estrarre i dati, la media non la inserisco nella query perché devo calcolarla a mano dopo
        $q_select = "COUNT(DISTINCT hotel_id) as n_hotel, SUM(conteggio) as n_click";

        $res_start = MailMultipleRead::hotelIds($ids)
            ->whereRaw('DATE(CONCAT(anno,"-",mese,"-",giorno)) >=  "' . $data_inizio_dt->toDateString() . '"')
            ->whereRaw('DATE(CONCAT(anno,"-",mese,"-",giorno)) <=  "' . $data_fine_dt->toDateString() . '"')
            ->orderByRaw('DATE(CONCAT(anno,"-",mese,"-",giorno))')
            ->select(DB::raw($q_select))
            ->first();

        $stats['n_hotel'] = $res_start['n_hotel'];
        $stats['n_click'] = $res_start['n_click'];

        if ((int) $stats['n_click'] > 0 && (int) $stats['n_hotel'] > 0) {
            $stats['media'] = round((int) $res_start['n_click'] / (int) $stats['n_hotel']);
        } else {
            $stats['media'] = "NA";
        }

        $mail_stats['Multipla'] = $stats;

        return $mail_stats;
    }

    /** -------------------------------------------------------- 
     *  PUBLIC 
     * --------------------------------------------------------- */

    /**
     * Controller per statistche generali
     *
     * @access public
     * @param Request $request
     * @return Response
     */

    public function generali(Request $request)
    {

        $data = array();
        $totalStats = array();

        /** Categorie selezionabili */ 
        foreach (Categoria::get() as $categoria) {
            $data['categorie'][$categoria->id] = $categoria->nome;
        }

        /** Macrolocalità selezionabili */ 
        foreach (Macrolocalita::get()->sortBy('nome') as $macrolocalita) {
            if (gettype(json_decode($macrolocalita->nome)) == 'object') {
                $macrolocalita->nome = json_decode($macrolocalita->nome)->it;
                $data['macrolocalita'][$macrolocalita->id] = $macrolocalita->nome;
            } else {
                $data['macrolocalita'][$macrolocalita->id] = $macrolocalita->nome;
            }
        }

        /** Macrolocalità selezionabili */ 
        foreach (Localita::get()->sortBy('nome') as $localita) {
            $data['localita'][$localita->id] = $localita->nome;
        }

        /** 
         * Se è una richiesta della form, la parso 
         */

        if ($request->method() == 'POST') {

            if (!empty($request->input('date_range')))
                $date_range = explode(" - ", $request->input('date_range'));
            else
                SessionResponseMessages::add('error', 'Entrambe le date devono essere compilate');

            $request->flash(); // Memorizza le request
            $result = $this->calculateGeneralStats($date_range[0], $date_range[1], $request->input('categoria'), $request->input('macrolocalita'), $request->input('localita'), $request->get('tipo'));

            if ($result !== false) 
                $totalStats = $result;
            else
                return SessionResponseMessages::redirect("/admin/stats/hotels", $request);

        }

        

        return view('admin.stats_hotel', compact('data', 'totalStats'));
        
    }

    /**
     * Mostra il rating di un hotel nel tempo
     *
     * @access public
     * @param  AcceptPolicyGalleryRequest  $request
     * @return Response
     */

    public function rating(Request $request)
    {   

        $stats = [];
        $min_val_year = end(self::$anni);
        $max_val_year = reset(self::$anni);
        return view('admin.stats_hotel_rating', compact('stats', 'min_val_year', 'max_val_year'));

    }

    /**
     * Risultati rating
     * @param  Request $request [description]
     * @return [type]           [description]
     */

    public function ratingResult(Request $request)
    {

        $request->flash();

        /*
         * Arriva nel formato
         * "17 Hotel Sabrina"
         * quindi passandolo per il casting int ottengo: 17
         */

        $hotel_id = (int) $request->input('hotel');

        if (!$hotel_id) {
            SessionResponseMessages::add('error', 'Selezionare un\'hotel');
            return SessionResponseMessages::redirect($request->getUri(), $request);
        }

        /** Ho degli errori di input */
        if (!empty($request->input('date_range'))) {
            $date_range = explode(" - ", $request->input('date_range'));
            $date_from = Carbon::createFromFormat("d/m/Y", $date_range[0]);
            $date_to = Carbon::createFromFormat("d/m/Y", $date_range[1]);
        } else {
            SessionResponseMessages::add('error', 'Entrambe le date devono essere compilate');
            return SessionResponseMessages::redirect($request->getUri(), $request);
        }

        /** Importo le variabili */
        $stats = [];
        $trend = ["last" => 0, "compare" => 0, "rating_trend" => "", "rating_review" => 0 ];
        $results = DB::table("tblRating")->where("hotel_id", $hotel_id)->orderBy("created_at")->get();

        /** Non no risultati */
        if (count($results) == 0) {
            SessionResponseMessages::add('error', 'Nessun dato trovato per questo intervallo');
            return SessionResponseMessages::redirect($request->getUri(), $request);
        }

        /** Ciclo sui dati */
        $x = 0;
        foreach ($results as $result):

            /** recupero i valori */
            $time        = $result->created_at;
            $year        = Carbon::createFromFormat('Y-m-d H:i:s', $time)->format("Y");
            $month       = Carbon::createFromFormat('Y-m-d H:i:s', $time)->format("m");
            $day         = Carbon::createFromFormat('Y-m-d H:i:s', $time)->format("d");
            $hour        = Carbon::createFromFormat('Y-m-d H:i:s', $time)->format("H");
            $minute      = Carbon::createFromFormat('Y-m-d H:i:s', $time)->format("i");
            $key         = $year . $month . $day . $hour . $minute;
            $stats[$key] = ["rating" => 0, "n_rating_ia" => 0, "source_rating_ia" => 0];
            $valori      = json_decode($result->punteggio);
            
            $t = 0;
            $fonti = 0;
            $numero_votanti = 0;
            $media_votanti = 0;

            $valori[1] = str_replace(".", "", $valori[1]);
            $valori[3] = str_replace(".", "", $valori[3]);
            $valori[5] = str_replace(".", "", $valori[5]);
            $valori[7] = str_replace(".", "", $valori[7]);
            $valori[9] = str_replace(".", "", $valori[9]);

            (float)$valori[0] > 0 && (float)$valori[1] > 0 ? $valore1 = (float)$valori[0] * (float)$valori[1] : $valore1 = 0;
            (float)$valori[2] > 0 && (float)$valori[3] > 0 ? $valore2 = (float)$valori[2] * (float)$valori[3] : $valore2 = 0;
            (float)$valori[4] > 0 && (float)$valori[5] > 0 ? $valore3 = (float)$valori[4] * (float)$valori[5] : $valore3 = 0;
            (float)$valori[6] > 0 && (float)$valori[7] > 0 ? $valore4 = (float)$valori[6] * (float)$valori[7] : $valore4 = 0;
            (float)$valori[8] > 0 && (float)$valori[9] > 0 ? $valore5 = (float)$valori[8] * (float)$valori[9] : $valore5 = 0;

            (float)$valori[0] > 0 ? $fonti++ : false;
            (float)$valori[2] > 0 ? $fonti++ : false;
            (float)$valori[4] > 0 ? $fonti++ : false;
            (float)$valori[6] > 0 ? $fonti++ : false;
            (float)$valori[8] > 0 ? $fonti++ : false;

            $numero_votanti = (float)$valori[1] + (float)$valori[3] + (float)$valori[5] + (float)$valori[7] + (float)$valori[9];
            $media_votanti = (float)$valore1 + (float)$valore2 + (float)$valore3 + (float)$valore4 + (float)$valore5;

            if ( $media_votanti > 0) 
                $media_rating = $media_votanti / $numero_votanti;
            else
                $media_rating = 0;

            $stats[$key]["rating"] = $media_rating;
            $stats[$key]["source_rating_ia"] = $numero_votanti;

            $x++;
        endforeach;

        $stats_popped     = $stats;
        $trend["last"]    = array_pop($stats_popped);
        $trend["compare"] = array_pop($stats_popped);

        // /** Scelgo il segno */
        // if (isset($trend["compare"]["rating"]) && isset($trend["last"]["rating"])) {

        //     if ($trend["compare"]["rating"] < $trend["last"]["rating"])
        //         $trend["rating_trend"] = "+";
        //     else
        //         $trend["rating_trend"] = "-";

        //     /** faccio la percentuale di differenza */
        //     $trend["rating_trend"]   .= number_format(($trend["compare"]["rating"] * $trend["last"]["rating"]),2);
        //     $trend["rating_review"]  = $trend["last"]["n_rating_ia"];

        // } else {

        //     $trend["rating_trend"] =  "= " . number_format($trend["last"]["rating"],2);
        //     $trend["rating_review"]  = $trend["last"]["n_rating_ia"];

        // }

        ksort($stats);

        return view('admin.stats_hotel_rating', compact('stats','trend'));

    }

     /**
     * Controller per statistche generali
     *
     * @return \Illuminate\Http\Response
     */

    public function dettaglio(Request $request)
    {

        $data = [];
        $totali = [];
        $totali_click = 0;
        $number_day = 1;
        return view('admin.stats_dett_hotel', compact('data', 'totali', 'totali_click', 'number_day'));

    }

    public function dettaglioResult(Request $request)
    {

        $request->flash();
        $stats = [];
        $totali_click = 0;
        $number_day = 0;

        if (Auth::user()->hasRole(["admin", "operatore", "commerciale"])) {
            $hotel_id = (int) $request->input('hotel');
        } elseif (Auth::user()->hasRole(["hotel"])) {
            $hotel_id = Auth::user()->hotel_id;
        }

        if (empty($hotel_id)) {

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

        $hotel_id = (int) $request->input('hotel');

        $query = DB::table("tblStatsHotelRead")
            ->selectRaw('anno,mese,giorno,hotel_id,visits')
            ->whereRaw('DATE(CONCAT(anno,"-",mese,"-",giorno)) >=  "' . $date_from->toDateString() . '"')
            ->whereRaw('DATE(CONCAT(anno,"-",mese,"-",giorno)) <=  "' . $date_to->toDateString() . '"')
            ->where("hotel_id", $hotel_id)
            ->orderByRaw('DATE(CONCAT(anno,"-",mese,"-",giorno))');

        $results = $query->get();

        if (count($results) == 0) {
            SessionResponseMessages::add('error', 'Nessun dato trovato per questo intervallo');
            return SessionResponseMessages::redirect($request->getUri(), $request);
        }

        $lastMonth = "";
        $totale = 0;

        foreach ($results as $result):

            $cdate = Carbon::createFromFormat('Y-m-d', $result->anno . "-" . $result->mese . "-" . $result->giorno);

            // Se non ci sono creo i rami necessari
            if (!isset($stats[$cdate->format("Y")][$cdate->format("M")]['totale'])) {
                $stats[$cdate->format("Y")][$cdate->format("M")]['totale'] = 0;
            }

            if (!isset($stats[$cdate->format("Y")][$cdate->format("M")]['giorni'][$cdate->format("d")])) {
                $stats[$cdate->format("Y")][$cdate->format("M")]['giorni'][$cdate->format("d")] = 0;
            }

            // Calcolo i valori
            $stats[$cdate->format("Y")][$cdate->format("M")]['giorni'][$cdate->format("d")] += $result->visits;
            $stats[$cdate->format("Y")][$cdate->format("M")]['totale'] += $result->visits;

            $totali_click += $result->visits;

            //echo  $result->anno."-".$result->mese."-".$result->giorno . " " . $result->visits . " " . $totale . "<br />";

        endforeach;

        //dd($stats);

        $da = $date_from;
        $a = $date_to;

        for ($cdate = $da; $cdate <= $a; $cdate->addDay()) {

            if (!isset($stats[$cdate->format("Y")][$cdate->format("M")]['giorni'][$cdate->format("d")])) {
                $stats[$cdate->format("Y")][$cdate->format("M")]['giorni'][$cdate->format("d")] = 0;
            }

            if (!isset($stats[$cdate->format("Y")][$cdate->format("M")]['totale'])) {
                $stats[$cdate->format("Y")][$cdate->format("M")]['totale'] = 0;
            }

            $number_day++;

        }

        return view('admin.stats_dett_hotel', compact('stats', 'totali_click', 'number_day'));

    }

    public function calls()
    {

        $stats = [];
        $min_val_year = end(self::$anni);
        $max_val_year = reset(self::$anni);
        return view('admin.stats_hotel_pagine_simple', compact('stats', 'min_val_year', 'max_val_year'));

    }
    /**
     * Risultati chiamate
     * @param  Request $request [description]
     * @return [type]           [description]
     */

    public function callsResult(Request $request)
    {

        $request->flash();

        /*
         * Arriva nel formato
         * "17 Hotel Sabrina"
         * quindi passandolo per il casting int ottengo: 17
         */

        $hotel_id = (int) $request->input('hotel');

        if (!$hotel_id) {

            SessionResponseMessages::add('error', 'Selezionare un\'hotel');
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

        $stats = [];

        $results = DB::table("tblStatsHotelCallRead")
            ->selectRaw('anno,mese,giorno,hotel_id,calls')
            ->whereRaw('DATE(CONCAT(anno,"-",mese,"-",giorno)) >=  "' . $date_from->toDateString() . '"')
            ->whereRaw('DATE(CONCAT(anno,"-",mese,"-",giorno)) <=  "' . $date_to->toDateString() . '"')
            ->where("hotel_id", $hotel_id)
            ->orderByRaw('DATE(CONCAT(anno,"-",mese,"-",giorno))')
            ->get();

        if (count($results) == 0) {

            SessionResponseMessages::add('error', 'Nessun dato trovato per questo intervallo');
            return SessionResponseMessages::redirect($request->getUri(), $request);

        }

        $lastMonth = "";
        $stats_list = array();

        $totale = 0;

        $super_totale = 0;
        foreach ($results as $result):

            $cdate = Carbon::createFromFormat('Y-m-d', $result->anno . "-" . $result->mese . "-" . $result->giorno);

            if ($lastMonth == "") {
                $lastMonth = $cdate->format("M");
            }

            if ($lastMonth != $cdate->format("M")):
                $lastMonth = $cdate->format("M");
                $totale = 0;
            endif;

            if (!isset($stats[$cdate->format("Y")][$cdate->format("M")]['giorni'][$cdate->format("d")])) {
                $stats[$cdate->format("Y")][$cdate->format("M")]['giorni'][$cdate->format("d")] = 0;
            }

            $stats[$cdate->format("Y")][$cdate->format("M")]['giorni'][$cdate->format("d")] += $result->calls;
            $totale += (int) $result->calls;
            $super_totale += (int) $result->calls;

            $stats[$cdate->format("Y")][$cdate->format("M")]['totale'] = $totale;

        endforeach;

        $da = $date_from;
        $a = $date_to;

        for ($cdate = $da; $cdate <= $a; $cdate->addDay()) {

            if (!isset($stats[$cdate->format("Y")][$cdate->format("M")]['giorni'][$cdate->format("d")])) {
                $stats[$cdate->format("Y")][$cdate->format("M")]['giorni'][$cdate->format("d")] = 0;
            }

            if (!isset($stats[$cdate->format("Y")][$cdate->format("M")]['totale'])) {
                $stats[$cdate->format("Y")][$cdate->format("M")]['totale'] = 0;
            }

        }

        return view('admin.stats_hotel_pagine_simple', compact('stats', 'super_totale'));

    }

    public function like()
    {

        $hotel_id = $this->getHotelId();
        $hotel = Hotel::findOrFail($hotel_id);
        $count_preferiti = $hotel->count_preferiti;

        /*
         * QUERY PER TROVARE I DATI AGGREGATI PER GIORNO
         *
        select DATE(created_at) as la_data, SUM( IF (azione = 'add' ,1,-1) )
        from tblHotelPreferiti
        where hotel_id = 1486
        group by la_data
        order by la_data asc
         *
         */

        $dati = DB::table('tblHotelPreferiti')
            ->select(DB::raw("DATE(created_at) as la_data, SUM( IF (azione = 'add' ,1,-1) ) as totale"))
            ->where('hotel_id', $hotel_id)
            ->groupBy(DB::raw("la_data"))
            ->orderBy("la_data")
            ->get();

        $val = 0;

        foreach ($dati as $count => $dato) {

            $val += $dato->totale;
            // non mostro mai i valori che, PER QUALCHE ERRORE, potrebbero essere negativi

            if ($val < 0) {
                $val = 0;
            }

            $dato->totale = $val;

            $carbon_date = Carbon::createFromFormat('Y-m-d', $dato->la_data);
            $dato->la_data = $carbon_date->format('d/m/Y');
            $dati[$count] = $dato;

        }

        return view('admin.stats_hotel_like', compact('dati', 'count_preferiti'));

    }

    public static function whatsapp()
    {
        $stats = [];
        return view('admin.stats_hotel_whatsapp', compact('stats'));

    }

    public static function whatsappResult(Request $request)
    {

        $hotel_id = (int) $request->input('hotel');

        if (!$hotel_id) {

            SessionResponseMessages::add('error', 'Selezionare un\'hotel');
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

        $stats = [];

        $results = DB::table("tblStatsHotelWhatsappRead")
            ->selectRaw('anno,mese,giorno,hotel_id,calls')
            ->whereRaw('DATE(CONCAT(anno,"-",mese,"-",giorno)) >=  "' . $date_from->toDateString() . '"')
            ->whereRaw('DATE(CONCAT(anno,"-",mese,"-",giorno)) <=  "' . $date_to->toDateString() . '"')
            ->where("hotel_id", $hotel_id)
            ->orderByRaw('DATE(CONCAT(anno,"-",mese,"-",giorno))')
            ->get();

        if (count($results) == 0) {

            SessionResponseMessages::add('error', 'Nessun dato trovato per questo intervallo');
            return SessionResponseMessages::redirect($request->getUri(), $request);

        }

        $lastMonth = "";
        $stats_list = array();

        $totale = 0;
        $super_totale = 0;

        foreach ($results as $result):

            $cdate = Carbon::createFromFormat('Y-m-d', $result->anno . "-" . $result->mese . "-" . $result->giorno);

            if ($lastMonth == "") {
                $lastMonth = $cdate->format("M");
            }

            if ($lastMonth != $cdate->format("M")):
                $lastMonth = $cdate->format("M");
                $totale = 0;
            endif;

            if (!isset($stats[$cdate->format("Y")][$cdate->format("M")]['giorni'][$cdate->format("d")])) {
                $stats[$cdate->format("Y")][$cdate->format("M")]['giorni'][$cdate->format("d")] = 0;
            }

            $stats[$cdate->format("Y")][$cdate->format("M")]['giorni'][$cdate->format("d")] += $result->calls;
            $totale += (int) $result->calls;
            $super_totale += (int) $result->calls;

            $stats[$cdate->format("Y")][$cdate->format("M")]['totale'] = $totale;

        endforeach;

        $da = $date_from;
        $a = $date_to;

        for ($cdate = $da; $cdate <= $a; $cdate->addDay()) {

            if (!isset($stats[$cdate->format("Y")][$cdate->format("M")]['giorni'][$cdate->format("d")])) {
                $stats[$cdate->format("Y")][$cdate->format("M")]['giorni'][$cdate->format("d")] = 0;
            }

            if (!isset($stats[$cdate->format("Y")][$cdate->format("M")]['totale'])) {
                $stats[$cdate->format("Y")][$cdate->format("M")]['totale'] = 0;
            }

        }

        return view('admin.stats_hotel_whatsapp', compact('stats', 'date_range', 'super_totale'));

    }

    

}
