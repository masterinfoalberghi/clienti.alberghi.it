<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KeywordRicerca extends Model
  {

  // tabella in cui vengono salvati i record 
  protected $table = 'tblKeywordRicerca';
  
  // attributi NON mass-assignable
  protected $guarded = ['id'];

  protected $fillable = ['keyword'];

  }
