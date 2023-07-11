<?php

namespace App;

use App\Offerta;
use App\OffertaPrenotaPrima;
use Illuminate\Database\Eloquent\Model;

class Motivazione extends Model
{
    // tabella in cui vengono salvati i record 
    protected $table = 'tblMotivazioni';

    // attributi NON mass-assignable
    protected $guarded = ['id'];


    /**
     * Tutte le offerte che hanno questa motivazione
     */
    public function offerte()
    {
        return $this->morphedByMany(Offerta::class, 'motivazionabile', 'tblMotivazionabili');
    }

    /**
     * Tutte le offerte PP che hanno questa motivazione
     */
    public function prenotaPrima()
    {
        return $this->morphedByMany(OffertaPrenotaPrima::class, 'motivazionabile', 'tblMotivazionabili');
    }




}
