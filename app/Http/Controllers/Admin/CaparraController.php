<?php

namespace App\Http\Controllers\Admin;

use Langs;
use App\Utility;
use App\Hotel;
use App\Caparra;
use Carbon\Carbon;
use App\LabelCaparra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CaparraController extends AdminBaseController
{

    public function index()
    {
        $cdms = Caparra::with('label')
            ->where("hotel_id", $this->getHotelId())
            ->orderBy('from', 'asc')->get();


        $params = ["politiche" => $cdms];

        $label_caparra = new LabelCaparra;

        foreach ($cdms as $c) {
            if (!is_null($c->label)) {
                $label_caparra = $c->label;
                break;
            }
        }

        $params['label_caparra'] = $label_caparra;

        if (Auth::user()->hasRole("hotel")) {
            $hotel = Auth::user()->hotel;
            if (!is_null($gruppo = $hotel->gruppo)) {
                $hotel_connesso = $hotel;
                $gruppo_da_connettere = [];
                foreach ($gruppo->hotels as $h) {
                    if ($h->id != $hotel->id) {
                        $gruppo_da_connettere[] = $h;
                    }
                }
                $params['gruppo_da_connettere'] = $gruppo_da_connettere;
                $params['hotel_connesso'] = $hotel_connesso;
            }
        }

        $is_hotel = Auth::user()->hasRole("hotel");

        $params['is_hotel'] = $is_hotel;

        $params['today'] = Carbon::today()->toDateString();

        return view('admin.caparra_index', $params);
    }

    public function create()
    {
        $options = [];
        $options[1] = __("labels.option_1");
        $options[2] = __("labels.option_2");
        $options[3] = __("labels.option_3");
        $options[4] = str_replace(["$1", "$2"], ['<input type="number" min="0" name="day_before_1" style="width:50px!important" value="" class="input-small" />', '<input min="0" type="number" name="perc_1" class="input-small" style="width:50px !important" value="" />'], __("labels.option_4"));
        $options[5] = __("labels.option_5");
        $options[6] = str_replace(["$1", "$2"], ['<input type="number" min="0" name="day_before_2" style="width:50px!important" value="" class="input-small" />', '<input min="0" type="number" name="month_after_1" class="input-small" style="width:50px !important" value="" />'], __("labels.option_6"));
        $options[7] = str_replace("$1", '<input type="number" min="0" name="day_before_3" style="width:50px!important" value="" class="input-small" />', __("labels.option_7"));
        $options[8] = str_replace(["$1", "$2"], ['<input type="number" min="0" name="day_before_4" style="width:50px!important" value="" class="input-small" />', '<input min="0" type="number" name="perc_2" class="input-small" style="width:50px !important" value="" />'], __("labels.option_8"));
        $options[9] = str_replace(["$1", "$2"], ['<input type="number" min="0" name="day_before_5" style="width:50px!important" value="" class="input-small" />', '<input min="0" type="number" name="month_after_2" class="input-small" style="width:50px !important" value="" />'], __("labels.option_9"));
        $options[10] = str_replace(["$1"], ['<input min="0" type="number" name="perc_3" class="input-small" style="width:50px !important" value="" />'], __("labels.option_10"));
        $options[11] = str_replace(["$1", "$3", "$2"], [
            '<input type="number" min="0" name="day_before_11" style="width:50px!important" value="" class="input-small" />',
            '<input min="0" type="number" name="month_after_11" class="input-small" style="width:50px !important" value="" />',
            '<input min="0" type="number" name="perc_11" class="input-small" style="width:50px !important" value="" />'
        ], __("labels.option_11"));

        $options[12] = str_replace(["$1", "$2"], ['<input type="number" min="0" name="day_before_12" style="width:50px!important" value="" class="input-small" />', '<input min="0" type="number" name="perc_4" class="input-small" style="width:50px !important" value="" />'], __("labels.option_12"));
        $options[13] = str_replace(["$1", "$2"], ['<input type="number" min="0" name="day_before_13" style="width:50px!important" value="" class="input-small" />', '<input min="0" type="number" name="perc_13" class="input-small" style="width:50px !important" value="" />'], __("labels.option_13"));

        $is_hotel = Auth::user()->hasRole("hotel");

        return view('admin.caparra_form', ["politica" => new Caparra(),  "options" => $options, "id" => 0, 'is_hotel' => $is_hotel]);
    }

    public function edit($id)
    {
        $politica = Caparra::where("id", $id)->first();
        if (!$politica)
            redirect('admin/politiche-cancellazione');

        $options = [];
        $options[1] = __("labels.option_1");
        $options[2] = __("labels.option_2");
        $options[3] = __("labels.option_3");

        if ($politica->option == 4)
            $options[4] = str_replace(["$1", "$2"], ['<input type="number" min="0" name="day_before_1" style="width:50px!important" value="' . $politica->day_before . '" class="input-small" />', '<input min="0" type="number" name="perc_1" class="input-small" style="width:50px !important" value="' . $politica->perc . '" />'], __("labels.option_4"));
        else
            $options[4] = str_replace(["$1", "$2"], ['<input type="number" min="0" name="day_before_1" style="width:50px!important" value="" class="input-small" />', '<input min="0" type="number" name="perc_1" class="input-small" style="width:50px !important" value="" />'], __("labels.option_4"));

        $options[5] = __("labels.option_5");

        if ($politica->option == 6)
            $options[6] = str_replace(["$1", "$2"], ['<input type="number" min="0" name="day_before_2" style="width:50px!important" value="' . $politica->day_before . '" class="input-small" />', '<input min="0" type="number" name="month_after_1" class="input-small" style="width:50px !important" value="' . $politica->month_after . '" />'], __("labels.option_6"));
        else
            $options[6] = str_replace(["$1", "$2"], ['<input type="number" min="0" name="day_before_2" style="width:50px!important" value="" class="input-small" />', '<input min="0" type="number" name="month_after_1" class="input-small" style="width:50px !important" value="" />'], __("labels.option_6"));

        if ($politica->option == 7)
            $options[7] = str_replace("$1", '<input type="number" min="0" name="day_before_3" style="width:50px!important" value="' . $politica->day_before . '" class="input-small" />', __("labels.option_7"));
        else
            $options[7] = str_replace("$1", '<input type="number" min="0" name="day_before_3" style="width:50px!important" value="" class="input-small" />', __("labels.option_7"));

        if ($politica->option == 8)
            $options[8] = str_replace(["$1", "$2"], ['<input type="number" min="0" name="day_before_4" style="width:50px!important" value="' . $politica->day_before . '" class="input-small" />', '<input min="0" type="number" name="perc_2" class="input-small" style="width:50px !important" value="' . $politica->perc . '" />'], __("labels.option_8"));
        else
            $options[8] = str_replace(["$1", "$2"], ['<input type="number" min="0" name="day_before_4" style="width:50px!important" value="" class="input-small" />', '<input min="0" type="number" name="perc_2" class="input-small" style="width:50px !important" value="" />'], __("labels.option_8"));

        if ($politica->option == 9)
            $options[9] = str_replace(["$1", "$2"], ['<input type="number" min="0" name="day_before_5" style="width:50px!important" value="' . $politica->day_before . '" class="input-small" />', '<input min="0" type="number" name="month_after_2" class="input-small" style="width:50px !important" value="' . $politica->month_after . '" />'], __("labels.option_9"));
        else
            $options[9] = str_replace(["$1", "$2"], ['<input type="number" min="0" name="day_before_5" style="width:50px!important" value="" class="input-small" />', '<input min="0" type="number" name="month_after_2" class="input-small" style="width:50px !important" value="" />'], __("labels.option_9"));

        if ($politica->option == 10)
            $options[10] = str_replace(["$1"], ['<input min="0" type="number" name="perc_3" class="input-small" style="width:50px !important" value="' . $politica->perc . '" />'], __("labels.option_10"));
        else
            $options[10] = str_replace(["$1"], ['<input min="0" type="number" name="perc_3" class="input-small" style="width:50px !important" value="" />'], __("labels.option_10"));

        if ($politica->option == 11)
            $options[11] = str_replace(["$1", "$3", "$2"], [
                '<input type="number" min="0" name="day_before_11" style="width:50px!important" value="' . $politica->day_before . '" class="input-small" />',
                '<input min="0" type="number" name="month_after_11" class="input-small" style="width:50px !important" value="' . $politica->month_after . '" />',
                '<input min="0" type="number" name="perc_11" class="input-small" style="width:50px !important" value="' . $politica->perc . '" />'
            ], __("labels.option_11"));
        else
            $options[11] = str_replace(["$1", "$3", "$2"], [
                '<input type="number" min="0" name="day_before_11" style="width:50px!important" value="" class="input-small" />',
                '<input min="0" type="number" name="month_after_11" class="input-small" style="width:50px !important" value="" />',
                '<input min="0" type="number" name="perc_11" class="input-small" style="width:50px !important" value="" />'
            ], __("labels.option_11"));


        if ($politica->option == 12)
            $options[12] = str_replace(["$1", "$2"], ['<input type="number" min="0" name="day_before_12" style="width:50px!important" value="' . $politica->day_before . '" class="input-small" />', '<input min="0" type="number" name="perc_4" class="input-small" style="width:50px !important" value="' . $politica->perc . '" />'], __("labels.option_12"));
        else
            $options[12] = str_replace(["$1", "$2"], ['<input type="number" min="0" name="day_before_12" style="width:50px!important" value="" class="input-small" />', '<input min="0" type="number" name="perc_4" class="input-small" style="width:50px !important" value="" />'], __("labels.option_12"));


        if ($politica->option == 13)
            $options[13] = str_replace(["$1", "$2"], ['<input type="number" min="0" name="day_before_13" style="width:50px!important" value="' . $politica->day_before . '" class="input-small" />', '<input min="0" type="number" name="perc_13" class="input-small" style="width:50px !important" value="' . $politica->perc . '" />'], __("labels.option_13"));
        else
            $options[13] = str_replace(["$1", "$2"], ['<input type="number" min="0" name="day_before_13" style="width:50px!important" value="" class="input-small" />', '<input min="0" type="number" name="perc_13" class="input-small" style="width:50px !important" value="" />'], __("labels.option_13"));

        $is_hotel = Auth::user()->hasRole("hotel");

        $today = Carbon::today()->toDateString();

        return view('admin.caparra_form', ["politica" => $politica, "options" => $options,  "id" => $id, 'is_hotel' => $is_hotel, 'today' => $today]);
    }

    public function moderazione()
    {
        $cdms = Caparra::with(["hotel.localita"])->where("enabled", 0)->orderBy("hotel_id")->get();

        $hotel_attivi = Caparra::where("enabled", 1)->orderBy("hotel_id")->count();
        $hotel_attesa = Caparra::where("enabled", 0)->orderBy("hotel_id")->count();

        $hotels = [];

        foreach ($cdms as $cdm) :

            if (!isset($hotels[$cdm->hotel->id])) {

                $hotels[$cdm->hotel->id]              = [];
                $hotels[$cdm->hotel->id]["nome"]      = $cdm->hotel->nome;
                $hotels[$cdm->hotel->id]["indirizzo"] = $cdm->hotel->indirizzo;
                $hotels[$cdm->hotel->id]["localita"]  = $cdm->hotel->localita->nome;
                $hotels[$cdm->hotel->id]["provincia"] = $cdm->hotel->localita->prov;
                $hotels[$cdm->hotel->id]["data"]      = [];
                $hotels[$cdm->hotel->id]["id"]        = $cdm->hotel->id;
            }

            $hotels[$cdm->hotel->id]["data"][] = $cdm;

        endforeach;

        return view('admin.moderazione-caparra', [
            "hotels" =>  $hotels,
            "hotel_attivi" => $hotel_attivi,
            "hotel_attesa" => $hotel_attesa,
        ]);
    }

    function storeModeratore(Request $request)
    {

        $id = $request->get("id");
        $action = $request->get("action");
        $value = $request->get("value");

        $caparra = Caparra::find($id);

        $hotel_id = $caparra->hotel_id;

        if ($action == "status") {

            if ($value == "enabled") {
                $caparra->update(["enabled" => 1]);
            } else {
                $caparra->update(["enabled" => 0]);
            }

            if ($caparra->isAttiva()) {
                $this->_deleteLabelCaparra($hotel_id);
            }
        } else if ($action == "delete") {

            Caparra::where("id", $id)->delete();

            $this->_deleteLabelCaparra($hotel_id);
        }

        return response()->json(['success' => 'success'], 200);
    }

    function store(Request $request)
    {

        $id = $request->get("id");
        if ($id == 0)
            $caparra = new Caparra();
        else
            $caparra = Caparra::find($id);

        $hotel_id = $this->getHotelId();

        $caparra->hotel_id = $hotel_id;
        // $caparra->ip = $request->ip();
        $caparra->from = Carbon::createFromFormat('d/m/Y', $request->get("from"));
        $caparra->to = Carbon::createFromFormat('d/m/Y', $request->get("to"));
        $caparra->option = $request->get("option");
        $caparra->day_before = $request->get("day_before");
        $caparra->perc = $request->get("perc");
        $caparra->month_after = $request->get("month_after");
        $caparra->enabled = true;
        $caparra->save();


        // se ho modificato la caparra && sono un hotel TOLGO LA LABEL
        if ($caparra->wasChanged() && Auth::user()->hasRole("hotel")) {
            $this->_deleteLabelCaparra($hotel_id);
        }


        return response()->json(['success' => 'success'], 200);
    }

    public function create_label(Request $request)
    {
        Validator::make($request->all(), [
            'testo_it' => 'required',
        ])->validate();

        $hotel = Hotel::find($this->getHotelId());

        $label_caparra = new LabelCaparra;

        $sorgente = nl2br(trim($request->get('testo_it')));

        $label_caparra->testo_it = $sorgente;

        foreach (Langs::getAll() as $lang) {
            if ($lang != 'it') {
                $column = 'testo_' . $lang;
                $label_caparra->$column = Utility::translate($sorgente, $lang);
            }
        }

        $label_caparra->hotel_id = $hotel->id;

        $label_caparra->save();

        foreach ($hotel->caparre as $c) {
            $c->label_id = $label_caparra->id;
            $c->save();
        }

        // $hotel->notifyMeLabelCaparra($type = 'create');

        return redirect('admin/elenco_hotel_politiche_cancellazione')->with('status', 'Label creata correttamente');
    }

    public function labelNotLabelable(Request $request)
    {
        $hotel = Hotel::find($this->getHotelId());

        foreach ($hotel->caparre as $caparra) {
            $caparra->labelable = 0;
            $caparra->save();
        }

        return redirect('admin/elenco_hotel_politiche_cancellazione')->with('status', 'Politiche di ' . $hotel->nome . ' (' . $hotel->id . ') non etichettabili');
    }

    public function update_label(Request $request, $label_id  = 0)
    {

        Validator::make($request->all(), [
            'testo_it' => 'required',
        ])->validate();


        $label_caparra = LabelCaparra::find($label_id);
        $modificato_ita = 0;

        ($label_caparra->testo_en == '' || $label_caparra->testo_fr == '' || $label_caparra->testo_de == '') ? $manca_lingua = 1 : $manca_lingua = 0;

        $sorgente = nl2br(trim($request->get('testo_it')));

        // se ho modificato italiano traduco tutto
        if ($label_caparra->testo_it != $sorgente || $manca_lingua) {
            $modificato_ita = 1;

            $label_caparra->testo_it = $sorgente;

            foreach (Langs::getAll() as $lang) {
                if ($lang != 'it') {
                    $column = 'testo_' . $lang;
                    $label_caparra->$column = Utility::translate($sorgente, $lang);
                }
            }
        } else {

            foreach (Langs::getAll() as $lang) {
                $column = 'testo_' . $lang;
                $label_caparra->$column = nl2br(trim($request->get($column)));
            }
        }

        $label_caparra->save();

        // if ($modificato_ita) {
        //     $label_caparra->hotel->notifyMeLabelCaparra($type = 'update');
        // }

        return redirect('admin/politiche-cancellazione')->with('status', 'Label aggiornata correttamente');
    }


    public function delete_label($label_id  = 0)
    {
        $label_caparra = LabelCaparra::find($label_id);

        $this->_deleteLabelCaparra($label_caparra->hotel_id);


        return redirect('admin/politiche-cancellazione')->with('status', 'Label eliminata correttamente');
    }

    private function _deleteLabelCaparra($hotel_id = 0)
    {
        Caparra::where('hotel_id', $hotel_id)
            ->update(['label_id' => NULL]);

        $hotel = Hotel::find($hotel_id);

        if (!is_null($hotel->labelCaparre)) {
            $hotel->labelCaparre->delete();
            // $hotel->notifyMeLabelCaparra($type = 'delete');
        }
    }


    public function elencoPoliticheCancellazione($hotel_id = 0)
    {

        $hotel = Hotel::find($hotel_id);

        $ui_editing_hotel = "$hotel->id $hotel->nome";

        Auth::user()->setUiEditingHotelId($hotel_id);
        Auth::user()->setUiEditingHotel($ui_editing_hotel);
        Auth::user()->setUiEditingHotelPriceList($hotel->hide_price_list);

        return redirect('admin/politiche-cancellazione');
    }
}
