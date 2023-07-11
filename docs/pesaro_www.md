
INSERT INTO tblMacrolocalita (`id`, `nome`, `latitudine`, `longitudine`, `zoom`, `linked_file`, `image`, `created_at`, `updated_at`) VALUES ('12', 'Pesaro', '44.281895', '12.342710', '13', 'milano-marittima.php', 'milano-marittima.jpg', '2000-01-01 00:00:00', '2015-11-11 13:06:01');

INSERT INTO tblLocalita (`id`, `nome`, `alias`, `prov`, `cap`, `latitudine`, `longitudine`, `zoom`, `macrolocalita_id`, `centro_lat`, `centro_long`, `centro_raggio`, `centro_coordinate_note`, `staz_lat`, `staz_long`, `staz_coordinate_note`, `created_at`, `updated_at`) VALUES ('50', 'Pesaro', 'Pesaro', 'PU', '61121', '44.281895', '12.342710', '13', '12', '44.277392', '12.348233', '300', 'Milano Marittima - Rotonda I Maggio', '44.258740', '12.347710', 'Stazione di Pesaro', '2000-01-01 00:00:00', '2000-01-01 00:00:00');


INSERT INTO `tblCmsPagine` (`lang_id`, `attiva`, `uri`, `alternate_uri`, `template`, `menu_riviera_romagnola`, `vetrine_top_enabled`, `seo_title`, `seo_description`, `immagine`, `h1`, `h2`, `descrizione_1`, `descrizione_2`, `menu_macrolocalita_id`, `menu_localita_id`, `vetrina_id`, `evidenza_vetrina_id`, `banner_vetrina_id`, `macrolocalita_count`, `ancora`, `menu_dal`, `menu_al`, `menu_auto_annuale`, `listing_attivo`, `listing_count`, `listing_macrolocalita_id`, `listing_localita_id`, `indirizzo_stradario`, `punto_di_forza`, `listing_puntoForzaChiave_id`, `listing_parolaChiave_id`, `listing_offerta`, `listing_offerta_prenota_prima`, `listing_gruppo_servizi_id`, `listing_categorie`, `listing_tipologie`, `listing_trattamento`, `listing_coupon`, `listing_bambini_gratis`, `listing_whatsapp`, `listing_green_booking`, `listing_eco_sostenibile`, `listing_annuali`, `n_offerte`, `prezzo_minimo`, `prezzo_massimo`, `listing_preferiti`, `tipo_evidenza_crm`, `created_at`, `updated_at`, `menu_evidenza`, `listing_bonus_vacanze_2020`) VALUES ('it', '1', 'pesaro.php', '139', 'localita', '0', '0', '{HOTEL_COUNT} Hotel di {LOCALITA}, usa il Bonus Vacanze di 500€ Estate 2020', 'Negli hotel di {LOCALITA} puoi scontare il Bonus Vacanze (Covid 19) e Risparmiare fino a 500€. Buoni vacanze {LOCALITA} Prenota qui prima del termine.', '', '{HOTEL_COUNT} Hotel di Pesaro', 'Hotel Pesaro', '<p>Pesaro &egrave; la localit&agrave; pi&ugrave; <strong>glamour </strong>della Riviera Romagnola, meta di vip, sportivi e personaggi famosi. Spiaggia, movida, <strong>locali di tendenza</strong>, shopping e vita notturna sono un richiamo soprattutto per i <strong>giovani </strong>in estate. Pesaro &egrave; una<strong>&nbsp;localit&agrave; esclusiva</strong> ma alla portata di tutti e non mancano divertimento e intrattenimento anche per le <strong>famiglie </strong>che qui trovano stabilimenti attrezzati, hotel moderni con piscine, eventi dedicati ai pi&ugrave; piccoli e un interessante contesto naturalistico per relax ed escursioni.</p>', '[{\"tipocontenuto\":\"text\",\"layout\":\"1col\",\"immagine\":\"\",\"h2\":\"Hotel Pesaro\",\"h3\":\"\",\"descrizione_2\":\"<div itemscope=\\\"\\\" itemtype=\\\"https:\\/\\/schema.org\\/FAQPage\\\">\\r\\n<h3>FAQ Vacanze a Pesaro<\\/h3>\\r\\n<div itemscope=\\\"\\\" itemtype=\\\"https:\\/\\/schema.org\\/Question\\\" itemprop=\\\"mainEntity\\\">\\r\\n<p><strong itemprop=\\\"name\\\">Sono aperti gli hotel di Pesaro in autunno e inverno 2020-2021?<\\/strong><\\/p>\\r\\n<div itemscope=\\\"\\\" itemtype=\\\"https:\\/\\/schema.org\\/Answer\\\" itemprop=\\\"acceptedAnswer\\\">\\r\\n<p itemprop=\\\"text\\\">Si, consulta l\'elenco degli <a href=\\\"\\/annuale\\/elenco-hotel\\/milano-marittima.php\\\">hotel di Pesaro aperti tutto l\'anno<\\/a>.<\\/p>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n<div itemscope=\\\"\\\" itemtype=\\\"https:\\/\\/schema.org\\/Question\\\" itemprop=\\\"mainEntity\\\">\\r\\n<p><strong itemprop=\\\"name\\\">Ci sono hotel a Pesaro che accettano il Bonus Vacanze 2020-2021?<\\/strong><\\/p>\\r\\n<div itemscope=\\\"\\\" itemtype=\\\"https:\\/\\/schema.org\\/Answer\\\" itemprop=\\\"acceptedAnswer\\\">\\r\\n<p itemprop=\\\"text\\\">S&igrave;, in questa pagina trovi gli <a href=\\\"\\/bonus-vacanze-covid19\\/milano-marittima.php\\\">hotel di Pesaro che accettano il Bonus Vacanze<\\/a>.<\\/p>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n<div itemscope=\\\"\\\" itemtype=\\\"https:\\/\\/schema.org\\/Question\\\" itemprop=\\\"mainEntity\\\">\\r\\n<p><strong itemprop=\\\"name\\\">Quali sono le norme di prevenzione del contagio da Covid-19 negli hotel di Pesaro?<\\/strong><\\/p>\\r\\n<div itemscope=\\\"\\\" itemtype=\\\"https:\\/\\/schema.org\\/Answer\\\" itemprop=\\\"acceptedAnswer\\\">\\r\\n<p itemprop=\\\"text\\\">Tutti gli hotel di Pesaro osservano le norme comunali e le linee guida della Regione Emilia Romagna per la gestione dell\'emergenza sanitaria da Covid-19. Leggi <a href=\\\"\\/note\\/covid-19\\/\\\">quello che c\'&egrave; da sapere per chi soggiorna negli hotel della Riviera Romagnola<\\/a>.<\\/p>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n<div itemscope=\\\"\\\" itemtype=\\\"https:\\/\\/schema.org\\/Question\\\" itemprop=\\\"mainEntity\\\">\\r\\n<p><strong itemprop=\\\"name\\\">Quali sono gli hotel di Pesaro con offerte per bambini?<\\/strong><\\/p>\\r\\n<div itemscope=\\\"\\\" itemtype=\\\"https:\\/\\/schema.org\\/Answer\\\" itemprop=\\\"acceptedAnswer\\\">\\r\\n<p itemprop=\\\"text\\\"><a href=\\\"\\/hotel-bambini\\/milano-marittima-hotel-bambini.php\\\">In questo elenco<\\/a> trovi gli hotel di Pesaro dove i bambini soggiornano gratis.<\\/p>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n<div itemscope=\\\"\\\" itemtype=\\\"https:\\/\\/schema.org\\/Question\\\" itemprop=\\\"mainEntity\\\">\\r\\n<p><strong itemprop=\\\"name\\\">&Egrave; aperto Mirabilandia?<\\/strong><\\/p>\\r\\n<div itemscope=\\\"\\\" itemtype=\\\"https:\\/\\/schema.org\\/Answer\\\" itemprop=\\\"acceptedAnswer\\\">\\r\\n<p itemprop=\\\"text\\\">No, al momento tutti i parchi della Riviera Romagnola, compreso Mirabilandia, sono chiusi.<\\/p>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n<h3>La spiaggia di Pesaro<\\/h3>\\r\\n<p>4 km di spiagge moderne e attrezzate con alle spalle la pineta di Cervia e Pesaro. Tra quelli pi&agrave; famosi segnaliamo:<br \\/> - <strong>Paparazzi 242:<\\/strong> frequentato dai vip ha anche un ristorante direttamente sulla spiaggia,&nbsp;bar, gelateria, attrezzi per il fitness, area benessere. Molto frequentato all\'aperitivo. (Viale Pietro Mascagni, 242 - www.paparazzibeach.it)<br \\/> - <strong>Papeete Beach:<\\/strong> si distingue da tutti gli altri per lo stile degli arredi e per gli aperitivi lounge sulla spiaggia, rinomati in tutta Italia (via Traversa III Pineta, 281 - www.papeetebeach.com<br \\/> - <strong>Fantini Club:<\\/strong>&nbsp;villaggio vacanza per gli amanti dello sport e del wellness. Servizi di lusso, eventi mondani e relax (Lungomare G.Deledda, 182 - fantiniclub.com<\\/p>\\r\\n<h3>Discoteche e vita notturna<\\/h3>\\r\\n<p>La vita notturna inizia all\'ora dell\'aperitivo, in spiaggia e negli street bar del centro.Dopo cena si balla nelle pi&ugrave; famose discoteche:<\\/p>\\r\\n<p><strong>- Club Pineta:&nbsp;<\\/strong>&egrave; il simbolo del divertimento di Pesaro, un vero e proprio monumento, una discoteca raffinata e lussuosa, con selezione all\'ingresso. Frequentata da personaggi del mondo dello spettacolo.(via Romagna, 66 - 0544.994728 - www.pinetadisco.com)<br \\/> - <strong>Villa Papeete:<\\/strong> discoteca di prestigio, la location &egrave; una grande villa immersa in un parco di ulivi (via Argine Destro Savio &ndash; 335.1275444 - www.villapapeete.com).<\\/p>\\r\\n<h3><strong>Parchi Divertimento<\\/strong><\\/h3>\\r\\n<p>-&nbsp;<strong>La casa delle Farfalle<\\/strong> &egrave; un parco educativo interamente dedicato alle farfalle tropicali e provenienti da ogni parte del mondo che in un habitat ricostruito e fedele volano libere e si riproducono.<br \\/> Il parco ha anche una sezione dedicata agli insetti e organizza visite, giochi e laboratori per i bambini (via Jelenia Gora 6\\/d - 0544.995671)<br \\/> - <strong>Mirabilandia<\\/strong> &egrave; uno dei parchi divertimento pi&ugrave; grandi e famosi d&rsquo;Europa, con attrazioni uniche e mozzafiato, per grandi e giochi per i pi&ugrave; piccoli, spettacoli, ruota panoramica e zone ristoro.<br \\/> Informazioni turistiche su <a href=\\\"http:\\/\\/www.milanomarittima.it\\\" target=\\\"_blank\\\" rel=\\\"noopener\\\"><u>www.milanomarittima.it<\\/u><\\/a><\\/p>\\r\\n<p>&nbsp;<\\/p>\"}]', '5', '0', '13', '0', '0', '79', 'Hotel Pesaro', '2020-06-29', '2021-06-29', '1', '0', '67', '5', '24', '', '', '0', '0', '', '', '0', '', '', '', '0', '0', '0', '0', '0', '0', '9', '20.00', '250.00', '0', '', '2000-01-01 00:00:00', '2020-12-22 10:11:43', '0', '0');



 UPDATE tblHotel 
