**Check del trattamento sull'invio delle mail**


__MOBILE__

- mail multipla mobile: scrivo a tutti gli hotel elencati in una pagina senza possibilità di filtro; E' STATO AGGIUNTO il filtro sul trattamento accettato dalla struttura contattata.
SIccome c'è il multicamere ed il trattamento è legato alla camera la struttura viene selezionata se ha TUTTI I TRATTAMENTI ricercati.

**Trattamenti spiaggia** 
 ATTENZIONE:  @Lucio&@Luigi 06/11/2020
Il filtro funziona così;
se seleziono mp => voglio mp oppure mp_s
se seleziono mp_s voglio SOLO mp_s
QUINDI se HO SELEZIONATO ENTRAMBI I TRATTAMENTI (con e senza spiaggia) 
VINCE QUELLO PIU' INCLUSIVO (quello che contiene anche la spiaggia)


- mail scheda mobile: vengono proposti nel menu di selezione solo i trattamenti della struttura a cui si scrive: C'E' il filtro sul trattamento accettato dalla struttura contattata.


__DESKTOP__

- mail multipla: scrivo a più hotel compilando un form di filtro; [post del form MailMultiplaController@richiestaMailMultipla]. C'E' il filtro sul trattamento accettato dalla struttura contattata. SIccome c'è il multicamere ed il trattamento è legato alla camera la struttura viene selezionata se ha TUTTI I TRATTAMENTI ricercati.

**Trattamenti spiaggia** 
 ATTENZIONE:  @Lucio&@Luigi 06/11/2020
Il filtro funziona così;
se seleziono mp => voglio mp oppure mp_s
se seleziono mp_s voglio SOLO mp_s
QUINDI se HO SELEZIONATO ENTRAMBI I TRATTAMENTI (con e senza spiaggia) 
VINCE QUELLO PIU' INCLUSIVO (quello che contiene anche la spiaggia)



- mail wishlist: scrivo a più hotel selezionandoli con un check da una pagina di listing; [post del form MailMultiplaController@richiestaWishlist]. NON C'E' il filtro sul trattamento perchè se ho appositamente selezionato quell'hotel voglio che la mail gli arrivi.

- mail scheda: vengono proposti nel menu di selezione solo i trattamenti della struttura a cui si scrive: C'E' il filtro sul trattamento accettato dalla struttura contattata.

__PAGINE LOCALITA__

- versione 2017: nelle localita gli slot girano con il sistema dei pesi ogni 2 minuti ( introdotta la cache )

- versione 2016: nelle localita gli slot girano con il sistema dei pesi



__VISTE DIFFERENTI PER DISPOSITIVO__

in /var/www/html/infoalberghiThirdEye/config/view.php seleziono il path delle view in base al dispositivo




__MAPPA RICERCA OFFERTE__

in lingua prende lo stesso numero di offerte dell'IT perché con l'eager loading vengono tirate su tutte le offerte ed il filtro che esclude quelle con la parte in lingua nulla viene fatto runtime NELLA VISTA ma nella mappa non c'è questa cosa e quindi prendo tutti gli hotel. Cmq siccome non devo far vedere l'offerta ma indicare solo l'hotel che ha questo tipo di offerta può avere senso !! L'hotel Sabrina ha l'offerta per agosto anche se non l'ha scritta in tedesco MA nella mappa visualizzo l'hotel NON l'offerta quindi VA BENE COSI'

 


__LISTING CON OFFERTE IN EVIDENZA__

Il numero degli slot VISIBILI delle VOT è 2: nel momento in cui un VOT non era visibile SPARIVA TOTALMENTE DAL LISTING a meno che lo stesso hotel non avesse un'offerta CONGRUENTE (cioè se sono nel listing Pasqua e non sono visibile con la VOT perché stro girando, sono cmq nel listing se ho un'offerta di Pasqua). Quindo chi ha SOLO la VOT nel momento in cui girà e NON E' VISIBILE SPARISCE DAL LISTING !!
QUESTA cosa è stata corretta, ed adesso una struttura con il VOT compare sempre nel listing con questa modalità:

