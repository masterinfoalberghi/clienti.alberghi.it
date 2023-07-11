<?php
namespace App;

use App\Hotel;
use Illuminate\Database\Eloquent\Model;

class StatVetrine extends Model
  {
  
  // tabella in cui vengono salvati i record
  protected $table = 'tblStatsVetrine';
  
  public function setUpdatedAtAttribute($value) 
    {
    
    // Do nothing.
    
    }
  
  // attributi NON mass-assignable
  protected $guarded = ['id'];
  
  protected $fillable = ['hotel_id', 'vetrina_id', 'slot_id', 'referer', 'useragent', 'IP'];
  
  public function cliente() 
    {
    return $this->belongsTo('App\Hotel', 'hotel_id', 'id');
    }

  public function scopeBeforeToday($query)
    {
    return $query->where("created_at", "<", date("Y-m-d 00:00:00"));
    }    
  }
