

> INSERT INTO `develop`.`tblMacrolocalita` (`id`, `nome`, `latitudine`, `longitudine`, `zoom`, `linked_file`, `image`, `created_at`, `updated_at`) VALUES ('12', 'Pesaro', '44.281895', '12.342710', '13', 'milano-marittima.php', 'milano-marittima.jpg', '2000-01-01 00:00:00', '2015-11-11 13:06:01');

> INSERT INTO `develop`.`tblLocalita` (`id`, `nome`, `alias`, `prov`, `cap`, `latitudine`, `longitudine`, `zoom`, `macrolocalita_id`, `centro_lat`, `centro_long`, `centro_raggio`, `centro_coordinate_note`, `staz_lat`, `staz_long`, `staz_coordinate_note`, `created_at`, `updated_at`) VALUES ('50', 'Pesaro', 'Pesaro', 'PU', '61121', '44.281895', '12.342710', '13', '12', '44.277392', '12.348233', '300', 'Milano Marittima - Rotonda I Maggio', '44.258740', '12.347710', 'Stazione di Pesaro', '2000-01-01 00:00:00', '2000-01-01 00:00:00');


- da sistemare in "tblLocalita": latitudine e longitudine; centro, stazione con le relative note

- da sistemare in "tblMacrolocalita": immagine e linked file





- copio milano-marittma.php come *pesaro.php* e __cambio__ da admin nella sezione "Template" __la Macrolocalita della pagina__ (Pesaro)



