<?php
namespace App;

use App\Coupon;
use Illuminate\Database\Eloquent\Model;

class UtenteCouponGenerato extends Model
  {
  
  // tabella in cui vengono salvati i record
  protected $table = 'tblUtenteCouponGenerati';
  
  // attributi NON mass-assignable
  protected $guarded = ['id'];

  /**
   * [getDates You may customize which fields are automatically mutated to instances of Carbon by overriding the getDates method]
   */
  public function getDates() 
    {
    return ['data_consegna'];
    }

  public function coupon() 
    {
    return $this->hasOne('App\CouponGenerato', 'couponGenerati_id', 'id');
    }


  /* =============================================
    =            Query scope            =
    ============================================= */

  public function scopeFindByEmail($query, $email) 
    {
    return $query->where('email', '=', $email);
    } 
    
  }
  