SET localita_id = 50
WHERE id IN (1875,1883,1884,1890,1891,1892,1897,1898,1899,1900,1901,1902,1903,1904,1905,1906)


UPDATE tblHotel 
SET attivo = 1
WHERE id IN (1875,1883,1884,1890,1891,1892,1897,1898,1899,1900,1901,1902,1903,1904,1905,1906)


RewriteCond %{REQUEST_URI} /hotel-4-quattro-stelle/pesaro.php [OR]
RewriteCond %{REQUEST_URI} /hotel-3-tre-stelle-superiore/pesaro.php [OR]
RewriteCond %{REQUEST_URI} /hotel-3-tre-stelle/pesaro.php [OR]
RewriteCond %{REQUEST_URI} /hotel-2-due-stelle/pesaro.php [OR]
RewriteCond %{REQUEST_URI} /hotel-1-una-stella/pesaro.php [OR]
RewriteCond %{REQUEST_URI} /residence/pesaro.php [OR]
RewriteCond %{REQUEST_URI} /bed-and-breakfast/pesaro.php [OR]
RewriteCond %{REQUEST_URI} /annuale/elenco-hotel/pesaro.php [OR]
RewriteCond %{REQUEST_URI} /animali-ammessi/pesaro.php [OR]
RewriteCond %{REQUEST_URI} /hotel-all-inclusive/pesaro.php [OR]
RewriteCond %{REQUEST_URI} /benessere/pesaro.php [OR]
RewriteCond %{REQUEST_URI} /internet-wireless-wifi/pesaro.php [OR]
RewriteCond %{REQUEST_URI} /economici/pesaro.php [OR]
RewriteCond %{REQUEST_URI} /hotel-giovani/pesaro.php [OR]
RewriteCond %{REQUEST_URI} /hotel-vicino-spiaggia/pesaro.php [OR]
RewriteCond %{REQUEST_URI} /offerte-piano-famiglia/pesaro.php [OR]
RewriteCond %{REQUEST_URI} /congressi/pesaro.php [OR]
RewriteCond %{REQUEST_URI} /hotel-disabili/pesaro.php [OR]
RewriteCond %{REQUEST_URI} /capodanno/pesaro.php [OR]
RewriteCond %{REQUEST_URI} /hotel-celiaci/pesaro.php [OR]
RewriteCond %{REQUEST_URI} /hotel-parcheggio/pesaro.php [OR]
RewriteCond %{REQUEST_URI} /hotel-giardino/pesaro.php [OR]
RewriteCond %{REQUEST_URI} /dove-dormire/pesaro.php [OR]
RewriteCond %{REQUEST_URI} /coupon-buoni-sconto/pesaro.php [OR]
RewriteCond %{REQUEST_URI} /estate-{CURRENT-YEAR}/pesaro.php [OR]
RewriteCond %{REQUEST_URI} /pensione-completa/pesaro.php [OR]
RewriteCond %{REQUEST_URI} /prezzi-hotel/pesaro.php [OR]
RewriteCond %{REQUEST_URI} /hotel-con-ascensore/pesaro.php [OR]
RewriteCond %{REQUEST_URI} /hotel-vista-mare/pesaro.php [OR]
RewriteCond %{REQUEST_URI} /prenotazione-hotel/pesaro.php [OR]
RewriteCond %{REQUEST_URI} /hotel-3-tre-stelle-sul-mare-con-piscina/pesaro.php [OR]
RewriteCond %{REQUEST_URI} /elenco-hotel/pesaro.php [OR]
RewriteCond %{REQUEST_URI} /bike-hotel/pesaro.php [OR]
RewriteCond %{REQUEST_URI} /disco-pesaro.php [OR]
RewriteCond %{REQUEST_URI} /offerte-notterosa/pesaro.php [OR]
RewriteCond %{REQUEST_URI} /offerte-ferragosto/pesaro.php [OR]
RewriteCond %{REQUEST_URI} /offerte-halloween/pesaro.php [OR]
RewriteCond %{REQUEST_URI} /offerte-25-aprile/pesaro.php [OR]
RewriteCond %{REQUEST_URI} /offerte-last-minute-maggio/pesaro.php [OR]
RewriteCond %{REQUEST_URI} /offerte-last-minute-giugno/pesaro.php [OR]
RewriteCond %{REQUEST_URI} /offerte-last-minute-luglio/pesaro.php [OR]
RewriteCond %{REQUEST_URI} /offerte-last-minute-agosto/pesaro.php [OR]
RewriteCond %{REQUEST_URI} /offerte-1-maggio/pesaro.php [OR]
RewriteCond %{REQUEST_URI} /offerte-last-minute-settembre/pesaro.php [OR]
RewriteCond %{REQUEST_URI} /offerte-2-giugno/pesaro.php [OR]
RewriteCond %{REQUEST_URI} /spiaggia-libera/pesaro.php [OR]
RewriteCond %{REQUEST_URI} /spiaggia-per-disabili/pesaro.php [OR]
RewriteCond %{REQUEST_URI} /solo-dormire/pesaro.php [OR]
RewriteCond %{REQUEST_URI} /early-booking/pesaro.php [OR]
RewriteCond %{REQUEST_URI} /hotel-5-cinque-stelle/pesaro.php [OR]
RewriteCond %{REQUEST_URI} /mezza-pensione/pesaro.php [OR]
RewriteCond %{REQUEST_URI} /hotel-piscina-fuori-struttura/pesaro.php [OR]
RewriteCond %{REQUEST_URI} /bonus-vacanze-covid19/pesaro.php [OR]



