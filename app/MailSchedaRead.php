<?php

namespace App;

use App\Hotel;
use App\CameraAggiuntiva;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class MailSchedaRead extends Model
{
	protected $table = 'tblMailSchedaRead';
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
