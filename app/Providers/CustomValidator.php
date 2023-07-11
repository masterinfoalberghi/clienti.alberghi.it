<?php

namespace App\Providers;

use App\Hotel;
use App\Utility;
use Carbon\Carbon;
use Illuminate\Validation\Validator;

class CustomValidator extends Validator
{

    public function validateEmailValidation($attribute, $value, $parameters)
    {
        return true;
        /*try {
        return EmailVerifier::verify($value);
        } catch (\Exception $e) {
        return true;
        }*/

    }

    /**
     * ATTENZIONE:
     * =============
     * $parameters[0]: è il nome del parametro (QUINDI SE IL PARAMETRO E' UN VALORE devo usare questa sintassi)
     * $this->getValue($parameters[0]): è il valore di un campo che ha quel nome nome ((QUINDI SE IL PARAMETRO E' UN VALORE NON POSSO usare quyesta sintassi))
     */

    public function validateMailMessageSpam($attribute, $value, $parameters)
    {

        try {
            if (strpos(strtolower($value), 'http') !== false || strpos(strtolower($value), 'www') !== false || strpos(strtolower($value), '://') !== false/* ||  strpos(strtolower($value), 'lavor') !== false || strpos(strtolower($value), 'camerier') !== false*/) {
                return false;
            } else {
                return true;
            }
        } catch (\Exception $e) {
            return false;
        }

    }

    public function validateOfferMessageSpam($attribute, $value, $parameters)
    {
        try {
            
            /**
             * se contiene quei caratteri oppure almeno 5 numeri attaccati
             */

            // if (str_contains(strtolower($value), ['http', 'www', '://', '@', '.it', '.com']) || preg_match("/[0-9]{5,}/", $value)) {
            if (
                strpos(strtolower($value), 'http') > -1 || 
                strpos(strtolower($value), 'www') > -1 || 
                strpos(strtolower($value), '://') > -1 || 
                strpos(strtolower($value), '@') > -1 || 
                strpos(strtolower($value), '.it') > -1 || 
                strpos(strtolower($value), '.com') > -1 || 
                preg_match("/[0-9]{5,}/", $value)) {
                return false;
            } else {
                return true;
            }

        } catch (\Exception $e) {

            return false;

        }

    }

    /** [validateMaxCharacter Per validate le offerte ed i last (che hanno anche un conta caratteri lato client)
     * non posso usare la validazione predefinita max: perché PHP conta per ogni riga in cui si va a capo i caratteri invisibili \n\r...
     * quindi prima strippo questi caratteri, poi faccio il strlen
     */
    public function validateMaxCharacter($attribute, $value, $parameters)
    {

        /*
        ATTENZIONE: la codifica dei caratteri mi fa scazzare il numero
        Esempio:
        Arrivano le feste natalizie e il #Ponte dell'Immacolata# Ã¨ una buona occasione per iniziare a festeggiare!Dal 5 al 9 dicembre 2015 in formula B&B prenota la tua vacanza invernale a #Rimini# al #Teminal Palace#.SPECIALE #PONTE IMMACOLATA# 2015Camera #Acquamarina# doppia â‚¬60 tripla â‚¬80 quadrupla â‚¬90Camera #Topazio# doppia â‚¬70 tripla â‚¬90 quadrupla â‚¬100Camera #Smeraldo# â‚¬80 tripla â‚¬100 quadrupla â‚¬110Sogg. min. 2 notti a partire da â‚¬30 a personaPossibilitÃ  di cene in ristoranti convenzionati

         */
        try {
            $my_value = str_replace(["\r\n"], '', $value);

            // ATTENZIONE IL SIMBOLO € viene trasformato in ?, ma SICCOME MI SERVE SOLO PER CONTARE
            // E' SEMPRE 1 CARATTERE e quindi la validazione non è alterata !!
            $my_value = utf8_decode($my_value);

            if (strlen($my_value) > $parameters[0]) {
                return false;
            } else {
                return true;
            }

        } catch (\Exception $e) {

            return false;

        }

    }

    /*
    Utilizzata per validare le date perché il validatore standard di laravel vuole il formato U.S mm/dd/yy e quindi bisognerebbe cambiare il formato delle date per utilizzarlo
    The main field is defined by $value, and the second one to compare with is captured as  $this->getValue($parameters[0]).
     */

    // valido_al > valido_dal
    public function validateDataMaggioreDi($attribute, $value, $parameters)
    {
        try {

            $data_maggiore_str = trim($value);
            $data_minore_str = trim($this->getValue($parameters[0]));

            $data_minore_str_carbon = Utility::getCarbonDate($data_minore_str);

            if (isset($parameters[1])) {
                $offset = (int) $parameters[1];
                $data_minore_str_carbon = $data_minore_str_carbon->addDays($offset);
            }

            return Utility::getCarbonDate($data_maggiore_str)->gt($data_minore_str_carbon); // + offset

        } catch (\Exception $e) {

            return false;

        }

    }

