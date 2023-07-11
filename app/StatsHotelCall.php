<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatsHotelCall extends Model
{
  protected $table = 'tblStatsHotelCall';

  /**
   * In questa tabella ho solo il timestamp created_at
   * lo gestisco manualmente, perchè altrimenti laravel cercherebbe di scrivere anche su
   * update_at facendo fallire la query
   *
   * @var bool
   */
  public $timestamps = false;


  // attributi NON mass-assignable
  protected $guarded = ['id'];

  protected $fillable = ['hotel_id', 'useragent', 'IP'];

  public function cliente()
    {
    return $this->belongsTo('App\Hotel', 'hotel_id', 'id');
    }

  public function scopeBeforeToday($query)
    {
    return $query->where("created_at", "<", date("Y-m-d 00:00:00"));
    }
    
  public function scopeHotelIds($query, $hotel_ids)
    {
    if(!$hotel_ids)
      return $query;

    return $query->whereIn("hotel_id", $hotel_ids);
    }
    
}