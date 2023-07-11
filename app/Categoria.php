<?php

namespace App;

use App\Hotel;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{


    protected $table = 'tblCategorie';
    protected $guarded = ['id'];

    const ID_STELLE_5 = 5;
    const ID_STELLE_4 = 4;
    const ID_STELLE_3S = 6;
    const ID_STELLE_3 = 3;
    const ID_STELLE_2 = 2;
    const ID_STELLE_1 = 1;

    public function clienti()
    {
        return $this->hasMany('App\Hotel', 'categoria_id', 'id');
    }

    public function descrizione()
    {
        $locale = App::getLocale();
        return Lang::get('labels.cat' . $this->id);
        
    }

    public function scopeReal($query)
    {
        return $query->where('ordinamento', '<', 7);
    }
}
