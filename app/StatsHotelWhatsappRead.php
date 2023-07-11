<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatsHotelWhatsappRead extends Model
{
  protected $table = 'tblStatsHotelWhatsappRead';

  public function cliente()
    {
    return $this->belongsTo('App\Hotel', 'hotel_id', 'id');
    }


  public function scopeHotelIds($query, $hotel_ids)
  {
    if (!$hotel_ids)
      return $query;

    return $query->whereIn("hotel_id", $hotel_ids);
  }

  public function scopeAnno($query, $anno)
    {
    if(!$anno)
      return $query;

    return $query->where("anno", $anno);
    }

  public function scopeMese($query, $mese)
    {
    if(!$mese)
      return $query;

    return $query->where("mese", $mese);
    }

  public function scopeMeseMaggiore($query, $mese)
    {
    if(!$mese)
      return $query;

    return $query->where("mese", '>', $mese);
    }

  public function scopeGiorno($query, $giorno)
    {
    if(!$giorno)
      return $query;

    return $query->where("giorno", $giorno);
    }

  public function scopeHotel($query, $hotel_id)
    {
    if(!$hotel_id)
      return $query;

    return $query->where("hotel_id", $hotel_id);
    }
}