<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

// use Illuminate\Support\Str;

class VetrinaOffertaTopLingua extends Model
{

    // tabella in cui vengono salvati i record
    protected $table = 'tblVetrineOfferteTopLang';

    // attributi NON mass-assignable
    protected $guarded = ['id'];

    /**
     *  * con questo attributo faccio in modo che NON SIA EVIDENZIATO COME UNA EVIDENZA NEL LISTING
     */
    protected $mark_as_evidenza;

    public function __construct()
    {
        $this->mark_as_evidenza = true;
    }

    public function markAsEvidenza($val = false)
    {
        $this->mark_as_evidenza = $val;
    }

    public function getMarkAsEvidenza()
    {
        return $this->mark_as_evidenza;
    }

    /**
     * soprattutto in seguito alle traduzioni automatiche con GT molti titoli avevano la HTML entity invece del carattere
     */

    public function getTitoloAttribute($value)
    {
        return html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    public function offerta()
    {
        return $this->belongsTo('App\VetrinaOffertaTop', 'master_id', 'id');
    }

    public function pagina()
    {
        return $this->belongsTo('App\CmsPagina', 'pagina_id', 'id');
    }

    /**
     *  Query scope
     */

    public function scopeDaOffertaAttiva($query)
    {
        return $query->whereHas('offerta', function ($q) {
            $q->whereAttivo(1)->whereRaw("FIND_IN_SET('" . date('n') . "-" . date('Y') . "',mese) > 0");
        });
    }

    public function scopeInLingua($query, $lang)
    {
        return $query->whereLang_id($lang);
    }

    public function scopeInPagina($query, $id)
    {
        return $query->wherePagina_id($id);
    }

    public function scopeTitoloLike($query, $chiave)
    {
        return $query->where('titolo', 'like', '%' . $chiave . '%');
    }

    public function scopeTestoLike($query, $chiave)
    {
        return $query->where('testo', 'like', '%' . $chiave . '%');
    }

    public function scopeAssociataPagina($query, $pagina_id)
    {
        return $query->where('pagina_id', $pagina_id);
    }

    /**
     * TODO: Eliminare
     * Non usata da n essuna parte
     */

//   public function getExcerptText($limit = 20)
//     {
//       $search = array('<br>', '<br />', '<p>','</p>');
//       $replace = "&nbsp;";
//       return Str::words(
//                 str_replace($search, $replace, trim($this->testo)),
//                 $limit
//               );
//     }

    public function scopeLimitIds($query, $ids = '')
    {
        if (empty($ids)) {
            return $query;
        }

        if (strpos($ids, ",") !== false) {
            return $query->whereIn("id", explode(",", $ids));
        } else {
            return $query->where("id", $ids);
        }

    }

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
