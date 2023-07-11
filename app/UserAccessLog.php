<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAccessLog extends Model
{

    /**
     * Il numero massimo di righe di storico per utente
     */
    const MAX_LOG_ROWS_PER_USER = 300;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tblUsersAccessLog';


    /**
     * In questa tabella ho solo il timestamp created_at
     * lo gestisco manualmente, perchè altrimenti laravel cercherebbe di scrivere anche su
     * update_at facendo fallire la query
     *
     * @var bool
     */
    public $timestamps = false;

    public function user()
      {
      return $this->belongsTo('App\User');
      }
}
