<?php
namespace App;

use App\Coupon;
use App\UtenteCouponGenerato;
use Illuminate\Database\Eloquent\Model;

class CouponGenerato extends Model
  {
  
  // tabella in cui vengono salvati i record
  protected $table = 'tblCouponGenerati';
  
  // attributi NON mass-assignable
  protected $guarded = ['id'];


  
  public function coupon() 
    {
    return $this->belongsTo('App\Coupon', 'coupon_id', 'id');
    }

  public function utente() 
    {
    return $this->hasOne('App\UtenteCouponGenerato', 'couponGenerati_id', 'id');
    }
   

  /* =============================================
    =            Query scope            =
    ============================================= */

  public function scopeFindByCodice($query, $codice) 
    {
    return $query->where('codice', '=', $codice);
    }


  }
  