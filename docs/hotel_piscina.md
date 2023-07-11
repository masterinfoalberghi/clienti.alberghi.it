

- hotel con piscina dentro la struttura
- hotel con piscina fuori dalla struttura




sono pagine di tipo servizi

- piscina in struttura 
- piscina fuori struttura



> hotel con piscina fuori dalla struttura

- i servizi sono: "Piscina fuori hotel" e "Piscina in spiaggia"


- come categoria farà parte di "Servizi in hotel"
- come servizio creo "Piscina fuori struttura"
- devo creare un gruppo servizi (di ricerca) "Piscina fuori struttura"




nel listing 


$clienti = $clienti->listingGruppoServizi($cms_pagina->listing_gruppo_servizi_id);


le viste richiamate sono:

cms_pagina_listing/listing_hotel.blade.php > cms_pagina_listing/clienti.blade.php > draw_item_cliente/draw_item_cliente_vetrina_listing.blade.php qui verifico che il gruppo della pagina appartenga a piscina e/o benessere

	'listing_gruppo_piscina' => 8,
	'listing_gruppo_benessere' => 7,

successivamente se sono in un listing piscina, allora cerco i servizi "piscina fuori hotel" e/o "piscina in spiaggia" per disegnare le label




Tutti gli hotel che hanno checckati i servizi "listing piscina" devono avere come servizio in hotel "Piscina fuori struttura" e nella pagina sopra questo controllo non lo devo fare per le pagine che appartengono al gruppo "Piscina" MA "Piscina fuori struttura"



creo la pagina (clono)

hotel-piscina-fuori-struttura/rimini-hotel-piscina-fuori-struttura.php




l'hotel Gabriella

https://info-alberghi.ssl/hotel.php?id=1491

che è nel listing https://info-alberghi.ssl/hotel-piscina/rimini-hotel-piscina.php
con piscina a 35 mt dall'hotel lo assegno al servizio



**ATTENZIONE** se toglo il servizio "Piscina" viene cancellato anche il record InfoPiscina!!!
dalla pagina /servizi/associa-servizi (ServiziController@viewServiziHotel) devo togliere questo automatismo
**OK**


Prendo un altro hotel Hotel Galassia

https://info-alberghi.ssl/hotel.php?id=1347



**ATTENZIONE** in CmsPagineController riga 1600
aggiungo delle elaborazioni all'oggetto cliente per visualizzare le label E' QUI CHE DEVO AGIRE

/**
* Aggiungo delle elaborazioni
*/

if ($cms_pagina->listing_gruppo_servizi_id == Utility::getGruppoPiscina() && $cms_pagina->listing_categorie == '') {




però devo agire ancora a monte, nell'eager loading


Hotel::withListingLazyEagerLoading($cms_pagina, $terms, $order)




**ATTENZIONE** affinché le label si vedano negli hotel con piscina fuori struttura devo 
assegnare ai servizi della categoria "Listing Piscina" il gruppo_id 35  (listing_gruppo_piscina_fuori) e non 8 (listing_gruppo_piscina)
In questo modo però, basta che ci sia associato un servizio "Listing Piscina" e l'hotel si vede automaticamente nel listing "fuori struttura"
anche se ha il servizio "piscina" e NON "piscina fuori struttura"

https://info-alberghi.ssl/hotel.php?id=1126 Hotel Residence Palm Beach

- ha i servizi "listing Piscina" viene mostata nel listing piscina fuori
- ha il servizio "Piscina" viene mostata nel listing piscina


**ATTENZIONE** se lascio i servizi listing al gruppo piscina, allora viene messo nel lisng corretto solo l'hotel col servizio "piscina fuori" MA NON HA LE LABEL: perché nell'eager_loading prendo tutti i servizi

'servizi' => function ($query) use ($cms_pagina)
  {
    $query
    ->where('gruppo_id', '=', $cms_pagina->listing_gruppo_servizi_id);
  },

e NON ci sono i servizi "Listing" che sono quelli che cerco per le label




**ALLA FINE**

- creo il gruppo di ricerca $gruppo->nome = "Piscina fuori struttura";
- creo il servizio "piscina fuori struttura" che appartiene al gruppo di ricerca sopra e alla categoria "Servizi in hotel"
- assegno ai servizi della categoria "ListingPiscina" il gruppo 0; questi servizi sono quelli che mi visualizzano le label; in questo modo nell'eager loading NON SONO PRECARICATI in automatico né con i servizi del gruppo "Piscina" né con quelli del gruppo "Piscina fuori struttura"
- li carico io nell'eager loading con la loro categoria "ListingPiscina"

'servizi' => function ($query) use ($cms_pagina)
  {
    $query
    ->where('gruppo_id', '=', $cms_pagina->listing_gruppo_servizi_id)->orWhere('categoria_id', 9);
  },

- in questo modo se l'hotel con i servizi ListingPiscina 
ha checkato il servizio "piscina" viene visualizzato nella pagina corretta con le label
ha checkato il servizio "piscina fuori struttura"  viene visualizzato nella pagina corretta con le label


**Associazione nuovo servizio**

- creo un artisan command __SwapPiscina__ per fare lo swap tra piscina e piscina fuori se c'è un servizio listing piscina


> php artisan swap:piscina


**Nuove pagine**

- creo un artisan command __CreatePagesPiscinaFuori__ per clonare le pagine piscina in pagine PiscinaFuori

> php artisan create:pagesPiscinaFuori