<?php

namespace App;

use App\MailMultipla;
use App\MailScheda;
use Illuminate\Database\Eloquent\Model;

class CameraAggiuntiva extends Model
  {

  // tabella in cui vengono salvati i record 
  protected $table = 'tblCamereAggiuntive';
  // attributi NON mass-assignable
  protected $guarded = ['id'];

  protected $fillable = ['arrivo', 'partenza', 'trattamento', 'adulti', 'bambini', 'eta_bambini','mailScheda_id','mailMultipla_id','date_flessibili', "whatsapp"];

  public function mailScheda()
    {
    return $this->belongsTo('App\MailScheda', 'mailScheda_id', 'id');
    }
   public function mailMultipla()
    {
    return $this->belongsTo('App\MailMultipla', 'mailMultipla_id', 'id');
    }

  }
