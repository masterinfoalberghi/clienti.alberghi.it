<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatsHotelOutboundLinkRead extends Model
{
  protected $table = 'tblStatsHotelOutboundLinkRead';

  public function cliente()
    {
    return $this->belongsTo('App\Hotel', 'hotel_id', 'id');
    }

  public function scopeAnno($query, $anno)
    {
    if(!$anno)
      return $query;

    return $query->where("anno", $anno);
    }

  public function scopeHotel($query, $hotel_id)
    {
    if(!$hotel_id)
      return $query;

    return $query->where("hotel_id", $hotel_id);
    }
}