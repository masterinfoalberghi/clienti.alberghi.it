
---

#### 03/05/2019 09:55 - Ordinamento offerte e OfferteTOP - **Luigi**

VOT: Offerte TOP
- quando NON ORDINO ed ho 2 VOT sempre IN CIMA, nella collection SOLO QUESTI 2 sono sempre instanceof VetrinaOffertaTopLingua e tutti gli altri Hotel
- quando ordino sono SEMPRE TUTTI SOLO instanceof Hotel e quelli che contengono il VOT hanno l'attributo evidenza con il VOT relativo


VST: Servizi TOP
in questo caso nella collection sono SEMPRE TUTTI SOLO instanceof Hotel; gli hotel che sono in cima (i 2 TOP che girano) e che verifico tramite $cliente->getTop() != '' hanno l'URL che registra l'accesso all'EVIDENZA. **In questo caso** NON POSSO estendere questo URL a tutti gli hotel che hanno un'evidenza anche quando non sono in cima perché quegli hotel sarebbero comunque nel listing e quando non sono in cima (quando ordino o quando non sono nei 2 visibili) sono hotel "normali" 


VTT: Trattamenti TOP

COME VST






PAGINAZIONE: ordinamento per prezzo
l'ordinamento per prezzo viene fatto dopo che ho già la collection degli hotel; in Riviera Romagnola quindi non faccio pù il paginate(), ma dopo che ho ottenuto la collezione di clienti ordinata, allora faccio una paginazione manuale








Nelle pagine in cui ci sono OfferteTOP (early-booking/rimini) se ne vedono sempre 2 che girano, ma in realtà sono di più (8 ad esempio)
Quando ordino le VOT vanno tutte in cima INDIPENDENTEMENTE DALL'ORDINAMENTO e il vero e propio ordine inzia DOPO QUESTO BLOCCO
Bisogna riuscire a farle girare insieme
In realtà se uno ha delle Offerte normali viene messo nell'ordinamento e gli vengono aggiunte anche le TOP; se ha solo le TOP deve essere messo in cima


SE HO UN ORDINAMENTO E NON DEVO METTERE I VOT PER PRIMI:
Gli hotel in cui ho aggiunto anche quelli che hanno dei VOT (listingParolaChiaveOfferteAttiveVotAttivi), sono già ordinati devo solo scrivere anche le offerte VOT per quelli che le contengono

SE NON HO UN ORDINAMENTO E DEVO METTERE I VOT PER PRIMI:
lascio le cose come sono ora






BUG:

<!-- ATTENZIONE:
NEL MOMENTPO IN CUI ORDINO NON ESITONO PIU' I TOP
QUINDI il link non registra più un click al top, ma una visita alla scheda
AVREMO MOLTE MENO VISITE AI TOP -->


<!-- **ORDINO PER PREZZO**: in realtà passano da 176 a 48 
perché
$clienti = $clienti->keyBy('prezzo_to_order');
raggruppa e sovrascrive quelli che hanno lo stesso valore di 'prezzo_to_order'

ELIMINO QUESTA OPERAZIONE!!! -->



**Prenota Prima RR**
NON VA

**Offerte Generiche Last Generici**
ADESSO VA MA: siccome dentro ogni slot ci sono sia offerte che last, dentro agli slot si mettono prima le evidenze e poi le offerte!!

**Sconti famiglia**
OK - è un'offerta per parola chiave!

**Bambini Gratis**




ATTENZIONE: nel metodo Hotel::withListingLazyEagerLoading
nell'eager loading delle offerte TOP in lingua devo aggiungere il vincolo

->where('pagina_id', '=', $cms_pagina->id)

adesso l'ho fatto solo in:

offerteTop.offerte_lingua




ATTENZIONE: nel metodo Hotel::withListingLazyEagerLoading
$offerte_lingua_top = VetrinaOffertaTopLingua::whereHas('offerta', function($query)
                        {
                            $query->where('attivo', 1)->where('valido_al', '>=', date('Y-m-d'));
                        })
                    ->inLingua($locale)
                    ->inPagina($cms_pagina_id)
                    ->multiTestoOrTitoloLike($terms)
                    ->get();


bisogna togliere il vincolo 
->where('valido_al', '>=', date('Y-m-d'))
perché LA VALIDITA' NON E' IN QUESTO CAMPO per le offerte TOP, queste hanno una validità mensile    

#### 27/03/2019 09:55 - Privacy checked default (Lucio DIXIT) - **Luigi**



---

#### 28/11/2018 - Traduzioni sezioni Piscina e Benessere scheda hotel  - **Luigi**

il campo "sggerimenti" nella sezione "Info Piscina" > "Posizione" diventerà un campo potenzilmente visibile se riempito e checkato il flag apposito.
Quindi va inserito in multilingua




---

#### 27/11/2018 - Traduzioni sezioni Piscina e Benessere scheda hotel  - **Luigi**



---

#### 19/11/2018 - Togliere frecce dal listing offerte per desktop  - **Luigi**


---

#### 16/11/2018 - Multiplingua per alcuni campi scheda hotel - **Luigi**

---

#### 12/11/2018 - Upgrade to Laravel 5.7 - **Luigi**


---

#### 04/10/2018 - Aggiunta dell'email secondaria - **Giovanni**

	Alla tabella tblHotel è stata aggiunto la possibilità di inserire una email secondaria.
	Se impostata tutte le email spazzatura arrivano a quella email, altrimenti funzioancome prima.
	Solo gli amministratori ( Giovanni, Gigi) posso modificare quelle email.
	L'amministrazione vede solo se sono impostate.
	

---

#### 07/07/2018 - Introdotti i preferiti anche da mobile - **Giovanni**

	Nella versione mobile ora si può aggiungere un hotel ai preferiti

---

#### 02/07/2018 - Creati i command di Alert - **Giovanni**

		Messi a cron 2 nuovi command per gli alert sulle email e sui file che spariscono

---

#### 21/06/2018 - Nuova gallery order admin - **Giovanni**

	Aggiornata la visualizzazione delle immagini nella pagina di ordinamento.

---

#### 15/06/2018 - Aggiornamento GDPR - **Giovanni**

	Aggiornamento dei grafici, database, cron.
	Rigenerazione delle statistiche e cancellazione automatica dei dati più vecchi di 36 mesi

---

#### 22/05/2018 - Menu tematico Mobile - **Luigi**

la funzione Utility::getMenuTematicoMobile() genera il menu verde per il mobile (mente Utility::getMenuTematico() per il desktop). Entrambe le funzioni trovano le pagine per la categoria con CmsPagina::getCategorieWithBB() quindi includendo dentro anche le pagine Bed&Breackafast

---

#### 27/04/2018 - Gestione offerte mappa listing ricerca - **Luigi**

Le offerte da visualizzare nella mappa sono gestite da BE tramite la sezione Parole Chiave.
Ho creato il BE per gestire le offerte nella mappa del listing (sezione parole chiave): E' possibile decidere quale parola chiave abilitare nella mappa (Quindi quale offerta) e decidere la label in lingua ed il periodo di esposizione. Un colore differente mostra le Parole chiave in corso, scadute o che saranno attive in futuro

---

#### 19/04/2018 - static-develop.info-alberghi.com

