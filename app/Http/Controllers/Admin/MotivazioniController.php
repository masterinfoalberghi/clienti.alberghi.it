<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Motivazione;
use Illuminate\Http\Request;
use SessionResponseMessages;

class MotivazioniController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $motivazioni = Motivazione::orderBy('id','desc')->get();

        return view('admin.motivazioni_index', compact('motivazioni'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'motivazione' => 'required',
        ]);

        $motivazione = Motivazione::create(['motivazione' => $request->get('motivazione')]);

        SessionResponseMessages::add("success", "Inserimento effettuato con successo.");
        
        return SessionResponseMessages::redirect("admin/motivazioni", $request);
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
        $motivazione = Motivazione::find($id);
        $motivazioni = Motivazione::orderBy('id','desc')->get();

        return view('admin.motivazioni_index', compact('motivazioni','motivazione'));
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

        $request->validate([
            'motivazione' => 'required',
        ]);
        $motivazione = Motivazione::find($id);
        $motivazione->motivazione = $request->get('motivazione');
        $motivazione->save();

        SessionResponseMessages::add("success", "Aggiornamento effettuato con successo.");
        
        return SessionResponseMessages::redirect("admin/motivazioni", $request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $motivazione = Motivazione::find($id);
        if(!is_null($motivazione)) 
          {
          $motivazione->offerte()->detach();
          $motivazione->prenotaPrima()->detach();
          $motivazione->delete();
          }

        SessionResponseMessages::add("success", "Cancellazione effettuato con successo.");
        
        return SessionResponseMessages::redirect("admin/motivazioni", $request);
    
    }
}
