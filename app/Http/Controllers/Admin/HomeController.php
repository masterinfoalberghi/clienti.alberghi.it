<?php

namespace App\Http\Controllers\Admin;

use App\Caparra;
use App\LabelCaparra;
use App\Hotel;
use App\HotelTagModificati;
use App\NewsletterLinks;
use App\Utility;
use Auth;
use Carbon\Carbon;
use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
class HomeController extends AdminBaseController
{

    private static $username_per_scadenze = array('ilaria@info-alberghi.com', 'luigi@info-alberghi.com', 'giovanni@info-alberghi.com', 'elena@info-alberghi.com', 'luca@info-alberghi.com', 'alessandra@info-alberghi.com', 'luciobonini@gmail.com', 'comunicazione@info-alberghi.com');

    private function _modera_foto($locale = 'it')
    {
        //////////////////////////////////////////////////////////////////////////////////////
        // devo trovare tutti gli hotel che hanno almeno una foto della gallery da moderare //
        //////////////////////////////////////////////////////////////////////////////////////

        /*$images = ImmagineGallery::whereHas('immaginiGallery_lingua', function($query) use ($locale) {
        $query
        ->where('lang_id', '=', $locale)
        ->where('moderato','=',0);
        })->orderBy('updated_at', 'desc')->get();
         */

        // ATTENZIONE: questa query viene tradotta da LARAVEL con
        /*SELECT
        FROM `tblImmaginiGallery`
        WHERE (
        SELECT COUNT(*)
        FROM `tblImmaginiGalleryLang`
        WHERE `tblImmaginiGalleryLang`.`master_id` = `tblImmaginiGallery`.`id` AND `lang_id` = 'it' AND `moderato` = '0') >= 1
        ORDER BY `updated_at` DESC*/
        //
        // NON VA BENE !!!!
        //
        //la query deve essere del tipo
        //
        /*
        select img.id, img.hotel_id tblImmaginiGallery, img.foto,img.position
        from tblImmaginiGallery img join tblImmaginiGalleryLang imgl on imgl.master_id = img.id
        where imgl.lang_id = 'it'  and imgl.moderato = 0
        order by img.updated_at desc
         */
        //

        $images = DB::table('tblImmaginiGallery')
            ->join('tblImmaginiGalleryLang', 'tblImmaginiGalleryLang.master_id', '=', 'tblImmaginiGallery.id')
            ->select('tblImmaginiGallery.id', 'tblImmaginiGallery.hotel_id', 'tblImmaginiGallery.foto', 'tblImmaginiGallery.position', 'tblImmaginiGallery.updated_at')
            ->where('tblImmaginiGalleryLang.lang_id', '=', $locale)
            ->where('tblImmaginiGalleryLang.moderato', '=', 0)
            ->get();

        $hotel_ids = array();
        $old_id = -1;

        foreach ($images as $img):

            if ($img->hotel_id == $old_id) {

                //$hotel_ids[$old_id]["foto"]++;

            } else {

                $old_id = $img->hotel_id;

                $hotel = Hotel::find($old_id);
                $hotel_id = array();

                $hotel_id["id"] = $hotel->id;
                $hotel_id["nome"] = $hotel->nome;
                $hotel_id["modifica_immagine"] = $img->updated_at;
                $hotel_ids[$old_id] = $hotel_id;

            }

        endforeach;

        return $hotel_ids;

    }

    private function _grabMarkDown(&$hotfix, &$sviluppi)
    {

        $hotfix = File::get(app_path() . '/../docs/hotfix.md');
        $sviluppi = File::get(app_path() . '/../docs/sviluppi.md');

        $hotfix = explode("---", $hotfix);
        $sviluppi = explode("---", $sviluppi);

        $hotfix = $hotfix[0];
        $sviluppi = $sviluppi[0];

        $hotfix = Markdown::convertToHtml($hotfix); // <p>foo</p>
        $sviluppi = Markdown::convertToHtml($sviluppi); // <p>foo</p>

        $hotfix .= '<br /><a class="btn btn-default" href="/admin/hotfix">Leggi tutto</a>';
        $sviluppi .= '<br /><a class="btn btn-default" href="/admin/featured">Leggi tutto</a>';
    }