- se non è nel VOT ed ha un'offerta congruente compare nel listing con la sua offerta DIVERSA DAL VOT, in una posizione RANDOM
- se non è nel VOT e NON HA un'offerta congruente, appare CON lA SUA OFFERTA VOT immediatamente sotto i VOT ATTIVI ma senza che sia marcato come VOT quindi come UN'OFFERTA SEMPLICE !!!!

Per quanto riguarda l'ordinamento le evidenze spariscono e quindi se un hotel HA SOLO la VOT sparisce dal listing ORIDNATO !!
QUESTA cosa è stata corretta, quando si ordina vengono aggiunte anche le VOT con questa modalità:

- i VOT visibili sono sempre IN ALTO indipendentemente dall'ordinamento
- se non è nel VOT ed ha un'offerta congruente compare nel listing con la sua offerta e in base all'ORDINAMENTO selezionato
- se non è nel VOT e NON HA un'offerta congruente, appare CON lA SUA OFFERTA VOT immediatamente sotto i VOT ATTIVI ma senza che sia marcato come VOT e SENZA ORDINAMENTO!!!!




Gli hotel che NON SONO visibili tra i VOT devono COMPORTARSI come hotel "normali": nel listing faccio l'eager loading non solo delle offerte ma anche delle VOT (filtrate per parola chiave)


ATTENZIONE: nella pagina http://www.info-alberghi.com/early-booking/rimini.php
l'hotel "Family Hotel Continental" non mostra l'offerta Prenota Prima (COGLI L’ATTIMO E RISPARMIA SULLA TUA VACANZA ESTIVA!) quando è fuori dai vot visibili: non compare proprio nel listing !!!!
NON VIENE TIRATO SU CON IL FILTRO SULL'EAGER LOADING ????



__Tabelle__

Archive: sono le tabelle che teniamo per 36 mesi 
Stat: sono giornaliere
Read: sono quelle con i dati aggregati



**COOKIE - Prefill dei form**



I cookie vengono utilizzati dalle applicazioni web lato server che __archiviano__ e __recuperano__ informazioni a lungo termine __SUL CLIENT__.
__I server inviano i cookie nella risposta HTTP__ e ci si aspetta che i browser salvino e inviino i cookie al server, __ogni qual volta si facciano richieste aggiuntive al server__.


CookieIA::getCookiePrefill() 
	=> RITORNA L'ARRAY $prefill: riempie l'array $prefill con i dati del cookie corrente OPPURE crea un array con valori di default e codice_cookie = nuovo 

CookieIA::setCookiePrefill($request) 
	=> RITORNA L'ARRAY $prefill con CookieIA::getCookiePrefill() e lo AGGIORNA con i valori della $request



- visualizzare la debug bar per vedere il cookie prefill (che viene criptato da Laravel) : il middleware "GetQueryLog", se sono verificate determinate condizioni, mostra la barra di debug ED IL VALORE DELL'ARRAY $prefill (CookieIA::getCookiePrefill()) !!

- il middleware "CheckSendCookie", solo se c'è un parametro nella request oppure c'è "ids_to_fill_cookie" in Session: 
	=> $prefill = CookieIA::setCookiePrefill($request) ottiene l'array $prefill AGGIORNATO 
	=> setta nella RICHIESTA il cookie "prefill_v" con $prefill (poi questo cookie verrà rispedito al client con la response)


==> in pratica ad ogni POST, attraverso il middleware "CheckSendCookie" aggiorno/creo il $prefill e lo aggiungo al cookie "prefill_v" che viene inviato con la richiesta; alla riposta del server il cookie sarà disponibile nel browser e da qui in avanti lo utilizzo per "precompilare" il form.


__come modificare__

il $prefill deve contenere solo il campo "codice_cookie" e tutto il resto lo scrivo in una tabella sul DB con key = codice_cookie. 
Il cookie è più leggero
La fase di precompilazione avviene leggendo il cookie per il campo "codice_cookie" e poi facendo una query sulla tabella per gli altri valori



__nuovi funzionamenti__

CookieIA::getCookiePrefill() 
	=> se nella request c'è il cookie: 
						allora ottengo il $prefill (e lo uso come chiave per leggere il campo associato nel DB)
		 altrimenti:
		 				creo il $prefill con il "codice_cookie" e tutti gli altri campi che erano key di questo array le metto in un altro array che poi scrivo nel DB come campo json associato alla chiave "codice_cookie"




