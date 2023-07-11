<?php

namespace App;

use App\Hotel;
use Illuminate\Database\Eloquent\Model;

class StatUpselling extends Model
  {

  // tabella in cui vengono salvati i record
  protected $table = 'tblStatsUpselling';
  // attributi NON mass-assignable
  protected $guarded = ['id'];

  protected $fillable = ['queue_id','hotel_id'];

  public function cliente()
    {
    return $this->belongsTo('App\Hotel', 'hotel_id', 'id');
    }

  public function mailUpsellingQueue()
    {
    return $this->belongsTo('App\MailUpsellingQueue', 'queue_id', 'id');
    }

  }
