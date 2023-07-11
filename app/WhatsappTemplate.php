<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WhatsappTemplate extends Model
{
    protected $table = 'tblWhatsappTemplate';
    protected $fillable = ['titolo','testo'];

    public function hotel()
    {
        return $this->belongsTo('App\Hotel', 'id', 'hotel_id');
    }

}
