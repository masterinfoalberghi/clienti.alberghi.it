<?php

namespace App;

use App\Hotel;
use Illuminate\Database\Eloquent\Model;

class MailUpsellingQueue extends Model
  {

  // tabella in cui vengono salvati i record 
  protected $table = 'tblMailUpsellingQueue';
  // attributi NON mass-assignable
  protected $guarded = ['id'];

  protected $fillable = ['referer','inviata','email'];


  public function cliente()
    {
    return $this->belongsTo('App\Hotel', 'hotel_id', 'id');
    }


  public function statUpselling()
    {
      return $this->hasMany('App\StatUpselling', 'queue_id', 'id');
    }

  }