Tutti gli assets sono nella cartella static e sono raggiungibili con l'url static.info-alberghi.com che sul serve punta alla cartella static/ di master.
Quando pubblico su develop, le modifiche vanno in static/ su develop MA l'URL continua a puntare su static/ di master e quindi non funziona !!!
Si mette l'URL nel file .env e lo si differenzia per develop e master in modo che ciascuna delle due app (master e develop) abbia un URL che punta alla PROPRIA static/ 

master
APP_CDN=//static.info-alberghi.com

develop
APP_CDN=//static.info-alberghi.com


dove il nuovo dominio static-develop.info-alberghi.com punterà alla cartella develop/static del server

---

#### 16/04/2018 - Nuova barra nei listing mobile - **Giovanni**

Aggiornata la barra top nei listing mobile per far rimanere sempre vivo l'ordinamento del listing 

ps. Lucio Ha voluto anche tenere il pulsante sotto.

---

#### 09/04/2018 - Offerte bambini gratis in lingua - **Luigi**

> Le offerte Bambini Gratis Top (bgt) SONO GIA' in LINGUA con la possibilità di traduzione automatica da parte di Google translate.


alla tabella tblBambiniGratis va aggiunta la tabella in lingua
```
tblBambiniGratisLang(id, master_id, lang_id, note, aprrovata , data_approvazione) 
```

dove i campi note, aprrovata , data_approvazione sono spostati dalla tabella tblBambiniGratis

---

#### 11/01/2018 - Vale vuole uploadare anche nella cartella public/img_testi - **Luigi**

E' stato creato un symlink alla nuova cartella in public/doc; inoltre siccome c'era l'esigenza di poter uploadare in entrambe le folder il file public/neon/js/tinymce/plugins/filemanager/upload.php è stato modificato in modo che il file uploadato nella cartella doc/ da plugin venga programmaticamente uploadato sia in img_testi/ e in rassegna/ (e non in doc/). Dove non serve verrà cancellato da plugin  


---

#### 11/01/2018 - Modifiche tabelle servizi x Chiara - **Luigi**

```
UPDATE tblServiziLang SET created_at = NULL , updated_at = NULL WHERE CAST(created_at AS CHAR(20)) = '2000-01-01 00:00:00';
ALTER TABLE `tblServiziLang` CHANGE COLUMN `nome` `nome` VARCHAR(255) NOT NULL DEFAULT '' AFTER `lang_id`;
UPDATE tblServiziPrivatiLingua SET created_at = NULL , updated_at = NULL WHERE CAST(created_at AS CHAR(20)) = '2000-01-01 00:00:00';
ALTER TABLE `tblServiziPrivatiLingua` CHANGE COLUMN `nome` `nome` VARCHAR(255) NOT NULL DEFAULT '' AFTER `lang_id`;
```

---

#### 10/01/2018 - Use cookie-free domains - **Luigi**

Serve static content from a different domain to avoid unnecessary cookie traffic.

>https://www.reginaldchan.net/serving-static-content-from-cookieless-subdomain/
>This technique is especially useful for pages referencing large volumes of rarely cached static content, such as frequently changing image thumbnails, or infrequently accessed image archives. We recommend this technique for any page that serves more than 5 static resources.

Infatti non tutte le immagini sono elencate perché sono in cache (quelle del listing ad esempio)
Perché i js non sono in cache ??
il parametro ?5.5.4.3 dovrebbe servire a questo!! (URL fingerprinting)
No no ci sono perché sono caricate in modo asincorno !!! (Cmq prima o poi le carica)
QUESTE RISORSE SONO SERVITE DA APACHE OPPURE DA NGNIX ??

**cat .htaccess**
```
php_value memory_limit 512M
```

```
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews
    </IfModule>
    RewriteEngine On
    RewriteCond %{HTTP_HOST} ^info-alberghi.com$
    RewriteRule (.*) http://www.info-alberghi.com/$1 [R=301,L]
    Redirect 301 /rassegna/corriere-romagna-capodanno.jpg http://www.info-alberghi.com/doc/rassegna/corriere-romagna-capodanno.pdf
    RewriteCond %{THE_REQUEST} ^[A-Z]{3,}\s/{2,} [NC]
    RewriteRule ^ /error/404 [L,R=301]
    # Redirect Trailing Slashes...If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d 
    RewriteRule ^(.*)/$ /$1 [L,R=301]
    RewriteRule ^index.php/(.*)$ /$1 [L,R=301]
    
    # Handle Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```
    
> there's not any default cache time on the server side, you have to use specific mechanism, but server can for example set header Cache-Control and tell all proxies and browsers what's the max-age of cached response 
- https://varvy.com/pagespeed/leverage-browser-caching.html
- https://varvy.com/pagespeed/htaccess.html

---

#### 09/01/2018 - Menu verde: Inserire link "Bed&Breakfast" nelle categorie - **Luigi**
il menu tematico viene creato da 
```
$menu_tematico = Utility::getMenuTematico( $locale, $cms_pagina->listing_macrolocalita_id, $cms_pagina->listing_localita_id);
```
---

#### 18/12/2017 - Nuovi servizi disabili - **Luigi**

__ACCESSIBILITA' HOTEL:__
- ingresso con rampa inclinata
- ingresso situato al piano terra
- ingresso con piattaforma elevatrice

__ACCESSO CAMERE__
- ascensore a norma per disabili
- camera situata al piano terra
- presenza piattaforma elevatrice interna

__CAMERA ACCESSIBILE:__
- spazio di manovra per sedia a rotelle
- spazio di manovra per sedia a rotelle e ausili (maniglie a x cm, interruttori a x cm ecc..)
BAGNO con WC con maniglioni, lavabo più basso, spazi di manovra e:
- vasca da bagno per disabili
- doccia con accesso per sedie a rotelle
- sedia per doccia

__ALTRO:__
tblGruppoServizi: gruppo di ricerca ("Disabili")
tblCategoriaServizi: categoria di servizi (Es: la categoria "Servizi per disabili")

Dopo il blocco "Servizi per disabili" con il relativo check, ci saranno altri 4 blocchi con relativi n servizi con checkbox ognuno;

Se per ogni blocco ci sarà almeno un servizio checkato il sistema dovrà automaticamente mettere il check sul "servizio master" "servizi per disabili" del blocco "Servizi per disabili". E naturalmente si dovrà anche togliere di conseguenza. L'evidenza sarà comunque sempre e solo associata al servizio "servizi per disabili" del blocco "Servizi per disabili".

Devo creare 4 NUOVE categorie di servizi
Aggiungo il campo "tipo" alla tblCategoriaServizi per identificare le NUOVE categorie disabili

---

#### 17/10/2017 - Single Sign On - **Luigi**

Per i clienti proprietari di più hotel si deve poter associare a questo account più hotel; loro poi devono poter identificarsi, come fa l'admin, ma solo con gli hotel a cui sono associati!!!! Questa cosa la si può usare anche per gli account commerciali in quanto oggi un commerciale può identificarsi con q.che hotel non solo con uno dei suoi.

Quando mi loggo come hotel ho già il menu di sx precaricato con le funzionalità che posso utilizzare, e non ho la text box per cambiare identità!!  

Devo creare un nuovo ruolo "multihotel"

creo una relazione molti a molti tra user e hotel
```
[tblMultiImpersonificazione(user_id, hotel_id)]
```
user può impersonificare molti hotel
hotel può essere impersonificato da molti user (esempio il cliente ed il commerciale) 

