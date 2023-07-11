<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\StatUpselling;
use Carbon\Carbon;

class StatsMailUpsellingController extends Controller
  {

  protected $lowest_year;

  private function getAllYears($with_zero = false)
    {
    $years = [];

    if ($with_zero)
      $years[] = 'Tutti';

    if (!$this->lowest_year)
      $this->lowest_year = StatUpselling::selectRaw('YEAR(created_at) AS anno_vecchio')
          ->orderBy('id', 'asc')
          ->first()['anno_vecchio'];

    $current_date = Carbon::now();

    foreach (range($this->lowest_year, $current_date->year) as $year)
      $years[$year] = $year;

    krsort($years);

    return $years;
    }

  public function strutture(Request $request)
    {
    $data['anni'] = $this->getAllYears();

    $stats = [];
    if ($request->method() == 'POST')
      {
      $anno = (int) $request->input('anno');

      $results = StatUpselling::with('cliente.localita')
        ->groupBy('hotel_id')
        ->whereRaw("YEAR(created_at) = '$anno'")
        ->selectRaw("hotel_id, COUNT(id) AS n_click")
        ->get();

      $stats['results'] = [];
      $stats['totale'] = 0;

      foreach ($results as $row_result)
        {
         // Devo controllare che il cliente esista, qualche record vecchio potrebbe avere un cliente che non esiste più
        if ($row_result->cliente)
          $stats['results'][] = [
            'hotel_id' => $row_result->hotel_id,
            'nome' => $row_result->cliente->nome,
            'localita' => $row_result->cliente->localita->nome,
            'n_click' => $row_result->n_click
          ];

        $stats['totale'] += $row_result->n_click;
        }
      $request->flash();
      }

    return view('admin.stats_upselling', compact('data', 'stats'));
    }

  }
