<?php

namespace App\Http\Controllers\Admin;

use App\CmsPagina;
use App\Http\Controllers\Controller;
use App\Macrolocalita;
use App\Utility;
use DB;
use Illuminate\Http\Request;
use SessionResponseMessages;



class MenusController extends AdminBaseController
{



  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index()
  {

    $macrolocalita = Macrolocalita::all();

    $menus = DB::table("tblMenuTematico")->get();

    $records = [];
    $cms_pagine_ids = [];
    foreach ($menus as $menu) {
      $records[$menu->macrolocalita_id][$menu->lang_id][$menu->type][$menu->ord] = $menu;
      $cms_pagine_ids[] = $menu->cms_pagine_id;
    }

    //dd($records);

    $tmp = CmsPagina::whereIn("id", $cms_pagine_ids);
    $cms_pagine = [];
    foreach ($tmp as $cms_pagina)
      $cms_pagine[$cms_pagina->id] = $cms_pagina;

    $data = ["records" => $records, "cms_pagine" => $cms_pagine, "macrolocalita" => $macrolocalita];

    return view('admin.menus_index', compact("data"));
  }


  private function _risolviConflittoOrderMenu(Request $request, $max, $menu_id, $macrolocalita_id, $type)
  {
    $max++;

    DB::table("tblMenuTematico")
      ->where("id", $menu_id)
      ->update(['ord' => $max]);

    SessionResponseMessages::add("error", "Fai refresh della pagina e controlla menu '$type'. Il sistema ha gestito un conflitto nel menu. PRIMA FARE REFRESH DELLA PAGINA e poi controllare l'ordine delle voci");
    return SessionResponseMessages::redirect("/admin/menus/{$macrolocalita_id}/edit", $request);
  }

  private function _getCmsPagineIdsNoMacro()
  {
    $array_ids = [];
    $res = DB::select("select id from tblCmsPagine where attiva = 1 and listing_macrolocalita_id = 0 and template in ('listing','statica')");
    foreach ($res as $res_row)
      $array_ids[] = $res_row->id;

    return $array_ids;
  }

  /* 
  Le pagine che posso selezionare NON SONO solo quelle
  con template listing, ma anche con template statico 
  (ci sono delle statiche che hanno i listing)

  Inoltre aggiungo le pagine X le quali NON E' definita né località, né macrolocalità
  in modo che POSSO creare delle pagine trasversali a più località

  RIVIERA ROMAGNOLA ??
   */
  protected function getCmsPagineIds($macrolocalita_id, $from = null)
  {
    $menus = DB::table("tblMenuTematico")
      ->whereIn("macrolocalita_id", [$macrolocalita_id, 0])
      ->get();

    $cms_pagine_ids = [];
    foreach ($menus as $menu)
      $cms_pagine_ids[] = $menu->cms_pagine_id;

    $tmp = CmsPagina::attiva()
      ->whereIn("template", ["listing", "statica"])
      ->whereIn("listing_macrolocalita_id", [$macrolocalita_id, 0])
      ->whereNotIn("id", $cms_pagine_ids)
      ->get();

    $cms_pagine = [];
    //? solo se vengo dal controller edit che chiama il menus_edit.blade
    //? voglio avere anche il riferimento del template
    if ($from == 'edit') {
      foreach ($tmp as $cms_pagina)
        $cms_pagine[$cms_pagina->template][$cms_pagina->lang_id][$cms_pagina->id] = $cms_pagina->uri;
    } else {
      foreach ($tmp as $cms_pagina)
        $cms_pagine[$cms_pagina->lang_id][$cms_pagina->id] = $cms_pagina->uri;
    }


    return $cms_pagine;
  }

  /**
   * // !Edit page 
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */


