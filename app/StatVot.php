<?php
namespace App;

use App\Hotel;
use Illuminate\Database\Eloquent\Model;

class StatVot extends Model
  {
  
  // tabella in cui vengono salvati i record
  protected $table = 'tblStatsVot';
  
  // attributi NON mass-assignable
  protected $guarded = ['id'];
  
  protected $fillable = ['hotel_id', 'pagina_id','referer', 'useragent', 'IP'];
  
  

  public function cliente() 
    {
    return $this->belongsTo('App\Hotel', 'hotel_id', 'id');
    }

  public function scopeBeforeToday($query)
    {
    return $query->where("created_at", "<", date("Y-m-d 00:00:00"));
    }      
  }
