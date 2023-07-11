<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * ATTENZIONE questo model fa riferimento ad una tabella con record temporanei
 * non utilizzare in lettura, utilizzare invece StatHotelRead
 */
class StatHotel extends Model
  {

  // tabella in cui vengono salvati i record
  protected $table = 'tblStatsHotel';

  public function setUpdatedAtAttribute($value)
    {

    // Do nothing.

    }

  // attributi NON mass-assignable
  protected $guarded = ['id'];

  protected $fillable = ['hotel_id', 'lang', 'referer', 'useragent', 'IP','codice_cookie'];

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