  public function edit(Request $request, $macrolocalita_id)
  {
    $cms_pagine = $this->getCmsPagineIds($macrolocalita_id, $from = 'edit');

    $tmp = CmsPagina::attiva()
      ->where("template", "localita")
      ->where("menu_macrolocalita_id", $macrolocalita_id)
      ->get();

    $pagine = [];
    foreach ($tmp as $cms_pagina) {
      $pagine[$cms_pagina->lang_id] = $cms_pagina->uri;
    }

    $menus = DB::table("tblMenuTematico")
      ->join("tblCmsPagine", "tblCmsPagine.id", "=", "tblMenuTematico.cms_pagine_id")
      ->select("tblMenuTematico.*", "tblCmsPagine.uri", "tblCmsPagine.menu_dal", "tblCmsPagine.menu_al", "tblCmsPagine.menu_auto_annuale", "tblCmsPagine.template")
      ->whereIn("macrolocalita_id", [$macrolocalita_id, 0])
      ->get();


    /**
     * Trovo il massimo valore dell'ordinamento per ciascuna tipologia di link (E' il valore che assegno in caso di CONFLITTO!!!)
     */
    $max['servizi'] = 0;
    $max['offerte'] = 0;
    $max['trattamenti'] = 0;
    $max['parchi'] = 0;
    $max['visibilita'] = 0;
    $max['famiglia'] = 0;
    $max['poi'] = 0;


    foreach ($menus as $row) {
      if ($row->ord > $max[$row->type]) {
        $max[$row->type] = $row->ord;
      }
    }

    $servizi = [];
    $offerte = [];
    $trattamenti = [];
    $parchi = [];
    $visibilita = [];
    $famiglia = [];
    $poi = [];

    /**
     * ATTENZIONE: per qualche motivo ci sono dei link della tabella tblMenuTematico che hanno, per la stessa lingua, lo stesso "type" e lo stesso "ord"
     * QUINDI ad esempio per il type = "offerte" 
     * $offerte[$row->lang_id][$row->ord] = $row;
     * verrà sovrascritto e prenderà SOLO l'ULTIMO VALORE, ecco perché alcuni link SPARISCONO !!!!
     */
    foreach ($menus as $row) {
      if ($row->type === 'servizi') {
        // se questa posizione è già occupata
        if (!empty($servizi[$row->lang_id][$row->ord])) {
          $this->_risolviConflittoOrderMenu($request, $max['servizi'], $row->id, $macrolocalita_id, $row->type);
        }
        $servizi[$row->lang_id][$row->ord] = $row;
      }

      if ($row->type === 'offerte') {
        // se questa posizione è già occupata
        if (!empty($offerte[$row->lang_id][$row->ord])) {
          $this->_risolviConflittoOrderMenu($request, $max['offerte'], $row->id, $macrolocalita_id, $row->type);
        }
        $offerte[$row->lang_id][$row->ord] = $row;
      }

      if ($row->type === 'trattamenti') {
        // se questa posizione è già occupata
        if (!empty($trattamenti[$row->lang_id][$row->ord])) {
          $this->_risolviConflittoOrderMenu($request, $max['trattamenti'], $row->id, $macrolocalita_id, $row->type);
        }
        $trattamenti[$row->lang_id][$row->ord] = $row;
      }


      if ($row->type === 'parchi') {
        // se questa posizione è già occupata
        if (!empty($parchi[$row->lang_id][$row->ord])) {
          $this->_risolviConflittoOrderMenu($request, $max['parchi'], $row->id, $macrolocalita_id, $row->type);
        }
        $parchi[$row->lang_id][$row->ord] = $row;
      }

      if ($row->type === 'visibilita') {
        // se questa posizione è già occupata
        if (!empty($visibilita[$row->lang_id][$row->ord])) {
          $this->_risolviConflittoOrderMenu($request, $max['visibilita'], $row->id, $macrolocalita_id, $row->type);
        }
        $visibilita[$row->lang_id][$row->ord] = $row;
      }

      if ($row->type === 'famiglia') {
        // se questa posizione è già occupata
        if (!empty($famiglia[$row->lang_id][$row->ord])) {
          $this->_risolviConflittoOrderMenu($request, $max['famiglia'], $row->id, $macrolocalita_id, $row->type);
        }
        $famiglia[$row->lang_id][$row->ord] = $row;
      }

      if ($row->type === 'poi') {
        // se questa posizione è già occupata
        if (!empty($poi[$row->lang_id][$row->ord])) {
          $this->_risolviConflittoOrderMenu($request, $max['poi'], $row->id, $macrolocalita_id, $row->type);
        }
        $poi[$row->lang_id][$row->ord] = $row;
      }
    }

    foreach ($servizi as $lang_id => $void)
      ksort($servizi[$lang_id]);
    foreach ($offerte as $lang_id => $void)
      ksort($offerte[$lang_id]);
    foreach ($trattamenti as $lang_id => $void)
      ksort($trattamenti[$lang_id]);
    foreach ($parchi as $lang_id => $void)
      ksort($parchi[$lang_id]);
    foreach ($visibilita as $lang_id => $void)
      ksort($visibilita[$lang_id]);
    foreach ($famiglia as $lang_id => $void)
      ksort($famiglia[$lang_id]);
    foreach ($poi as $lang_id => $void)
      ksort($poi[$lang_id]);

    $data = [
      "poi" => $poi,
      "servizi" => $servizi,
      "offerte" => $offerte,
      "trattamenti" => $trattamenti,
      "parchi" => $parchi,
      "visibilita" => $visibilita,
      "famiglia" => $famiglia,
      "cms_pagine" => $cms_pagine,
      "pagine" => $pagine,
      "macrolocalita_id" => $macrolocalita_id,
      "macrolocalita" => Macrolocalita::findOrFail($macrolocalita_id)
    ];

    //dd($data['cms_pagine']);

    return view('admin.menus_edit', compact("data"));
  }