__Offerte sopeciali__

**Mobile**
HotelController@offerte_mobile
ATTENZIONE!! Qui la ramificazione avviene a livello di HotelController con lastminute_mobile, bambinigratis_mobile, prenotaprima_mobile....
@include('composer.offerte')
view()->composer('composer.offerte', 'App\Http\ViewComposers\OfferteComposer');

==> resources/phone_views/composer/offerte.blade.php
scrive prima le offerte top poi quelle normali



**desktop**
HotelController@index
hotel/hotel
@include('composer.offerteTop', array('tipoTop' => 'offerta'))    
view()->composer('composer.offerteTop', 'App\Http\ViewComposers\OfferteTopComposer');

==> resources/views/composer/offerteTop.blade.php

ATTENZIONE!! Questa unica view dell'unico compser "composer.offerteTop" chiamato, include 3 altri composer (composer.offertePrenotaPrima, composer.offerteLast, composer.offerteLast) 

Nel primo composer vengono scritte le offerteTOP, nell'altro ('composer.offerteLast) le offerte.


composer.offerteLast => App\Http\ViewComposers\OfferteLastComposer




__Last minute__

**Mobile**
HotelController@lastminute_mobile
 view()->composer('composer.offerteLast', 'App\Http\ViewComposers\OfferteLastComposer');

 ==> resources/phone_views/composer/offerteLast.blade.php
 scrive prima le offerte top poi quelle normali
 

**Desktop**
HotelController@index
hotel/hotel
@include('composer.offerteTop', array('tipoTop' => 'lastminute'))   
view()->composer('composer.offerteTop', 'App\Http\ViewComposers\OfferteTopComposer');

==> resources/views/composer/offerteTop.blade.php

composer.offerteLast => App\Http\ViewComposers\OfferteLastComposer


__Prenota Prima__

**Mobile**
HotelController@prenotaprima_mobile
view()->composer('composer.offertePrenotaPrima', 'App\Http\ViewComposers\OffertePrenotaPrimaComposer');

==> resources/phone_views/composer/offertePrenotaPrima.blade.php

**Desktop**
HotelController@index
hotel/hotel
@include('composer.offerteTop', array('tipoTop' => 'prenotaprima'))
view()->composer('composer.offerteTop', 'App\Http\ViewComposers\OfferteTopComposer');

==> resources/views/composer/offerteTop.blade.php

view()->composer('composer.offertePrenotaPrima', 'App\Http\ViewComposers\OffertePrenotaPrimaComposer');



**Menu verde/tematico**

la funzione Utility::getMenuTematicoMobile() genera il menu verde per il mobile (mente Utility::getMenuTematico() per il desktop). Entrambe le funzioni trovano le pagine per la categoria con CmsPagina::getCategorieWithBB() quindi includendo dentro anche le pagine Bed&Breackafast





**Mail doppia parziale 07/05/19**

Quando mando una mial a più hotel uguale ad una mail inviata prima ad altri hotel oppure ad un singolo hotel, faccio un confronto tra gli ID hotel di prima e quelli di adesso ed elimino da quelli di adesso quelli a cui l'avevo spedita prima

- se NON ci sono più ID, vuole dire che l'avevo inviata già a tutti prima e quindi è una mail DOPPIA (TOTALE) e non viene più scritta nel DB delle mail con nessuna etichetta

- se ci sono degli ID risultanti, questi sono nuovi ID a cui deve essere spedita ma la mail viene loggata nel DB con etichetta "DOPPIA PARZIALE" ad indicare che faceva parte di un invio a più hotel e che qualcuno dei destinatari originali è stato eliminato perché per LUI ERA DOPPIA


ALLA FINE: chi riceve, nelle stats, la mail con scritto "DOPPIA PARZIALE" è una mail che ha comunque ricevuto; da oggi nessuno dovrebbe più ricevere mail con la scritta "TIPOLOGIA DOPPIA", perché le mail doppie non sono più scritte nel DB  



**Tolgo il JSON dalle mail 01/10/19**

/var/www/html/infoalberghiThirdEye/app/Console/Commands/SendEmails.php:
  312: 				$dati_mail['sign_email'] 		= base64_encode(json_encode($dati_json));	 

Comando che rispedisce le mail al cliente in modo batch e asincrono


