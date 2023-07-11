<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApiHotel extends Model
{
	
	use SoftDeletes;

	protected $connection = 'api';

  // tabella in cui vengono salvati i record 
	protected $table = 'tblAPIHotel';
}