  /**
   * Store a newly created resource in storage.
   * @param  Request  $request
   * @return Response
   */
  public function add(Request $request)
  {
    /*
     * Cancello sempre tutti i record e li inserisco nuovamente
     */

    $macrolocalita_id = (int)$request->input("macrolocalita_id");

    //DB::table("tblMenuTematico")->where("macrolocalita_id", $macrolocalita_id)->delete();

    /*
     * Devo capire da quale ordinamento partire
     */
    $ords_last = DB::table("tblMenuTematico")
      ->select("type", "lang_id", DB::raw("MAX(ord) as ord"))
      ->whereIn("macrolocalita_id", [$macrolocalita_id, 0])
      ->groupBy("type")
      ->groupBy("lang_id")
      ->get();

    $ords = [];
    if ($ords_last)
      foreach ($ords_last as $row)
        $ords[$row->lang_id][$row->type] = $row->ord;


    $cms_pagine = $this->getCmsPagineIds($macrolocalita_id);


    $data = [];

    // array degli id delle  pagine di tipo "listing","statica" che hanno macro = 0
    $tmp = $this->_getCmsPagineIdsNoMacro();

    foreach ($cms_pagine as $lang => $_cms_pagine)
      foreach ($_cms_pagine as $id => $uri) {
        $type = $request->input("cms_pagina_{$lang}_{$id}", 0);

        if ($type) {
          if (!isset($ords[$lang][$type]))
            $ords[$lang][$type] = 0;

          $ords[$lang][$type]++;

          /*
          Devo sapere se la pagina che associo a questo menu ha idMacro = 0 
          se è così (E IL MENU NON E' DELLA MACRO RR) è una pagina trasversale e la devo mettere in tblMenuTematico con macrolocalita_id = 0
           */

          //$macro = (in_array($id,$tmp) && $macrolocalita_id != Utility::getMacroRR()) ? 0 : $macrolocalita_id;
          $macro = $macrolocalita_id;

          $data[] = [
            "lang_id" => $lang,
            "type" => $type,
            "macrolocalita_id" => $macro,
            "cms_pagine_id" => $id,
            "ord" => $ords[$lang][$type]
          ];
        }
      }

    if (!empty($data)) {
      DB::table("tblMenuTematico")->insert($data);

      SessionResponseMessages::add("success", "Link aggiunti con successo.");
    }


    return SessionResponseMessages::redirect("/admin/menus/$macrolocalita_id/edit", $request);
  }

  public function saveOrder(Request $request)
  {
    $tmp = $request->input("ids");

    $ids = [];
    if (strpos($tmp, ',') !== false)
      $ids = explode(',', $tmp);

    if ($ids) {
      DB::table("tblMenuTematico")
        ->whereIn("id", $ids)
        ->update(['ord' => 0]);

      $i = 0;
      foreach ($ids as $id) {
        $i++;

        DB::table("tblMenuTematico")
          ->where("id", $id)
          ->update(['ord' => $i]);
      }
    }
  }


  public function delete(Request $request, $id, $id_macro)
  {

    // Estraggo il record che voglio cancellare
    $row_rm = DB::table("tblMenuTematico")
      ->where("id", $id)
      ->first();

    // Decremento la posizione di ordinamento per tutti i record successivi
    DB::table("tblMenuTematico")
      ->where("lang_id", $row_rm->lang_id)
      ->where("type", $row_rm->type)
      ->where("macrolocalita_id", $id_macro)
      ->where("ord", ">", $row_rm->ord)
      ->update(["ord" => DB::raw("ord -1")]);

    // infine cancello il record, sicuro di non aver prodotto un buco nell'ordinamento
    DB::table("tblMenuTematico")
      ->where("id", $id)
      ->delete();

    SessionResponseMessages::add("success", "Modifiche salvate con successo.");

    return SessionResponseMessages::redirect("/admin/menus/{$id_macro}/edit", $request);
  }
}