> INSERT INTO `develop`.`tblCmsPagine` (`lang_id`, `attiva`, `uri`, `alternate_uri`, `template`, `menu_riviera_romagnola`, `vetrine_top_enabled`, `seo_title`, `seo_description`, `immagine`, `h1`, `h2`, `descrizione_1`, `descrizione_2`, `menu_macrolocalita_id`, `menu_localita_id`, `vetrina_id`, `evidenza_vetrina_id`, `banner_vetrina_id`, `macrolocalita_count`, `ancora`, `menu_dal`, `menu_al`, `menu_auto_annuale`, `listing_attivo`, `listing_count`, `listing_macrolocalita_id`, `listing_localita_id`, `indirizzo_stradario`, `punto_di_forza`, `listing_puntoForzaChiave_id`, `listing_parolaChiave_id`, `listing_offerta`, `listing_offerta_prenota_prima`, `listing_gruppo_servizi_id`, `listing_categorie`, `listing_tipologie`, `listing_trattamento`, `listing_coupon`, `listing_bambini_gratis`, `listing_whatsapp`, `listing_green_booking`, `listing_eco_sostenibile`, `listing_annuali`, `n_offerte`, `prezzo_minimo`, `prezzo_massimo`, `listing_preferiti`, `tipo_evidenza_crm`, `created_at`, `updated_at`, `menu_evidenza`, `listing_bonus_vacanze_2020`) VALUES ('it', '1', 'pesaro.php', '139', 'localita', '0', '0', '{HOTEL_COUNT} Hotel di {LOCALITA}, usa il Bonus Vacanze di 500€ Estate 2020', 'Negli hotel di {LOCALITA} puoi scontare il Bonus Vacanze (Covid 19) e Risparmiare fino a 500€. Buoni vacanze {LOCALITA} Prenota qui prima del termine.', '', '{HOTEL_COUNT} Hotel di Pesaro', 'Hotel Pesaro', '<p>Pesaro &egrave; la localit&agrave; pi&ugrave; <strong>glamour </strong>della Riviera Romagnola, meta di vip, sportivi e personaggi famosi. Spiaggia, movida, <strong>locali di tendenza</strong>, shopping e vita notturna sono un richiamo soprattutto per i <strong>giovani </strong>in estate. Pesaro &egrave; una<strong>&nbsp;localit&agrave; esclusiva</strong> ma alla portata di tutti e non mancano divertimento e intrattenimento anche per le <strong>famiglie </strong>che qui trovano stabilimenti attrezzati, hotel moderni con piscine, eventi dedicati ai pi&ugrave; piccoli e un interessante contesto naturalistico per relax ed escursioni.</p>', '[{\"tipocontenuto\":\"text\",\"layout\":\"1col\",\"immagine\":\"\",\"h2\":\"Hotel Pesaro\",\"h3\":\"\",\"descrizione_2\":\"<div itemscope=\\\"\\\" itemtype=\\\"https:\\/\\/schema.org\\/FAQPage\\\">\\r\\n<h3>FAQ Vacanze a Pesaro<\\/h3>\\r\\n<div itemscope=\\\"\\\" itemtype=\\\"https:\\/\\/schema.org\\/Question\\\" itemprop=\\\"mainEntity\\\">\\r\\n<p><strong itemprop=\\\"name\\\">Sono aperti gli hotel di Pesaro in autunno e inverno 2020-2021?<\\/strong><\\/p>\\r\\n<div itemscope=\\\"\\\" itemtype=\\\"https:\\/\\/schema.org\\/Answer\\\" itemprop=\\\"acceptedAnswer\\\">\\r\\n<p itemprop=\\\"text\\\">Si, consulta l\'elenco degli <a href=\\\"\\/annuale\\/elenco-hotel\\/milano-marittima.php\\\">hotel di Pesaro aperti tutto l\'anno<\\/a>.<\\/p>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n<div itemscope=\\\"\\\" itemtype=\\\"https:\\/\\/schema.org\\/Question\\\" itemprop=\\\"mainEntity\\\">\\r\\n<p><strong itemprop=\\\"name\\\">Ci sono hotel a Pesaro che accettano il Bonus Vacanze 2020-2021?<\\/strong><\\/p>\\r\\n<div itemscope=\\\"\\\" itemtype=\\\"https:\\/\\/schema.org\\/Answer\\\" itemprop=\\\"acceptedAnswer\\\">\\r\\n<p itemprop=\\\"text\\\">S&igrave;, in questa pagina trovi gli <a href=\\\"\\/bonus-vacanze-covid19\\/milano-marittima.php\\\">hotel di Pesaro che accettano il Bonus Vacanze<\\/a>.<\\/p>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n<div itemscope=\\\"\\\" itemtype=\\\"https:\\/\\/schema.org\\/Question\\\" itemprop=\\\"mainEntity\\\">\\r\\n<p><strong itemprop=\\\"name\\\">Quali sono le norme di prevenzione del contagio da Covid-19 negli hotel di Pesaro?<\\/strong><\\/p>\\r\\n<div itemscope=\\\"\\\" itemtype=\\\"https:\\/\\/schema.org\\/Answer\\\" itemprop=\\\"acceptedAnswer\\\">\\r\\n<p itemprop=\\\"text\\\">Tutti gli hotel di Pesaro osservano le norme comunali e le linee guida della Regione Emilia Romagna per la gestione dell\'emergenza sanitaria da Covid-19. Leggi <a href=\\\"\\/note\\/covid-19\\/\\\">quello che c\'&egrave; da sapere per chi soggiorna negli hotel della Riviera Romagnola<\\/a>.<\\/p>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n<div itemscope=\\\"\\\" itemtype=\\\"https:\\/\\/schema.org\\/Question\\\" itemprop=\\\"mainEntity\\\">\\r\\n<p><strong itemprop=\\\"name\\\">Quali sono gli hotel di Pesaro con offerte per bambini?<\\/strong><\\/p>\\r\\n<div itemscope=\\\"\\\" itemtype=\\\"https:\\/\\/schema.org\\/Answer\\\" itemprop=\\\"acceptedAnswer\\\">\\r\\n<p itemprop=\\\"text\\\"><a href=\\\"\\/hotel-bambini\\/milano-marittima-hotel-bambini.php\\\">In questo elenco<\\/a> trovi gli hotel di Pesaro dove i bambini soggiornano gratis.<\\/p>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n<div itemscope=\\\"\\\" itemtype=\\\"https:\\/\\/schema.org\\/Question\\\" itemprop=\\\"mainEntity\\\">\\r\\n<p><strong itemprop=\\\"name\\\">&Egrave; aperto Mirabilandia?<\\/strong><\\/p>\\r\\n<div itemscope=\\\"\\\" itemtype=\\\"https:\\/\\/schema.org\\/Answer\\\" itemprop=\\\"acceptedAnswer\\\">\\r\\n<p itemprop=\\\"text\\\">No, al momento tutti i parchi della Riviera Romagnola, compreso Mirabilandia, sono chiusi.<\\/p>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n<h3>La spiaggia di Pesaro<\\/h3>\\r\\n<p>4 km di spiagge moderne e attrezzate con alle spalle la pineta di Cervia e Pesaro. Tra quelli pi&agrave; famosi segnaliamo:<br \\/> - <strong>Paparazzi 242:<\\/strong> frequentato dai vip ha anche un ristorante direttamente sulla spiaggia,&nbsp;bar, gelateria, attrezzi per il fitness, area benessere. Molto frequentato all\'aperitivo. (Viale Pietro Mascagni, 242 - www.paparazzibeach.it)<br \\/> - <strong>Papeete Beach:<\\/strong> si distingue da tutti gli altri per lo stile degli arredi e per gli aperitivi lounge sulla spiaggia, rinomati in tutta Italia (via Traversa III Pineta, 281 - www.papeetebeach.com<br \\/> - <strong>Fantini Club:<\\/strong>&nbsp;villaggio vacanza per gli amanti dello sport e del wellness. Servizi di lusso, eventi mondani e relax (Lungomare G.Deledda, 182 - fantiniclub.com<\\/p>\\r\\n<h3>Discoteche e vita notturna<\\/h3>\\r\\n<p>La vita notturna inizia all\'ora dell\'aperitivo, in spiaggia e negli street bar del centro.Dopo cena si balla nelle pi&ugrave; famose discoteche:<\\/p>\\r\\n<p><strong>- Club Pineta:&nbsp;<\\/strong>&egrave; il simbolo del divertimento di Pesaro, un vero e proprio monumento, una discoteca raffinata e lussuosa, con selezione all\'ingresso. Frequentata da personaggi del mondo dello spettacolo.(via Romagna, 66 - 0544.994728 - www.pinetadisco.com)<br \\/> - <strong>Villa Papeete:<\\/strong> discoteca di prestigio, la location &egrave; una grande villa immersa in un parco di ulivi (via Argine Destro Savio &ndash; 335.1275444 - www.villapapeete.com).<\\/p>\\r\\n<h3><strong>Parchi Divertimento<\\/strong><\\/h3>\\r\\n<p>-&nbsp;<strong>La casa delle Farfalle<\\/strong> &egrave; un parco educativo interamente dedicato alle farfalle tropicali e provenienti da ogni parte del mondo che in un habitat ricostruito e fedele volano libere e si riproducono.<br \\/> Il parco ha anche una sezione dedicata agli insetti e organizza visite, giochi e laboratori per i bambini (via Jelenia Gora 6\\/d - 0544.995671)<br \\/> - <strong>Mirabilandia<\\/strong> &egrave; uno dei parchi divertimento pi&ugrave; grandi e famosi d&rsquo;Europa, con attrazioni uniche e mozzafiato, per grandi e giochi per i pi&ugrave; piccoli, spettacoli, ruota panoramica e zone ristoro.<br \\/> Informazioni turistiche su <a href=\\\"http:\\/\\/www.milanomarittima.it\\\" target=\\\"_blank\\\" rel=\\\"noopener\\\"><u>www.milanomarittima.it<\\/u><\\/a><\\/p>\\r\\n<p>&nbsp;<\\/p>\"}]', '5', '0', '13', '0', '0', '79', 'Hotel Pesaro', '2020-06-29', '2021-06-29', '1', '0', '67', '5', '24', '', '', '0', '0', '', '', '0', '', '', '', '0', '0', '0', '0', '0', '0', '9', '20.00', '250.00', '0', '', '2000-01-01 00:00:00', '2020-12-22 10:11:43', '0', '0');


