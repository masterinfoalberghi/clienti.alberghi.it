<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MailMultipleRead extends Model
{
	protected $table = 'tblStatsMailMultipleRead';
	protected $guarded = ['hotel_id'];
	protected $fillable = ['anno', 'mese', 'giorno', 'hotel_id', 'conteggio'];
	
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
	
}
