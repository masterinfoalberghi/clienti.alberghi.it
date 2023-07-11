<?php

namespace App;

use App\Hotel;
use App\Utility;
use Illuminate\Database\Eloquent\Model;

class Caparra extends Model
{
    
    // tabella in cui vengono salvati i record
    protected $table = 'tblCaparre';
    // attributi NON mass-assignable
    protected $guarded = ['id'];


     /**
	   * [getDates You may customize which fields are automatically mutated to instances of Carbon by overriding the getDates method]
	   */
	  public function getDates() 
	    {
	    return ['from', 'to'];
	    }


    
    public function hotel()
    {
        return $this->belongsTo('App\Hotel', "hotel_id", 'id');
    }


    public function label()
    {
        return $this->belongsTo('App\LabelCaparra', 'label_id', 'id');
    }



    public function isCancellazioneGratuita($for_js = 0)
    {
      if ($for_js) 
        {
          return (in_array($this->option, Utility::optionsCancellazioneGratuita())) ? 1 : 0;
        } 
      else 
        {
        return in_array($this->option, Utility::optionsCancellazioneGratuita());
        }
    }




    public function isAttiva($for_js = 0)
    {
      if ($for_js) 
        {
          return ($this->to >= date('Y-m-d')) ? 1: 0;
        } 
      else 
        {
        return ($this->to >= date('Y-m-d'));
        }
    }



    public function scopeAttiva($query)
    {
        return $query->where('enabled', 1)->where('to', '>=', date('Y-m-d'));
    }



    public function getAsString($locale)
      {

      $dal = Utility::myFormatLocalized($this->from, '%d %B', $locale);
      $al = Utility::myFormatLocalized($this->to, '%d %B', $locale);

      $option = $this->option;
      $option_txt = __("labels.option_" . $option);

      if ($this->option == "4")
        $option_txt = str_replace(["$1","$2"], [$this->day_before, $this->perc], $option_txt);

      else if ($this->option == "6")
        $option_txt = str_replace(["$1","$2"],[$this->day_before,  $this->month_after], $option_txt);

      else if ($this->option == "7")
        $option_txt = str_replace(["$1"],[$this->day_before], $option_txt);

      else if ($this->option == "8")
        $option_txt = str_replace(["$1","$2"],[$this->day_before, $this->perc], $option_txt);

      else if ($this->option == "9")
        $option_txt = str_replace(["$1","$2"],[$this->day_before, $this->month_after], $option_txt);
      
      else if ($this->option == "10")
          $option_txt = str_replace(["$1"],[$this->perc], $option_txt);
      
      else if ($this->option == "11")
					$option_txt = str_replace(["$1","$2", "$3"],[$this->day_before, $this->perc, $this->month_after], $option_txt);

      else if ($this->option == "12")
          $option_txt = str_replace(["$1", "$2"], [$this->day_before, $this->perc], $option_txt);
        
      else if ($this->option == "13")
          $option_txt = str_replace(["$1","$2"],[$this->day_before, $this->perc], $option_txt);

      $intro = __("labels.options_nel_priodo_dal");
      $intro .= ' '.$dal;

      $intro .= ' '. __("labels.options_al");

      $intro .= ' '.$al;


      return '<span style="text-decoration: underline;">'.$intro .'</span> - '. $option_txt;
      }

}
