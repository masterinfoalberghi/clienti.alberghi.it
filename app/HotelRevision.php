<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelRevision extends Model
{
    use HasFactory;

    protected $table = "tblHotelRevisions";
    
    protected $casts = [
        "data" => "json"
    ];

    public function editors() {
        return $this->hasOne('App\User', "id", "editor_id" );
    }

}
