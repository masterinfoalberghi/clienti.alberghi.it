<?php
namespace App;

use File;
use Config;
use Request;
use App\Listino;
use App\Offerta;
use App\Utility;
use App\Localita;
use App\CmsPagina;
use Carbon\Carbon;
use Carbon\Caparra;
use App\Macrolocalita;
use App\PuntoForzaChiave;
use App\PuntoForzaLingua;
use App\VetrinaOffertaTop;
use App\AccettazioneCaption;
use App\VetrinaOffertaTopLingua;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    
    /**
     * After creating the accessor, add the attribute name to the appends property on the model. Note that attribute names are typically referenced in "snake case", even though the accessor is defined using "camel case":
     */

    //protected $appends = ['img_mappa_listing','label_centro','punti_di_forza'];
    protected $appends = ['img_mappa_listing', 'label_centro', 'punti_it', 'punti_en', 'punti_fr', 'punti_de'];

   
    ///////////////////////////////////////////////////////////////////////////////////////////////
    // con questo attributo faccio in modo che l'HOTEL VISUALIZZI LA VOT NEL LISTING                           //
    ///////////////////////////////////////////////////////////////////////////////////////////////
    protected $evidenza;
    protected $prezzo_to_order;



    public function __construct()
    {
        $this->evidenza = 0;
        $this->prezzo_to_order = 0;
    }

    public function appendedAttributes() {
        return array_merge($this->appends, ['first_image', 'immagini_gallery']);
    }

    public function setEvidenza($vot = null)
    {
        if (!is_null($vot)) {
            $this->evidenza = $vot;
        }
    }

    public function setPrezzoToOrder($prezzo_to_order = null)
    {
        if (!is_null($prezzo_to_order)) {
            $this->prezzo_to_order = $prezzo_to_order;
        }
    }

    public function getListingImgAttribute($value)
      {
      if($value != '')
        {
        return $value;
        }
      else
        {
        //? perché c'è eager loading
        //return optional($this->immaginiGallery->first())->foto;
        return !is_null($this->first_image) ? $this->first_image : optional($this->immaginiGallery->first())->foto;
        
        }
      }

    public function getEvidenza()
    {
        return $this->evidenza;
    }

    public function getPrezzoToOrder()
    {
        return $this->prezzo_to_order;
    }

    /* =============================================
    =            Accessors & Mutators       =
    To define an accessor, create a getFooAttribute method on your model
    where Foo is the "camel" cased name of the column you wish to access.
    ============================================= */

    /**
     * Appending Values To JSON
     * Occasionally, when casting models to an array or JSON, you may wish to add attributes that do not have a corresponding column in your database. To do so, first define an accessor for the value:
     */

    // return $this->attributes['img_mappa_listing'] = "//static.info-alberghi.com/"."images/gallery/360x200/767_01_Hotel_Aqua_57d25d6030daa.jpg";

    public function getImgMappaListingAttribute()
    {
        return $this->listing_img;
        //return "//static.info-alberghi.com/"."images/gallery/360x200/767_01_Hotel_Aqua_57d25d6030daa.jpg";
    }

    public function getLabelCentroAttribute()
    {
        return $this->distanza_centro;
    }

    public function getPuntiItAttribute()
    {
        return $this->tmp_punti_di_forza_it;
    }

    public function getPuntiEnAttribute()
    {
        return $this->tmp_punti_di_forza_en;
    }

    public function getPuntiFrAttribute()
    {
        return $this->tmp_punti_di_forza_fr;
    }

    public function getPuntiDeAttribute()
    {
        return $this->tmp_punti_di_forza_de;
    }

    // è il path per le funzioni laravel tipo url()
    const LISTING_IMG_PATH = "images/listing";
    const GALLERY_IMG_PATH = "images/gallery/";

    // dimensioni immagine listing
    const L_W = 360;
    const L_H = 200;

    const RAGGIO_IN_KM = 0.8;

    const LIMIT_OFFERTE = 5;
    const LIMIT_LAST = 3;
    const LIMIT_OFFERTE_PRENOTA_PRIMA = 5;

    // tabella in cui vengono salvati i record
    protected $table = 'tblHotel';

    // attributi NON mass-assignable
    protected $guarded = ['id'];

    protected $fillable = [
        'attivo', 
        'mail_upselling', 
        'listing_img', 
        'accettazioneCaption_id', 
        'note_ai_it',
        'note_ai_en',
        'note_ai_fr',
        'note_ai_de',
        'note_pc_it',
        'note_pc_en',
        'note_pc_fr',
        'note_pc_de',
        'note_mp_it',
        'note_mp_en',
        'note_mp_fr',
        'note_mp_de',
        'note_mp_spiaggia_it',
        'note_mp_spiaggia_en',
        'note_mp_spiaggia_fr',
        'note_mp_spiaggia_de',
        'note_bb_it',
        'note_bb_en',
        'note_bb_fr',
        'note_bb_de',
        'note_bb_spiaggia_it',
        'note_bb_spiaggia_en',
        'note_bb_spiaggia_fr',
        'note_bb_spiaggia_de',
        'note_sd_it',
        'note_sd_en',
        'note_sd_fr',
        'note_sd_de',
        'note_sd_spiaggia_it',
        'note_sd_spiaggia_en',
        'note_sd_spiaggia_fr',
        'note_sd_spiaggia_de',
        'note_altro_it',
        'note_altro_en',
        'note_altro_fr',
        'note_altro_de',
        'altro_trattamento_it',
        'altro_trattamento_en',
        'altro_trattamento_de',
        'altro_trattamento_fr',
        'tmp_punti_di_forza_it',
        'tmp_punti_di_forza_en',
        'tmp_punti_di_forza_de',
        'tmp_punti_di_forza_fr',
        'altro_pagamento',
        'note_altro_pagamento_it',
        'note_altro_pagamento_en',
        'note_altro_pagamento_fr',
        'note_altro_pagamento_de'
    ];

    // può valere '' oppure vtt|33 oppure vst|33
    protected $position_top = '';
    protected $evidenze_bb = 0;

    public function setToTop($id_vetrina_top = '')
    {
        $this->position_top = $id_vetrina_top;
    }

    public function setEvidenzaBB()
    {
        $this->evidenze_bb = 1;
    }

    public function gettEvidenzaBB()
    {
        return $this->evidenze_bb;
    }

   

    /**
     * [getTop description]
     * @return [type] [ritorna 0 oppure un array con url da chiamare per il contaclick e id della VetrinaTopLingua]
     */
    public function getTop()
    {
        if ($this->position_top == '') {
            return $this->position_top;
        } else {
            return explode('|', $this->position_top);
        }

    }

   

    /**
     * [getDates You may customize which fields are automatically mutated to instances of Carbon by overriding the getDates method]
     */
    public function getDates()
    {
        return ['aperto_dal', 'aperto_al','data_moderazione'];
    }

    /* =============================================
    =            Relazioni con altre tabelle      =
    ============================================= */

    public function revisions() {
        return $this->hasMany('App\HotelRevision', "hotel_id", "id")->orderBy("id", "DESC");
    }

    // public function revisions_last() {
    //     return $this->hasMany('App\HotelRevision', "hotel_id", "id")->orderBy("id", "DESC")->first();
    // }

    public function editors() {
        return $this->hasOne('App\User', "id", "editor" );
    }

    public function rating()
    {
        return $this->hasMany('App\Rating', "hotel_id", "id");
    }

    // public function latestRating()
    // {
    //     return $this->hasOne('\App\Rating', "hotel_id", "id")->latest();
    // }

    public function caparraGratuita()
    {
        return $this->hasMany('App\Caparra', "hotel_id", "id")->where("option" , "<=", 4);
    }


    public function caparreCancellazioneGratuita()
    {
        return $this->hasMany('App\Caparra', "hotel_id", "id")->whereIn("option" , Utility::optionsCancellazioneGratuita());
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'id', 'hotel_id');
    }

    public function commerciale()
    {
        return $this->belongsTo('App\User', 'commerciale_id', 'id');
    }

    public function localita()
    {
        return $this->belongsTo('App\Localita', 'localita_id', 'id');
    }

    public function gruppo()
    {
        return $this->belongsTo('App\GruppoHotel', 'gruppo_id', 'id');
    }


    public function labelCaparre()
    {
        return $this->hasOne('App\LabelCaparra', 'hotel_id', 'id');
    }

    public function caparre()
    {
        return $this->hasMany('App\Caparra', 'hotel_id', 'id');
    }

    public function caparreAttive()
    {
        return $this->hasMany('App\Caparra', 'hotel_id', 'id')
            ->where('enabled', 1)
            ->where('to', '>=', date('Y-m-d'));
    }

    //? Sono le cancellazioni valide per il conteggio in HP
    public function caparreAttiveFlessibili()
    {
        return $this->hasMany('App\Caparra', 'hotel_id', 'id')
        ->where('enabled', 1)
        ->where('to', '>=', date('Y-m-d'))
        ->whereIn('option',[1,2,3,4,7,9]);
    }


    public function caparreAttiveNoLabel()
    {
        return $this->hasMany('App\Caparra', 'hotel_id', 'id')
            ->where('enabled', 1)
            ->where('to', '>=', date('Y-m-d'))
            ->whereNull('label_id');
    }

    public function getLabelCaparra($locale = 'it')
      {
        $column = 'testo_'.$locale;
        return optional($this->labelCaparre)->$column;
      }

    public function offerteLast()
    {
        return $this->hasMany('App\Offerta', 'hotel_id', 'id');
    }

    public function offerteLastAttiva()
    {
        return $this->hasMany('App\Offerta', 'hotel_id', 'id')->where("attivo", "=", "1");
    }

    public function numero_offerte_last_attivi()
    {
        return $this->offerteLast()->attiva()
            ->selectRaw('hotel_id, count(*) as aggregate_offerte_last')
            ->groupBy('hotel_id');
    }

    public function offerte()
    {
        return $this->hasMany('App\Offerta', 'hotel_id', 'id')->where('tipologia', '=', 'offerta');
    }

    public function numero_offerte_attive()
    {
        return $this->offerte()->attiva()
            ->selectRaw('hotel_id, count(*) as tot')
            ->groupBy('hotel_id');
    }

    
    public function last()
    {
        return $this->hasMany('App\Offerta', 'hotel_id', 'id')->where('tipologia', '=', 'lastminute')->orderBy("tipologia", "ASC");
    }

    public function prenotaprima()
    {
        return $this->hasMany('App\OffertaPrenotaPrima', 'hotel_id', 'id');
    }

    public function numero_last_attivi()
    {
        return $this->last()->attiva()
            ->selectRaw('hotel_id, count(*) as tot')
            ->groupBy('hotel_id');
    }

    public function numero_pp_attivi()
    {
        return $this->prenotaprima()->attiva()
            ->selectRaw('hotel_id, count(*) as tot')
            ->groupBy('hotel_id');
    }

    public function offertePrenotaPrima()
    {
        return $this->hasMany('App\OffertaPrenotaPrima', 'hotel_id', 'id');
    }

    public function numero_prenota_prima_attivi()
    {
        return $this->offertePrenotaPrima()->attiva()
            ->selectRaw('hotel_id, count(*) as tot')
            ->groupBy('hotel_id');
    }

    public function offerteTop()
    {

        return $this->hasMany('App\VetrinaOffertaTop', 'hotel_id', 'id')
            ->attiva()
            ->visibileInScheda()
            ->whereIn("tipo", array('lastminute', 'offerta', 'prenotaprima'))
            ->orderByRaw("FIELD (tipo , 'lastminute', 'offerta', 'prenotaprima')");

    }

    public function gestioneMultiple()
    {
        return $this->hasMany('App\GestioneMultiple', 'hotel_id', 'id');
    }

    public function offerteTopLast()
    {
        return $this->hasMany('App\VetrinaOffertaTop', 'hotel_id', 'id')->where('tipo', "lastminute");
    }

    public function offerteTopPP()
    {
        return $this->hasMany('App\VetrinaOffertaTop', 'hotel_id', 'id')->where('tipo', "prenotaprima");
    }

    public function offerteTopOS()
    {
        return $this->hasMany('App\VetrinaOffertaTop', 'hotel_id', 'id')->where('tipo', "offerta");
    }

    public function offerteBambiniGratisTop()
    {
        return $this->hasMany('App\VetrinaBambiniGratisTop', 'hotel_id', 'id');
    }

    public function vetrineTrattamentoTop()
    {
        return $this->hasMany('App\VetrinaTrattamentoTop', 'hotel_id', 'id');
    }


    public function goolePoi()
    {
        return $this->hasMany('App\GooglePoi', "hotel_id", "id");
    }

    /**
     * @Sacco 01/08/2019
     * Se id = 1583 la relazione stelle() (Hotel.php) è fatta con la tabella CategoriaFake che visualizza come 
     * nome "4stelle SUP"; SE faccio l'eager loading però non è stato ancora instanziato l'hotel con l'id e quindi 
     * viene sempre caricata la relazione con la tabella Categoria.
     * Quindi faccio un loop sugli hotel con egarloading e per quello che deve cambiare assegno le stelle 
     * chimando la relazione sull'oggetto instanziato
     */

    public function stelle()
    {
        if (in_array($this->id, Utility::fakeHotel())) {
            return $this->belongsTo('App\CategoriaFake', 'categoria_id', 'id');
        } else {
            return $this->belongsTo('App\Categoria', 'categoria_id', 'id');
        }
    }

    public function tipologia()
    {
        return $this->belongsTo('App\Tipologia', 'tipologia_id', 'id');
    }

    public function puntiDiForza()
    {
        return $this->hasMany('App\PuntoForza', 'hotel_id', 'id');
    }

    public function servizi()
    {
        return $this->belongsToMany('App\Servizio', 'tblHotelServizi', 'hotel_id', 'servizio_id')->withPivot('position', 'note', 'note_en', 'note_fr', 'note_de');
    }

    public function serviziGratuiti()
    {
        return $this->belongsToMany('App\Servizio', 'tblHotelServizi', 'hotel_id', 'servizio_id')->gratuito()->withPivot('position', 'note', 'note_en', 'note_fr', 'note_de');
    }

    public function serviziPerBambini()
    {
        return $this->belongsToMany('App\Servizio', 'tblHotelServizi', 'hotel_id', 'servizio_id')->perBambini()->withPivot('position', 'note');
    }

    public function serviziPrivati()
    {
        return $this->hasMany('App\ServizioPrivato', 'hotel_id', 'id');
    }

    public function serviziPrivatiGratuiti()
    {
        return $this->hasMany('App\ServizioPrivato', 'hotel_id', 'id')->gratuito();
    }

    public function serviziPrivatiPerBambini()
    {
        return $this->hasMany('App\ServizioPrivato', 'hotel_id', 'id')->where('categoria_id', 3);
    }

    public function serviziCovid()
    {
        return $this->belongsToMany('App\ServizioCovid', 'tblHotelServiziCovid', 'hotel_id', 'servizio_id')->withPivot('distanza');
    }

    public function serviziInOut()
    {
        return $this->belongsToMany('App\ServizioInOut', 'tblHoltelServiziInOut', 'hotel_id', 'servizio_id')->withPivot('valore_1', 'valore_2', 'opzione');
    }

    public function serviziGreen()
    {
        return $this->belongsToMany('App\ServizioGreen', 'tblHotelServiziGreen', 'hotel_id', 'servizio_id')->withPivot('altro');
    }

    public function poi()
    {
        return $this->belongsToMany('App\Poi', 'tblHotelPoi', 'hotel_id', 'poi_id')
                    ->withPivot('distanza', 'g_distanza' , 'g_durata','g_distanza_numeric', 'g_durata_numeric','g_descrizione_rotta','g_modo','g_url')
                    ->withTimestamps();
    }

    /**
     * $h->poi_new: si comporta come $h->poi e restituisce tutti i POI associati
     * $h->poi_new(true)->get(): restituisce SOLO i POI associati con g_modo = NULL, 
     * cioè quelli che non hanno i campi di google (ad esempio quando aggiungo un POI ad una località 
     * ma ho già calcolato le distanze di tutti gli altri)
     *
     * @param boolean $only_new
     * @return void
     */
    public function poi_new($only_new = false)
    {
        if (!$only_new) {
            return $this->belongsToMany('App\Poi', 'tblHotelPoi', 'hotel_id', 'poi_id')
            ->withPivot('distanza', 'g_distanza', 'g_durata', 'g_distanza_numeric', 'g_durata_numeric', 'g_descrizione_rotta', 'g_modo', 'g_url')
            ->withTimestamps();
        } else {
            return $this->belongsToMany('App\Poi', 'tblHotelPoi', 'hotel_id', 'poi_id')
            ->withPivot('distanza', 'g_distanza', 'g_durata', 'g_distanza_numeric', 'g_durata_numeric', 'g_descrizione_rotta', 'g_modo', 'g_url')
            ->withTimestamps()
            ->wherePivotNull('g_modo');
        }
        
    }

    public function bambiniGratis()
    {
        return $this->hasMany('App\BambinoGratis', 'hotel_id', 'id');
    }

    public function bambiniGratisAttivi()
    {
        return $this->hasMany('App\BambinoGratis', 'hotel_id', 'id')->attivo()->notArchiviato();
    }

    public function numero_bambini_gratis_attivi()
    {
        return $this->bambiniGratisAttivi()
            ->selectRaw('hotel_id, count(*) as tot')
            ->groupBy('hotel_id');
    }

    public function immaginiGallery()
    {
        return $this->hasMany('App\ImmagineGallery', 'hotel_id', 'id');
    }

    public function firstImmagineGallery()
    {
        return $this->hasOne('App\ImmagineGallery', 'hotel_id', 'id')->orderBy('position','asc');
    }

     public function first_image()
    {
        return $this->belongsTo('App\ImmagineGallery', 'hotel_id', 'id');
    }

    public function first_image_group()
    {
        return $this->belongsTo('App\ImmagineGallery', 'hotel_id', 'id');
    }


    public function scopeWithFirstImage($query)
    {
        return $query->addSelect(['first_image' => ImmagineGallery::select('foto')
                                ->whereColumn('hotel_id','tblHotel.id')
                                ->orderBy('position','asc')
                                ->take(1)
                                ])->with('first_image');
    }


    public function scopeWithFirstImageGroup($query, $gruppo_id)
    {
        return $query->addSelect([
            'first_image_group' => ImmagineGallery::select('foto')
                ->whereColumn('hotel_id', 'tblHotel.id')
                ->where('gruppo_id', $gruppo_id)
                ->take(1)
        ])->with('first_image_group');
    }




    public function numero_immagini_gallery()
    {
        return $this->immaginiGallery()
            ->selectRaw('hotel_id, count(*) as tot')
            ->groupBy('hotel_id');
    }

    /*public function immaginiListing($gruppo_id)
    {
    return $this->hasMany('App\ImmagineGallery', 'hotel_id', 'id')->where('gruppo_id', '=', $gruppo_id);
    }*/

    /*
    Mi serve per sapere se l'hotel ha un'immagine legata ad un listing con gruppo di ricerca
    $h->immaginiListing(8)->first() => ritorna l'immagine se ce n'è una nella gallery associata al gruppo di ricerca 8 (piscina) oppure NULL
     */

    /* Nell'eager loading NON POSSO USARE relazioni con parametri, la filtro nel momento del caricamento */

    public function hotelPreferiti()
    {
        return $this->hasMany('App\HotelPreferito', 'hotel_id', 'id');
    }

    public function immaginiListing()
    {
        return $this->hasMany('App\ImmagineGallery', 'hotel_id', 'id');
    }

    public function descrizioneHotel()
    {
        return $this->hasOne('App\DescrizioneHotel', 'hotel_id', 'id')->where("online", 1);
    }

    public function listini()
    {
        return $this->hasMany('App\Listino', 'hotel_id', 'id');
    }

    public function listiniMinMax()
    {
        return $this->hasMany('App\ListinoMinMax', 'hotel_id', 'id');
    }

    public function listiniCustom()
    {
        return $this->hasMany('App\ListinoCustom', 'hotel_id', 'id');
    }

    public function notaListino()
    {
        return $this->hasOne('App\NotaListino', 'hotel_id', 'id');
    }

    public function mailScheda()
    {
        return $this->hasMany('App\MailScheda', 'hotel_id', 'id');
    }

    public function mailMultiple()
    {
        return $this->belongsToMany('App\MailMultipla', 'tblHotelMailMultiple', 'hotel_id', 'mailMultipla_id');
    }

    public function mailUpsellingQueue()
    {
        return $this->hasMany('App\MailUpsellingQueue', 'hotel_id', 'id');
    }

    public function stats()
    {
        return $this->hasMany('App\StatHotel', 'hotel_id', 'id');
    }

    public function statsOutboundLink()
    {
        return $this->hasMany('App\StatHotelOutboundLink', 'hotel_id', 'id');
    }

    public function statsOutboundLinkRead()
    {
        return $this->hasMany('App\StatHotelOutboundLinkRead', 'hotel_id', 'id');
    }

    public function slotVetrine()
    {
        return $this->hasMany('App\SlotVetrina', 'hotel_id', 'id');
    }

    public function infoPiscina()
    {
        return $this->hasOne('App\InfoPiscina', 'hotel_id', 'id');
    }

    public function infoBenessere()
    {
        return $this->hasOne('App\InfoBenessere', 'hotel_id', 'id');
    }

    public function wa_templates()
    {
        return $this->hasMany('App\WhatsappTemplate', "hotel_id", "id");
    }

    public function getTitleOld($locale = 'it')
    {
        $title = $this->nome . ' ' . $this->localita->nome . ' ' . $this->indirizzo . ' ' . Lang::get('hotel.tit_rr');

        // Regina Elena 57 e Oro Bianco Spa
        if ($this->id == 381) {
            $title .= 'Hotel ' . $title;
        }

        return $title;
    }

    public function getTitle($locale = 'it')
    {
        $anno = date("Y")+Utility::fakeNewYear();
        $title = $this->nome . ' ' . $this->localita->nome . ' [' . $anno . '] ' . $this->stelle->descrizione();
        
        if ($this->n_camere > 0)
            {
            $title .=  ', ' . $this->n_camere . ' ' . trans('hotel.camere');
            }
        
        $title .= ', ' . $this->n_posti_letto . ' ' . trans('hotel.posti_letto');      


        // Regina Elena 57 e Oro Bianco Spa
        if ($this->id == 381) 
            $title .= 'Hotel ' . $title;
        

        return $title;
    }

    public function getPrezzoMinMobile()
    {

        return substr($this->prezzo_min, 0, strpos($this->prezzo_min, '.'));

    }

    public function getPrezzoMaxMobile()
    {

        return substr($this->prezzo_max, 0, strpos($this->prezzo_max, '.'));

    }

    public function getDescriptionOld($locale = 'it')
    {

        /*ATTENZIONE ID 1114 (Hotel Rigobello) vuole una description personalizzata in italiano*/
        if ($this->id == 1114 && $locale == 'it') {
            $desc = "L'Hotel Rigobello e' la tipica pensione della riviera romagnola di Riccione, il rapporto qualita' - prezzo e' cio che ci distingue.";
        } else {

            $off = $this->offerte->count() + $this->last->count();
            $prezzo_min = $this->prezzo_min;

            $off_str = ($off == 1) ? Lang::get('hotel.1_off') : $off . ' ' . Lang::get('hotel.n_off');

            if ($prezzo_min > 0 && $off > 0) {
                $desc = $this->nome . ' ' . Lang::get('hotel.di') . ' ' . $this->localita->nome . ', ' . Lang::get('hotel.prezzi_a_partire') . ' ' . $prezzo_min . ' ' . Lang::get('hotel.con') . ' ' . $off_str . Lang::get('hotel.prenota_subito_1');
            } elseif ($off > 0) {
                $desc = $this->nome . ' ' . Lang::get('hotel.di') . ' ' . $this->localita->nome . ', ' . $off_str . Lang::get('hotel.prenota_subito_2');
            } elseif ($prezzo_min > 0) {
                $desc = $this->nome . ' ' . Lang::get('hotel.di') . ' ' . $this->localita->nome . ', ' . Lang::get('hotel.prezzi_a_partire') . ' ' . $prezzo_min . Lang::get('hotel.prenota_subito_3');
            } else {
                $desc = Lang::get('hotel.organizza') . $this->nome . ' ' . Lang::get('hotel.di') . ' ' . $this->localita->nome . Lang::get('hotel.scopri');
            }

        }

        return $desc;

    }

    public function getDescription($locale = 'it')
    {

        /*ATTENZIONE ID 1114 (Hotel Rigobello) vuole una description personalizzata in italiano*/
        if ($this->id == 1114 && $locale == 'it') {
            $desc = "L'Hotel Rigobello e' la tipica pensione della riviera romagnola di Riccione, il rapporto qualita' - prezzo e' cio che ci distingue.";
        } 
        elseif ($locale == 'it' && $this->bonus_vacanze_2020 == 1) {
            $desc = $this->nome . ' ' . $this->localita->nome . " aderisce al bonus vacanze. Prenota qui senza intermediari per avere il MIGLIOR PREZZO POSSIBILE. Prezzi: min " . $this->prezzo_min . " €, max " . $this->prezzo_max . " €";   
        }
        else {

            $desc = $this->nome . ' ' . $this->localita->nome;

            $desc .= ', ' . lcfirst(Lang::get('hotel.preonota_qui'));

            $desc .= ' ' . ucfirst(Lang::get('labels.prezzi')) . ': min ' . $this->prezzo_min . ' €, max ' . $this->prezzo_max . ' €';

        }

        return $desc;

    }

    private function _lat_km_to_decimaldeg($n = 0)
    {
        //Converti i km in miglia nautiche sapendo che un miglio=1852 metri.
        $miglia = $n * 1000 / 1852;

        //echo 'miglia = primi di grado = '.$miglia.'<br>';

        $dec_grado_lat = $miglia / 60;

        //echo 'decimali di grado = '.$dec_grado.'<br>';
        //echo 'AUMENTO (mord) e DIMINUISCO (sud) LA MIA LATITUDINE di '.  $dec_grado . ' decimi di grado';

        return $dec_grado_lat;
    }

    private function _long_km_to_decimaldeg($n = 0, $lat = 44)
    {

        $correzione = 0.54 / cos(deg2rad($lat));

        //echo "cos($lat) = ".cos($lat).'<br>';
        //echo '$correzione = '.$correzione.'<br>';

        $primi_di_grado = $n * $correzione;

        $dec_grado_long = $primi_di_grado / 60;

        return $dec_grado_long;
    }

    public function countImmaginiGallery()
    {
        return $this->immaginiGallery->count();
    }

    public function superatoLimiteOfferte($to_add = 0)
    {
        $attivi_compreso_questo = self::offerte()->attiva()->count() + 1;
        return $attivi_compreso_questo > self::LIMIT_OFFERTE;
    }

    public function superatoLimiteLast($to_add = 0)
    {
        $attivi_compreso_questo = self::last()->attiva()->count() + 1;
        return $attivi_compreso_questo > self::LIMIT_LAST;
    }

    public function superatoLimiteOffertePrenotaPrima()
    {
        return self::offertePrenotaPrima()->attiva()->count() >= self::LIMIT_OFFERTE_PRENOTA_PRIMA;
    }

    public function deleteGallery($id = 0)
    {

        if (!$id) {

            // cancello i file dal server
            foreach ($this->immaginiGallery as $immagineGallery) {
                $immagineGallery->deleteFiles();
            }

            // cancello le immagini dal DB
            $this->deleteImmaginiGallery();

        } else {

            ImmagineGallery::find($id)->deleteFiles();
            ImmagineGallery::find($id)->delete();

        }
        
    }

    public function createEmptyDescription()
    {
        $desc_hotel = new DescrizioneHotel;
        $desc_hotel->video_url = "";
        $desc_hotel->online = 1;

        $descrizione = $this->descrizioneHotel()->save($desc_hotel);

        foreach (Utility::linguePossibili() as $lang) {

            $descrizione_lingua = new DescrizioneHotelLingua;
            $descrizione_lingua->lang_id = $lang;
            $descrizione_lingua->testo = "";
            $descrizione->descrizioneHotel_lingua()->save($descrizione_lingua);
            
        }

        return;
    }

    public function createEmptyNota()
    {
        $nota_hotel = new NotaListino;

        $nota = $this->notaListino()->save($nota_hotel);

        foreach (Utility::linguePossibili() as $lang) {
            $nota_lingua = new NotaListinoLingua;
            $nota_lingua->lang_id = $lang;
            $nota_lingua->testo = "";
            $nota->noteListino_lingua()->save($nota_lingua);
        }

        return;
    }

    private function _getFieldDoubleZero($value)
    {
        if ($value == '0:0') {
            return '';
        } elseif (substr($value, -2) == ':0') {
            return $value . '0';
        } else {
            return $value;
        }

    }

    public function writeReception()
    {
        $reception = '';
        if ($this->reception1_da != '0:0' || $this->reception1_a != '0:0') {
            $reception .= $this->_getFieldDoubleZero($this->reception1_da) . ' - ' . $this->_getFieldDoubleZero($this->reception1_a);
        }
        if ($this->reception2_da != '0:0' || $this->reception2_a != '0:0') {
            $reception .= ' ' . $this->_getFieldDoubleZero($this->reception2_da) . ' - ' . $this->_getFieldDoubleZero($this->reception2_a);
        }

        return $reception;
    }

    public function addCountFavourite()
    {
        $this->count_preferiti++;
        $this->save();
    }

    public function subCountFavourite()
    {
        if ($this->count_preferiti > 0) {
            $this->count_preferiti--;
            $this->save();
        }
    }

    public function isFavourite()
    {

        return CookieIA::isFavourite($this->id);

    }

    /**
     * [getCap se il campo cap è valorizzato prendo quello, altrimenti prendo il CAP della località]
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public function getCapAttribute($value)
    {
        if ($value != '') {
            return $value;
        } else
        if (!is_null($this->localita)) {
            return $this->localita->cap;
        } else {
            return '';
        }

    }

    /**
     * DEPRECATA
     * Chiesta da Alessandra la rimozione della funzione degli spazi automatici visto che se hai un numero fisso 
     * viene spaccato male
     */

    
    public function getWhatsappAttribute($value)
    {
        // if ($value == '' || strpos($value, ' ') === true) {
        //     return $value;
        // } else {
        //     return substr($value, 0, 3) . ' ' . substr($value, 3, 3) . ' ' . substr($value, 6);
        // }
        return $value;

    }

    // public function getWhatsappAttribute($value) {
    //     return $value;
    // }

    // public function setWhatsappAttribute($value)
    // {   
       
    //     $this->attributes['whatsapp'] = str_replace(' ', '', $value);
    // }

    /*
    CAMPI Orari dei Pasti
     */

    public function getPranzoAAttribute($value)
    {
        if ($value == '0:0') {
            return '';
        } elseif (substr($value, -2) == ':0') {
            return $value . '0';
        } else {
            return $value;
        }

    }

    public function getPranzoDaAttribute($value)
    {
        if ($value == '0:0') {
            return '';
        } elseif (substr($value, -2) == ':0') {
            return $value . '0';
        } else {
            return $value;
        }

    }

    public function getCenaAAttribute($value)
    {
        if ($value == '0:0') {
            return '';
        } elseif (substr($value, -2) == ':0') {
            return $value . '0';
        } else {
            return $value;
        }

    }

    public function getCenaDaAttribute($value)
    {
        if ($value == '0:0') {
            return '';
        } elseif (substr($value, -2) == ':0') {
            return $value . '0';
        } else {
            return $value;
        }

    }

    public function getColazioneAAttribute($value)
    {
        if ($value == '0:0') {
            return '';
        } elseif (substr($value, -2) == ':0') {
            return $value . '0';
        } else {
            return $value;
        }

    }

    public function getColazioneDaAttribute($value)
    {
        if ($value == '0:0') {
            return '';
        } elseif (substr($value, -2) == ':0') {
            return $value . '0';
        } else {
            return $value;
        }

    }

    /**
     * [getTestoLinkAttribute se testo_link è vuoto restituisce il link medesimo come testo]
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public function getTestoLinkAttribute($value)
    {
        if ($value == '') {
            return $this->link;
        } else {
            return $value;
        }
    }

    /**
     * [getDistanzaCentroPoi restituisce la distanza dal centro tenendo conto del raggio]
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public function getDistanzaDalCentroPoi($value, $centro_raggio)
    {
        $distanza_centro_mtrs = $value * 1000;
        $pos = $distanza_centro_mtrs - $centro_raggio;

        if ($centro_raggio > 0) {

            if ($pos <= 0) {
                return "0.0";
            } else {
                return $pos / 1000;
            }
        } else {

            return $value;
        }
    }

    /**
     * [getListingTrattamento ritorna un array per popolare la combobox del form richiedi preventivo:
     * se c'è almeno un valore selezionato allora prende quello/quelli, altrimenti li prende tutti]
     * @return [type] [description]
     */
    public function getListingTrattamento()
    {

        $trattamenti_res = array();
        $trattamenti_arr = Utility::Trattamenti(); // Traduce

        if ($this->trattamento_sd == 0 && $this->trattamento_sd_spiaggia == 0 && $this->trattamento_bb == 0 && $this->trattamento_bb_spiaggia == 0 && $this->trattamento_mp == 0 && $this->trattamento_mp_spiaggia == 0 && $this->trattamento_pc == 0 && $this->trattamento_ai == 0) {

            foreach ($trattamenti_arr as $key => $value) {
                $trattamenti_res[$key] = $value;
            }

        } else {

            foreach ($trattamenti_arr as $key => $value) {
                if ($this->$key) {
                    $trattamenti_res[$key] = $value;
                }
            }

        }

        return $trattamenti_res;
    }

    /**
     * [macroCategoria tutti i clienti della macrolocalità X e di categoria Y]
     * @param string  $macro_localita_nome [description]
     * @param integer $categoria           [description]
     * ES:
     * $cli = new App\Hotel
     * $cli->MacroCategoria('Rimini',3)
     */
    public function macroCategoria($macro_localita_nome = "", $categoria = 0)
    {
        $clienti = array();

        $clienti_categoria = $this->categoria($categoria)->attivo()->orderBy('nome', 'asc')->get();

        foreach ($clienti_categoria as $cliente) {

            $loc = $cliente->localita;
            $macro = $loc->macrolocalita;

            // ATTENZIONE CI SONO DELLE LOCALITA' CHE HANNO macrolocalita_id = 0 (PERCHE' ?????)
            if (!is_null($macro)) {
                $nome = $macro->nome;
                if (Utility::confrontaStringa($nome, $macro_localita_nome)) {
                    $clienti[] = $cliente;
                }
            }
        }

        return $clienti;
    }

    public function getIdNome()
    {
        return $this->id . " " . str_replace("'", "", $this->nome);
    }

    public function getIdNomeLoc()
    {

        $loc = Localita::find($this->localita_id);

        if (!is_null($loc)) {
            $micro = $loc->nome;
            return $this->id . " " . str_replace("'", "", $this->nome) . " " . $micro;
        } else {
            return $this->getIdNome();
        }

    }

    /**
     * Ritorna il path dell'immagine, ideale per essere passato alle funzioni built-in di Laravel url() public_path()
     * @param  boolean $image_not_found_placeholder true per fare in modo che se l'immagine non c'è, torna il path del placeholder "immagine non trovata"
     * @return string|boolean
     */

    public function getListingImg($version, $image_not_found_placeholder = false, $passing_image = null)
    {
        
        if (is_null($passing_image))
            $image = $this->listing_img;
        else
            $image = $passing_image;

        $path = config("app.cdn_online") . "/" . self::GALLERY_IMG_PATH . $version. "/" . $this->id . "/" . $image;
        return $path;

    }
    
    /*public function getListingImg($version, $image_not_found_placeholder = false, $passing_image = null)
    {

        if (Config::get("image.image404") && $image_not_found_placeholder)
            $image_not_found_placeholder = false;

        /*
         * 1. Inizio
         * 2. E' in una cartella esistente
         * 3. E' un file esistente
         * 4. copio il file nella cartella
         * 5. eseguo il crop/resize
         * 6. restituisco il file
         * /

        if (is_null($passing_image)) 
            $image = $this->listing_img;
        else
            $image = $passing_image;

        $path = self::GALLERY_IMG_PATH . "/$version/{$image}";
        $file_exists = true;
        $ok = true;

        // non è stata definita
        if (empty($image)) {
            $ok = false;
        }

        // è stata definita ma è invalida (non c'è fisicamente sul filesystem o è illeggibile)
        elseif (!$file_exists) {
            $ok = false;
        }

        dump(Utility::assetsLoaded(self::GALLERY_IMG_PATH . "/$version/ia.jpg"));
        dd( Utility::assetsLoaded($path) );

        if ($ok) {
            return Utility::assetsLoaded($path);
        } elseif ($image_not_found_placeholder) {
            return Utility::assetsLoaded(self::GALLERY_IMG_PATH . "/$version/ia.jpg");
        }
        
        return false;

    } */ 

    public function deleteListingImg()
    {
        $ok = 0;

        if ($this->getListingImg("360x200") !== false) {
            if (File::delete(public_path($this->getListingImg("360x200")))) {
                $ok++;
            }
        }

        if ($this->getListingImg("720x400") !== false) {
            if (File::delete(public_path($this->getListingImg("720x400")))) {
                $ok++;
            }

        }

        if ($ok == 2) {
            $this->listing_img = '';

            return true;
        }

        return false;
    }


    public function notifyMeTrattamenti()
      {
       Utility::swapToSendGrid();
      $from = "supporto@info-alberghi.com";
      $nome = "Lo staff di Info Alberghi";
      $oggetto = "Moderazione dei trattamenti";

      $hotel_id = $this->id; // $this->nome_cliente
      $hotel_name = $this->nome; // $this->nome_cliente
      $nome_cliente = "cliente";

      $email_cliente = explode(',', $this->email);

      if ($this->email_secondaria != "") {
          $email_cliente = explode(',', $this->email_secondaria);
      }

      Mail::send('emails.trattamenti',

          compact(

              'nome_cliente',
              'hotel_id',
              'hotel_name',
              'oggetto'

          ), function ($message) use ($from, $oggetto, $nome, $email_cliente) {

              $message->from($from, $nome);
              $message->replyTo($from);
              $message->to($email_cliente);
              $message->bcc( 'offerte@info-alberghi.com');
              $message->subject($oggetto);
          });

      return;

      }

    public function notifyMeLabelCaparra($type = 'create')
      {
       Utility::swapToSendGrid();
      $from = "supporto@info-alberghi.com";
      $nome = "Lo staff di Info Alberghi";
      $oggetto = "Moderazione politiche di cancellazione";
      $label = "";

      $hotel_id = $this->id; // $this->nome_cliente
      $hotel_name = $this->nome; // $this->nome_cliente
      $nome_cliente = "cliente";

      if($type != 'delete')
        {
        //? ATTENZIONE siccome questa relazione è caricata di default con with
        //? all'inizio è vuota perché la label lk'ho appena creata
        $this->refresh();
        $label = $this->labelCaparre->testo_it;
        }

      $email_cliente = explode(',', $this->email);

      if ($this->email_secondaria != "") {
          $email_cliente = explode(',', $this->email_secondaria);
      }

      Mail::send('emails.label_caparre',

          compact(
              'type',
              'nome_cliente',
              'hotel_id',
              'hotel_name',
              'oggetto',
              'label',

          ), function ($message) use ($from, $oggetto, $nome, $email_cliente) {

              $message->from($from, $nome);
              $message->replyTo($from);
              $message->to($email_cliente);
              $message->bcc( 'offerte@info-alberghi.com');
              $message->subject($oggetto);
          });

      return;

      }

    public function notifyMeApprovedTitles()
    {
        Utility::swapToSendGrid();

        $from = "supporto@info-alberghi.com";
        $nome = "Lo staff di Info Alberghi";

        $oggetto = "Moderazione dei titoli della tua gallery";

        $hotel_id = $this->id; // $this->nome_cliente
        $hotel_name = $this->nome; // $this->nome_cliente
        $nome_cliente = "cliente";

        $email_cliente = explode(',', $this->email);

        if ($this->email_secondaria != "") {
            $email_cliente = explode(',', $this->email_secondaria);
        }

        Mail::send('emails.moderazione',

            compact(

                'nome_cliente',
                'hotel_id',
                'hotel_name',
                'oggetto'

            ), function ($message) use ($from, $oggetto, $nome, $email_cliente) {

                $message->from($from, $nome);
                $message->replyTo($from);
                $message->to($email_cliente);
                $message->subject($oggetto);
            });

        return;

    }

    /**
     * Shortcut per richiamare il contesto "Eager Loading" per i listing degli hotel<br>
     * in questo modo ho un unico punto dove configurare (e quindi eventualmente correggere)<br>
     * questo eager loading
     * @param  string $locale La lingua: it, de, fr, en
     * @return \Illuminate\Database\Eloquent\Builder|static
     */

    public static function withListingEagerLoading($locale, $terms = [], $listing_gruppo_servizi_id = null)
    {

        if (is_null($listing_gruppo_servizi_id)) {
            return self::with([
                'stelle',
                'caparreAttive',
                'labelCaparre',
                'localita.macrolocalita',
                'offerte' => function ($query) use ($locale) {
                    $query
                        ->attiva()
                        ->orderByRaw("RAND()");
                },
                'offerte.offerte_lingua' => function ($query) use ($locale, $terms) {
                    $query
                        ->where('lang_id', '=', $locale)
                        ->multiTestoOrTitoloLike($terms);
                },
                'last' => function ($query) use ($locale) {
                    $query
                        ->attiva()
                        ->orderByRaw("RAND()");
                },
                'last.offerte_lingua' => function ($query) use ($locale, $terms) {
                    $query
                        ->where('lang_id', '=', $locale)
                        ->multiTestoOrTitoloLike($terms);
                },

                'offertePrenotaPrima' => function ($query) use ($locale) {
                    $query
                        ->attiva()
                        ->orderByRaw("RAND()");
                },
                'offertePrenotaPrima.offerte_lingua' => function ($query) use ($locale, $terms) {
                    $query
                        ->where('lang_id', '=', $locale)
                        ->multiTestoOrTitoloLike($terms);
                },

                'offerteLast' => function ($query) use ($locale) {
                    $query
                        ->attiva()
                        ->orderByRaw("RAND()");
                },
                'offerteLast.offerte_lingua' => function ($query) use ($locale, $terms) {
                    $query
                        ->where('lang_id', '=', $locale)
                        ->multiTestoOrTitoloLike($terms);
                },
                'bambiniGratisAttivi' => function ($query) {
                    $query
                        ->orderBy('valido_dal', 'asc');
                },

                'immaginiGallery',
            ]);
        } else {
            return self::with([
                'stelle',
                'localita.macrolocalita',
                'offerte' => function ($query) use ($locale) {
                    $query
                        ->attiva()
                        ->orderByRaw("RAND()");
                },
                'offerte.offerte_lingua' => function ($query) use ($locale, $terms) {
                    $query
                        ->where('lang_id', '=', $locale)
                        ->multiTestoOrTitoloLike($terms);
                },
                'last' => function ($query) use ($locale) {
                    $query
                        ->attiva()
                        ->orderByRaw("RAND()");
                },
                'last.offerte_lingua' => function ($query) use ($locale, $terms) {
                    $query
                        ->where('lang_id', '=', $locale)
                        ->multiTestoOrTitoloLike($terms);
                },

                'offertePrenotaPrima' => function ($query) use ($locale) {
                    $query
                        ->attiva()
                        ->orderByRaw("RAND()");
                },
                'offertePrenotaPrima.offerte_lingua' => function ($query) use ($locale, $terms) {
                    $query
                        ->where('lang_id', '=', $locale)
                        ->multiTestoOrTitoloLike($terms);
                },

                'offerteLast' => function ($query) use ($locale) {
                    $query
                        ->attiva()
                        ->orderByRaw("RAND()");
                },
                'offerteLast.offerte_lingua' => function ($query) use ($locale, $terms) {
                    $query
                        ->where('lang_id', '=', $locale)
                        ->multiTestoOrTitoloLike($terms);
                },
                'bambiniGratisAttivi' => function ($query) {
                    $query
                        ->orderBy('valido_dal', 'asc');
                },

                'immaginiGallery',

                'immaginiListing' => function ($query) use ($listing_gruppo_servizi_id) {
                    $query
                        ->where('gruppo_id', '=', $listing_gruppo_servizi_id);
                },

                'servizi' => function ($query) use ($listing_gruppo_servizi_id) {
                    $query
                        ->where('gruppo_id', '=', $listing_gruppo_servizi_id);
                },

                'servizi.servizi_lingua' => function ($query) use ($locale) {
                    $query
                        ->where('lang_id', '=', $locale);
                },

                'servizi.categoria' => function ($query) {
                    $query
                        ->where('listing', 1);
                },

            ]);
        }

    }

    /**
     * [withListingLazyEagerLoading
    l'idea è quella di NON includere nell'eager loading delle relazioni che non serviranno nel listing: ad esempio nel listing per categorie non includo le offerte in lingua perché NON devo cercare dentro le offerte come nel listing offerte.]
    13/12/17 Se l'ordinamento è per prezzo min/max ordino le offerte associate a ciascun hotel di conseguenza; altrimenti sono ordinate per validità
     * @param  CmsPagina $cms_pagina [description]
     * @return [type]                [description]
     */
    public static function withListingLazyEagerLoading(CmsPagina $cms_pagina, $terms = [], $order = null)
    {

        if ($cms_pagina->listing_parolaChiave_id) {

            $hotel = self::with([
                'stelle',
                'localita.macrolocalita',
                'numero_offerte_attive',
                'numero_last_attivi',
                'numero_pp_attivi',
                'numero_bambini_gratis_attivi',
                'numero_immagini_gallery',
                'caparreAttive',
                'labelCaparre',
                'offerteTop' => function ($query) use ($order) {
                    $query
                        ->attiva()
                        ->ordinaPer($order);

                },
                'offerteTop.offerte_lingua' => function ($query) use ($cms_pagina, $terms) {
                    $query
                        ->where('lang_id', '=', $cms_pagina->lang_id)
                        ->where('pagina_id', '=', $cms_pagina->id)
                        ->multiTestoOrTitoloLike($terms);
                },
                'offerteLast' => function ($query) use ($order) {
                    $query
                        ->attiva()
                        ->ordinaPer($order);

                },
                'offerteLast.offerte_lingua' => function ($query) use ($cms_pagina, $terms) {
                    $query
                        ->where('lang_id', '=', $cms_pagina->lang_id)
                        ->multiTestoOrTitoloLike($terms);
                },

            ])
            ->withFirstImage()
            ->withCount(['caparreAttive', 'immaginiGallery', 'serviziCovid']);
        } elseif ($cms_pagina->listing_offerta == 'offerta') {

            $hotel = self::with([
                'stelle',
                'caparreAttive',
                'labelCaparre',
                'localita.macrolocalita',
                'numero_offerte_attive',
                'numero_last_attivi',
                'numero_pp_attivi',
                'numero_bambini_gratis_attivi',
                'numero_immagini_gallery',
                'offerteTopOS' => function ($query) use ($order) {
                    $query
                        ->attiva()
                        ->ordinaPer($order);

                },
                'offerteTopOS.offerte_lingua' => function ($query) use ($cms_pagina) {
                    $query
                        ->where('pagina_id', '=', $cms_pagina->id)
                        ->where('lang_id', '=', $cms_pagina->lang_id);
                },
                'offerte' => function ($query) use ($order) {
                    $query
                        ->attiva()
                        ->ordinaPer($order);
                },
                'offerte.offerte_lingua' => function ($query) use ($cms_pagina) {
                    $query
                        ->where('lang_id', '=', $cms_pagina->lang_id);
                },
            ])->withFirstImage()->withCount(['caparreAttive', 'immaginiGallery', 'serviziCovid']);
        } elseif ($cms_pagina->listing_offerta == 'lastminute') {

            $hotel = self::with([
                'stelle',
                'caparreAttive',
                'labelCaparre',
                'localita.macrolocalita',
                'numero_offerte_attive',
                'numero_last_attivi',
                'numero_pp_attivi',
                'numero_bambini_gratis_attivi',
                'numero_immagini_gallery',
                'offerteTopLast' => function ($query) use ($order) {
                    $query
                        ->attiva()
                        ->ordinaPer($order);

                },
                'offerteTopLast.offerte_lingua' => function ($query) use ($cms_pagina) {
                    $query
                        ->where('pagina_id', '=', $cms_pagina->id)
                        ->where('lang_id', '=', $cms_pagina->lang_id);
                },
                'last' => function ($query) use ($order) {
                    $query
                        ->attiva()
                        ->ordinaPer($order);
                },
                'last.offerte_lingua' => function ($query) use ($cms_pagina) {
                    $query
                        ->where('lang_id', '=', $cms_pagina->lang_id);
                },

            ])->withFirstImage()->withCount(['caparreAttive', 'immaginiGallery', 'serviziCovid']);
        } elseif (!empty($cms_pagina->listing_offerta_prenota_prima)) {

            $hotel = self::with([
                'stelle',
                'caparreAttive',
                'labelCaparre',
                'localita.macrolocalita',
                'numero_offerte_attive',
                'numero_last_attivi',
                'numero_bambini_gratis_attivi',
                'numero_immagini_gallery',
                'numero_pp_attivi',
                'offerteTopPP' => function ($query) use ($order) {
                    $query
                        ->attiva()
                        ->ordinaPer($order);
                },
                'offerteTopPP.offerte_lingua' => function ($query) use ($cms_pagina) {
                    $query
                        ->where('pagina_id', '=', $cms_pagina->id)
                        ->where('lang_id', '=', $cms_pagina->lang_id);
                },
                'offertePrenotaPrima' => function ($query) use ($order) {
                    $query
                        ->attiva()
                        ->ordinaPer($order);
                },
                'offertePrenotaPrima.offerte_lingua' => function ($query) use ($cms_pagina) {
                    $query
                        ->where('lang_id', '=', $cms_pagina->lang_id);
                },

            ])->withFirstImage()->withCount(['caparreAttive', 'immaginiGallery', 'serviziCovid']);
        } elseif ($cms_pagina->listing_bambini_gratis) {

            $hotel = self::with([
                'stelle',
                'caparreAttive',
                'labelCaparre',
                'localita.macrolocalita',
                'numero_immagini_gallery',
                'bambiniGratisAttivi' => function ($query) {
                    $query
                        ->orderBy('valido_dal', 'asc');
                },
                'bambiniGratisAttivi.offerte_lingua' => function ($query) use ($cms_pagina) {
                    $query
                        ->where('lang_id', '=', $cms_pagina->lang_id);
                },
                'bambiniGratisAttivi.translate_'.$cms_pagina->lang_id,
                'offerteBambiniGratisTop' => function ($query) {
                    $query
                        ->attiva()
                        ->orderBy('valido_dal', 'asc');
                },
                'offerteBambiniGratisTop.offerte_lingua' => function ($query) use ($cms_pagina) {
                    $query
                        ->where('pagina_id', '=', $cms_pagina->id)
                        ->where('lang_id', '=', $cms_pagina->lang_id);
                },
            ])
            ->withFirstImage()->withCount(['caparreAttive', 'immaginiGallery', 'serviziCovid']);

        } elseif ($cms_pagina->listing_gruppo_servizi_id) {

            if ($cms_pagina->listing_gruppo_servizi_id == Utility::getGruppoPiscina() || $cms_pagina->listing_gruppo_servizi_id == Utility::getGruppoBenessere() || $cms_pagina->listing_gruppo_servizi_id == Utility::getGruppoPiscinaFuori()) {

                
                $hotel = self::with([
                    'stelle',
                    'caparreAttive',
                    'labelCaparre',
                    'localita.macrolocalita',
                    'numero_immagini_gallery',
                    'infoBenessere',
                    'infoPiscina',
                    'immaginiListing' => function ($query) use ($cms_pagina) {
                        $query
                            ->where('gruppo_id', '=', $cms_pagina->listing_gruppo_servizi_id);
                    },

                    'servizi' => function ($query) use ($cms_pagina) {
                        $query
                            ->where('gruppo_id', '=', $cms_pagina->listing_gruppo_servizi_id)->orWhere('categoria_id', 9);
                    },

                    'servizi.servizi_lingua' => function ($query) use ($cms_pagina) {
                        $query
                            ->where('lang_id', '=', $cms_pagina->lang_id);
                    },

                    'servizi.translate_it',

                    'servizi.categoria' => function ($query) {
                        $query
                            ->where('listing', 1);
                    },

                ])
                ->withFirstImage()
                ->withFirstImageGroup($cms_pagina->listing_gruppo_servizi_id);

                

                if ($cms_pagina->listing_gruppo_servizi_id == Utility::getGruppoPiscina()) {
                    // con questo vincolo prendo SOLO QUELLI CHE HANNO LA RELAZIONE infoPiscina con sup > 0
                    $hotel = $hotel->whereHas('infoPiscina', function ($query) {
                        $query->where('sup', '>', '0');
                    });
                } elseif ($cms_pagina->listing_gruppo_servizi_id == Utility::getGruppoPiscinaFuori()) {
                    // con questo vincolo prendo SOLO QUELLI CHE HANNO LA RELAZIONE infoPiscina con sup > 0
                    $hotel = $hotel->whereHas('infoPiscina', function ($query) {
                        $query->where('sup', '>', '0');
                    });
                } elseif ($cms_pagina->listing_gruppo_servizi_id == Utility::getGruppoBenessere()) {
                    // con questo vincolo prendo SOLO QUELLI CHE HANNO LA RELAZIONE infoBenessere con sup > 0
                    $hotel = $hotel->whereHas('infoBenessere', function ($query) {
                        $query->where('sup', '>', '0');
                    });
                }

            } else {



                // ES: /hotel-parcheggio/rimini.php
                // ES: /internet-wireless-wifi/riccione.php

                
                $hotel = self::with([
                    'stelle',
                    'localita.macrolocalita',
                    'numero_offerte_attive',
                    'numero_last_attivi',
                    'numero_pp_attivi',
                    'numero_bambini_gratis_attivi',
                    'numero_immagini_gallery',
                    'offerteTop',
                    'caparreAttive',
                    'labelCaparre',
                    'immaginiListing' => function ($query) use ($cms_pagina) {
                        $query
                            ->where('gruppo_id', '=', $cms_pagina->listing_gruppo_servizi_id);
                    },

                    // 'servizi' => function ($query) use ($cms_pagina) {
                    //     $query
                    //         ->where('gruppo_id', '=', $cms_pagina->listing_gruppo_servizi_id);
                    // },

                    // 'servizi.servizi_lingua' => function ($query) use ($cms_pagina) {
                    //     $query
                    //         ->where('lang_id', '=', $cms_pagina->lang_id);
                    // },

                    // 'servizi.categoria' => function ($query) {
                    //     $query
                    //         ->where('listing', 1);
                    // },

                ])
                ->withFirstImage()->withFirstImageGroup($cms_pagina->listing_gruppo_servizi_id);

                

            }

        // listing_gruppo_servizi_id
        } else {

            /*categoria*/
            /* es: località*/
            /* es: tarttamento*/
            $hotel = self::with([
                'stelle',
                'localita.macrolocalita',
                'numero_offerte_attive',
                'numero_last_attivi',
                'numero_pp_attivi',
                'numero_bambini_gratis_attivi',
                'offerteTop',
                'caparreAttive',
                'labelCaparre'
            ])
            ->withFirstImage()
                ->withCount(['caparreAttive', 'immaginiGallery', 'serviziCovid']);
            

        }

        return $hotel;

    } // END withListingLazyEagerLoading

    public static function withListingEagerLoadingPerSnippetOfferte($locale, $terms = [])
    {
        return self::with([
            'stelle',
            'caparreAttive',
            'labelCaparre',
            'localita.macrolocalita',
            'offerte' => function ($query) use ($locale) {
                $query
                    ->attiva()
                    ->orderByRaw("RAND()");
            },
            'offerte.offerte_lingua' => function ($query) use ($locale, $terms) {
                $query
                    ->where('lang_id', '=', $locale)
                    ->multiTestoOrTitoloLike($terms);
            },
            'last' => function ($query) use ($locale) {
                $query
                    ->attiva()
                    ->orderByRaw("RAND()");
            },
            'last.offerte_lingua' => function ($query) use ($locale, $terms) {
                $query
                    ->where('lang_id', '=', $locale)
                    ->multiTestoOrTitoloLike($terms);
            },
            'offerteLast' => function ($query) use ($locale) {
                $query
                    ->attiva()
                    ->orderByRaw("RAND()");
            },
            'offerteLast.offerte_lingua' => function ($query) use ($locale, $terms) {
                $query
                    ->where('lang_id', '=', $locale)
                    ->multiTestoOrTitoloLike($terms);
            },
            'immaginiGallery',
        ]);
    }

    public static function withListingSimiliEagerLoading($locale = 'it')
    {

        return self::with([
            'stelle',
            'caparreAttive',
            'labelCaparre',
            'localita.macrolocalita',
            /* 'puntiDiForza.puntiDiForza_lingua' => function($query) use ($locale){
            $query->where('lang_id', '=', $locale);
            },*/
            'numero_immagini_gallery',
        ]);
    }

    public function getEmailAttribute($value)
    {

        // in admin voglio che vedano la mail VERA SEMPRE !!!
        if (App::environment() == "local") {
            return config('mail.fake_mail');
        } else {
            return $value;
        }

        return $value;

    }

    public function getEmailSecondariaAttribute($value)
    {
        // in admin voglio che vedano la mail VERA SEMPRE !!!
        if (App::environment() == "local" && Request::segment(1) != 'admin') {
            return config('mail.fake_mail');
        } else {
            return $value;
        }

        return $value;
    }

    /* =============================================
    =            Query scope                =
    ============================================= */

    public function scopeAttivo($query)
    {
        return $query->whereAttivo(1);
    }



    //? ATTENZIONE: questo è un o scope momentaneo che mi permette di selezionare gli hotel attivi e quelli di Pesaro
    // ? viene utilizzato per creare gli slot della vetrina pesaro anche se gli hotel sono DISABILITATI
    public function scopeAttivoOrPesaro($query)
    {
        return $query->whereAttivo(1)->orWhereIn('id',[1875, 1883, 1884, 1890, 1891, 1892, 1897, 1898, 1899, 1900, 1901, 1902, 1903, 1904, 1905, 1906]);
    }


    public function scopeNotChiusoTemp($query)
    {
        return $query->where('chiuso_temp', 0);
    }

    public function scopeAttivoWithDemo($query)
    {
        return $query->whereAttivo(1)->orWhere('attivo', -1);
    }

    public function scopeReception24h($query)
    {
        return $query->where('reception_24h', 1);
    }

    public function scopeNome($query, $nome_hotel)
    {
        return $query->where(DB::raw('UPPER(`nome`)'), 'like', '%' . $nome_hotel . '%');
    }

    public function scopePrezzoMinPositivo($query)
    {
        return $query->where('prezzo_min', '>', 0);
    }

    public function scopeCategoria($query, $categoria)
    {
        if (!$categoria) {
            return $query;
        }

        return $query->where('categoria_id', '=', $categoria);
    }

    public function scopeConWhatsapp($query)
    {
        return $query->where('whatsapp', '!=', '');
    }

    public function scopeConNome($query, $nome_hotel)
    {
        return $query->where('nome', 'like', '%' . $nome_hotel . '%');
    }

   
    
    
    public function scopeMacroOrLoc($query, $idMacro, $idLoc)
    {
        if ($idMacro != false) {
            return
            $query->whereHas('localita', function ($q) use ($idMacro) {
                $q->where('macrolocalita_id', $idMacro);
            });
        } elseif ($idLoc != false) {
            // cerco in base all'id della localita
            return $query->where('localita_id', '=', $idLoc);
        }

    }

    public function scopeMacrolocalita($query, $macrolocalita)
    {
        if (!$macrolocalita) {
            return $query;
        }

        return $query->whereHas('localita', function ($q) use ($macrolocalita) {
            $q->where('macrolocalita_id', $macrolocalita);
        });
    }

    public function scopeLocalita($query, $localita)
    {
        if (!$localita) {
            return $query;
        }

        return $query->where('localita_id', '=', $localita);
    }

    public function scopeExclude($query, $filters = 0)
    {
        if (!$filters) {
            return $query;
        }

        $id = $filters['id'];
        return $query->where("id", '!=', $id);
    }

    public function scopeRaggio($query, $filters = 0)
    {
        if (!$filters) {
            return $query;
        }

        $lat = $filters['lat'];
        $long = $filters['long'];

        $raggio = isset($filters['raggio']) ? $filters['raggio'] : self::RAGGIO_IN_KM;

        /**
         * Trovo un intorno di n KM come valore decimale dei gradi da aggiungere/sottrarre alla lat/long dell'hotel attuale
         */

        $min_lat = $lat - $this->_lat_km_to_decimaldeg($raggio);
        $max_lat = $lat + $this->_lat_km_to_decimaldeg($raggio);

        /**
         * FINE
         */

        // trovo i gradi della latitudine

        $gradi_lat = $lat;
        $pos = strpos($lat, '.');

        if ($pos !== false) {
            $gradi_lat = intval(substr($lat, 0, $pos));
        }

        $min_long = $long - $this->_long_km_to_decimaldeg($raggio, $gradi_lat);
        $max_long = $long + $this->_long_km_to_decimaldeg($raggio, $gradi_lat);

        $str_WHERE_raggio = " AND mappa_latitudine > $min_lat AND mappa_latitudine < $max_lat AND mappa_longitudine > $min_long AND mappa_longitudine < $max_long";

        return $query
            ->where("mappa_latitudine", '>', $min_lat)
            ->where("mappa_latitudine", '<', $max_lat)
            ->where("mappa_longitudine", '>', $min_long)
            ->where("mappa_longitudine", '<', $max_long);

    }

    public function scopeUpselling($query, $checkForUpselling)
    {
        if (!$checkForUpselling) {
            return $query;
        }

        return $query->where('mail_upselling', 1);
    }

    public function scopeListingMacrolocalita($query, $id_macrolocalita)
    {
        if (!$id_macrolocalita) {
            return $query;
        }

        if ($id_macrolocalita == Utility::getMacroRR()) {
            return $query->where("localita_id", '!=', Utility::getIdMicroPesaro());
        }

        $macrolocalita = Macrolocalita::with("localita")
            ->find($id_macrolocalita);

        $ids = [];
        foreach ($macrolocalita->localita as $localita) {
            $ids[] = $localita->id;
        }

        return $query->whereIn("localita_id", $ids);
    }

    public function scopeListingLocalita($query, $id_localita)
    {
        if (!$id_localita) {
            return $query;
        }

        // SE LA LOCALITA è RR NON DEVO PIU' tornare la query e NON FILTRARE MA TOGLIERE LA LOCALITA' DI PESARO
        if ($id_localita == Utility::getMicroRR()) {
            return $query->where("localita_id", '!=', Utility::getIdMicroPesaro());
            
        }

        return $query->where("localita_id", $id_localita);
    }

    public function scopeListingLocalitaMultiple($query, $id_localita = array())
    {
        if (!isset($id_localita)) {
            return $query;
        }

        return $query->whereIn("localita_id", $id_localita);
    }

    public function scopeListingTipologie($query, $tipologie)
    {
        if (!$tipologie) {
            return $query;
        }

        if (strpos($tipologie, ",") !== false) {
            return $query->whereIn("tipologia_id", explode(",", $tipologie));
        } else {
            return $query->where("tipologia_id", $tipologie);
        }

    }

    public function scopeListingCategorie($query, $categorie)
    {
        if (!$categorie) {
            return $query;
        }

        if (strpos($categorie, ",") !== false) {
            return $query->whereIn("categoria_id", explode(",", $categorie));
        } else {
            return $query->where("categoria_id", $categorie);
        }

    }

    public function scopeListingFromCmsPagina($query, $clienti_ids_arr)
    {
        if (!count($clienti_ids_arr)) {
            return $query;
        }

        return $query->whereIn("id", $clienti_ids_arr);
    }

    public function scopeListingTrattamento($query, $trattamenti, $prezzo = 0, $a_partire_da = "", $ricerca_avanzata = 0)
    {
        if (!$trattamenti) {
            return $query;
        }

        if (strpos($trattamenti, ",") !== false) {
            $_trattamenti = explode(",", $trattamenti);
        } else {
            $_trattamenti = [$trattamenti];
        }

        /*
         * Estraggo gli id degli hotel con un listino attivo oggi
         * per i trattamenti richiesti
         */
        $ids = [];

        if (!$ricerca_avanzata) {
            $listini = Listino::attivoPerTrattamento($_trattamenti)->get();
        } else {
            $listini = Listino::attivoAPartireDaPerTrattamento($_trattamenti, $prezzo, $a_partire_da)->get();
        }

        foreach ($listini as $listino) {
            $ids[$listino->hotel_id] = $listino->hotel_id;
        }

        if (!$ricerca_avanzata) {
            $listini = ListinoMinMax::attivoPerTrattamento($_trattamenti)->get();
        } else {
            $listini = ListinoMinMax::attivoAPartireDaPerTrattamento($_trattamenti, $prezzo, $a_partire_da)->get();
        }

        foreach ($listini as $listino) {
            $ids[$listino->hotel_id] = $listino->hotel_id;
        }

        return $query->whereIn("id", $ids);

    } /* end scopeListingTrattamento*/

    /**
     * [scopeListingTrattamentoNew
     * Sarà presente nel listing dei trattamenti NON PIU' CHI HA IL LISTINO CORRISPONDENTE COMPILATO, ma chi ha il trattemnto checkato nella scheda hotel (gli stessi che utilizziamo per il form del calcolo preventivo). Naturalmente siccome le evidenze npon compaiono se l'hotel non è nel listinh chi ha un'evidenza nel trattamento bisogna che abbia il check corrispondente]
     * @param  [type]  $query            [description]
     * @param  [type]  $trattamenti      [description]
     * @param  integer $prezzo           [description]
     * @param  string  $a_partire_da     [description]
     * @param  integer $ricerca_avanzata [description]
     * @return [type]                    [description]
     */
    public function scopeListingTrattamentoNew($query, $trattamenti, $prezzo = 0, $a_partire_da = "", $ricerca_avanzata = 0)
    {
        if (!$trattamenti) {
            return $query;
        }

		$trattamenti_con_spiaggia = Utility::getTrattamentiSpiaggia();

        if (strpos($trattamenti, ",") !== false) {
            $_trattamenti = explode(",", $trattamenti);
        } else {
            $_trattamenti = [$trattamenti];
        }

        if ($ricerca_avanzata == 1) {

            foreach ($_trattamenti as $trattamento) {
							
							if( in_array($trattamento, $trattamenti_con_spiaggia) )
								{
								$query->where(function($q) use ($trattamento) { 
									$q->where($trattamento, 1)
									->orWhere($trattamento.'_spiaggia', 1);
									});
								}
							else
								{
									$query->where($trattamento, 1);
								}
            }
				
				} else {

            foreach ($_trattamenti as $trattamento) {
                if(in_array("trattamento_" . $trattamento, $trattamenti_con_spiaggia))
									{
									$query->where(function($q) use ($trattamento) { 
											$q->where("trattamento_" . $trattamento, 1)
											->orWhere("trattamento_" . $trattamento.'_spiaggia',1);
											});
									}
								else
									{
									$query->where("trattamento_" . $trattamento, 1);
									}
            }

        }

        return $query;

    } /* end scopeListingTrattamento*/

    public function scopeListingWhatsapp($query, $whatsapp)
    {
        if (!$whatsapp) {
            return $query;
        }

        return $query->where("whatsapp", "!=", "");
	}
	
	public function scopeListingBonusVacanze($query, $bonus)
    {
        if (!$bonus) {
            return $query;
        }

        return $query->where("bonus_vacanze_2020", 1);
    }

    public function scopeListingIndirizzoStradario($query, $indirizzo_stradario)
    {
        if (empty($indirizzo_stradario)) {
            return $query;
        }

        return $query->where("indirizzo", "like", '%' . $indirizzo_stradario . '%');
    }

    public function scopeListingGreenBooking($query, $listing_green_booking)
    {
        if (!$listing_green_booking) {
            return $query;
        }

        return $query->where("green_booking", 1);
    }

    public function scopeListingEcoSostenibile($query, $listing_eco_sostenibile)
    {
        if (!$listing_eco_sostenibile) {
            return $query;
        }

        return $query->where("eco_sostenibile", 1);
    }

    public function scopeListingAnnuali($query, $annuale)
    {
        if (!$annuale) {
            return $query;
        }

        return $query->where("annuale", 1);
    }

    public function scopeListingPreferiti($query, $listing_preferiti)
    {

        $cookie = CookieIA::getFavourite();

        if (!$listing_preferiti) {
            return $query;
        }

        if (!$cookie) {
            return $query->where("id", 0);
        }

        $cookieArr = explode(",", $cookie);
        $_cookieArr1 = array_pop($cookieArr);
        $_cookieArr2 = array_shift($cookieArr);

        return $query->whereIn("id", $cookieArr);

    }

    public function scopeListingParolaChiave($query, $id_parola_chiave, $locale = 'it')
    {
        if (!$id_parola_chiave) {
            return $query;
        }

        /*
         * Forse i passaggi che seguono non sono molto "laravel way"
         * ma sono stati fatti - mysql general log - alla mano
         * cercando di contenere il numero delle query
         * Non è da escludere che alla fine sia meglio andarci direttamente di raw query...
         */

        /*
         * Dalla parola chiave ottengo le parole chiave espanse
         */
        $parola_chiave = ParolaChiave::with("alias")->find($id_parola_chiave);

        $terms = [];
        foreach ($parola_chiave->alias as $term) {
            $terms[] = $term->chiave;
        }

        /*
         * Dalle parole chiave espanse ottengo le offerte che matchano
         */
        $offerte_lingua = OffertaLingua::inLingua($locale)->multiTestoOrTitoloLike($terms)->get();

        $ids = [];
        foreach ($offerte_lingua as $offerta_lingua) {
            $ids[$offerta_lingua->master_id] = $offerta_lingua->master_id;
        }

        $offerte = Offerta::whereIn("id", $ids)
            ->attiva()
            ->get();

        /*
         * Dalle offerte estraggo gli id degli hotel
         */
        $hotel_ids = [];
        if ($offerte) {
            foreach ($offerte as $offerta) {
                $hotel_ids[$offerta->hotel_id] = $offerta->hotel_id;
            }
        }

        return $query->whereIn("id", $hotel_ids);
    } /* END ListingParolaChiave */

    public function scopeListingParolaChiaveOfferteAttive($query, $id_parola_chiave, $locale = 'it')
    {
        if (!$id_parola_chiave) {
            return $query;
        }

        /*
         * Forse i passaggi che seguono non sono molto "laravel way"
         * ma sono stati fatti - mysql general log - alla mano
         * cercando di contenere il numero delle query
         * Non è da escludere che alla fine sia meglio andarci direttamente di raw query...
         */

        /*
         * Dalla parola chiave ottengo le parole chiave espanse
         */
        $parola_chiave = ParolaChiave::with("alias")->find($id_parola_chiave);

        $terms = [];
        if (isset($parola_chiave->alias)) {
            foreach ($parola_chiave->alias as $term) {
                $terms[] = $term->chiave;
            }
        }

        /*
         * Dalle parole chiave espanse ottengo le offerte che matchano
         */
        $offerte_lingua = OffertaLingua::whereHas('offerta', function ($query) {
            $query->where('attivo', 1)->where('valido_al', '>=', date('Y-m-d'));

        })
            ->inLingua($locale)
            ->multiTestoOrTitoloLike($terms)
            ->get();

        $ids = [];
        foreach ($offerte_lingua as $offerta_lingua) {
            $ids[$offerta_lingua->master_id] = $offerta_lingua->master_id;
        }

        $offerte = Offerta::whereIn("id", $ids)
            ->attiva()
            ->get();

        /*
         * Dalle offerte estraggo gli id degli hotel
         */

        $hotel_ids = [];
        if ($offerte) {
            foreach ($offerte as $offerta) {
                $hotel_ids[$offerta->hotel_id] = $offerta->hotel_id;
            }
        }
        ;
        return $query->whereIn("id", $hotel_ids);
    } /* END scopeListingParolaChiaveOfferteAttive */

    public function scopeListingParolaChiaveOfferteAttiveVotAttivi($query, $cms_pagina_id, $id_parola_chiave, $locale ='it', $listing_macrolocalita_id = 0, $listing_localita_id = 0)
    {
        if (!$id_parola_chiave) {
            return $query;
        }

        /*
         * Forse i passaggi che seguono non sono molto "laravel way"
         * ma sono stati fatti - mysql general log - alla mano
         * cercando di contenere il numero delle query
         * Non è da escludere che alla fine sia meglio andarci direttamente di raw query...
         */

        /*
         * Dalla parola chiave ottengo le parole chiave espanse
         */
        $parola_chiave = ParolaChiave::with("alias")->find($id_parola_chiave);

        $terms = [];
        if (isset($parola_chiave->alias)) {
            foreach ($parola_chiave->alias as $term) {
                $terms[] = $term->chiave;
            }
        }


        $localita_ids = [];

        if ($listing_macrolocalita_id != Utility::getMacroRR() || $listing_localita_id != Utility::getMicroRR()) {

            if ($listing_macrolocalita_id != 0) {
                $macro = Macrolocalita::with('localita')->find($listing_macrolocalita_id);

                foreach ($macro->localita as $l) {
                    $localita_ids[] =  $l->id;
                }
            } elseif ($listing_localita_id != 0) {
                $localita_ids[] = $listing_localita_id;
            }
        }

        /*
         * Dalle parole chiave espanse ottengo le offerte che matchano
         */

        //////////////////
        // OFFERTRE TOP //
        //////////////////
        $offerte_lingua_top = VetrinaOffertaTopLingua::whereHas('offerta.cliente', function ($query)  use ($localita_ids) {
            $query->where('attivo', 1);
            if (count($localita_ids)) {
                $query->whereIn('tblHotel.localita_id', $localita_ids);
            }
            
        })
            ->inLingua($locale)
            ->inPagina($cms_pagina_id)
            ->multiTestoOrTitoloLike($terms)
            ->get();

        $ids = [];
        foreach ($offerte_lingua_top as $offerta_lingua) {
            $ids[$offerta_lingua->master_id] = $offerta_lingua->master_id;
        }

        $offerte_top = VetrinaOffertaTop::whereIn("id", $ids)
            ->attiva()
            ->get();

        /*
         * Dalle offerte_top estraggo gli id degli hotel
         */
        $hotel_ids = [];

        if ($offerte_top) {
            foreach ($offerte_top as $offerta) {
                $hotel_ids[$offerta->hotel_id] = $offerta->hotel_id;
            }
        }
        ;

        /////////////
        // OFFERTE //
        /////////////
        $offerte_lingua = OffertaLingua::whereHas('offerta.cliente', function ($query)  use ($localita_ids) {
            $query->where('attivo', 1)->where('valido_al', '>=', date('Y-m-d'));
            if (count($localita_ids)) {
                $query->whereIn('tblHotel.localita_id', $localita_ids);
            }

        })
        ->inLingua($locale)
        ->multiTestoOrTitoloLike($terms)
        ->get();



        $ids = [];
        foreach ($offerte_lingua as $offerta_lingua) {
            $ids[$offerta_lingua->master_id] = $offerta_lingua->master_id;
        }

        $offerte = Offerta::whereIn("id", $ids)
            ->attiva()
            ->get();

        /*
         * Dalle offerte estraggo gli id degli hotel
         */

        if ($offerte) {
            foreach ($offerte as $offerta) {
                if (!array_key_exists($offerta->hotel_id, $hotel_ids)) {
                    $hotel_ids[$offerta->hotel_id] = $offerta->hotel_id;
                }

            }
        }
        ;

        return $query->whereIn("id", $hotel_ids);
    } /* END listingParolaChiaveOfferteAttiveVotAttivi */

    public function scopeListingMultiParolaChiaveOfferteAttive($query, $id_parola_chiave, $locale = 'it')
    {
        if (!$id_parola_chiave) {
            return $query;
        }

        /*
         * Forse i passaggi che seguono non sono molto "laravel way"
         * ma sono stati fatti - mysql general log - alla mano
         * cercando di contenere il numero delle query
         * Non è da escludere che alla fine sia meglio andarci direttamente di raw query...
         */

        $id_parola_chiave_arr = explode(',', $id_parola_chiave);

        $hotel_ids = [];
        foreach ($id_parola_chiave_arr as $key => $id_parola_chiave) {
            /*
             * Dalla parola chiave ottengo le parole chiave espanse
             */
            $parola_chiave = ParolaChiave::with("alias")->find($id_parola_chiave);

            $terms = [];
            if (isset($parola_chiave->alias)) {
                foreach ($parola_chiave->alias as $term) {
                    $terms[] = $term->chiave;
                }
            }

            /*
             * Dalle parole chiave espanse ottengo le offerte che matchano
             */
            $offerte_lingua = OffertaLingua::whereHas('offerta', function ($query) {
                $query->where('attivo', 1)->where('valido_al', '>=', date('Y-m-d'));

            })
                ->inLingua($locale)
                ->multiTestoOrTitoloLike($terms)
                ->get();

            $ids = [];
            foreach ($offerte_lingua as $offerta_lingua) {
                $ids[$offerta_lingua->master_id] = $offerta_lingua->master_id;
            }

            $offerte = Offerta::whereIn("id", $ids)
                ->attiva()
                ->get();

            /*
             * Dalle offerte estraggo gli id degli hotel
             */

            if ($offerte) {
                foreach ($offerte as $offerta) {
                    if ($key == 0) {
                        $hotel_ids[$offerta->hotel_id] = 1;
                    } else {
                        if (array_key_exists($offerta->hotel_id, $hotel_ids) && ($hotel_ids[$offerta->hotel_id] < ($key + 1))) {
                            $hotel_ids[$offerta->hotel_id]++;
                        }
                    }
                }
            }
        }
        // prendo solo $hotels_id[$cliente->id] che sono in tutti i gruppi
        foreach ($hotel_ids as $id => $sum) {
            if ($sum < count($id_parola_chiave_arr)) {
                unset($hotel_ids[$id]);
            }
        }

        ///////////////////////////////////////////////////////////////////////////////
        // ATTENZIONE: gli id_hotel sono le chiavi, i values sono il conteggio !!!!! //
        ///////////////////////////////////////////////////////////////////////////////
        foreach ($hotel_ids as $key => $value) {
            $hotel_ids[$key] = $key;
        }

        return $query->whereIn("id", $hotel_ids);

    } /* END scopeListingMultiParolaChiaveOfferteAttive */

    public function scopeListingPuntoForzaChiave($query, $puntoForzaChiave_id)
    {
        if (!$puntoForzaChiave_id) {
            return $query;
        }

        /*
         * Dalla parola chiave ottengo le parole chiave espanse
         */
        $puntoForza_chiave = PuntoForzaChiave::with("alias")->find($puntoForzaChiave_id);

        $terms = [];
        foreach ($puntoForza_chiave->alias as $term) {
            $terms[] = $term->chiave;
        }

        /*
         * Dalle parole chiave espanse ottengo i punti di forza che matchano
         */

        $puntiDiForza_lingua = PuntoForzaLingua::with(['puntoDiForza.cliente'])->match($terms)->get();

        $hotel_ids = [];

        foreach ($puntiDiForza_lingua as $pfl) {
            $hotel_ids[$pfl->puntoDiForza->cliente->id] = $pfl->puntoDiForza->cliente->id;
        }

        return $query->whereIn("id", $hotel_ids);
    } /* END scopeListingParolaChiaveOfferteAttive */

    public function scopeListingOfferta($query, $listing_offerta, $locale)
    {

        if (empty($listing_offerta)) {
            return $query;
        }

        $offerte = Offerta::with([
            'offerte_lingua' => function ($query) use ($locale) {
                $query
                    ->where('lang_id', '=', $locale);
            },
        ])
            ->attiva()
            ->tipo($listing_offerta)->get();

        $hotel_ids = [];

        if ($offerte) {
            foreach ($offerte as $off) {
                if (!is_null($off->offerte_lingua->first())) {
                    $hotel_ids[$off->hotel_id] = $off->hotel_id;
                }
            }
        }

        return $query->whereIn("id", $hotel_ids);
    }

    public function scopeListingOffertaVot($query, $cms_pagina_id, $listing_offerta, $locale)
    {

        if (empty($listing_offerta)) {
            return $query;
        }

        ////////////////////////////////
        // OFFERTRE TOP LASTMINUTE     //
        ////////////////////////////////
        $offerte = VetrinaOffertaTop::with([
            'offerte_lingua' => function ($query) use ($locale, $cms_pagina_id) {
                $query
                    ->inPagina($cms_pagina_id)
                    ->where('lang_id', '=', $locale);
            }])
            ->attiva()
            ->tipo($listing_offerta)
            ->get();

        $hotel_ids = [];

        if ($offerte) {
            foreach ($offerte as $off) {
                if (!is_null($off->offerte_lingua->first())) {
                    $hotel_ids[$off->hotel_id] = $off->hotel_id;
                }
            }
        }

        $offerte = Offerta::with([
            'offerte_lingua' => function ($query) use ($locale) {
                $query
                    ->where('lang_id', '=', $locale);
            },
        ])
            ->attiva()
            ->tipo($listing_offerta)->get();

        if ($offerte) {
            foreach ($offerte as $off) {
                if (!is_null($off->offerte_lingua->first())) {
                    if (!array_key_exists($off->hotel_id, $hotel_ids)) {
                        $hotel_ids[$off->hotel_id] = $off->hotel_id;
                    }
                }
            }
        }

        return $query->whereIn("id", $hotel_ids);
    }

    public function scopeListingOffertaPrenotaPrima($query, $listing_offerta_prenota_prima, $locale)
    {

        if (empty($listing_offerta_prenota_prima)) {
            return $query;
        }

        $offerte = OffertaPrenotaPrima::with([
            'offerte_lingua' => function ($query) use ($locale) {
                $query
                    ->where('lang_id', '=', $locale);
            }])
            ->attiva()->get();

        $hotel_ids = [];

        if ($offerte) {
            foreach ($offerte as $off) {
                if (!is_null($off->offerte_lingua->first())) {
                    $hotel_ids[$off->hotel_id] = $off->hotel_id;
                }
            }
        }

        return $query->whereIn("id", $hotel_ids);
    }

    public function scopeListingOffertaPrenotaPrimaVotPrenotaPrima($query, $cms_pagina_id, $listing_offerta_prenota_prima, $locale)
    {

        if (empty($listing_offerta_prenota_prima)) {
            return $query;
        }

        ////////////////////////////////
        // OFFERTRE TOP PRENOTA PRIMA //
        ////////////////////////////////
        $offerte = VetrinaOffertaTop::with([
            'offerte_lingua' => function ($query) use ($locale, $cms_pagina_id) {
                $query
                    ->inPagina($cms_pagina_id)
                    ->where('lang_id', '=', $locale);
            }])
            ->attiva()
            ->tipo('prenotaprima')
            ->get();

        $hotel_ids = [];

        if ($offerte) {
            foreach ($offerte as $off) {
                if (!is_null($off->offerte_lingua->first())) {
                    $hotel_ids[$off->hotel_id] = $off->hotel_id;
                }
            }
        }

        ////////////////////////////////
        // OFFERTRE PRENOTA PRIMA         //
        ////////////////////////////////
        $offerte = OffertaPrenotaPrima::with([
            'offerte_lingua' => function ($query) use ($locale) {
                $query
                    ->where('lang_id', '=', $locale);
            }])
            ->attiva()->get();

        if ($offerte) {
            foreach ($offerte as $off) {
                if (!is_null($off->offerte_lingua->first())) {
                    if (!array_key_exists($off->hotel_id, $hotel_ids)) {
                        $hotel_ids[$off->hotel_id] = $off->hotel_id;
                    }
                }
            }
        }

        return $query->whereIn("id", $hotel_ids);

    }

    public function scopeListingGruppoServizi($query, $id_gruppo_servizi, $listing_macrolocalita_id = 0, $listing_localita_id = 0)
    {
        
        if (!$id_gruppo_servizi) {
            return $query;
        }

        if (!is_array($id_gruppo_servizi))
            $id_gruppo_servizi = [$id_gruppo_servizi];

        $localita_ids = [];

        if ($listing_macrolocalita_id != Utility::getMacroRR() || $listing_localita_id != Utility::getMicroRR()) {
            
            if($listing_macrolocalita_id != 0) {
                 $macro = Macrolocalita::with('localita')->find($listing_macrolocalita_id);
                
                foreach ($macro->localita as $l) {
                    $localita_ids[] =  $l->id;   
                }

            } elseif ($listing_localita_id != 0) {
                $localita_ids[] = $listing_localita_id;
            }

        }


        $servizi = Servizio::with(["clienti" => function($query)  use ($localita_ids) {
            $query->attivo();
            $query->select('tblHotel.id');
            if(count($localita_ids)) {
                $query->whereIn('localita_id',$localita_ids);
            }
        }])
        ->whereIn("gruppo_id", $id_gruppo_servizi)->get(['id', 'gruppo_id']);

        $hotels_id = [];
        $hotels_servizio_cliente_id = [];
        $hotels_serzizio_precedente_id = "";
        $t = 0;

        

        /**
         * I servizi devono essere legati in AND e non in OR 
         * Ciclo sui servizi
         */
        
        foreach ($servizi as $servizio) {
            
            /**
             * Ciclo sugli hotel associati a i servizi
             */

            foreach ($servizio->clienti as $cliente) {

                if (!isset($hotels_servizio_cliente_id[$cliente->id]))
                    $hotels_servizio_cliente_id[$cliente->id] = [];
                
                $hotels_servizio_cliente_id[$cliente->id][$servizio->gruppo_id] = $servizio->gruppo_id;

            }

        }


        /** Conto i filtri */
        $verify_count = count($id_gruppo_servizi);


        /** Ciclo sugli hotel che ho trovato */
        foreach ($hotels_servizio_cliente_id as $key => $hotel_search_id):

            /** Imposto la verifica */
            $verify_hotel = 0;

            /** Ciclo sui gruppi servizi */
            foreach ($id_gruppo_servizi as $id_g_servizio): 

                /** Se esiste questo gruppo servizio in questo hotel aggiorno il conteggio */
                if (in_array($id_g_servizio, $hotel_search_id))
                    $verify_hotel++;

            endforeach;

            /** Se il conteggio non coincide allora toglo l'hotel dalla lista */
            if ($verify_hotel < $verify_count)
                unset($hotels_servizio_cliente_id[$key]);

        endforeach;

        return $query->whereIn("id", array_keys($hotels_servizio_cliente_id));

    }

    public function scopeListingMultiServiziSingoli($query, $servizi_non_gruppo, $macrolocalita_id)
    {
        if (!$servizi_non_gruppo) {
            return $query;
        }

        $ids = [];
        if ($macrolocalita_id > 0) {

            if ($macrolocalita_id != Utility::getMacroRR()) {
                $macrolocalita = Macrolocalita::with("localita")
                    ->find($macrolocalita_id);

                foreach ($macrolocalita->localita as $localita) {
                    $ids[] = $localita->id;
                }

            } else {
                $ids = Localita::notMontagna()->pluck('id')->toArray();
            }

        }

        $servizi = Servizio::with([
            "clienti" => function ($query) use ($ids) {
                $query->whereIn("localita_id", $ids);
            },
        ])
            ->whereIn('id', $servizi_non_gruppo)
            ->get();

        //////////////////////////////////////////////////////////////
        // devo prendere tutti i clienti che HANNO TUTTI I SERVIZI //
        /////////////////////////////////////////////////////////////
        $hotels_id = [];

        foreach ($servizi as $key => $servizio) {
            foreach ($servizio->clienti as $cliente) {

                //////////////////////////////////////////////////
                // i servizi del PRIMO GRUPPO LI AGGIUNGO TUTTI //
                //////////////////////////////////////////////////
                if ($key == 0) {
                    $hotels_id[$cliente->id] = 1;
                } else {
                    if (array_key_exists($cliente->id, $hotels_id) && ($hotels_id[$cliente->id] < ($key + 1))) {
                        $hotels_id[$cliente->id]++;
                    }
                }

            }

        }

        // prendo solo $hotels_id[$cliente->id] che hanno tutti i servizi
        foreach ($hotels_id as $id => $sum) {
            if ($sum < $servizi->count()) {
                unset($hotels_id[$id]);
            }
        }

        foreach ($hotels_id as $key => $value) {
            $hotels_id[$key] = $key;
        }

        return $query->whereIn("id", $hotels_id);

    }

    public function scopeListingMultiGruppiServizi($query, $gruppo_servizi, $macrolocalita_id)
    {
        if (!$gruppo_servizi) {
            return $query;
        }

        $ids = [];
        $hotels_id = [];
        if ($macrolocalita_id > 0) {

            if ($macrolocalita_id != Utility::getMacroRR()) {
                $macrolocalita = Macrolocalita::with("localita")
                    ->find($macrolocalita_id);

                foreach ($macrolocalita->localita as $localita) {
                    $ids[] = $localita->id;
                }

            } else {
                $ids = Localita::notMontagna()->pluck('id')->toArray();
            }

        }

        if (strpos($gruppo_servizi, ",") !== false) {
            $gruppo_servizi_ids = explode(",", $gruppo_servizi);
            $servizi_arr = [];
            foreach ($gruppo_servizi_ids as $id) {
                if (count($ids)) {
                    $servizi = Servizio::with([
                        "clienti" => function ($query) use ($ids) {
                            $query->whereIn("localita_id", $ids);
                        },
                    ])
                        ->where("gruppo_id", $id)
                        ->get();
                } else {
                    $servizi = Servizio::with("clienti")->where("gruppo_id", $id)->get();
                }

                $servizi_arr[] = $servizi; // metto i servizi di ogni gruppo in un array
            }
            //dd($servizi_arr);
            //////////////////////////////////////////////////////////////
            // devo prendere tutti i clienti che SONO in TUTTI I GRUPPI //
            //////////////////////////////////////////////////////////////

            //////////////////////////////////////////////////////////////////////////////////////
            // CAMBIO CODICE PERCHE' CON ALCUNI SERVIZI IL FITRO NON FUNZIONA 25/01/2019 @Luigi //
            //////////////////////////////////////////////////////////////////////////////////////
            /*foreach ($servizi_arr as $key => $servizi)
            {
            foreach ($servizi as $servizio)
            {
            foreach ($servizio->clienti as $cliente)
            {
            //////////////////////////////////////////////////
            // i servizi del PRIMO GRUPPO LI AGGIUNGO TUTTI //
            //////////////////////////////////////////////////
            if ($key == 0)
            {
            $hotels_id[$cliente->id] = 1;
            }
            else
            {
            if( array_key_exists($cliente->id,$hotels_id) && ($hotels_id[$cliente->id] < ($key+1)) )
            {
            $hotels_id[$cliente->id]++;
            }
            }

            }
            }
            }

            // prendo solo $hotels_id[$cliente->id] che sono in tutti i gruppi
            foreach ($hotels_id as $id => $sum)
            {
            if($sum < count($servizi_arr))
            {
            unset($hotels_id[$id]);
            }
            }

            foreach ($hotels_id as $key => $value) {
            $hotels_id[$key] = $key;
            }*/

            ////////////////////////////////////////////////////////////////////
            // CAMBIO CODICE PERCHE' CON ALCUNI SERVIZI IL FITRO NON FUNZIONA //
            ////////////////////////////////////////////////////////////////////

            $clienti_ids = [];
            foreach ($servizi_arr as $key => $servizi) {
                foreach ($servizi as $servizio) {
                    foreach ($servizio->clienti as $cliente) {
                        $clienti_ids[$key][] = $cliente->id;
                    }
                }
            }

            foreach ($clienti_ids as $key => $arr_value) {
                if (!is_array($arr_value)) {
                    unset($clienti_ids[$key]);
                }
            }

            // Calls the callback given by the first parameter with the parameters in param_arr.

            // Nota (Giovanni 20/05/2019):
            // Se clienty_ids è vuoto ricevo un errore quindi metto una condizione per cui se è vuoto
            // faccio un whereIn con id non esistente per non mandare in errore la function

            if (count($clienti_ids)) {
                $hotels_id = call_user_func_array('array_intersect', $clienti_ids);
                ///////////////////////////////////////////////////////////////////////////////
                // ATTENZIONE: gli id_hotel sono le chiavi, i values sono il conteggio !!!!! //
                ///////////////////////////////////////////////////////////////////////////////
                return $query->whereIn("id", $hotels_id);
            } else {
                return $query->whereIn("id", [0]);
            }

        } else {

            $servizi = Servizio::with([
                "clienti" => function ($query) use ($ids) {
                    $query->whereIn("localita_id", $ids);
                },
            ])
                ->where("gruppo_id", $gruppo_servizi)
                ->get();

            foreach ($servizi as $servizio) {
                foreach ($servizio->clienti as $cliente) {
                    $hotels_id[$cliente->id] = $cliente->id;
                }
            }

            return $query->whereIn("id", $hotels_id);

        }

    }

  

    public function scopeListingBambiniGratis($query, $bambini_gratis)
    {
        if (!$bambini_gratis) {
            return $query;
        }

        $bambini_gratis = BambinoGratis::attivo()->get();

        $hotels_id = [];
        foreach ($bambini_gratis as $bambino_gratis) {
            $hotels_id[] = $bambino_gratis->hotel_id;
        }

        return $query->whereIn("id", $hotels_id);
    }

    public function scopeListingBambiniGratisVaat($query, $cms_pagina_id, $bambini_gratis,$locale, $listing_macrolocalita_id = 0, $listing_localita_id = 0)
    {
        if (!$bambini_gratis) {
            return $query;
        }


        $localita_ids = [];

        if ($listing_macrolocalita_id != Utility::getMacroRR() || $listing_localita_id != Utility::getMicroRR()) {

            if ($listing_macrolocalita_id != 0) {
                $macro = Macrolocalita::with('localita')->find($listing_macrolocalita_id);

                foreach ($macro->localita as $l) {
                    $localita_ids[] =  $l->id;
                }
            } elseif ($listing_localita_id != 0) {
                $localita_ids[] = $listing_localita_id;
            }
        }


        ///////////////////////////////////////
        // Vetrine bAmbini grAtis Top (VAAT) //
        ///////////////////////////////////////
        if (count($localita_ids)) {

            $offerte = VetrinaBambiniGratisTop::with([
                'offerte_lingua' => function ($query) use ($locale, $cms_pagina_id) {
                    $query
                        ->associataPagina($cms_pagina_id)
                        ->inLingua($locale);
                }
            ])
            ->whereHas('cliente.localita', function ($q)  use ($localita_ids) {
                $q->whereIn('tblHotel.localita_id', $localita_ids);
            })
            ->attiva()
            ->get();

        } else {

            $offerte = VetrinaBambiniGratisTop::with([
                'offerte_lingua' => function ($query) use ($locale, $cms_pagina_id) {
                    $query
                    ->associataPagina($cms_pagina_id)
                    ->inLingua($locale);
                }])
                ->attiva()
                ->get();
                
        };

        $hotels_id = [];

        if ($offerte) {
            foreach ($offerte as $off) {
                if (!is_null($off->offerte_lingua->first())) {
                    $hotels_id[$off->hotel_id] = $off->hotel_id;
                }
            }
        }

        /////////////////////////////
        // OFFERTE BAMBINNI GRATIS //
        /////////////////////////////

      

        if (count($localita_ids)) {
        
            $bambini_gratis = BambinoGratis::with([
                'offerte_lingua' => function ($query) use ($locale) {
                    $query
                        ->inLingua($locale);
            }
            ])
            ->whereHas('cliente.localita', function ($q)  use ($localita_ids) {
                $q->whereIn('tblHotel.localita_id', $localita_ids);
            })
            ->attivo()
            ->get();
        } else {
            $bambini_gratis = BambinoGratis::with([
                'offerte_lingua' => function ($query) use ($locale) {
                    $query
                        ->inLingua($locale);
                }
            ])
              
            ->attivo()
            ->get();
        }

        foreach ($bambini_gratis as $bambino_gratis) {
            if (!is_null($bambino_gratis->offerte_lingua->first())) {
                $hotels_id[$bambino_gratis->hotel_id] = $bambino_gratis->hotel_id;
            }
        }

        return $query->whereIn("id", $hotels_id);
    }

    /**
     * [scopeListingApertura valori che arrivano dalla select box del filtro sull'apertua nel listing]
     * @param  [type] $query                      [ATTENZIONE: quelli annuali devono apparrire anche IN TUTTI GLI ALTRI CASI !!]
     * @param  string $expand_filter_for_apertura [possibili valori: 'annuale', 'aperto_capodanno', 'aperto_pasqua', 'dopo_10_settembre']
     * @return [type]                             [description]
     */
    public function scopeListingApertura($query, $expand_filter_for_apertura = 0)
    {

        if (!$expand_filter_for_apertura) {
            return $query;
        }

        if ($expand_filter_for_apertura == 'annuale') {
            return $query->where($expand_filter_for_apertura, 1);
        } elseif ($expand_filter_for_apertura != 'dopo_10_settembre') {

            return $query->where(function ($query1) use ($expand_filter_for_apertura) {
                $query1->where($expand_filter_for_apertura, 1)->orWhere('annuale', 1);
            });

        } else {

            return $query->where(function ($query1) {
                $query1->where('aperto_al', '>', Carbon::createFromDate(date('Y'), 9, 10))->orWhere('annuale', 1);
            });

        }

    }

    public function scopeListingDistanzaDalCentro($query, $distanza)
    {
        if (!$distanza) {
            return $query;
        }

        return $query->where('distanza_centro', '<=', $distanza);
    }

    public function scopeListingDistanzaDallaSpiaggia($query, $distanza)
    {
        if (!$distanza) {
            return $query;
        }

        return $query->where('distanza_spiaggia', '<=', $distanza);
    }

    public function scopeListingDistanzaDallaStazione($query, $distanza)
    {
        if (!$distanza) {
            return $query;
        }

        return $query->where('distanza_staz', '<=', $distanza);
    }

    public function scopeListingDistanzaDallaFiera($query, $distanza)
    {
        if (!$distanza) {
            return $query;
        }

        return $query->where('distanza_fiera', '<=', $distanza);
    }

    public function scopeListingServizi($query, $servizi_ids_str)
    {

        if (!$servizi_ids_str) {
            return $query;
        }

        $servizi = explode(',', $servizi_ids_str);

        $n_servizi = count($servizi);

        $array_ids = [];

        // ogni servizio ha un elenco di hotel a cui è associato
        foreach ($servizi as $servizio_id) {

            $hotels_servizio = Servizio::find($servizio_id)->clienti;

            foreach ($hotels_servizio as $hotel) {

                // ogni volta che un hotel ha un servizio aggingo 1 al conteggio

                if (array_key_exists($hotel->id, $array_ids)) {
                    $array_ids[$hotel->id] += 1;
                } else {
                    $array_ids[$hotel->id] = 1;
                }

            } // end for hotel

        } // end for servizi

        // alla fine prendo solo gli hotel che hanno tutti i servizi (conteggio == n. servizi)

        foreach ($array_ids as $hotel_id => $count) {
            if ($count < $n_servizi) {
                unset($array_ids[$hotel_id]);
            }
        }

        ///////////////////////////////////////////////////////////////////////////////
        // ATTENZIONE: gli id_hotel sono le chiavi, i values sono il conteggio !!!!! //
        ///////////////////////////////////////////////////////////////////////////////
        return $query->whereIn("id", array_keys($array_ids));

    } // end function scopeListingServizi

    public function scopeListingOrderBy($query, $order = 0, $uri = "")
    {
        //dd($order);
        if ($order == 'prezzo_min') {
            return $query->orderByRaw("`prezzo_min` = '0',  prezzo_min");
        } elseif ($order == 'prezzo_max') {
            return $query->orderByRaw("prezzo_max desc");
        } elseif ($order == 'categoria_asc') {
            return $query->orderByRaw("IF(categoria_id != 6, categoria_id*10 ,categoria_id*6) asc");
        } elseif ($order == 'categoria_desc') {
            return $query->orderByRaw("IF(categoria_id != 6, categoria_id*10 ,categoria_id*6) desc");
        } elseif ($order == 'nome') {
            return $query->orderByRaw("nome asc");
        } elseif ($order == 'numero_click')

        /*
         * Se ho una offerta in evidenza lo mando in cima
         */

        {
            if (Utility::checkOrderOfferteInEvidenza()) {
                return $query->orderByRaw("chiuso_temp, numero_click DESC, RAND()");
            } else {
                return $query->orderByRaw("chiuso_temp, numero_click, RAND()");
            }
        } elseif (strpos($uri, 'italia/hotel_riviera_romagnola') !== false || strpos($uri, 'riviera-romagnola') !== false) {
            // return $query->orderByRaw("numero_click"); @Lucio: mi metti il random su ogni listing? non più con i pesi.
            return $query->orderByRaw("chiuso_temp, RAND()"); 
        } else {
            return $query->orderByRaw("chiuso_temp, RAND()");
        }

    }

    public function scopeListingFilter($query, $filter = 0)
    {

        if (!$filter) {
            return $query;
        }

        return $query->whereRaw($filter . "=1");

    }

    public function scopeDeleteImmaginiGallery($query)
    {
        return self::immaginiGallery()->delete();
    }

    public function scopeNotInGroup($query)
    {
        return $query->where('gruppo_id', 0);
    }

    public function attachCaparreDalAl($dal, $al, $locale = 'it')
    {

        $caparre = [];

        list($d, $m, $y) = explode('/', $dal);
        $dal_carbon = Carbon::createFromDate($y, $m, $d);

        list($d, $m, $y) = explode('/', $al);
        $al_carbon = Carbon::createFromDate($y, $m, $d);

        foreach ($this->caparreAttive as $caparra) {
            // verifico l'intersezione della prenotazione con la caparra
            // se dal è compreso tra inizio_caperra e fine_cparra
            // OPPURE
            // se al è compreso tra inizio_caparra e fine_caparra

            if ($dal_carbon->between($caparra->from, $caparra->to) || $al_carbon->between($caparra->from, $caparra->to)) {
                $caparre[] = $caparra->getAsString($locale);
            }

        }

        return $caparre;
    }

}
