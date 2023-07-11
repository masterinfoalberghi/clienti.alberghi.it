<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Blocca il conteggio dei click sulo stesso IP per la giornata odierna
    |--------------------------------------------------------------------------
    |
    */

    'blocco_click_su_ip' => env("BLOCCO_CLICK_SU_IP", true),

    /*
     |--------------------------------------------------------------------------
     | Blocca il conteggio dei click sulo stesso IP per la giornata odierna
     |--------------------------------------------------------------------------
     |
     */

    'gestione_num_hotel_multiple' => env("GESTIONE_NUM_HOTEL_MULTIPLE", 25),

];

