<?php

namespace App\Http\Controllers\Admin;

use App\GruppoHotel;
use App\Hotel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GruppoHotelRequest;
use Illuminate\Http\Request;
use SessionResponseMessages;

class GruppoHotelController extends Controller
{

  private function _getHotelsSelect($notInGroup = 1)
    {
    $hotels = [];
    
    if ($notInGroup) 
      {
      $collection = Hotel::notInGroup()->pluck('nome','id');
      } 
    else 
      {
      $collection = Hotel::pluck('nome','id');
      }
    
    
    foreach ($collection as $id => $nome) 
      {
      $hotels[$id] = '(' . $id .')' . ' ' .$nome;
      }

    return $hotels;  
    }

  private function _delHotelGruppo(GruppoHotel $gruppo)
    {
    foreach ($gruppo->hotels as $hotel) 
      {
      $hotel->gruppo_id = 0;
      $hotel->save();
      }
    }

  private function _setHotelGruppo(GruppoHotelRequest $request, $id_gruppo=0)
    {
    if (count($request->hotels) > 0) 
      {
      foreach ($request->hotels as $hotel_id) 
        {
        $hotel = Hotel::findOrFail($hotel_id);
        $hotel->gruppo_id = $id_gruppo;
        $hotel->save();
        }
      }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    
    $gruppi = GruppoHotel::with(['hotels'])->get();

    return view('admin.gruppi_hotels_index', compact("gruppi"));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    $gruppo = New GruppoHotel;
    $hotels = $this->_getHotelsSelect($notInGroup = 1);
    $associated_hotels = null;
    return view('admin.gruppi_hotels_form', compact('gruppo','hotels','associated_hotels'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GruppoHotelRequest $request)
    {
      $gruppo = New GruppoHotel;
      $gruppo->nome = $request->nome;
      $gruppo->note = $request->note;
      $gruppo->save();

      $this->_setHotelGruppo($request, $gruppo->id);

      SessionResponseMessages::add("success", "Il gruppo è stato creato con successo");
      return SessionResponseMessages::redirect("admin/gruppo-hotels", $request);

      
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
     $gruppo = GruppoHotel::findOrFail($id);
     $hotels = $this->_getHotelsSelect($notInGroup = 0);
     $associated_hotels = $gruppo->hotels()->pluck('id')->toArray();
     return view('admin.gruppi_hotels_form', compact('gruppo','hotels','associated_hotels'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(GruppoHotelRequest $request, $id)
      {

      $gruppo = GruppoHotel::findOrFail($id);
      $gruppo->nome = $request->nome;
      $gruppo->note = $request->note;
      $this->_delHotelGruppo($gruppo);
      $gruppo->save();

      $this->_setHotelGruppo($request, $id);

      SessionResponseMessages::add("success", "Il gruppo è stato modificato con successo");
      return SessionResponseMessages::redirect("admin/gruppo-hotels", $request);

      }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
      $gruppo = GruppoHotel::findOrFail($id);
      $this->_delHotelGruppo($gruppo);
      
      $gruppo->delete();

      SessionResponseMessages::add("success", "Il gruppo è stato eliminato con successo");
      return SessionResponseMessages::redirect("admin/gruppo-hotels", $request);      
    }
}