- modificare opportunamente i campi:

	menu_macrolocalita_id (id_pesaro)
	menu_localita_id (0) 
	listing_macrolocalita_id (id_pesaro = 12)
	listing_localita_id (id_pesaro = 50 )
	localita_id_stradario (NULL)
	macrolocalita_id_stradario (NULL)




- questi sono gli hotel inseriti da Sacco


> SELECT * FROM tblHotel h WHERE h.id IN (1875,1883,1884,1890,1891,1892,1897,1898,1899,1900,1901,1902,1903,1904,1905,1906)


- gli devo cambiare localita_id in 50

> UPDATE tblHotel 
SET localita_id = 50
WHERE id IN (1875,1883,1884,1890,1891,1892,1897,1898,1899,1900,1901,1902,1903,1904,1905,1906)





- devo __creare la vetrina di pesaro__, altrimenti la pagina è vuota


la vetrina Pesaro esiste già ed ha ID = 73


ATTENZIONE GLI HOTEL DELLE VETRINE DEVONO ESSERE ATTIVI PER VEDERSI

UPDATE tblHotel 
SET attivo = 1
WHERE id IN (1875,1883,1884,1890,1891,1892,1897,1898,1899,1900,1901,1902,1903,1904,1905,1906)







__MENU IN ALTO__