ALTER TABLE `tblCmsPagine` 
	CHANGE COLUMN `immagine` `immagine` VARCHAR(255) NOT NULL DEFAULT '' COLLATE 'utf8_unicode_ci' AFTER `seo_description`;
ALTER TABLE `tblCmsPagine`
	CHANGE COLUMN `descrizione_2` `descrizione_2` TEXT NOT NULL DEFAULT '' COLLATE 'utf8_unicode_ci' AFTER `descrizione_1`;
ALTER TABLE `tblCmsPagine`
	CHANGE COLUMN `listing_tipologie` `listing_tipologie` VARCHAR(255) NOT NULL DEFAULT '' COLLATE 'utf8_unicode_ci' AFTER `listing_categorie`;
ALTER TABLE `tblCmsPagine`
	CHANGE COLUMN `listing_trattamento` `listing_trattamento` VARCHAR(255) NOT NULL DEFAULT '' COLLATE 'utf8_unicode_ci' AFTER `listing_tipologie`;
ALTER TABLE `tblCmsPagine`
	CHANGE COLUMN `tipo_evidenza_crm` `tipo_evidenza_crm` VARCHAR(255) NOT NULL DEFAULT '' COLLATE 'utf8_unicode_ci' AFTER `listing_preferiti`;


- creo le pagine in italiano 
INSERT INTO `tblCmsPagine` (`lang_id`, `attiva`, `uri`, `alternate_uri`, `template`, `seo_title`, `seo_description`, `h1`, `h2`, `descrizione_1`, `menu_macrolocalita_id`, `menu_localita_id`, `vetrina_id`, `evidenza_vetrina_id`, `banner_vetrina_id`, `macrolocalita_count`, `ancora`, `menu_dal`, `menu_al`, `menu_auto_annuale`, `listing_attivo`, `listing_count`, `listing_macrolocalita_id`, `listing_localita_id`, `localita_id_stradario`, `macrolocalita_id_stradario`, `listing_parolaChiave_id`, `listing_gruppo_servizi_id`, `listing_categorie`, `listing_coupon`, `listing_bambini_gratis`, `listing_whatsapp`, `listing_annuali`, `created_at`, `updated_at`, `listing_bonus_vacanze_2020`) 
SELECT
`lang_id`, 
`attiva`, 
REPLACE (uri,'milano-marittima','pesaro') AS uri, 
`alternate_uri`, 
`template`, 
REPLACE (seo_title,'Milano Marittima','Pesaro') AS seo_title,
REPLACE (seo_description,'Milano Marittima','Pesaro') AS seo_description, 
REPLACE (h1,'Milano Marittima','Pesaro') AS h1,
REPLACE (h2,'Milano Marittima','Pesaro') AS h2,
REPLACE (descrizione_1,'Milano Marittima','Pesaro') AS descrizione_1,
IF(menu_macrolocalita_id=5, 12, 0) AS menu_macrolocalita_id,
IF(menu_localita_id=24, 50, 0) AS menu_localita_id,
IF(vetrina_id=13, 73, 0) AS vetrina_id,
`evidenza_vetrina_id`, 
`banner_vetrina_id`, 
`macrolocalita_count`, 
REPLACE (ancora,'Milano Marittima','Pesaro') AS ancora,
`menu_dal`, 
`menu_al`, 
`menu_auto_annuale`, 
`listing_attivo`, 
`listing_count`, 
IF(listing_macrolocalita_id=5, 12, 0) AS listing_macrolocalita_id,
IF(listing_localita_id=24, 50, 0) AS listing_localita_id,
`localita_id_stradario`, 
`macrolocalita_id_stradario`, 
`listing_parolaChiave_id`, 
`listing_gruppo_servizi_id`, 
`listing_categorie`, 
`listing_coupon`, 
`listing_bambini_gratis`, 
`listing_whatsapp`, 
`listing_annuali`, 
`created_at`, 
`updated_at`, 
`listing_bonus_vacanze_2020`
FROM tblCmsPagine p WHERE p.uri LIKE '%milano-marittima.php%' AND lang_id = 'it' AND attiva = 1 AND template != 'localita'





*ATTENZIONE* 

la residence deve avere listing_tipologie = 2 x apparire automaticamente nel menu

la b&b deve avere listing_trattamento = bb x apparire automaticamente nel menu

UPDATE `infoalberghi`.`tblMacrolocalita` SET `linked_file`='pesaro.php' WHERE  `id`=12;


UPDATE tblCmsPagine
SET alternate_uri = ''
where uri LIKE '%pesaro%' AND attiva = 1 


tolgo l'associazione tra le pagine in lingua perché partiamo solo con it



- toglie la descrizione da tutte le pagine di Pesaro

update tblCmsPagine 
SET descrizione_1 = ''
WHERE uri LIKE '%pesaro%' AND attiva = 1 AND template='listing'


