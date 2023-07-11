<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewsletterLinks extends Model
  {

  // tabella in cui vengono salvati i record 
  protected $table = 'tblNewsletterLinks';
  
  // attributi mass-assignable
  protected $fillable = ['titolo','data_invio','url','note'];


  /**
   * [getDates You may customize which fields are automatically mutated to instances of Carbon by overriding the getDates method]
   */
  public function getDates() 
    {
    return ['data_invio', 'created_at', 'updated_at'];
    }

  }