incluso con header_menu.blade.php che richiama {!!Utility::getMenuLocalita($locale)!!}; aggiungere il campo ordering a tblMacrolocalita e ordinare per quello in mod da avere RR sempre in ultima posizione



> UPDATE tblMacrolocalita m
SET m.ordering = m.id

> UPDATE tblMacrolocalita m
SET m.ordering = 13 WHERE id = 11







_ copio la pagina hotel-1-una-stella/milano-marittima.php e la modifico per creare *hotel-1-una-stella/pesaro.php*




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





__MENU VERDE__


NON COMPARE




- query per copiare tutte le pagine di MiMa come fossero Pesaro

INSERT INTO `develop`.`tblCmsPagine` (`lang_id`, `attiva`, `uri`, `alternate_uri`, `template`, `seo_title`, `seo_description`, `h1`, `h2`, `descrizione_1`, `menu_macrolocalita_id`, `menu_localita_id`, `vetrina_id`, `evidenza_vetrina_id`, `banner_vetrina_id`, `macrolocalita_count`, `ancora`, `menu_dal`, `menu_al`, `menu_auto_annuale`, `listing_attivo`, `listing_count`, `listing_macrolocalita_id`, `listing_localita_id`, `localita_id_stradario`, `macrolocalita_id_stradario`, `listing_parolaChiave_id`, `listing_gruppo_servizi_id`, `listing_categorie`, `listing_coupon`, `listing_bambini_gratis`, `listing_whatsapp`, `listing_annuali`, `created_at`, `updated_at`, `listing_bonus_vacanze_2020`) 
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



INSERT INTO `develop`.`tblCmsPagine` (`lang_id`, `attiva`, `uri`, `alternate_uri`, `template`, `seo_title`, `seo_description`, `h1`, `h2`, `descrizione_1`, `menu_macrolocalita_id`, `menu_localita_id`, `vetrina_id`, `evidenza_vetrina_id`, `banner_vetrina_id`, `macrolocalita_count`, `ancora`, `menu_dal`, `menu_al`, `menu_auto_annuale`, `listing_attivo`, `listing_count`, `listing_macrolocalita_id`, `listing_localita_id`, `localita_id_stradario`, `macrolocalita_id_stradario`, `listing_parolaChiave_id`, `listing_gruppo_servizi_id`, `listing_categorie`, `listing_coupon`, `listing_bambini_gratis`, `listing_whatsapp`, `listing_annuali`, `created_at`, `updated_at`, `listing_bonus_vacanze_2020`) 
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
FROM tblCmsPagine p WHERE p.uri LIKE '%milano-marittima.php%' AND lang_id = 'en' AND attiva = 1 AND template != 'localita'


