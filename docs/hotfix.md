#### 02/05/2019 - Mappa hotel - **Luigi**
Nella scheda hotel la mappa era quella statica di GoogleMaps (https://maps.googleapis.com/maps/api/staticmap) che però la fa pagare ogni volta che viene caricata e negli ultimi mesi la spesa raggiungeva anche 300 USD al mese. E' stata sostituita da una immagine fissa diversa per ogni microlocalità. SOLO PER IL DESKTOP


#### 23/04/2019 - Mail doppie parziali - **Luigi**
Alcune mail "doppie parziali" venivano comunque inviate dal sistema, metre le API le scartavano giuistamente; è stato uniformato il controllo in modo che queste mail non siano né nelle API né nella spedizione "normale"


#### 23/10/2018 - Totali mancanti nelle stats - **Luigi**


#### 24/09/2018 - Email risponditori - **Giovanni**
E' stata aggiunto un campo nella tabella hotel chiamato email_risponditori.
Se lo trovo compilato le email di preventivo vengono inviate a questo campo invece che email_principale.
Per ora lo usiamo solo per 1clicksuite.

#### 16/08/2018 - Referer mancante - **Giovanni**
Aggiunto nell'header del sito il meta referer always

#### 12/08/2018 - Link da mobile delle offerte TOP - **Giovanni**
Sistemato i link da mobile delle offerte TOP.
Nelle pagina mobile delle offerte top tutti i link venivano segnati come offerte top mentre solo il primo era effettivamente Top.


---
#### 07/07/2018 - Aumentato memory_limit - **Giovanni**
Aggiornato il valore *memory_limit* a 1024M nell'htaccess

---

#### 16/04/2018 - Maschera IP nelle email - **Giovanni**
Aggiunta un maschera per l'ip nelle email di comunicazione del cliente verso gli albergatori.

---

#### 13/04/2018 - Paginazione pagine con vetrine - **Giovanni**
1. Messa a posto del css admin che si era rotto.
2. Riordino della homepage
3. Correzione dei file md

---

#### 02/02/2018 - Paginazione pagine con vetrine - **Giovanni**
Aggiunta la paginazione alle pagine con le vetrine

---

#### 22/08/2017 - Filtro trattamento - **Luigi**
mail multipla mobile: scrivo a tutti gli hotel elencati in una pagina senza possibilità di filtro; E' STATO AGGIUNTO il filtro sul trattamento accettato dalla struttura contattata.
SIccome c'è il multicamere ed il trattamento è legato alla camera la struttura viene selezionata se ha TUTTI I TRATTAMENTI ricercati.
Adesso il filtro c'è sempre tranne nella wishlist: perchè se ho appositamente selezionato quell'hotel voglio che la mail gli arrivi.

---

#### 26/06/2017 - Email bug - **Giovanni**

- Risolto il bug sui cookie ( bad request )
- Risolto il bug 404 su email multipla mobile 

---

#### 15/03/2017
Ripristino campo esca form multiplo mobile

---

#### 07/03/2017

Pagine trattamenti RR

---

#### 03/03/2017 - Evidenze Bellaria-Igea - **Luigi**

Le evidenze associate alle pagine  
hotel_riviera_romagnola/bellaria/offerte_speciali.php e 
hotel_riviera_romagnola/bellaria/last_minute.php 
 
vengono create UGUALI anche nelle pagine
hotel_riviera_romagnola/igea-marina/offerte_speciali.php e 
hotel_riviera_romagnola/igea-marina/last_minute.php  

deve essere così tanto è vero che ho creato un'eccezione in admin per far vedere tra le pagine da associare quelle di igea-marina 
anche SE NON E' UNA MACRO

Il problema è che nella scheda hotel si vedono 2 evidenze UGUALI.

Si crea un campo nascondi_in_scheda [boolean|false] nella tabella tblVetrineOfferteTop e si setta a true nelle offerte di IgeaMArine create uguali a quelle di Bellaria (da admin con spiegazione) far apparire solo per gli hotel di Bellaria/Igea Marina (macrolocalita_id = 6 -> localita = 18, 19) 

---

#### 02/03/2017 - Oggetto Mail - **Luigi**

Oggetto mail sbagliato: prende le età come numero di bambini

---

#### 27/02/2017 - Bambini Gratis - **Luigi**

Mail di notifica solo se attivo

---

#### 16/02/2017 - JSON - **Giovanni**

Aggiunto language al json email

---

#### 19/12/2016 - bug menu - **Giovanni**

Aggiunto il canonical in homepage

---

#### 04/11/2016 - bug menu - **Luigi**

menu mobile localita non in lingua

---

#### 03/11/2016 - Hotel montagna - **Luigi**

tolto 399 dai disabilitati

---

#### 26/10/2016 - bug - **Luigi**

servizi personalizzati bambini e listing offerte e early booking

---

#### 25/10/2016 - css - **Luigi**

Offerte bambini gratis più periodi troppo attaccati

---

#### 24/10/2016 - Rimozione menu barusso dalla sessione - **Giovanni**

Abbiamo rimosso il menu barusso dalla sessione e ripristinato i numeri per i titoli nella sezione Riviera romagnola.

---

#### 20/10/2016 - Bug generali - **Luigi**

Traduzione delle categorie servizi nella scheda hotel

---

#### 12/10/2016 - Bug generali - **Luigi**

Aggiunto campo ü tra quelli accettabili per il nome hotel nella ricerca in admin

---

#### 10/10/2016 - Bug generali - **Luigi**

301 delle guide Pasqua, Natale, Capodanno

---

#### 07/10/2016 - Bug generali - **Giovanni**

Prima trance di correzioni della premiata ditta Nicola/Elena

---

#### 23/09/2016 - Offerte TOP tradotte automaticamente in inserimento - **Luigi**

Le offerte TOP sono automaticamente tradotte in lingua quando sono create la prima volta.
Successivamente dopo ogni nuova traduzione si viene mantenuti nella pagina di editing

---

#### 22/09/2016 - Check contenuto offerte e note listino - **Luigi**

Le offerte (Offerte/Last/PrenotaPrima) e le note listino , che sono inserite dagli albergatori,
vengono validate anche verificando la presenza di stringhe che indicano email, url e numeri di telefono

---

#### 21/09/2016 - Bugfix - **Luigi**

Bugfix - ottimizzazione query eseguita al login per elenco hotel che hanno taggato almeno 1 foto.
Join con RAW quey e NON  WhereHas che viente tradotto da Laravel con "where id in (select *....)" :(

---

#### 21/09/2016 - Bugfix - **Giovanni**

Bugfix - zindex menu
Bugfix - mappa e punti di interesse
Bugfix - spazio dopo la parentesi e virgola
Bugfix - cache servizi

---

#### 19/09/2016 - Cambio filtri nelle pagine - **Giovanni**

Cambio del tipo di "input template" ( text->Select ) nella lista pagine dentro l'admin

---

#### 19/09/2016 - BugFix su menu tematico - **Giovanni**

Bugfix sul menu tematico di riviera romagnola e rimozione delle voci doppie sui menu localita

---

#### 15/09/2016 - BugFix su localita - **Giovanni**

Bugfix sulle localita e sui replace dei tag nell'editor di massa

---

#### 14/09/2016 - BugFix - **Giovanni**

Fissati alcuni bug sull'editor inline e aggiunta della URI di riferimento

---

#### 07/09/2016 - Modifiche al menu tematico desktop e mobile - **Giovanni**

Modifiche al menu tematico desktop e mobile per l'aggiunta delle offerte Halloween e Capodanno

---

#### 06/09/2016 - Servizi ajax - **Giovanni**

Risolto il problema sul salvataggio dei servizi ajax nell'admin.

---