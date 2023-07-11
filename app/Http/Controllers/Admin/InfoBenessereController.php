<?php

namespace App\Http\Controllers\Admin;

use Langs;
use App\Hotel;
use App\Utility;
use App\Http\Requests;
use App\InfoBenessere;
use Illuminate\Http\Request;
use SessionResponseMessages;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\Admin\InfoBenessereRequest;

class InfoBenessereController extends AdminBaseController
{
    function __construct()
    {

      $this->boolean_fields = [
                              'area_fitness',
                              'aperto_annuale',
                              'a_pagamento',
                              'in_hotel',
                              'piscina',
                              'idro',
                              'sauna_fin',
                              'b_turco',
                              'docce_emo',
                              'cascate_ghiaccio',
                              'percorso_kneipp',
                              'aromaterapia',
                              'cromoterapia',
                              'massaggi',
                              'tratt_estetici',
                              'area_relax',
                              'letto_marmo_riscaldato',
                              'stanza_sale',
                              'kit_benessere',
                              'obbligo_prenotazione',
                              'uso_in_esclusiva',
                              ];

      $this->caratt_fields = [
                            'piscina',
                            'idro',
                            'sauna_fin',
                            'b_turco',
                            'docce_emo',
                            'cascate_ghiaccio',
                            'percorso_kneipp',
                            'aromaterapia',
                            'cromoterapia',
                            'massaggi',
                            'tratt_estetici',
                            'area_relax',
                            'letto_marmo_riscaldato',
                            'stanza_sale',
                            'kit_benessere',
                            ];
  
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
public function getCaratt_fields()
  {
    $caratteristiche = $this->caratt_fields;

    return $caratteristiche;
  }

  private function sendMailSuggerimento($sezione='Piscina', $hotel,$suggerimento='')
  {

    Utility::swapToSendGrid();

    $from = "assistenza@info-alberghi.com";
    $nome = "Per lo staff di Info Alberghi";  

    $oggetto = "Suggerimento $sezione";

    $hotel_id = $hotel->id; 
    $nome_cliente = $hotel->nome;

    if (App::environment() == "local") 
      $email_destinatario = 'giovanni@info-alberghi.com';
    else 
      $email_destinatario = "assistenza@info-alberghi.com";
  
    $mail_to_send = 'emails.suggerimenti_benessere';

    Mail::send($mail_to_send, compact(

      'suggerimento', 
      'nome_cliente', 
      'hotel_id',
      'tipo',
      'sezione'

    ), function ($message) use ($from, $oggetto, $nome, $email_destinatario) {

        $message->from($from, $nome);
        $message->to($email_destinatario);

        if (App::environment() != "local") 
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
      
      if ($hotel->has('infoBenessere') && !is_null($hotel->infoBenessere)) 
        {
        $info = $hotel->infoBenessere;
        } 
      else 
        {
          $info = new InfoBenessere;
          $hotel->infoBenessere()->save($info);
        }


      $mesi = Utility::mesi();
      return view('admin.info-benessere_edit', compact('info', 'mesi'));
      }



    /**
     * [reset cancella infoPiscina e chiama index che la ricrea vuota]
     * @return [type] [description]
     */
    public function reset($ajax_call = 0)
      {
      $hotel = Hotel::find($this->getHotelId());

      $hotel->infoBenessere()->delete();

      if(!$ajax_call)
        {
        return Redirect::to('admin/info-benessere');
        }
      else
        {
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
    // public function store(InfoBenessereRequest $request)
    // @Lucio 27/04 Togliere tutte le validazioni da Piscina e Benessere!!!
    public function store(Request $request)
    {

        /*dd($request->all());*/

        $hotel_id = $this->getHotelId();
        $hotel = Hotel::find($this->getHotelId());
        Utility::clearCacheHotel($hotel_id);
        
        //////////////////////////////////////////////////////
        // nell'index creo sempre la relazione !!           //
        //////////////////////////////////////////////////////
        $info = $hotel->infoBenessere;
		    $suggerimento = ""; 
		
        foreach ($this->boolean_fields as $field) 
          {
          $info->$field = 0;
          }

        // se peculiarità è compilata in italiano
        // traduco i campi in lingua che NON SONO compilati
        // e li rimetto nella request
        if ($request->get('peculiarita') != '') {
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

        $info->fill($request->all());

        if ($request->filled('suggerimento')) 
          {
          $suggerimento = $request->get('suggerimento');
          } 
        ///////////////////////////////////////////////////
        // INVIO MAIL NOTIFICA MODIFICA PER APPROVAZIONE //
        ///////////////////////////////////////////////////
           
        /*if ($info->suggerimento != $suggerimento) 
          {

          $info->suggerimento = $suggerimento;
          try 
            {
            
            self::sendMailSuggerimento($sezione='Benessere', $hotel,$suggerimento);
            SessionResponseMessages::add("success", "Grazie per i tuoi suggerimenti, per noi sono importanti!");
            
            } 
          catch (\Exception $e) 
            {

            SessionResponseMessages::add("error", "NON è stato possibile inviare i tuoi suggerimenti. Riprova più tardi !!! [".$e->getMessage()."]" );
            
            }

          }*/

        // NON HO ANCORA SALVATO $info !!!
        $hotel->infoBenessere()->save($info);  
        SessionResponseMessages::add("success", "Modifiche salvate con successo !!.");
        return SessionResponseMessages::redirect("admin/info-benessere", $request);

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