    public function validateSwitchFromOfferToLast($attribute, $value, $parameters)
    {
        try {

            $hotel_id = $parameters[0];
            if ($value == 'lastminute') {

                $hotel = Hotel::find($hotel_id);

                return !$hotel->superatoLimiteLast($to_add = 1);

            } else {

                return true;

            }

        } catch (\Exception $e) {

            return false;

        }

    }

    public function validateSwitchFromLastToOffer($attribute, $value, $parameters)
    {
        try {

            $hotel_id = $parameters[0];
            if ($value == 'offerta') {

                $hotel = Hotel::find($hotel_id);

                return !$hotel->superatoLimiteOfferte($to_add = 1);

            } else {

                return true;

            }

        } catch (\Exception $e) {

            return false;

        }

    }

    /**
     * nella scheda hotel controllo le date di arrivo
     */

    // The main field is defined by $value
    public function validateArrivoMaggioreDiOggi($attribute, $value, $parameters)
    {

        $arrivi = $value;

        // multicamera quindi
        // "arrivo" => array:2 [▼
        //  0 => "26/07/2017"
        //  1 => "16/09/2017"
        //  ]
        //

        $oggi_c = Carbon::now();
        foreach ($arrivi as $arrivo) {

            list($d, $m, $y) = explode("/", $arrivo);

            $arrivo_c = Carbon::createFromDate($y, $m, $d);
            if ($arrivo_c->lt($oggi_c)) {
                return false;
            }
        }

        return true;

    }

    /*
    Utilizzata nel coupon per validare che  che la data finale – i giorni di durata mininma sia maggiore della data iniziale
    cioè data_finale > data_iniziale + durata
     */

    // valido_al > valido_dal + durata
    public function validateDataMaggioreDiConOffset($attribute, $value, $parameters)
    {
        try {

            $data_maggiore_str = trim($value);
            $data_minore_str = trim($this->getValue($parameters[0]));
            $durata = $this->getValue($parameters[1]);

            return Utility::getCarbonDate($data_maggiore_str)->gte(Utility::getCarbonDate($data_minore_str)->addDays($durata));

        } catch (\Exception $e) {

            return false;

        }

    }

    /*
    Utilizzata nel PrenotaPrima: prenota_entro < dal - 7gg
    se l'offerta parte il 5 agosto npn posso dire se prenoti entro il 4 agosto (NON E' UN PRENOTA PRIMA!)
     */
    // prenota_entro < valido_dal - durata
    public function validateDataMinoreDiConOffset($attribute, $value, $parameters)
    {
        try {

            $data_min_str = trim($value);
            $data_magg_str = trim($this->getValue($parameters[0]));
            $durata = $this->getValue($parameters[1]);

            return Utility::getCarbonDate($data_min_str)->lt(Utility::getCarbonDate($data_magg_str)->subDays($durata));

        } catch (\Exception $e) {

            return false;

        }

    }

    // valido_dal deve essere >= oggi
    public function validateDataMaggioreIeri($attribute, $value, $parameters)
    {

        try {

            $data_maggiore_str = trim($value);

            return Utility::getCarbonDate($data_maggiore_str)->gte(Carbon::today());

        } catch (\Exception $e) {

            return false;

        }

    }

    // valido_al - valido_dal <= 8 MESI
    public function validateEntroMesiDaAdesso($attribute, $value, $parameters)
    {

        try {

            $data_fine_str = trim($value);
            // ES: echo $dt->diffInDays($dt->copy()->addWeek()) == 7
            return Carbon::now()->diffInMonths(Utility::getCarbonDate($data_fine_str)) < $parameters[0];

        } catch (\Exception $e) {

            return false;

        }

    }

    // max 45 gg range validità - valido_al entro 45 gg dalla data valido_dal
    public function validateRangeValiditaDaGiornoDal($attribute, $value, $parameters)
    {

        try {
            
            $date_end = trim($value);
            $div_params = explode(":", $parameters[0]);

            $date_start = Utility::getCarbonDate( $div_params[1] );

            return $date_start->diffInDays( Utility::getCarbonDate( $date_end ) ) <= $div_params[0];

        } catch (\Exception $e) {

            return false;

        }

    }

    // valido_dal entro 20 gg da adesso ("A Natale non posso fare un last minute che parte ad Agosto !!!")
    public function validateEntroGiorniDaAdesso($attribute, $value, $parameters)
    {

        try {
            // dal
            $data_inizio_str = trim($value);
            // ES: echo $dt->diffInDays($dt->copy()->addWeek()) == 7

            return Carbon::now()->diffInDays(Utility::getCarbonDate($data_inizio_str)) <= $parameters[0];

        } catch (\Exception $e) {

            return false;

        }

    }

    public function validateAperturaNonMaggioreDi($attribute, $value, $parameters)
    {

        try {

            return $value <= $this->getValue($parameters[0]);

        } catch (\Exception $e) {

            return false;

        }

    }

}