/var/www/html/infoalberghiThirdEye/app/Http/Controllers/MailMultiplaController.php:
  793: 		$dati_mail['sign_email'] 		= base64_encode(json_encode($dati_json)); // richiesta mail multipla 
 1458: 		$dati_mail['sign_email'] 		= base64_encode(json_encode($dati_json)); // richiesta mail multipla mobile
 2075: 		$dati_mail['sign_email'] 		= base64_encode(json_encode($dati_json)); // richiesta wishlist

/var/www/html/infoalberghiThirdEye/app/Http/Controllers/MailSchedaController.php:
  646: 		$dati_mail['sign_email'] 		= base64_encode(json_encode($dati_json)); // richiesta preventivo desktop
 1251: 		$dati_mail['sign_email'] 		= base64_encode(json_encode($dati_json)); // richiesta preventivo mobile


 Ad eccezione del Command creo una funzione centralizzta a cui passo $dati_json



 **Modifica al dollaro 08/10/19**
Hotel con mail "" deve avere la mail a questo indirizzo con il JSON


nelle mail multiple la mail è unica con gli invii in BCC quindi controllo se bcc contiene indirizzo e poi lo tolgo da bcc e creo un altro bcc_al_dollaro con solo la sua mail a cui invio

in $dati_mail aggiungo sempre il campo 'sign_email_al_dollaro' che dopo andrà tolto: contiene il base64 perché dopo posso modificare $dati_json 

$dati_mail['sign_email'] 		= Utility::putJsonMail($dati_json);
$sign_email_al_dollaro 		= base64_encode(json_encode($dati_json));



nella sezione con commento 
// CODICE PER "AL DOLLARO"

cerco se c'è la spedizione per un un hotel con la mail 1click...


if(isset($bcc_dollaro))

faccio la spedizione SOLO al dollaro con la firma



**NUovi servizi CRM 16/10/19**


Su IA "Servizi Gratuiti", "Servizi in Hotel" sono CATEGORIE (tblCategoriaServizi)
Invece i GRUPPI (tblGruppoServizi) sono i gruppi di ricerca

Un servizio (tblServizi) può essere in 1! gruppo e in 1! servizio

- faccio un seeder per sistemare i servizi "NuoviServiziCRMSeeder"



**Ritorno dopo invio mail 27/01/20**


Dopo aver inviato una mail, si deve tornare:

- ultimo listing
- hotel
- localita
- HP

in ordine di priorità.

Per salvare l'ultimo listing, il controller CmspPagineController mettere in sessione l'id della pagina listing visitiata

in CmsPagineController metto l'URI in sessione in last_listing_page sia per il listing sia per la località, perché se no potrebbe succedere che trov un hotel con il listing e ritorno lì, poi ne trovo uno con la localià ma siccome ho ancora in sessione il listin rirtono sempre nel listing!!



**Richiesta contatto via WhatsApp 04/02/2020**

__attualmente solo sul form di Lucio__
__solo per le mail alla scheda (mobile e desktop)__


se check WhatsApp viene scritto il boolean WhatsApp=1 

nella mailScheda su IA

quando scrivo sull'API ==> nella tblAPIMailScheda

nel testo della mail viene inserito un link per chattare direttamente con il numero che il cliente ha inserito https://wa.me/<numero>?text=
con un testo riepilogativo della richiesta


__archiviazione IA__

la ImportStatsMailDirette archivia le mail nella tabella tblMailSchedaArchive con il campo whatsapp


__archiviazione API__

la ArchiveMailDirette archivia le mail nella tabella tblAPIMailSchedaArchive con il campo whatsapp



__EARLY BOOKING__

ES: early-booking/cattolica.php 

- è una pagina con template = listing e con listing_offerte_prenota_prima != ''

- ATTENZIONE! se ci sono delle offerte scadute, sono le offerte TOP che sono valide e che quando girano devono apparire come offerte normali

Per ottenere i clienti del listing:

- $clienti = $this->getListing($cms_pagina, $locale, $order, $page, $filter);
		-	$clienti =  $this->_getClientiListing($cms_pagina, $locale , $order_by, $filter, $terms);	

		Qui prendo le offertePrenotaPrima attive (e guardo la scadenza) + le offerteTopPP e NON GUARDO la scadenza MA I MESI PER CUI DEVE RIMANERE ONLINE (se c'è una OFFERTA TOP PP che deve rimanere online fino la mese di maggio, la lascio anche se l'offerta dice di prenotare entro il 31 marzo !!!)

