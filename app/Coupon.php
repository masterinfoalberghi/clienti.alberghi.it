<?php
namespace App;

use App\CouponAttivazione;
use App\CouponGenerato;
use App\Hotel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Coupon extends Model
  {
  
  // tabella in cui vengono salvati i record
  protected $table = 'tblCoupon';
  
  // attributi NON mass-assignable
  protected $guarded = ['id'];
  
  /**
   * [getDates You may customize which fields are automatically mutated to instances of Carbon by overriding the getDates method]
   */
  public function getDates() 
    {
    return ['periodo_dal', 'periodo_al', 'data_chiusura'];
    }
  

  /* =============================================
  =            Relazioni con altre tabelle      =
  ============================================= */
  public function cliente() 
    {
    return $this->belongsTo('App\Hotel', 'hotel_id', 'id');
    }

  public function attivazioni() 
    {
    return $this->hasMany('App\CouponAttivazione', 'coupon_id', 'id');
    }

  public function generati() 
    {
    return $this->hasMany('App\CouponGenerato', 'coupon_id', 'id');
    } 


  public function attivazioniConMail($email) 
    {
    return $this->hasMany('App\CouponAttivazione', 'coupon_id', 'id')->where('email', '=', $email);
    }




  public function disponibile()
    {
      return $this->numero - $this->utilizzati;
    }


  public function triggerUtilizzati()
    {
      $this->utilizzati = $this->utilizzati + 1;
      $this->save();
    }



  /* 
  ACCESSOR METHODS
  */

  public function getCreatedAtAttribute($date)
  {
      return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d/m/Y  H:i:s');
  }


  public function getDataChiusuraAttribute($date)
  {
    try {
        return Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y');
    } catch (\Exception $e) {
        return "Data non valida";
    }
  }




  /* =============================================
    =            Query scope            =
    ============================================= */
  
  public function scopeAttivo($query) 
    {
    return $query->whereAttivo(1);
    }

  public function scopeDisponibile($query) 
    {
    return $query->where('numero', '>' , DB::raw('utilizzati'));
    }

  public function scopeFruibile($query) 
    {
    return $query->where( DB::raw('DATE_SUB(periodo_al, INTERVAL(durata_min-1) DAY)') ,'>',  DB::raw('CURDATE()'));
    }

    
  }
  