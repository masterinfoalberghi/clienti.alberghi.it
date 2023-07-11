<?php
namespace App;

use App\Coupon;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class CouponAttivazione extends Model
  {
  
  // tabella in cui vengono salvati i record
  protected $table = 'tblCouponAttivazioni';
  
  // attributi NON mass-assignable
  protected $guarded = ['id'];
  
  /**
   * [getDates You may customize which fields are automatically mutated to instances of Carbon by overriding the getDates method]
   */
  public function getDates() 
    {
    return ['data_attivazione'];
    }
  
  public function coupon() 
    {
    return $this->belongsTo('App\Coupon', 'coupon_id', 'id');
    }

  

    /**
     * [aggiornaRigaAttivazione aggiorna la tblCouponAttivazioni a seguito del click sul link di attivazione]
     * @return [type] [description]
     */
  public function aggiornaRigaAttivazione()
    {
      $this->attivato = 1;
      $this->IP = $_SERVER['REMOTE_ADDR'];
      $this->user_agent = $_SERVER['HTTP_USER_AGENT'];
      $this->data_attivazione = Carbon::now();
    }



  /* =============================================
    =            Query scope            =
    ============================================= */
  
  public function scopeAttivato($query) 
    {
    return $query->whereAttivato(1);
    }

  public function scopeFindByUniqueID($query, $uniqueID) 
    {
    return $query->where('uniqueID', '=', $uniqueID);
    }
    
  }
  