INSERT INTO `develop`.`tblCmsPagine` (`lang_id`, `attiva`, `uri`, `alternate_uri`, `template`, `seo_title`, `seo_description`, `h1`, `h2`, `descrizione_1`, `menu_macrolocalita_id`, `menu_localita_id`, `vetrina_id`, `evidenza_vetrina_id`, `banner_vetrina_id`, `macrolocalita_count`, `ancora`, `menu_dal`, `menu_al`, `menu_auto_annuale`, `listing_attivo`, `listing_count`, `listing_macrolocalita_id`, `listing_localita_id`, `localita_id_stradario`, `macrolocalita_id_stradario`, `listing_parolaChiave_id`, `listing_gruppo_servizi_id`, `listing_categorie`, `listing_coupon`, `listing_bambini_gratis`, `listing_whatsapp`, `listing_annuali`, `created_at`, `updated_at`, `listing_bonus_vacanze_2020`) 
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
FROM tblCmsPagine p WHERE p.uri LIKE '%milano-marittima.php%' AND lang_id = 'fr' AND attiva = 1 AND template != 'localita'


INSERT INTO `develop`.`tblCmsPagine` (`lang_id`, `attiva`, `uri`, `alternate_uri`, `template`, `seo_title`, `seo_description`, `h1`, `h2`, `descrizione_1`, `menu_macrolocalita_id`, `menu_localita_id`, `vetrina_id`, `evidenza_vetrina_id`, `banner_vetrina_id`, `macrolocalita_count`, `ancora`, `menu_dal`, `menu_al`, `menu_auto_annuale`, `listing_attivo`, `listing_count`, `listing_macrolocalita_id`, `listing_localita_id`, `localita_id_stradario`, `macrolocalita_id_stradario`, `listing_parolaChiave_id`, `listing_gruppo_servizi_id`, `listing_categorie`, `listing_coupon`, `listing_bambini_gratis`, `listing_whatsapp`, `listing_annuali`, `created_at`, `updated_at`, `listing_bonus_vacanze_2020`) 
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
FROM tblCmsPagine p WHERE p.uri LIKE '%milano-marittima.php%' AND lang_id = 'de' AND attiva = 1 AND template != 'localita'



- inserisco le pagine localita in lingua


INSERT INTO `develop`.`tblCmsPagine` (`lang_id`, `attiva`, `uri`, `alternate_uri`, `template`, `seo_title`, `seo_description`, `h1`, `h2`, `descrizione_1`, `menu_macrolocalita_id`, `menu_localita_id`, `vetrina_id`, `evidenza_vetrina_id`, `banner_vetrina_id`, `macrolocalita_count`, `ancora`, `menu_dal`, `menu_al`, `menu_auto_annuale`, `listing_attivo`, `listing_count`, `listing_macrolocalita_id`, `listing_localita_id`, `localita_id_stradario`, `macrolocalita_id_stradario`, `listing_parolaChiave_id`, `listing_gruppo_servizi_id`, `listing_categorie`, `listing_coupon`, `listing_bambini_gratis`, `listing_whatsapp`, `listing_annuali`, `created_at`, `updated_at`, `listing_bonus_vacanze_2020`) 
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
FROM tblCmsPagine p WHERE p.uri LIKE '%milano-marittima.php%' AND lang_id != 'it' AND attiva = 1 AND template = 'localita'





__Problemi__


- nel menu verde "Residence" e "Bed&Breackfast" devono essere nel menu categoria (vediamo tility::getMenuTematico())

- compare in Homepage (e linka al file nella colonna tblMacrolocalita.linked_file)