1) prendo i clienti filtrati solo per quelli che hanno offertePrenotaPrima attive (e guardo la scadenza) + le offerteTopPP attive (e NON GUARDO la scadenza MA I MESI PER CUI DEVE RIMANERE ONLINE)

2) a tutti questi clienti metto comunque in relazioine anche i vot (setEvidenza)

3) i 2 vot che girano li metto in testa e li tolgo dai clienti

4) i clienti che rimangono visualizzano comunque il vot tra le offerte




__CHIUSO TEMPORANEAMENTE__

1) check chiuso_temporaneamente (default false)

2) label nel listing per quelli chiusi

DESKTOP

- widget titolo dovrebbe essere DAPPERTUTTO (CHECK) 
(widget/item-title.blade.php
resources/assets/desktop/scss/layout/item-listing.scss)




MOBILE

resources/phone_views/draw_item_cliente/draw_item_cliente_offerta_listing.blade.php (???)

resources/phone_views/widget/item-figure.blade.php
resources/assets/mobile/css/css-above/above-listing-mobile.css




3) label nella scheda per quelli chiusi (fixed on scroll)

DESKTOP
widget/intestazione_scheda.blade.php
resources/assets/desktop/scss/scheda.scss

PHONE
resources/phone_views/composer/hotelGallery.blade.php
resources/assets/mobile/css/css-above/above-scheda-mobile.css


TABLET
resources/tablet_views/widget/intestazione_scheda.blade.php
resources/assets/tablet/scss/scheda.scss [@media .button-ipad]



4) No mail whishlist e multiple fino al 30/09/2020

DESKTOP

WL: 
resources/views/widget/filtri.blade.php
POST https://info-alberghi.ssl/wishlist 'uses' => 'MailMultiplaController@wishlist' 
che richiama templates/mail_multipla che ha un form con un hidden con elenco degli ID selezionati
<input type="hidden" name="ids_send_mail" value="608,812,846,1000">
POST https://info-alberghi.ssl/richiesta-wishlist  'uses' => 'MailMultiplaController@richiestaWishlist'
===========================================================================
MM:
POST https://info-alberghi.ssl/richiesta-mail-multipla => MailMultiplaController@richiestaMailMultipla
===========================================================================
MS:
POST https://info-alberghi.ssl/richiesta-mail-scheda => MailSchedaController@richiestaPreventivo


MOBILE

SCRIVI A TUTTI:
POST https://info-alberghi.ssl/richiesta-mail-multipla  => MailMultiplaController@richiestaMailMultiplaMobile

MS:
POST https://info-alberghi.ssl/richiesta-mail-scheda => MailSchedaController@richiestaMailSchedaMobile




# devono andare in fondo ai listing


## categorie

- hotel-3-tre-stelle-superiore/milano-marittima.php ho tolto le vetrine ma continuano ad apparire con TOP 
nell'ordinamento $clienti = $clienti->listingOrderBy($order, $cms_pagina->uri);

perché i primi 2 sono App\SlotVetrina invece di App\Hotel, evidentemente prende le vetrine anche disabilitate!!!

$clienti = $this->mergeListingClientiSlotVetrine($clienti, $vetrina, $cms_pagina, $locale);

quando cerco gli slots su cui iterare per sostituirli ai clienti prendo solo quelli attivi, ho aggiunto il queryScope attivo sullo Slot 

public function getSlots($listing_categorie = 0)
    {
      if ($listing_categorie == 0) {
        return $this->slots()->attivo();
      } else {
        return $this->slots()->attivo()->categoria($listing_categorie);
      }
      
    }

## servizi 

- hotel-piscina/milano-marittima-hotel-piscina.php



__CANCELLATION POLICY__

# stampare le info dei periodi in tutti i listing

- localita
- listing capegoria
- servizi
views/draw_item_cliente/draw_item_cliente_vetrina_listing.blade.php

- offerte
views/draw_item_cliente/snippet_offerta_generica.blade.php

- bambini gratis 
- sconti famiglia
-trattamento