se uno user appartiene alla tabella tblMultiImpersonificazione, allora quando si logga ha la possibilità di impersonificare gli hotel con gli id nella tabella  

__LOGIN:__

Post del form di login: LoginController@login
 ```
 protected $redirectTo = '/admin';
 Admin\HomeController@index
 ```
 
 questo controller estende AdminBaseController che ha i metodi sempre visibili come getHotelId; questo metodo nel caso sia admin, operatore o commerciale mi restituisce come idHotel quello che ho salvato in sessione quando mi sono impersonificato come hotel (altrimenti 0)
 
 Per impersonificarmi faccio un post a seleziona-hotel ===> AdminController@selezionaHotel
che prende l'id 

Arriva nel formato

>"17 Hotel Sabrina"

quindi passandolo per il casting int ottengo: 17

```
$hotel_id = (int)$request->input("ui_editing_hotel");
```
e lo mette in sessione

```
\Session::put("ui_editing_hotel", $string);
``` 

In routes.php devo aggiungere, dove c'è tra i roles 'hotel' anche 'multihotel', perché cmq lui può fare tutte le cose dell'hotel
in admin_inc_menu.blade.php creo il nuovo ramo 

```
@elseif (Auth::user()->hasRole("multihotel")) con le relative voci
```

e la sottosezione

```
@if (Auth::user()->getMultiHotelUiEditingHotelId())
```
che mostra le voci quando un multihotel impersonifica un hotel.

