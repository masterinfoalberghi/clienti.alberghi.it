<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * ATTENZIONE questo model fa riferimento ad una tabella che viene modificata da un CRON
 * non modificare i record della tabella manualmente, utilizzare invece StatHotel
 */
class StatHotelRead extends Model
{
  protected $table = "tblStatsHotelRead";

  public function cliente()
    {
    return $this->belongsTo('App\Hotel', 'hotel_id', 'id');
    }

  public function scopeHotelIds($query, $hotel_ids)
    {
    if(!$hotel_ids)
      return $query;

    return $query->whereIn("hotel_id", $hotel_ids);
    }

  public function scopeHotelVisitorMonth($query, $year, $month)
    {
    if (!$year || !$month)
      return $query;

    return $query
      ->where('anno', '=', $year)
      ->where('mese', '=', $month)
      ->select(['visits', 'mese', 'anno']);
    }

  public function scopeHotelVisitorYear($query, $year)
    {
    if (!$year)
      return $query;

    return $query
      ->where('anno', '=', $year)
      ->select(['visits', 'mese', 'anno']);
    }
}
