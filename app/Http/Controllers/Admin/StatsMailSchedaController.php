<?php

/**
 * StatsMailSchedaController
 *
 * @author Info Alberghi Srl
 *
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Localita;
use App\Macrolocalita;
use App\MailScheda;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SessionResponseMessages;

class StatsMailSchedaController extends Controller
{

    protected $lowest_year = 0;

    /* ------------------------------------------------------------------------------------
     * METODI PRIVATI
     * ------------------------------------------------------------------------------------ */

    /**
     * Prende gli anni per i filtri
     *
     * @access private
     * @param bool $with_zero (default: false)
     * @return array $years
     */

    private function getAllYears($with_zero = false)
    {

        $years = [];

        if ($with_zero) {
            $years[] = 'Tutti';
        }

        if (!$this->lowest_year) {
            $this->lowest_year = MailScheda::selectRaw('YEAR(created_at) AS anno_vecchio')
                ->orderBy('id', 'asc')
                ->first()['anno_vecchio'];
        }

        $current_date = Carbon::now();

        foreach (range($this->lowest_year, $current_date->year) as $year) {
            $years[$year] = $year;
        }

        krsort($years);
        return $years;

    }

    /**
     * Prende i mesi in formato abbreviato il grafico
     *
     * @access private
     * @param bool $with_zero (default: false)
     * @return array $mesi
     */

    private function getAllMonthShort($with_zero = false)
    {
        $mesi = [];

        if ($with_zero) {
            $mesi[0] = 'Tutti';
        }

        $date = new Carbon();

        foreach (range(1, 12) as $monthNumber) {
            $date->setDate(2015, $monthNumber, 1);
            $mesi[$monthNumber] = ucfirst($date->formatLocalized("%b"));
        }

        return $mesi;

    }

    protected function getMesi()
    {
        return [1 => "Gen", "Feb", "Mar", "Apr", "Mag", "Giu", "Lug", "Ago", "Set", "Ott", "Nov", "Dic"];
    }

    /* ------------------------------------------------------------------------------------
     * METODI PUBBLICI ( VIEWS )
     * ------------------------------------------------------------------------------------ */

    /**
     * Ritorna le statisciche per mese
     *
     * @access public
     * @param Request $request
     * @return void
     */

    public function schede(Request $request)
    {

        $data = [];
        $stats = [];
        $stats_chart = [];
        $hotel_id = [];
        $data['tipologie'] = MailScheda::getTipologieSelezionabili();

        return view('admin.stats_mail_schede', compact('data', 'stats', 'stats_chart', 'hotel_id'));

    }

    public function schedeResult(Request $request)
    {

        $data = [];
        $stats = [];
        $stats_chart = [];
        $request->flash();
        $data['tipologie'] = MailScheda::getTipologieSelezionabili();
        $hotel_id = (int) $request->input('hotel');
        $tipologia = $request->input('tipologia');
        $raggruppa = $request->input('raggruppa');
        $results_doppie = [];
        $mesi = $this->getMesi();

        if (!empty($request->input('date_range'))) {

            $date_range = explode(" - ", $request->input('date_range'));
            $date_from = Carbon::createFromFormat("d/m/Y", $date_range[0]);
            $date_to = Carbon::createFromFormat("d/m/Y", $date_range[1]);

        } else {

            SessionResponseMessages::add('error', 'Entrambe le date devono essere compilate');
            return SessionResponseMessages::redirect("/admin/stats/mail_scheda", $request);

        }

        if (empty($hotel_id)) {

            SessionResponseMessages::add('error', 'Selezionare almeno un hotel');
            return SessionResponseMessages::redirect("/admin/stats/mail_scheda", $request);

        }

        $query = DB::table("tblMailSchedaRead")
            ->selectRaw('anno,mese,giorno,tipologia,hotel_id,conteggio')
            ->whereRaw('DATE(CONCAT(anno,"-",mese,"-",giorno)) >=  "' . $date_from->toDateString() . '"')
            ->whereRaw('DATE(CONCAT(anno,"-",mese,"-",giorno)) <=  "' . $date_to->toDateString() . '"')
            ->orderByRaw('DATE(CONCAT(anno,"-",mese,"-",giorno))');

        if ($hotel_id) {
            $query = $query->where("hotel_id", $hotel_id);
        }

        if ($tipologia == "-1") {

            // Tutto quanto

        } else if ($tipologia == "-2") {

            $query = $query->where("tipologia", "=", "doppia");
            //$query = $query->where("tipologia", "=" ,"doppia parziale");

        } else {

            if ($tipologia == 0) {
                $query = $query->where("tipologia", "normale");
            } else {
                $query = $query->where("tipologia", "mobile");
            }

        }

        $results = $query->get();

        if (count($results) == 0) {
            SessionResponseMessages::add('error', 'Nessun dato trovato per questo intervallo');
            return SessionResponseMessages::redirect($request->getUri(), $request);
        }

        $stats = [];
        $stats_chart = [];
        $totali_raggruppati = [];
        $totali = [];

        foreach ($results as $r) {
            $stats_chart[$r->anno][$r->mese][$r->giorno][$r->tipologia] = $r->conteggio;

            if (!isset($totali_raggruppati[$r->anno])) {
                $totali_raggruppati[$r->anno] = 0;
            }

            if (!isset($totali[$r->anno][$r->mese])) {
                $totali[$r->anno][$r->mese] = 0;
            }

            $totali[$r->anno][$r->mese] += $r->conteggio;
            $totali_raggruppati[$r->anno] += $r->conteggio;

        }

        for ($date = $date_from; $date->lte($date_to); $date->addDay()) {

            $periodo_a = explode("/", $date->format("d/m/Y"));

            foreach (array("normale", "mobile", "doppia") as $tipo) {
                if (!isset($stats_chart[$periodo_a[2]][$periodo_a[1]][$periodo_a[0]][$tipo])) {
                    $stats_chart[$periodo_a[2]][$periodo_a[1]][$periodo_a[0]][$tipo] = 0;
                }

                ksort($stats_chart[$periodo_a[2]][$periodo_a[1]]);
                ksort($stats_chart[$periodo_a[2]]);

            }

        }

        ksort($stats_chart);

        return view('admin.stats_mail_schede', compact('data', 'stats', 'stats_chart', 'hotel_id', 'mesi', 'totali', 'totali_raggruppati', 'raggruppa'));

    }

    /**
     * Ritorna le statistiche giornaliere
     *
     * @access public
     * @return view
     */

    public function giornaliere()
    {

        /**
         * Non ci sono form, calcolo direttamente i dati
         */

        $stats = MailScheda::selectRaw("DATE(created_at) AS giorno, COUNT(id) AS n_mail")
            ->whereHas('cliente', function ($q) {
                $q->attivo();
            })
            ->groupBy(DB::raw("DATE(created_at)"))
            ->get();

        return view('admin.stats_mail_schede_giornaliere', compact('stats'));
    }

    /**
     * localitazone function.
     *
     * @access public
     * @param Request $request
     * @return view
     */

    public function localitazone(Request $request)
    {

        $stats = [];
        return view('admin.stats_mail_schede_localitazone', compact('stats'));

    }

    public function localitazoneResult(Request $request)
    {

        $stats_scheda = [];
        $stats_multiple = [];

        if (!empty($request->input('date_range'))) {

            $date_range = explode(" - ", $request->input('date_range'));
            $date_from = Carbon::createFromFormat("d/m/Y", $date_range[0]);
            $date_to = Carbon::createFromFormat("d/m/Y", $date_range[1]);

        } else {

            SessionResponseMessages::add('error', 'Entrambe le date devono essere compilate');
            return SessionResponseMessages::redirect($request->getUri(), $request);

        }

        if ($request->input('tipo') == 'localita') {

            $localita = true;
            $localitaItems = Localita::get();

        } else {

            $localita = false;
            $localitaItems = Macrolocalita::get();

        }

        /**
         * Costruisco la query
         */

        if ($localita) {

            $results_scheda = DB::table("tblHotel")
                ->selectRaw('sum(conteggio) as conteggio, tblLocalita.id')
                ->join('tblMailSchedaRead', 'tblHotel.id', '=', 'tblMailSchedaRead.hotel_id')
                ->join('tblLocalita', 'tblLocalita.id', '=', 'tblHotel.localita_id')
                ->whereRaw('DATE(CONCAT(anno,"-",mese,"-",giorno)) >=  "' . $date_from->toDateString() . '"')
                ->whereRaw('DATE(CONCAT(anno,"-",mese,"-",giorno)) <=  "' . $date_to->toDateString() . '"')
                ->groupBy('tblLocalita.id')
                ->get();

            $results_multiple = DB::table("tblHotel")
                ->selectRaw('sum(conteggio) as conteggio, tblLocalita.id')
                ->join('tblStatsMailMultipleRead', 'tblHotel.id', '=', 'tblStatsMailMultipleRead.hotel_id')
                ->join('tblLocalita', 'tblLocalita.id', '=', 'tblHotel.localita_id')
                ->whereRaw('DATE(CONCAT(anno,"-",mese,"-",giorno)) >=  "' . $date_from->toDateString() . '"')
                ->whereRaw('DATE(CONCAT(anno,"-",mese,"-",giorno)) <=  "' . $date_to->toDateString() . '"')
                ->groupBy('tblLocalita.id')
                ->get();

        } else {

            $results_scheda = DB::table("tblHotel")
                ->selectRaw('sum(conteggio) as conteggio, tblMacrolocalita.id, tblMacrolocalita.nome')
                ->join('tblMailSchedaRead', 'tblHotel.id', '=', 'tblMailSchedaRead.hotel_id')
                ->join('tblLocalita', 'tblLocalita.id', '=', 'tblHotel.localita_id')
                ->join('tblMacrolocalita', 'tblMacrolocalita.id', '=', 'tblLocalita.macrolocalita_id')
                ->whereRaw('DATE(CONCAT(anno,"-",mese,"-",giorno)) >=  "' . $date_from->toDateString() . '"')
                ->whereRaw('DATE(CONCAT(anno,"-",mese,"-",giorno)) <=  "' . $date_to->toDateString() . '"')
                ->groupBy('tblMacrolocalita.id')
                ->get();

            $results_multiple = DB::table("tblHotel")
                ->selectRaw('sum(conteggio) as conteggio, tblMacrolocalita.id, tblMacrolocalita.nome')
                ->join('tblStatsMailMultipleRead', 'tblHotel.id', '=', 'tblStatsMailMultipleRead.hotel_id')
                ->join('tblLocalita', 'tblLocalita.id', '=', 'tblHotel.localita_id')
                ->join('tblMacrolocalita', 'tblMacrolocalita.id', '=', 'tblLocalita.macrolocalita_id')
                ->whereRaw('DATE(CONCAT(anno,"-",mese,"-",giorno)) >=  "' . $date_from->toDateString() . '"')
                ->whereRaw('DATE(CONCAT(anno,"-",mese,"-",giorno)) <=  "' . $date_to->toDateString() . '"')
                ->groupBy('tblMacrolocalita.id')
                ->get();

        }

        foreach ($localitaItems as $loc_item):

            $stats[$loc_item["id"]] = ["nome" => $loc_item["nome"], "dirette" => "0", "multiple" => "0"];

        endforeach;

        $rr_dirette = 0;
        $rr_multiple = 0;

        if ($results_scheda) {
            foreach ($results_scheda as $row_result):

                $stats[$row_result->id]["dirette"] = number_format($row_result->conteggio, 0, ',', '.');
                $rr_dirette += $row_result->conteggio;

            endforeach;

            if ($results_multiple) {
                foreach ($results_multiple as $row_result):

                    $stats[$row_result->id]["multiple"] = number_format($row_result->conteggio, 0, ',', '.');
                    $rr_multiple += $row_result->conteggio;

                endforeach;

                if ($localita) {

                    $stats[49]["dirette"] = number_format($rr_dirette, 0, ',', '.');
                    $stats[49]["multiple"] = number_format($rr_multiple, 0, ',', '.');

                }
            }
        } else {

            $stats[11]["dirette"] = number_format($rr_dirette, 0, ',', '.');
            $stats[11]["multiple"] = number_format($rr_multiple, 0, ',', '.');
        }

        return view('admin.stats_mail_schede_localitazone', compact('stats'));

    }

}