__ATTENZIONE:__ tra queste 2 Model C'E' GIA' una relazione 1 a 1 !!!! 
Per NON cambiare questa relazione (che è sempre in relazione 1 a 1 con l'utente) potrei definire un campo gruppo_id nella tabella degli hotel (relazione gruppo-hotel 1-molti): in questo modo se sono un utente di un hotel che appartiene ad un gruppo mi posso identificare con gli altri hotel del gruppo.

Quando entro con un hotel che appartiene ad un gruppo, invece di impersonificare, creo la possibilità di fare autologin con gli altri hotel del gruppo!!

Siccome la sicurezza è già stata "testata" con il primo login, posso creare un meotodo che 

SOLO SE SONO GIA' LOGGATO COME HOTEL CHE APPARTIENE AD UN GRUPPO, mi fa fare autologin con uno degli atri hotel del gruppo senza pwd !!!!

__MIGRAZIONI:__

1. creare la tabella tblGruppoHotel(id, nome)
2. aggiungere la FK:gruppo_id alla tabella users 

---

#### 13/10/2017 - Nuovo campo Peculiarità piscina - **Luigi**

AGGIUNGERE nelle info piscina un campo textarea "peculiarità" VISIBILE solo ad admin e commerciale (l'hotel lo vede in modalità read-only) da compilare a mano con elenco di eventuali caratteristiche peculiari . Questo campo va sincronizzato con il CRM !!!

---

#### 29/09/2017 - Nuovi servizi - **Luigi**

- soprt e benessere verrà diviso in 2 gruppi di servizi Piscina e benessere; 
QUESTI 2 gruppi non verrano riempiti a mano MA saranno espressione dei dati delle sezioni infoPiscina e infoBenessere
- questi 2 blocchi di servizi non saranno presenti sul precontratto del crm perché creato in automatico a partire da infoPiscina e infoBenessere
- "vasca bambini" e "vasca idromassaggio a parte" saranno solo 2 voci che ci sono oppure no in base ai campi compilati nella sezione infoBenessere
- "vasca bambini" e "vasca idromassaggio a parte" saranno solo 2 voci che ci sono oppure no in base ai campi compilati nella sezione infoBenessere
- la sezione "SPORT E BENESSE" salta completamente MA alcuni servizi vengono riposizionati: piscina, solarium, palestra e centro benessere vanno in "Servizi in hotel"
(cambiare la categoria mantenendo il gruppo di ricerca e l'id del servizio)
SPORT E BENESSERE (categoria_id = 7)
piscina ===> SERVIZI IN HOTEL
```
php artisan db:seed --class=cambiaServiziSportBenessere
```
> feature/Feature-nuovi_blocchi_servizi
- la sezione **"SPORT E BENESSE"** salta completamente sia su IA che sul CRM ed alcuni dei servizi vengono riassegnati in altre categorie

---

#### 10/2017 - Servizi per disabili - **Luigi**

- servizi disabili sarà un altro blocco di servizi (4 servizi): se si compilano TUTTI e 4 questi servizi saranno anche nel PDF altrimenti nel PDF sarà vuoto; allo stesso modo la scheda hotel visualizzerà il blocco servizi per disabili solo se presenti tutti e 4. Anche la ricerca funzionerà SOLO se tutti e 4
 	**ATTENZIONE: LE EVIDENZE ANDRANNO VENDUTE SOLO SE CI SONO TUTTI I SERVIZI*
dalle infoPiscina deve ottenere una struttura tipo
```
"Servizi gratuiti" => array:9 [▼
    0 => "connessione WI-FI in tutta la struttura"
    1 => "aria condizionata in camera "
    2 => "animali tutte le taglie, purché tranquilli che non disturbino altri ospiti"
    3 => "utilizzo bici "
    4 => "late check out solo se la camera non necessita di essere ripulita il giorno stesso"
    5 => "bevande comprese acqua illimitata compresa anche fuori dai pasti"
    6 => "culla se disponibile "
    7 => "deposito bagagli "
    8 => "parco acquatico con acquascivoli, 5 piscine (a 4 km con navetta)"
  ]
  ``` 
**METTERE i servizi aggiuntivi nella sezione disabili della scheda hotel**
- servizi disabili sarà un latro blocco di servizi (4 servizi): se si compilano TUTTI e 4 questi servizi saranno anche nel PDF altrimenti nel PDF sarà vuoto; allo stesso modo la scheda hotel visualizzerà il blocco servizi per disabili solo se presenti tutti e 4. Anche la ricerca funzionerà SOLO se tutti e **4 ATTENZIONE: LE EVIDENZE ANDRANNO VENDUTE SOLO SE CI SONO TUTTI I SERVIZI**
- nel foglio servizi aggiungere PRIMA DEI SERVIZI PER DISABILI, la dicitura che avvisa che la ricerca come hotel per disabili funzionerà solo compilando tutti i check.

Su IA: associazione servizi per disabili:
ci sarà SOLO un check nella categoria "servizi per disabili" e sarà nel gruppo (di ricerca) Disabili: 
se è checkato vuol dire che avevano compilato tutti i servizi sul CRM, altrimenti e decheckato. Nell'importazione dei servizi sul CRM se è checkato li compilo tutti e 4 altrimenti nessuno !!!
devo spostare il padre del servizio con nome it "servizi per disabili" in questa nuova categoria (il gruppo di ricerca è già corretto ed è "Disabili")
in questo modo l'associazione tra gli hotel ed il servizio rimane integra !!!
tblGruppoServizi: gruppo di ricerca ("Disabili")
tblCategoriaServizi: categoria di servizi (devo creare la categoria "Servizi per disabili")
```
php artisan db:seed --class=addServiziDisabiliSeeder
```
a mano sistemare la posizione nella tblCategoriaServizi in modo che sia in ultima posizione prima dei listing

---

#### 10/2017 - Peculiarità benessere - **Luigi**

AGGIUNGERE nelle info benessere un campo textarea "peculiarità" VISIBILE solo ad admin e commerciale (l'hotel lo vede in modalità read-only) da compilare a mano con elenco di eventuali caratteristiche peculiari della spa. Questo campo va sincronizzato con il CRM !!!

---

#### 10/2017 - Hotel con tag moderati - **Luigi**

modera le foto: elenco degli hotel; quando clicco inserisco **ID_HOTEL, DATA.**
Da qui presento una tabella con id, nome e commerciale che corrisponde all'elenco degli hotel che hanno inserito i tag (perché creata da quelli moderati e quindi inseriti prima dall'hotel)
l'elenco di questi hotel si deve poter azzerare  

__ATTENZIONE:__ su IA non ho l'associazione tra commerciale e hotel, questa è sul CRM: creare un metodo sul crm che restituisce un json con queste associazioni quando viene passato un hotel_id (altrimenti tutte le associazioni: NON "visivbile a" ma il campo di testo "commerciale")
```
hotel_tag_modificati = (id, hotel_id, user_id, created_at)
```
user_id: mi dice l'account su IA che ha cliccato sull'hotel per moderare le foto ed ha generato il record nella tabella hotel_tag_modificati

---

#### 25/09/2017 - Scadenziario per evidenze (Offerte e Bambini gratis) - **Luigi**

Nella pagina di inserimento/modifica dell'offerta sarà possibile aggiungere una data di scadenza al raggiungimento della quale (con un delta prestabilito: es 3 gg prima) il sistema invierà una mail ad un determinato account (chiara@info-alberghi.com)
Si crea una tabella One-to-One tblVetrineOfferteTop: tblScadenzeEvidenze

---

#### 06/09/2017 - Paragrafi sulla descrizione scheda - **Giovanni**

Aggiunto la possibilità di paragrafare meglio la scheda hotel.

---

#### 29/08/2017 - Richiesta preventivo via whatsapp - **Luigi**

Per gli hotel che lo vorranno (da admin spunta preventivo_whastapp) ci sarà nel form mobile della loro scheda la possibilità da parte dell'utente di inviare anche la richiesta preventivo tramite whatsapp mediante Curl a 
https://api.whatsapp.com/send?phone=+393337988224&amp;text=Ciao+vorrei+un+preventivo+per+il+periodo+dal+18%2F08+al+24%2F08+-+3+adulti+e+2+bambini+%28+7%2C8+anni+%29+in+pensione+completa%0Agrazie%0AGiovanni%0A%0Ainfo-alberghi.com
dove phone sarà il numero dell'albergatore mentre il numero mittente sarà di chi compila il fom perché è a lui che si aprirà whatsapp con il testo
Quel link per aprire whatsapp deve essere cliccato !!! (niente cUrl e window.open).

---

#### 14/08/2017 - Condividi con whatsapp - ** Giovanni **

Sistema di conteggio per il ritorno delle condivisioni

---

#### 11/08/2017 - Condividi con whatsapp - ** Giovanni **

Sistema di condivisione mobile su whatsapp e statistiche dati

---

#### 27/07/2017 - Mail doppie su database - ** Giovanni **

portato il sistema di controllo delle email doppie su database

---

#### 27/07/2017 - Intento di chiamata - **Luigi**

la mail sul "bottone chiama" è una chiamata ajax a cascata su quella che logga l'azione sul DB
la mail di riepilogo è stata associata al comando/task  

```
import:stats-calls
```

che ogni notte alle 02:00 prende i log del giorno prima e li mette nella tabella archive; con tutti questi log del giorno prima mando anche le email riepilogative

---

#### 05/07/2017 - Aggiustamenti Backend- **Giovanni**
1. Ora l'immagine listing si deve scegliere con il tasto e non più con l'ordine
2. Il prezzo delle offerte per i residence possono essere a pacchetto
---

#### 27/06/2017 - Rapporto mail - **Luigi**

si vuole rispondere alla domanda: << Il cliente ID=17 dice che non riceve mail da una settiamana, possiamo verificare ? >>

---

#### 26/06/2017 - Nuove featureds - **Giovanni**

- Match tra offerte e listino
- Nuove statistiche email ( solo per IA )

---

#### 26/06/2017 - Pagine che pescano tra i punti di forza associati - **Luigi**

Si realizza uno schema "chiave" --> "chiave espansa" anche per i pti di forza (come quello per le offerte) 

---

#### 20/06/2017 - Pti di forza Clusterizzati - **Luigi**

Problemi:
- le pagine che vengono create in automatico e di fatto sostituiscono una pagina di IA già esistente 
es: all-inclusive/rimini.php Vs hotel-all-inclusive/rimini.php 
--> soluzione forse più semplice è creare degli slug da escludere in modo che alune pagine non venagno create (ma in questo modo vengono create poche pagine e poco significative)
all'ottimo dovrebbero essere sostituite da quelle già esistenti prevedendo una gestione di tutti i punti di forza con lo slug automatico associato e la possibilità di rinominarlo (nel caso con lo slug della pagina esistente); in questo caso bisogna anche eliminare la pagina creata automaticamente con lo slug "vecchio". Questa cosa va gestita per TUTTE LE LINGUE.
Siccome gli slug "sono in mano agli albergatori" ci vuole un periodico

Chiamata con Lucio:
- i pti di forza clusterizzati servono solo come indicazione per creare delle pagine di visibilità (menu barusso); es: pagina visibilità "gestione familiare" oppure "cucina tipica romagnola"; le pagine andranno create a mano e nel CMS dovrà essere inserità la capacità di cercare tra i punti di forza una determinata parola.

#### 12/06/2017 - NUOVO COOKIE - **Giovanni**

Cookie località visitate di recente

---

#### 12/06/2017 - MENU - **Luigi**

Prevedere un modo per far "uscire automaticamente" dal menu alcuni link (come "offerte maggio", oppure "offerte giugno"); le stesse voci devono apparire automaticamente ad inizio di una certa data; ATTENZIONE: i link usciti devono andare a finire automaticmante nel "barusso" !!! 

---

## 12/06/2017 - OTTIMIZZAZIONE GESTIONE COOKIE - **Giovanni**

Introdotta la modal App\CookieIA.php per gestire tutti i cookie del sito

---

#### 12/06/2017 - MENU - **Luigi**

Prevedere un modo per far "uscire automaticamente" dal menu alcuni link (come "offerte maggio", oppure "offerte giugno"); le stesse voci devono apparire automaticamente ad inizio di una certa data 

---

#### 12/06/2017 - OTTIMIZZAZIONE GESTIONE COOKIE - **Giovanni**

---

#### 14/06/2017 - PUNTI DI FORZA - **Luigi**

Si vogliono creare dei pti di forza "cliccabili" che rimandano a tutti gli hotel che hanno quel punto di forza in quella macrolocalità.
es: http://www.info-alberghi.com/gestione-familiare/rimini.php
Le pagine saranno dei template listing con il campo "punto_di_forza" compilato e sul quale avverrà il filtro; le pagine saranno create in automatico come per lo stradario.
attualmente i pti di forza sono separati dalla virgola; devo creare altri 4 campi con gli slug dei medesimi pti di forza!!
Per creare i punti di forza si trasforma la funzione di HotelController in un comando artisan.

---

#### 12/06/2017 - NUOVO COOKIE - **Giovanni**

Cookie località visitate di recente

---

#### 12/06/2017 - MENU - **Luigi**

Prevedere un modo per far "uscire automaticamente" dal menu alcuni link (come "offerte maggio", oppure "offerte giugno"); le stesse voci devono apparire automaticamente ad inizio di una certa data 

---

#### 12/06/2017 - OTTIMIZZAZIONE GESTIONE COOKIE - **Giovanni**

Introdotta la modal App\CookieIA.php per gestire tutti i cookie del sito

---

#### 31/05/2017 - OTTIMIZZAZIONE IMMAGINI - **Luigi**

Provo ad attaccare l'ottimizzazione all'upload delle immagini.

---

#### 29/05/2017 - OTTIMIZZAZIONE IMMAGINI - **Luigi**

Si sceglie di installare al ivello di server la libreria JPEGOptim (consigliata anche dalla linee guida di google https://developers.google.com/speed/docs/insights/OptimizeImages) e poi eseguire mediante un cron uno script per lanciare il comando su tutte le immagini in modo periodico.

Invece di uno script shelle faccio un command artisan
1. Creo il console command
2. registro il comando in app/Console/Kernel.php
3. in config/filesystem.php definisco i nuovi dischi per le folder che volgio ottimizzare(gallery, listing, ...)
``` 
php artisan image:optimize_jpg >> ottimizzazione_immagini.txt
``` 

---

#### 19/05/2017 - Stradario- **Luigi**

Si sono individuate le strade che hanno almeno 5 hotel raggruppate per microlocalità.
``` 
SELECT SUBSTRING_INDEX(h.indirizzo, ', ', 1) AS `indirizzo`,l.nome as localita, l.id as id_localita,  m.nome as macrolocalita, m.id as id_macrolocalita, COUNT(*)
FROM (tblHotel h
JOIN tblLocalita l ON h.localita_id = l.id)
JOIN tblMacrolocalita m ON l.macrolocalita_id = m.id
WHERE h.attivo = 1
GROUP BY SUBSTRING_INDEX(h.indirizzo, ', ', 1), l.id, m.id
HAVING COUNT(*) > 4 
ORDER BY COUNT(*) DESC
``` 

ATTENZIONE: dopo aver trovato le strade, trovare quelle che hanno il nome con il punto ed esplicitarlo: FARLO A MANO CON DELLE QUERY

``` 
update tblHotel set indirizzo = "Viale Gabriele D'Annunzio"
where indirizzo like "%Viale G. D'Annunzio%"
update tblHotel set indirizzo = "Via Cristoforo Colombo"
where indirizzo like "%Via C. Colombo%""
update tblHotel set indirizzo = "Viale Giulio Cesare" 
where indirizzo like "%Viale G. Cesare%"
update tblHotel set indirizzo = "Lungomare Grazia Deledda" 
where indirizzo like "%Lungomare G. Deledda%"
update tblHotel set indirizzo = "Viale 2 Giugno" 
where indirizzo like "%Viale II Giugno%"
``` 

le url alle pagine (automatiche ma SEMPRE da verificare/modificare) saranno del tipo
/<macro>/<micro>/slug(indirizzo)

nella tabella tblCmsPagine ci sarà 
- un nuovo template: "stradario" (forse basta lasciare il template listing e fare un check sulla compilazione del campo indirizzo_stradario)
- un nuovo campo "indirizzo_stradario | string"

---

#### 16/05/2017 - UPGRADE LARAVEL 5.4.23- **Luigi**

---

#### 03/05/2017 - SEZIONE BENESSERE - **Luigi**
Viene gestita come la piscina, solo che i dati sono inseriti SOLO dall'amministratore e **NON DAI CLIENTI AUTONOMAMENTE !!!**
Per il momento, per non escludere gli hotel che non hanno compilato la scheda benessere faccio ritornare da 
``` Utility::getGruppoBenessere() ```  un valore farlocco in modo che NON SI ESEGUA MAI IL CODICE del gruppo benessere

---


#### 27/04/2017 - CHECK MAIL GIA' INVIATE - **Giovanni --> Luigi**
1. mail singola
2. post a MailSchedaController@richiestaPreventivo
3. IN TUTTE LE RICHIESTE prima di passare la richiesta al controller c'è il middleware CheckSendCookie che:
  - se esiste il cookie lo legge e lo deserializza per ottenere un array OPPURE crea un array vuoto con valori di default 
  - SE CI SONO DEGLI INPUT nella request riempie un array associativo ($prefill) con i dati presi dalla request (cercando i dati del form)
  - serializza l'array e lo mette in un cookie "prefill_v11" associato alla risposta QUINDI AVRO' nel controller alla prossima richiesta 
4. quando sono nel controller 
  - creo un nuovo array associativo $prefill con i dati della richiesta
  
__PROBLMEA 1:__

1. se vado in wishlist dalla selezione hotel, siccome ho un post con gli id hotels lui mi cambia nel cookie gli id_hotel con questi; alla prossima richiesta (che è il POST) gli di da spedire corrispondono a quelli a cui ho spedito (trovati nel cookie) e non fa spedire !!!!

---


#### 30/03/2017 - SEZIONE PISCINA - **Luigi**
Lucio Bonini: PISCINA

``` 
superficie 91 mq, altezza min. 1,00 - max 2,70 esposta 8 ore al sole

posizione: giardino, panoramica ultimo piano, in spiaggia, esterna hotel,interna hotel

particolarità: riscaldata, salata, idromassaggio cervicale, idromassaggio, scivoli, trampolino, aperitivi in piscina, getto di bolle, cascata d'acqua, musica subacquea, 40 lettini prendisole disponibili

Vasca bambini: superficie 10 mq altezza 1,00
vasca idromassaggio a parte: 6 posti disponibili
```
Nel momento in cui l'admin simula un hotel ci sarà la nuova sezione "INFO" con le voci "Piscina" e "Centro benessere".

Queste info saranno su una tabella separata dalla tabella tblHoltel con una relazione di tipo One-to-One; 
```
$sup = Hotel::find(17)->infoPiscina->sup;
```
> To retrieve the sup number, Laravel will look for a foreign key in the tblInfoPiscina table named hotel_id, matching the ID stored in that column with the hotel’s ID.

---

#### 22/03/2017 - Visualizza offerte - **Luigi**
l'idea è quella di visualizzare tutte le offerte e non solo 1 random per hotel

__OFFERTE PER PAROLE CHIAVE__

nei VOT si vogliono aggiungere anche le offerte "normali" (che sarebbero visualizzate sotto !!!); introduco una relazione 
VOTLingua 

1. Offerte ??
2. quando trovo le $vot ```_getVotOnLine``` aggancio a queste il cliente ed anche tutte le sue offerte  (solo se sono in listing con parolaChiave_id)
```->withClienteLazyEagerLoaded($locale,$cms_pagina)``` filtrate per i termini dellla parola chiave.
2. quando aggiungo i vot ai clienti non chiamo più il metodo _addVot ma  
```$clienti = $this->_addVotConOfferte($clienti, $vot, $cms_pagina);```
questo metodo prima di appendere il vot al cliente, attacca al vot una relazione con le offerte filtrate per i termini dellla parola chiave (quelle trovate al punto 1.)
```
foreach ($vot as $v) 
        {
       
        $hotel = $v->offerta->cliente;
        
        if($cms_pagina->listing_parolaChiave_id)
          $v->setRelation('altre_offerte',$hotel->offerteLast);
        $clienti->prepend($v);
        
        }
		```
3. adesso il mio oggetto VetrinaOffertaTopLingua avrà anche una relazione altre_offerte
```
 #relations: array:2 [▼
    "offerta" => VetrinaOffertaTop {#1005 ▶}
    "altre_offerte" => Collection {#955 ▼
      #items: array:5 [▼
        0 => Offerta {#1064 ▶}
        1 => Offerta {#1065 ▶}
        2 => Offerta {#1067 ▶}
        3 => Offerta {#1078 ▶}
        4 => Offerta {#1079 ▶}
      ]
    }
  ]
  ```
che E' DELLO STESSO TIPO offerteLast che hanno gli oggetti Hotel
```
Hotel {#2744 ▼
  #table: "tblHotel"
  #guarded: array:1 [▶]
  .......
  #relations: array:8 [▼
    "stelle" => Categoria {#2687 ▶}
    "localita" => Localita {#2677 ▶}
    "numero_offerte_attive" => Collection {#2638 ▶}
    "numero_last_attivi" => Collection {#2966 ▶}
    "numero_coupon_attivi" => Collection {#3166 ▶}
    "numero_bambini_gratis_attivi" => Collection {#6616 ▶}
    "numero_immagini_gallery" => Collection {#6496 ▶}
    "offerteLast" => Collection {#6231 ▼
      #items: array:7 [▼
        0 => Offerta {#5523 ▶}
        1 => Offerta {#5522 ▶}
        2 => Offerta {#5521 ▶}
        3 => Offerta {#5305 ▶}
        4 => Offerta {#5274 ▶}
        5 => Offerta {#5125 ▶}
        6 => Offerta {#5124 ▶}
      ]
    }
  ]
  ```
4. A questo punto iclienti che hanno i vot devono essere TOLTI dal listing normale come per i VST e VTT
5. ORDINAMENTO
Nell'ordinamento devo aggiungere la relazione 'altre_offerte' nei vot che sono inseriti insieme ai clienti perché ordinati (non sono più in testa)
6. APERTURA
Anche qui deveo aggiungere nei vot la relazione 'altre_offerte'

__OFFERTE PRENOTA PRIMA:__

1. quando trovo le $vot (_getVotOnLine) aggancio a queste il cliente ed anche tutte le sue offerte prenotaPrima  ```( solo se  !empty($cms_pagina->listing_offerta_prenota_prima) 
 	->withClienteLazyEagerLoaded($locale,$cms_pagina) ```
2. in questo caso il metodo _addVotConOfferte attacca la vot la relazione 'altre_offerte' che comprende le offerte di tipo prenotPrima
```
elseif(!empty($cms_pagina->listing_offerta_prenota_prima))
          {
          $v->setRelation('altre_offerte',$hotel->offertePrenotaPrima);  
          }
		  ```
3. ORDINAMENTO
Nell'ordinamento devo aggiungere la relazione 'altre_offerte' nei vot che sono inseriti insieme ai clienti perché ordinati (non sono più in testa)

__OFFERTE con listing_offerta != ''  (lastminute | offerta)__ 

Sono tutte le offerte ed i last con url del tipo "hotel_riviera_romagnola": in questo caso ogni struttura dovrà avere TUTTE le OFFERTE (oppure LAST); ed anche i VOT dovranno elencare sotto tutte le offerte o tutti i last 
1. quando carico i vot (scopeWithClienteLazyEagerLoaded di VetrinaOffertaTop) carico le offerte oppure i last 

---

#### 14/03/2017 - Custom EagerLoading - **Luigi**

L'idea è quella di NON includere nell'eager loading delle relazioni che non serviranno nel listing: ad esempio nel listing per categorie non includo le offerte in lingua perché NON devo cercare dentro le offerte come nel listing offerte.

Valuto il "lazy loading"

---

#### 08/03/2017 - Pagination RR - **Luigi**

__LISTING__:

il meotodo getListing() della classe CmsPaginecontroller verifica se l'URL contiene
riviera-romagonla oppure riviera_romagnola ```
strpos($cms_pagina->uri, 'riviera-romagnola') !== false || strpos($cms_pagina->uri, 'italia/hotel_riviera_romagnola') !== false ```
e nel caso invece del ```->get()``` si chiama il metodo ```->paginate(300). ```
A questo punto l'oggetto ritornato è del tipo
$clienti instanceof LengthAwarePaginator 

ATTENZIONE le pagine con riviera_romagnola contengono offerte,

ATTENZIONE SICCOME IL PAGINATION E' UN OGGETTO GIA' PREPARATO DA LARAVEL SE GLI AGGIUNGO QUALCOSA (evidenze) oppure scambio l'ordine della collection spostando gli hotel BB in alto perdo l'istanza  ==> QUINDI NELLA PAGINA http://www.info-alberghi.com/bed-and-breakfast/riviera-romagnola.php in cui per omogeneità con le località si dovevano spostare tutti i BB in **ALTO NON SI POUO' PIU' FARE AL MOMENTO**

__SEO__:

- tutte le pagine della paginazione hanno un canonical alla pagina iniziale (senza parametro page=)
- l'href_lang ci deve essere SOLO nella pagina iniziale (intanto le altre fanno riferimento a lei con il canonical); [modifica del middleware HrefLang]
- tutte le pagine della paginazione devo avere dei meta tag del tipo <link rel="prev" href="" /> e <link rel="next" href="" />

---

#### 28/02/2016 - Hotel EcoSostenibili - **Luigi**
- mettere un check ecosostenibile nella scheda hotel (colonna boolean|0 come greenbooking)
- aggiungere un filtro nel listing per selezionare hotel ecosostenibili

---

#### 24/02/2016 - Piscina - **Luigi**

- creo 2 servizi degl gruppo piscina (piscina fuori hotel e piscina in spiaggia); questi 2 servizi si possono associare all'hotel MA non compaiono nella scheda, MA nel listing con una label tipo aanuale. 
- i 2 servizi appartengono alla NUOVA categoria "ListingPiscina"
- nella tabella tblCategoriaServizi creo la colonna listing[false] e la categoria di questi nuovi servizi ListingPiscina sarà di tipo listing=true
- I 2 servizi sono alternativi quindi radio button
- nel momento dell'associazione ci sarà il campo mt. obbligatorio
piscina fuori hotel a metri 50
piscina in spiaggia a metri 120
Creo il gruppo Listing che non si vede nella scheda hotel e che contiene solo dei servizi che modificano il listing, come appunto "piscina fuori hotel" e "piscina in spiaggia"

__FRONTEND__

Se la pagina è di tipo listing ed ha listing_gruppo_servizi_id != null devo verificare, per ogni hotel, se esistono dei servizi di quel gruppo, di tipo listing !!!
Voglio caricare gli eventuali servizi di tipo listing legati al gruppo della pagina nell'eagerLoading dell'hotel (withListingEagerLoading); 

**__MOBILE__** ????

---

#### 24/02/2016 - Foto Listing servizi - **Luigi**

Solo gli admin hanno la possibilità, nell'elenco delle foto associate al cliente, di selezionare un gruppo (di ricerca) per ogni foto: select box SOTTO LA FOTO perché sopra c'è il draganddrop
**ATTENZIONE:** quando creo una foto nella gallery creo già tutte le versioni per la gallery tra cui anche 220X148 (Hotel Sabrina non ha tutte le versioni e quindi la foto non viene visualizzata)
Nel listing in base al gruppo vado a prendere la PRIMA FOTO se è associata al gruppo
Ho messo a mano nel db nella foto di lucio (foto like '%17_02_Hotel_Sabrina_Rimini_587e29b7b8995.jpg%') il gruppo_id=8;
nell'hotel ho creato la relazione immaginListing come immaginiGallery però la uso nell'eager loading con il filtro su id_gruppo 

**__MOBILE__**  ????

---

#### 24/02/2016 - Mappa mobile - **Luigi**
Mappa hotel mobile

---

#### 16/02/2016 - Nuove distanze - **Luigi**
I punti di interessi saranno associati alle microlocalità (in questo modo chi sta a Ravenna non avrà come pti la ruota panoramica di Rimini);
Ci dovrà essere la gestione web dei punti (nome, lat, long) e la associazione con le località (molti a molti)
In realtà per non creare delle model delle tabelle pivot e complicare la gestione (https://softonsofa.com/laravel-custom-pivot-model-in-eloquent/) si è deciso di lasciate l'associazione molti-a-molti tra hotel e poi. Nel momento in cui si cambia un poi da una località andranno rigenerate le associazioni tra poi e tutti gli hotel di quella localita

---

#### 16/02/2016 - Listing trattamento - **Luigi**
Sarà presente nel listing dei trattamenti NON PIU' CHI HA IL LISTINO CORRISPONDENTE COMPILATO, ma chi ha il trattemnto checkato nella scheda hotel (gli stessi che utilizziamo per il form del calcolo preventivo). Naturalmente siccome le evidenze npon compaiono se l'hotel non è nel listinh chi ha un'evidenza nel trattamento bisogna che abbia il check corrispondente]

---

#### 15/02/2016 - Codice Json - **Giovanni**
Pubblicato il codice Json sulle email

---

#### 14/02/2016 - check riga intestazione listino custom - **Luigi**
il listino custom non viene salvato se nella tabella non c'è il tag <thead> (crea la riga arancione) e gli admin ahnno la possibilità di vedere il 
sorgente della tabella ed aggiungere la riga di intestazione editando l'html.
Nel composer che crea il listino, vine fatto un replace di < **table** > con una table con delle classi che formattano in modo corretto la tabella anche senza thead; quindi ci sono molti listini che non hanno il <thead> e che non passerebbero la validazione  MA che si vedno bene ugualmente, quindi: la validazione la metto solo in inserimento e lascio le cose come stanno nella modifica per non invalidare tutti i listini che comunque si vedono

---

#### xx/02/2016 - varie ed eventuali - **Luigi**
- calendario bambini gratis
- query archiviazione automatica dei prenota prima scaduti
- mail cancellazione offerta bambini gratis
- numero adulti nelle offerte fino a 50 (da 10 con step di 5)
- mail multicamera in tutti i form
---

#### 11/01/2017 - Lettura offerte da IPERNET - **Luigi**

---

#### 29/12/2016 - Modifiche Lucio - **Luigi**
1. possibilità di aggiungere una camera, cioè nuova entry adulti + bambini (https://www.hotelplanner.com/GroupForm.cfm?sc=Google_grouprates&gclid=CImCiL_Rr9ACFcyRGwodTk4Erg)
la relazione preventivo
 * camere_aggiuntive è di tipo 1-n, ma si fissa un numero massimo di camere (es:5) e si aggiungono le colonne alla tabella ??
 * ci sono dei clienti che hanno il campo "chiedi_camere"=true e hanno già una select box con il numero di camere, MA non aggiungono nuove entry quindi successivamente andranno eliminate !!!
	la gestione del ripopolamento del form tramite cookie si gestisce attraverso un middleware CheckSendCookie; dopo che ho fatto post viene creato il cookie prefill_v7 con i dati di un array che sono serializzati.
In HotelController viene ricreato l'array prefill con 
```
$prefill = Utility::getCookiePrefill();
```

dove l'array viene ricostruito (oppure messo a vuoto)

```
array:20 [▼
  "servizi" => []
  "localita_multi" => []
  "multiple_loc_single" => []
  "trattamento" => "trattamento_pc"
  "prefill_eta_array" => array:5 [▼
    1 => "9"
    2 => "11"
    3 => "-"
    4 => "-"
    5 => "-"
  ]
  "cat_1" => 0
  "cat_2" => 0
  "cat_3" => 0
  "cat_4" => 0
  "cat_5" => 0
  "cat_6" => 0
  "nome" => "Luigi Maroncelli"
  "email" => "lmaroncelli@gmail.com"
  "arrivo" => "12/01/2017"
  "partenza" => "28/01/2017"
  "adulti" => "2"
  "bambini" => "2"
  "telefono" => ""
  "richiesta" => ""
  "camere" => "0"
]
dopo che aggiungo le camere aggiutive
array:22 [▼
  "servizi" => []
  "localita_multi" => []
  "multiple_loc_single" => []
  "trattamento" => "trattamento_ai"
  "prefill_eta_array" => array:5 [▼
    1 => "9"
    2 => "11"
    3 => "-"
    4 => "-"
    5 => "-"
  ]
  "cat_1" => 0
  "cat_2" => 0
  "cat_3" => 0
  "cat_4" => 0
  "cat_5" => 0
  "cat_6" => 0
  "nome" => "Luigi Maroncelli"
  "email" => "lmaroncelli@gmail.com"
  "arrivo" => "27/01/2017"
  "partenza" => "31/01/2017"
  "adulti" => "2"
  "bambini" => "2"
  "telefono" => ""
  "richiesta" => ""
  "camere" => "0"
  "camere_aggiuntive" => array:1 [▼
    0 => array:3 [▼
      "adulti" => "1"
      "bambini" => "2"
      "eta_array" => array:2 [▼
        1 => "3"
        2 => "9"
      ]
    ]
  ]
  "numero_camere_aggiuntive" => "1"
]
```
---

#### 06/12/2016 - Evidenze BB - **Luigi**
- attualmente il query scope scopeListingTrattamento cerca nei listini... si vuole fare in modo che le pagine b&b (che cercano per questo trattamento nei listini) 
abbiano in testa delle evidenze che sono TUTTI GLI HOTEL che hanno come servizi principali (SOLO sd && bb) || (SOLO bb)

---

#### 01/12/2016 - Cerca - **Luigi**
- inizialmente si cerca in title, description, uri e le parole devono essere in AND

---

#### 24/10/2016 - Moderazione offerte - **Luigi**
1. Il campo note delle offerte bambini gratis deve essere limitato come nel caso delle offerte [OK]
2. verificare la moderazione del prenota prima [OK]
3. [OK]
- ci sarà una pagina con l'elenco di tutte le offerte/last/PrenotaPrima non raggruppate per utente; 
- le offerte saranno visualizzate in modalità raw con i campi elencati MA non formattati come il frontend;
- di fianco ad ogni offerta ci saranno 2 pulsanti "approva" e "modifica": il primo modifica lo stato in approvato (ricarico la lista e la tolgo o uso ajax o la evidenzio graficamente con il bordo verde ma rimane lì...????); il secondo mi porta alla pagina di modifica dell'offerta (autologin utente e redirect nella pagina edit in una nuova scheda in modo da avere sempre la lista)
4. FRONTEND - Schda Hotel [OK]
Nella scheda hotel ogni offerta deve avere una label con 
"In atteda di modareazione"
"Verificata/moderata dallo Staff di Info Alberghi in data"
desktop / mobile 
le offerte devono avere 2 nuovi campi
approvata: boolean|0
data_approvazione: datetime
ATTENZIONE: in accordo con Nicole ed Elena si considera solo la lingua italiana, ma si deve fare in modo che una modifica sulla lingua italiana possa essere (chekcbox) ritradotta in tute le lingue
- quando modifica un utente che non è admin, root, operatore le offerte devono essere rimesse da aprrovare [OK]
5. Mettere il check "traduci" nella modifica delle offerte in modo che l'italiano modificato possa sovrascrivere tutte lingue (adesso la traduzione avviene solo in inserimento) [OK]
6. limitare le note prezzi [OK]
Siccome il campo è un WYSIWYG c'è solo una limitazione lato server con un FormRequest

---

#### 24/10/2016 - Listing offerte deve avere il prezzo MIN e MAX delle offerte visualizzate - **Luigi**
Il prezzo min/max della struttura lo strovo nel controller, MA il prezzo delle offerte NON POSSO perché ogni strutturta può avere più offerte e nel controller
le eager loado TUTTE.

---

#### 21/10/2016 - Campo anno nelle offerte TOP - **Luigi**
Nelle offerte TOP si vuole inserire anche l'anno, quindi la select box dei mesi viene ripetuta per anno corrente + 2 successivi (+1 precedente per storico ???).
Sul DB nel campo mese (STRING) che memorizza i dati nel formato 2,3,4,8,9 verranno memorizzati nel formato 2-2016,3-2016,4-2016,8-16,9-2016
chiaramente cambierà la query di filtro...
MODIFICHE ADMIN:
Questi seeder riscrivono i mesi delle VOT con il nuovo pattern
- php artisan db:seed --class=aggiustaMesiVOTSeeder
- php artisan db:seed --class=aggiustaMesiVAATSeeder
- php artisan db:seed --class=aggiustaMesiVTTSeeder
- php artisan db:seed --class=aggiustaMesiVSTSeeder

MODIFICHE FRONTEND:
bisognerà cambiare gli scopeAttivo() di tutte le offerte TOP

---

#### 18/10/2016 - Menu RR nelle pagine non cms- **Luigi**
E' stato inserito il menu RR nelle pagine mail_multipla e ricerca_avanzata che non sono create con il CMS  

---

#### 18/10/2016 - Vetrine desktop setssa immagine del listing- **Luigi**
Vetrine desktop setssa immagine del listing

---

#### 13/10/2016 - Modifica tag foto in lingua albergatore- **Luigi**
Interfaccia per la modifica delle traduzioni per labergatore

---

#### 12/10/2016 - Modifica tag foto in lingua + traduzioni batch- **Luigi**
Interfaccia per la modifica delle traduzioni + traduzioni batch tag già inseriti

---

#### 11/10/2016 - Traduzione tag foto - **Luigi**
I tag delle foto inseriti sono AUTOMATICAMENTE tradotti in lingua con Google translate.
Non è presente l'interfaccia per la modifica delle traduzioni

---

#### 06/10/2016 - Scheda di prova - **Luigi**

E' tstato creato un hotel di prova con attivo = -1 che non viene visualizzato all'interno del sito.
L'accesso avviene solo via url parlante /hotel.demo.
La scheda è nascosta ai motori di ricerca con meta tag robots noindex, nofollow.

---

#### 05/10/2016 - Menu Riviera Romagnola come località - **Luigi**
Gestione del menu 

Le pagine di tipo localita 
```
(es: italia/hotel_riviera_romagnola.html) che devono avere il menu RR devono avere listing_id_macro e listing_id_localita uguale a RR perché il menu tematico dei blocchi offerte, servizi,.. (tranne categoria) viene costruito filtrando dalla tabella menu tematico le pagine associate alla id_macro oppure quelle trasversali (id_macro = 0)
->whereIn("tblMenuTematico.macrolocalita_id", [$id_macrolocalita,0])
```

Le pagine di tipo listing 
dovrebbero avere listing_id_macro e listing_id_localita uguale a RR per agganciare il menu **RR**, **MA** in questo modo funzionano più perché per prendere tutti gli hotel non deve essere selezionata nessuna micro e/o macro; se le metto senza listing_id_macro e listing_id_localita selezionano gli hotel correttamente MA non **AGGANCIANO IL MENU RR**
Come workaround modifico gli scope Hotel::scopeListingMacrolocalita e Hotel::scopeListingLocalita in modo che **NON VIENE FILTRATO** l'hotel per macro e/micro se il valore è 0 uppure se il valore coincide con la macro e mico di RR
Per le pagine "trasversali" (aquafan.php) vorrei che anche queste avessere il menu RR "dinamico", MA per agganciarlo devo impostare listing_id_macro e listing_id_localita a RR ed a quel punto **NON SONO PIU'** selezionabili come pagine "trasversali" perché di fatto appartengono ad una località che è RR (in realtà se prima le associo come trasversali e dopo setto listing_id_macro e listing_id_localita FUNZIA ma è una schifezza !!!)
Sfrutto il check "Attiva Menu Riviera Romagnola": quando è checkato lancio la funzione che crea il menu passando isting_id_macro e listing_id_localita di RR FORZOSAMENTE
```
{!! Utilities::getMenuTematico(app('request'), $locale, Utility::getMacroRR(), Utility::getMicroRR()) !!}
```

---

#### 03/10/2016 - Tolti i 3 livelli hotelperdisabili e fiera - **Luigi**

1. le url nei menu NON devono più puntare al 3 livello
2. gli URL del tipo http://www.info-alberghi.com/hotelperdisabili/rimini.php devono essere gestiti correttamente
3. htaccess terzolivello

---

#### 21/09/2016 - Generalizzazione del pulsate per pulire la cache - **Giovanni**
Inserito il tasto "Cancella la Cache" nel menu principale admin/operatore

---

#### 21/09/2016 - Cambio homepage - **Giovanni**
Cambio del sistema offerte in homepage con aggiunta dei giorni di validità

---

#### 15/09/2016 - Attivazione di Riviera Romagnola come localita - **Giovanni**
Abilitazione di una nuova localita Riviera Romagnola

---

#### 13/09/2016 - Editor inline e massivo dei campi SEO - **Giovanni**
Realizzato una pagina per l'editing massive o inline dei campi SEO delle pagine.
Restyling dell'elenco pagine

---

#### 06/09/2016 - Pagine per la consultazione delle nuove featured e degli hotfix - **Giovanni**
Nella homepage root, admin, operatore è stata aggiunta una colonna a dx per la lettura delle nuove implementazioni e degli hotfix

---

#### 06/09/2016 - Add località nella ricerca hotel - **Luigi**

Nel campo compilando il quale si ricercao gli hotel, appare come preview id - nome , si vuole aggiungere anche la località

---

#### 05/09/2016 - Tag foto per i commerciali - **Luigi**

I commerciali devono avere la possibilità di modificare i tag delle foto **SENZA FARE ALTRO** (upload, cancellazione..)
Attualmente i commerciali hanno solo accesso alle statistiche quindi devono poter avere la possibilità di ricercare un hotel ed impersonificarlo.
A questo punto l'unica cosa che si presenta è la possibilità di taggare le foto.
**ATTENZIONE:** Siccome non c'è l'associazione tra hotel e commerciale **NON E' POSSIBILE** assicurarsi che un commerciale acceda solo ai suoi hotel 

