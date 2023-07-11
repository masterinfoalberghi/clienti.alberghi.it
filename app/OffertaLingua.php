<?php

namespace App;

use App\Offerta;
use Illuminate\Database\Eloquent\Model;

class OffertaLingua extends Model
{

    // tabella in cui vengono salvati i record
    protected $table = 'tblOfferteLang';

    // attributi NON mass-assignable
    protected $guarded = ['id'];

    public function last_modifica()
    {
        return ($this->created_at > $this->updated_at) ? $this->created_at : $this->updated_at;
    }

    /**
     * [getDates You may customize which fields are automatically mutated to instances of Carbon by overriding the getDates method]
     */
    public function getDates()
    {
        return ['data_approvazione'];
    }

    // soprattutto in seguito alle traduzioni automatiche con GT molti titoli avevano la HTML entity invece del carattere
    public function getTitoloAttribute($value)
    {
        return html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    public function offerta()
    {
        return $this->belongsTo('App\Offerta', 'master_id', 'id');
    }

    /**
     * Query scope
     */

    public function scopeDaOffertaAttiva($query)
    {
        return $query->whereHas('offerta', function ($q) {
            $q->whereAttivo(1)->where('valido_al', '>=', date('Y-m-d'));
        });
    }

    public function scopeInLingua($query, $lang)
    {
        return $query->whereLang_id($lang);
    }

    public function scopeTitoloLike($query, $chiave)
    {
        return $query->where('titolo', 'like', '%' . $chiave . '%');
    }

    public function scopeTestoLike($query, $chiave)
    {
        return $query->where('testo', 'like', '%' . $chiave . '%');
    }

    /**
     * TODO: Eliminare
     * Non usata da n essuna parte
     */

    // public function getExcerptText($limit = 20)
    // {
    //     $search = array('<br>', '<br />', '<p>','</p>');
    //     $replace = "&nbsp;";
    //     return Str::words(
    //                         str_replace($search, $replace, trim($this->testo)),
    //                         $limit
    //                     );
    // }

    public function scopeMultiTestoOrTitoloLike($query, $chiave = array())
    {

        if (!count($chiave)) {
            return $query;
        }

        if (count($chiave) == 1) {

            $alias = $chiave[0];
            return $query->whereRaw("(titolo like ? OR testo like ?)", ['%' . $alias . '%', '%' . $alias . '%']);

        } else {

            $count = 0;
            $q = "";
            $bindings = array();
            $elements = count($chiave) - 1;
            foreach ($chiave as $alias) {
                if ($count == 0) {
                    // attenzione apro la parentesi!!!
                    $q .= "(titolo like ? OR testo like ?";
                    $bindings[] = '%' . $alias . '%';
                    $bindings[] = '%' . $alias . '%';
                } else {

                    if ($elements == $count) {
                        // attenzione chiudo parentesi !!!
                        $q .= " OR titolo like ? OR testo like ?)";
                        $bindings[] = '%' . $alias . '%';
                        $bindings[] = '%' . $alias . '%';

                        return $query->whereRaw($q, $bindings);

                    } else {

                        $q .= " OR titolo like ? OR testo like ? ";
                        $bindings[] = '%' . $alias . '%';
                        $bindings[] = '%' . $alias . '%';

                    }

                }
                $count++;
            }

        }
    }

}
