<?php

namespace App\Http\Controllers\Admin;

use App\CmsPagina;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\ParolaChiave;
use App\ParolaChiaveEspansa;
use App\Utility;
use Illuminate\Http\Request;
use Langs;
use SessionResponseMessages;

class ParoleChiaveController extends Controller
  {

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index($id = 0)
  {
    $data = [];

    $data['records'] = [];
    $data['record'] = null;
    $data['id'] = $id;

    $data['records'] = ParolaChiave::with('alias')      
      ->orderBy("chiave", "asc")
      ->get();

    if ($data['id'])
    {
        $data['record'] = ParolaChiave::findOrFail($data['id']);
        $data['record_keywords'] = $data['record']->alias()->get();
        $data['pagine'] = CmsPagina::where("listing_parolaChiave_id", $data['id'])->get();
    }

    return view("admin.parole-chiave_index", compact('data'));
  }

  /**
   * Gestisco tutti i salvataggi
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function save(Request $request)
    {

    // Accetto solo i POST
    if ($request->method() == 'POST')
      {

      // Nuova Parola chiave
      if ($request->input('parola-chiave.0'))
        {
        $keyword = new ParolaChiave();
        $keyword->chiave = $request->input('parola-chiave.0');
        $keyword->save();

        $id = $keyword->id;

        SessionResponseMessages::add("success", "Parola chiave inserita");
        }
      else // Non è un inserimento, ma una modifica della parola chiave
        {
        if ($request->input('id') > 0)
          {
          $id = $request->input('id');

          // Se è impostato, aggiungo una nuova parola chiave estesa alla parola chiave
          if ($request->input('parola-chiave-ext.0'))
            {
            $keyword_ext = new ParolaChiaveEspansa();
            $keyword_ext->parolaChiave_id = $request->input('id');
            $keyword_ext->chiave = $request->input('parola-chiave-ext.0');
            $keyword_ext->save();

            SessionResponseMessages::add("success", "Parola chiave espansa inserita");
            }

          // Di base salvo sempre tutto

          // l'eventuale modifica del nome della keyword
          $keyword = ParolaChiave::find($id);

          if ($keyword)
            {

            $keyword->ricerca_mappa = $request->filled('ricerca_mappa');

            $keyword->chiave = $request->input('parola-chiave');


            //////////////////////////////////////////////
            // inserimento label in lingua per la mappa //
            //////////////////////////////////////////////
            if($request->get('nome_it') != '')
              {

              $valore_it = $request->get('nome_it');
              $keyword->nome_it = $valore_it;
              foreach (Langs::getAll() as $lang) 
                {
                if($lang != 'it')
                  {

                  $col = 'nome_'.$lang;
                  $valore = $request->get($col);
                  if ($valore == '') 
                    {
                    $valore = Utility::translate($valore_it, $lang);
                    }  
                  
                  $keyword->$col = $valore;
                  
                  }
                }

              }

            if($request->get('mappa_dal') != '' && $request->get('mappa_al') != '')
              {
              $keyword->mappa_dal = Utility::getCarbonDate($request->get('mappa_dal'));
              $keyword->mappa_al = Utility::getCarbonDate($request->get('mappa_al'));
              }
            
            $keyword->save();

            $to_delete = $request->input('parola-chiave-ext-delete', []);

            $deleted = 0;

            // Tutte le modifiche alle keyword
            foreach ($request->input('parola-chiave-ext') as $parola_chiave_esp_id => $parola_chiave_esp_chiave)
              {
              // Salto quelli con id 0, perché è una chiave nuova
              if ($parola_chiave_esp_id == 0)
                continue;

              $keyword_ext = ParolaChiaveEspansa::find($parola_chiave_esp_id);

              if ($keyword_ext)
                {
                if (in_array($keyword_ext->id, $to_delete))
                  {
                  $keyword_ext->delete();
                  $deleted++;
                  }
                else
                  {
                  $keyword_ext->chiave = $parola_chiave_esp_chiave;
                  $keyword_ext->save();
                  }
                }
              else
                SessionResponseMessages::add("error", "Si è verificato un errore durante il controllo dei dati (Parola Chiave Espansa non trovata $parola_chiave_esp_id).");
              }

            if ($deleted)
              SessionResponseMessages::add('success', "Eliminate correttamente $deleted Parole chiavi espanse.");
            }
          else
            SessionResponseMessages::add("error", "Si è verificato un errore durante il controllo dei dati (Parola Chiave trovata).");

          }
        else
          SessionResponseMessages::add("error", "Si è verificato un errore durante il controllo dei dati (Id non trovato).");
        }

      if (!SessionResponseMessages::hasErrors())
        return SessionResponseMessages::redirect("/admin/parole-chiave/{$id}/edit", $request);
      else
        return SessionResponseMessages::redirect("/admin/parole-chiave", $request);
      }
    }

  public function delete(Request $request)
    {
    if ($request->method() == 'POST')
      {
      $id = $request->input('id');
      $keyword = ParolaChiave::find($id);

      if ($keyword)
        $keyword->delete();
      else
        SessionResponseMessages::add("error", "Si è verificato un errore durante il controllo dei dati (Id non trovato).");

      if (!SessionResponseMessages::hasErrors())
        SessionResponseMessages::add("success", "Parola chiave eliminata correttamente");

      return SessionResponseMessages::redirect("/admin/parole-chiave", $request);
      }
    }
  }
