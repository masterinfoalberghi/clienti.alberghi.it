<?php

namespace App\Http\Controllers\Admin;

use App\Hotel;
use App\Http\Requests\Admin\InfoPiscinaRequest;
use App\InfoPiscina;
use App\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Langs;
use SessionResponseMessages;

class InfoPiscinaController extends AdminBaseController
{
    public function __construct()
    {

        $this->radio_fields = ['pos_giardino', 'pos_panoramica', 'pos_spiaggia', 'pos_esterna', 'pos_interna', 'pos_rialzata'];
        $this->radio_fields_vasca_idro = ['vasca_idro_pos_giardino', 'vasca_idro_pos_panoramica', 'vasca_idro_pos_spiaggia', 'vasca_idro_pos_esterna', 'vasca_idro_pos_interna', 'vasca_idro_pos_rialzata'];
        $this->radio_fields_vasca = ['vasca_idro_pos_giardino', 'vasca_idro_pos_panoramica', 'vasca_idro_pos_spiaggia', 'vasca_idro_pos_esterna', 'vasca_idro_pos_interna', 'vasca_idro_pos_rialzata'];

        $this->check_fields = ['aperto_annuale', 'coperta', 'riscaldata',
            'salata',
            'idro',
            'idro_cervicale',
            'scivoli',
            'trampolino',
            'aperitivi',
            'getto_bolle',
            'cascata',
            'musica_sub',
            'espo_sole_tutto_giorno',
            'wi_fi',
            'pagamento',
            'salvataggio',
            'nuoto_contro',
            'vasca_bimbi_riscaldata',
            'vasca_idro_riscaldata',
            'vasca_pagamento',
        ];

        $this->caratt_fields = ['coperta',
            'riscaldata',
            'salata',
            'idro',
            'idro_cervicale',
            'scivoli',
            'trampolino',
            'aperitivi',
            'getto_bolle',
            'cascata',
            'musica_sub',
            'wi_fi',
            'pagamento', 'salvataggio', 'nuoto_contro'];

    }

////////////////////////////////////
    // li espongo per il composer !!! //
    ////////////////////////////////////
    public function getPos_fields()
    {
        return $this->radio_fields;
    }

////////////////////////////////////
    // li espongo per il composer !!! //
    ////////////////////////////////////
    public function getPos_fields_vasca_idro()
    {
        return $this->radio_fields_vasca_idro;
    }

////////////////////////////////////
    // li espongo per il composer !!! //
    ////////////////////////////////////
    public function getCaratt_fields()
    {
        $caratteristiche = $this->caratt_fields;

        return $caratteristiche;
    }

    private function sendMailSuggerimento($sezione = 'Piscina', $hotel, $suggerimento = '', $ambito = '')
    {

        Utility::swapToSendGrid();

        $from = "assistenza@info-alberghi.com";
        $nome = "Per lo staff di Info Alberghi";

        $oggetto = "Suggerimento $ambito $sezione";

        $hotel_id = $hotel->id;
        $nome_cliente = $hotel->nome;

        if (App::environment() == "local") {
            $email_destinatario = 'giovanni@info-alberghi.com';
        } else {
            $email_destinatario = "assistenza@info-alberghi.com";
        }

        $mail_to_send = 'emails.suggerimenti';

        Mail::send($mail_to_send,

            compact(

                'suggerimento',
                'nome_cliente',
                'hotel_id',
                'tipo',
                'sezione',
                'ambito'

            ), function ($message) use ($from, $oggetto, $nome, $email_destinatario) {

                $message->from($from, $nome);
                $message->to($email_destinatario);
                $message->bcc('giovanni@info-alberghi.com');
                $message->subject($oggetto);

            });

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $hotel = Hotel::find($this->getHotelId());

        if ($hotel->has('infoPiscina') && !is_null($hotel->infoPiscina)) {
            $info = $hotel->infoPiscina;
        } else {
            $info = new InfoPiscina;
            $hotel->infoPiscina()->save($info);
        }

        $mesi = Utility::mesi();
        return view('admin.info-piscina_edit', compact('info', 'mesi'));
    }

    /**
     * [reset cancella infoPiscina e chiama index che la ricrea vuota]
     * @return [type] [description]
     */
    public function reset($ajax_call = 0)
    {
        $hotel = Hotel::find($this->getHotelId());

        $hotel->infoPiscina()->delete();

        if (!$ajax_call) {
            return Redirect::to('admin/info-piscina');
        } else {
            echo "ok";
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(InfoPiscinaRequest $request)
    // @Lucio 27/04 Togliere tutte le validazioni da Piscina e Benessere!!!
    public function store(Request $request)
    {
        
        $hotel_id = $this->getHotelId();
        $hotel = Hotel::find($this->getHotelId());
        Utility::clearCacheHotel($hotel_id);

        //////////////////////////////////////////////////////
        // nell'index creo sempre la relazione !!           //
        //////////////////////////////////////////////////////
        $info = $hotel->infoPiscina;

        foreach ($this->check_fields as $field) {
            $info->$field = 0;
        }

        // gestione checkbox suggerimento_visibile
        if ($request->has('suggerimento_visibile') && $request->get('suggerimento_visibile') == 1) {
            $info->suggerimento_visibile = 1;
        } else {
            $info->suggerimento_visibile = 0;
        }

        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // se il campo deve essere visibilie //
        // modifico i cmpi della request che sono in lingua con le traduzioni se non ci sono e se c'è la versione italiana //
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        if ($info->suggerimento_visibile && $request->get('suggerimento_posizione_it') != '') {
            $value = $request->get('suggerimento_posizione_it');
            foreach (Langs::getAll() as $lang) {
                if ($lang != 'it') {
                    $field = 'suggerimento_posizione_' . $lang;
                    if ($request->get($field) == '') {
                        $request->merge([$field => Utility::translate($value, $lang)]);
                    }
                }
            }
        }



        // se peculiarità è compilata in italiano
        // traduco i campi in lingua che NON SONO compilati
        // e li rimetto nella request
        if($request->get('peculiarita') != '') {
            $value = $request->get('peculiarita');
            foreach (Langs::getAll() as $lang) {
                if ($lang != 'it') {
                    $field = 'peculiarita_' . $lang;
                    if ($request->get($field) == '') {
                        $request->merge([$field => Utility::translate($value, $lang)]);
                    }
                }
            }
        }

        $info->fill($request->except(['posizione', 'posizione_vasca']));

        if ($request->filled('posizione')) {
            foreach ($this->radio_fields as $value) {
                $info->$value = 0;
            }
            $pos = $request->get('posizione');
            $info->$pos = 1;
        }

        if ($request->filled('posizione_vasca')) {
            foreach ($this->radio_fields_vasca as $value) {
                $info->$value = 0;
            }
            $pos = $request->get('posizione_vasca');
            $info->$pos = 1;
        }

        $hotel->infoPiscina()->save($info);

        SessionResponseMessages::add("success", "Modifiche salvate con successo !!.");
        return SessionResponseMessages::redirect("admin/info-piscina", $request);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