    public function stats($hotel_id)
    {

        $date_from = Carbon::now()->subMonth(1);
        $date_to = Carbon::now();

        $emailDirette = $query = DB::table("tblMailSchedaRead")
            ->selectRaw('SUM(conteggio) as conteggio')
            ->whereRaw('DATE(CONCAT(anno,"-",mese,"-",giorno)) >=  "' . $date_from->toDateString() . '"')
            ->whereRaw('DATE(CONCAT(anno,"-",mese,"-",giorno)) <=  "' . $date_to->toDateString() . '"')
            ->where("hotel_id", $hotel_id)
            ->first();

        $emailMultiple = $query = DB::table("tblStatsMailMultipleRead")
            ->selectRaw('SUM(conteggio) as conteggio')
            ->whereRaw('DATE(CONCAT(anno,"-",mese,"-",giorno)) >=  "' . $date_from->toDateString() . '"')
            ->whereRaw('DATE(CONCAT(anno,"-",mese,"-",giorno)) <=  "' . $date_to->toDateString() . '"')
            ->where("hotel_id", $hotel_id)
            ->first();

        $statisticheMese["email"] = (int) $emailDirette->conteggio + (int) $emailMultiple->conteggio;

        $visite = $query = DB::table("tblStatsHotelRead")
            ->selectRaw('SUM(visits) as conteggio')
            ->whereRaw('DATE(CONCAT(anno,"-",mese,"-",giorno)) >=  "' . $date_from->toDateString() . '"')
            ->whereRaw('DATE(CONCAT(anno,"-",mese,"-",giorno)) <=  "' . $date_to->toDateString() . '"')
            ->where("hotel_id", $hotel_id)
            ->first();

        $statisticheMese["visite"] = (int) $visite->conteggio;

        if ($statisticheMese["visite"] > 0 && $statisticheMese["email"] > 0) {
            $statisticheMese["media"] = round(($statisticheMese["email"] / $statisticheMese["visite"]), 2) * 100;
        } else {
            $statisticheMese["media"] = "NA";
        }

        $statisticheMese["media"] .= "%";

        return $statisticheMese;
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */

    public function index()
    {
        $hotel_ids = [];
        $statisticheMese = [];

        if (Auth::user()->hasRole("hotel")) {

            $hotel = Auth::user()->hotel;
            $statisticheMese = Self::stats($hotel->id);

            //////////////////////////////////////////
            // elenco ultimi 5 link alle newsletter //
            //////////////////////////////////////////
            $newsletterLinks = NewsletterLinks::OrderBy('data_invio', 'desc')->limit(Utility::getMaxNewsletterLinks())->get();

            // SE FACCIO PARTE DI UN GRUPPO HO UNA HOME CON LA SCELTA DEGLI HOTEL !!!
            if (!is_null($gruppo = $hotel->gruppo)) {
                return view('admin.select_group_hotels', compact('hotel', 'gruppo', 'newsletterLinks', 'statisticheMese'));
            } else {
                return view('admin.dashboard_hotel', compact('hotel', 'newsletterLinks', 'statisticheMese'));
            }

        } elseif (Auth::user()->hasRole("commerciale")) {
            return redirect("admin/commerciale");
            
        } elseif (Auth::user()->hasRole(["root", "admin", "operatore"])) {

            $num_caparre = Caparra::where("enabled", false)->count();
            $num_bonus = Hotel::where("bonus_vacanze_2020", 2)->orWhere("bonus_vacanze_2020", -2)->count();

            $dashboard_links = array(
                'admin/modera-foto' => 'Modera le foto|tile-green|',
                'admin/offerte_da_approvare' => 'Modera le offerte|tile-blue|',
                // 'admin/politiche-cancellazione/moderazione' => 'Modifica/cancellazione prenotazione|tile-red|' . $num_caparre . " hotel in attesa",
                // 'admin/politiche-bonus/moderazione' => 'Accettazione bonus vacanze|tile-red|' . $num_bonus . " hotel in attesa",
            );

            if (in_array(Auth::user()->username, self::$username_per_scadenze)) {

                $dashboard_links['admin/elenco_scadenze_vot'] = 'Scadenza evidenze |tile-red|';
                $dashboard_links['admin/elenco_scadenze_vtt'] = 'Scadenza evidenze bambini gratis |tile-aqua| ';
                //$dashboard_links['admin/elenco_hotel_tag_modificati'] = 'Hotel con tag moderati |tile-plum|';
                $dashboard_links['admin/elenco_hotel_politiche_cancellazione'] = 'Hotel con politiche di cancellazione |tile-plum|';
                $dashboard_links['admin/elenco_hotel_trattamenti_modificati'] = 'Hotel con trattamenti da approvare |tile-plum|';

            }

            // bounces count
            // $url = $this->_make_url_sd('bounces.count.json');
            // $bounces_count = json_decode($this->_get_call_sd($url));

            // block count
            // $url = $this->_make_url_sd('blocks.count.json');
            // $blocks_count = json_decode($this->_get_call_sd($url));

            $bounces_count = null;
            $blocks_count = null;

        }

        self::_grabMarkDown($hotfix, $sviluppi);
        return view('admin.home', compact('dashboard_links', 'hotfix', 'sviluppi', 'statisticheMese', 'bounces_count', 'blocks_count'));

    }

    public function moderaFoto()
    {

        $hotel_ids = [];

        $hotel_ids = self::_modera_foto();

        self::_grabMarkDown($hotfix, $sviluppi);

        return view('admin.home', compact('hotel_ids', 'hotfix', 'sviluppi'));

    }

    public function elencoHotelTagModificati()
    {
        $hotel_tag = HotelTagModificati::all();

        return view('admin.hotel_tag', compact('hotel_tag'));
    }


    public function elencoHotelTrattamentiModificati()
			{
            $hotel_trattamenti = Hotel::where('attivo','!=',0)->where('trattamenti_moderati',0)->pluck('nome','id');
			
			return view('admin.hotel_trattamenti', compact('hotel_trattamenti'));
			}


    public function elencoHotelPoliticheCancellazione()
      {
      $hotel_politiche_cancellazione = [];

      ////////////////////////////////////////////////////////////////////////////////////////////////
      // tutti gli hotel che hanno caparre valide e CHE NON HANNO LA LABEL E CHE SONO ETICHETTABILI //
      ////////////////////////////////////////////////////////////////////////////////////////////////
      $politiche_cancellazione = DB::table('tblCaparre')
                                ->where('enabled', 1)
                                ->where('to', '>=', date('Y-m-d'))
                                ->whereNull('label_id')
                                ->where('labelable',true)
                                ->select('hotel_id')
                                ->distinct('hotel_id')
                                ->get();

      $hotel_ids_politiche_cancellazione = [];

      foreach ($politiche_cancellazione as $p) {
        $hotel_ids_politiche_cancellazione[] = $p->hotel_id;
      }

      $hotel_politiche_cancellazione = Hotel::with(['localita', 'caparre'])->where('attivo','!=',0)->whereIn('id',$hotel_ids_politiche_cancellazione)->get();


      /////////////////////////////////////////////////////
      // tutti gli hotel a cui ho già assegnato la label //
      /////////////////////////////////////////////////////
      
      ////////////////////////////////////////////////////////////////////////
      // tutti gli hotel che hanno caparre valide e CHE HANNO LA LABEL //
      ///////////////////////////////////////////////////////////////////////
      $politiche_cancellazione_label = DB::table('tblCaparre')
                                        ->where('enabled', 1)
                                        ->where('to', '>=', date('Y-m-d'))
                                        ->whereNotNull('label_id')
                                        ->select('hotel_id')
                                        ->distinct('hotel_id')
                                        ->get();

      $hotel_ids_politiche_cancellazione_label = [];

      foreach ($politiche_cancellazione_label as $p) {
        $hotel_ids_politiche_cancellazione_label[] = $p->hotel_id;
      }

      $label =  DB::table('tblLabelCaparre')
                ->whereIn('hotel_id',$hotel_ids_politiche_cancellazione_label)
                ->select('hotel_id')
                ->get();

      $hotel_ids_label = [];

      foreach ($label as $l) {
        $hotel_ids_label[] = $l->hotel_id;
      }

      $hotel_label = Hotel::with(['localita','labelCaparre'])
                    ->where('attivo','!=',0)
                    ->whereIn('id',$hotel_ids_label)
                    ->get();

      $hotel_label = collect(
                    $hotel_label->sortByDesc(function($query){
                           return $query->labelCaparre->updated_at;
                        })
                        ->all()
                    );

  
      return view('admin.hotel_politiche_cancellazione', compact('hotel_politiche_cancellazione','hotel_label'));
      }


    public function removeHotelTagModificati()
    {
        HotelTagModificati::truncate();

        return redirect("admin");

    }

}
