<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabelCaparra extends Model
{
    use HasFactory;



    // tabella in cui vengono salvati i record
    protected $table = 'tblLabelCaparre';
    // attributi mass-assignable
    protected $fillable = ['testo_it','testo_en', 'testo_fr', 'testo_de','hotel_id','moderata','data_moderazione'];



    public function caparre()
    {
        return $this->hasMany('App\Caparra', 'label_id', 'id');
    }

    public function hotel()
    {
        return $this->belongsTo('App\Hotel', "hotel_id", 'id');
    }


    public function scopeDaModerare($query)
      {
      return $query->where('moderata', 0);
      }

    public function getTestoBrToNl($lang = 'it')
        {
        $column = 'testo_'.$lang;
        $breaks = array("<br />","<br>","<br/>");  
        return str_ireplace($breaks,"",$this->$column);  
        }



}
