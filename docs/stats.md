

__Tabelle__

Archive: sono le tabelle che teniamo per 36 mesi 
Stat: sono giornaliere
Read: sono quelle con i dati aggregati




# Generali Hotel, Media click per categoria

devo aggiungere le medie per

- mail (scheda, multipla)
- whatsapp
- telefonate


> admin/stats/hotels  



# nuove stats

*Evidenze*

> admin/statsv2/offerte/generale



tblStatsVotReadV2: rappresenta il dato aggregato dei click delle vetrineOfferteTOP (VOT) raggruppate per 'desktop','tablet','phone'

Esempio:
$tblStatsVotReadV2 = array(
	array(
		"created_at" => "2018-09-11",
		"pagina_id" => 173,
		"hotel_id" => 1233,
		"visits" => 1,
		"device" => "tablet",
	),
	array(
		"created_at" => "2018-09-11",
		"pagina_id" => 173,
		"hotel_id" => 1086,
		"visits" => 1,
		"device" => "phone",
	),
	array(
		"created_at" => "2018-09-11",
		"pagina_id" => 173,
		"hotel_id" => 1233,
		"visits" => 2,
		"device" => "phone",
	),
);

nella tabella tblStatsVot ho infatti queste righe in quella data:

"431127"	"173"	"1233"	"https://www.info-alberghi.com/week-end/rimini-week-end.php"	"Mozilla/5.0 (Linux; Android 8.0.0; BLA-L09 Build/HUAWEIBLA-L09S) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.91 Mobile Safari/537.36"	"37.159.91.184"	"2018-09-11 13:04:21"	"2018-09-11 13:04:21"
"431161"	"173"	"1233"	"https://www.info-alberghi.com/week-end/rimini-week-end.php"	"Mozilla/5.0 (iPad; CPU OS 11_4_1 like Mac OS X) AppleWebKit/604.1.34 (KHTML, like Gecko) GSA/57.0.209471814 Mobile/15G77 Safari/604.1"	"151.65.1.30"	"2018-09-11 19:19:56"	"2018-09-11 19:19:56"
"431188"	"173"	"1086"	"https://www.info-alberghi.com/week-end/rimini-week-end.php"	"Mozilla/5.0 (Linux; Android 5.1; HUAWEI CUN-L21 Build/HUAWEICUN-L21) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.92 Mobile Safari/537.36"	"151.34.54.96"	"2018-09-11 23:22:00"	"2018-09-11 23:22:00"
"431189"	"173"	"1233"	"https://www.info-alberghi.com/week-end/rimini-week-end.php"	"Mozilla/5.0 (Linux; Android 5.1; HUAWEI CUN-L21 Build/HUAWEICUN-L21) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.92 Mobile Safari/537.36"	"151.34.54.96"	"2018-09-11 23:23:55"	"2018-09-11 23:23:55"





*Per calcolare il costo*

la coppia hotel_id, pagia_id mi identifica un'evidenza univoca
ogni riga è una evidenza cliccata almeno una volta (per essere nella tabella)


1) dal 1/6 al 30/8 la pagina week-end/week-end-rimini.php ha avuto 2084 evidenze che hanno generato 44,954 click ($Anno_ClickTotali->conteggio)

2) dal CRM devo trovare il costo di tutte le evidenze su questa pagina in questo periodo 




tabella tblStatsVot

pagina_id = 606 and `created_at` >= '2018-06-01 11:56:39' AND `created_at` <= '2018-08-30 11:56:39'



Le tabelle delle statistiche contengono troppi record di bot e/o con regerer vuoto che inficiano il risultato finale: ad esempio da giugno a agosto la VOT SULLA pagina pasqua Rimini (che non è online risulta generare 1521 click !!!!)


se vado nella tabella statsVotArchive e filtro per 

SELECT count(*)
FROM tblStatsVotArchive
WHERE pagina_id = 606 AND `created_at` >= '2018-06-01 11:56:39' AND `created_at` <= '2018-08-30 11:56:39' AND useragent LIKE'%bot%'

trovo 1083 righe



SELECT count(*)
FROM tblStatsVotArchive
WHERE pagina_id = 606 AND `created_at` >= '2018-06-01 11:56:39' AND `created_at` <= '2018-08-30 11:56:39' AND useragent = ''

trovo 460 righe





1) devo modificare lo script che crea le archive per evitare le richieste dei bot

2) per le read e le readv2 devo mettere i filtri
  > AND useragent != ''
  > AND useragent NOT LIKE'%bot%'



*Importazione costi dal crm*

questa è la vista da costruire sul crm per importare i dati


create or replace view v_evidenze_for_export_to_ia as
SELECT `em`.`id_mese` AS `id_mese`,`em`.`id_hotel` AS `id_hotel`,`e`.`id_tipo_evidenza` AS `id_tipo_evidenza`,`tem`.`costo` AS `costo`,`te`.`id_macro` AS `id_macro`,`te`.`nome` AS `nome`,`te`.`macrotipologia` AS `macrotipologia`
FROM (((`ev_evidenze_mese` `em`
JOIN `ev_evidenza` `e` ON((`e`.`id` = `em`.`id_evidenza`)))
JOIN `ev_tipo_evidenza_mese` `tem` ON((`tem`.`id_tipo_evidenza` = `e`.`id_tipo_evidenza` and tem.id_mese = em.id_mese)))
JOIN `ev_tipo_evidenza` `te` ON((`te`.`id` = `tem`.`id_tipo_evidenza`)))
-- WHERE (`te`.`id_macro` > 0)
ORDER BY id_macro, nome, macrotipologia


con le nuove tabelle diventa


create or replace view v_evidenze_for_export_to_ia as
SELECT `em`.`mese_id` AS `id_mese`,`em`.`cliente_id` AS `id_hotel`,`e`.`tipoevidenza_id` AS `id_tipo_evidenza`,`tem`.`costo` AS `costo`,`te`.`macrolocalita_id` AS `id_macro`,`te`.`nome` AS `nome`,`te`.`macrotipologia` AS `macrotipologia`
FROM (((`tblEVEvidenzeMesi` `em`
JOIN `tblEVEvidenze` `e` ON((`e`.`id` = `em`.`evidenza_id`)))
JOIN `tblEVTipiEvidenzeMesi` `tem` ON((`tem`.`tipoevidenza_id` = `e`.`tipoevidenza_id` and tem.mese_id = em.mese_id)))
JOIN `tblEVTipiEvidenze` `te` ON((`te`.`id` = `tem`.`tipoevidenza_id`)))
-- WHERE (`te`.`macrolocalita_id` > 0)
ORDER BY macrolocalita_id, nome, macrotipologia




-- devo importare le statsArchive da IA perché in locale le ho solo fino a Maggio e quindi il periodo estivo è vuoto (sia la Read che le Read2)

-- le ho importare quindi rilancio gli script di regen




-- importo la vista del crm: **per il momento** la esporto manualmente come sql e la importo manualmente nel DB di IA

1. Sul DB del crm creao una tabella dalla vista

>create table evidenze_for_export_to_ia
>as select * from v_evidenze_for_export_to_ia

2. la esporto e la importo su IA



**ATTENZIONE:** __Ogni anno__ bisogna importare la nuova tabella e rinominare quella vecchia come _anno_vecchio (ad esempio nel 2019 la rinomino evidenze_for_export_to_ia_2018 e importo la evidenze_for_export_to_ia del 2019) 




-- sul crm ho 39 nomi di tipi di evidenze che mi servono per capire quale pagina di IA agganciare, devo predenre tutte le pagine di IA con delle evidenze ed associare questi tipi; quindi nell'admin della pagina metterò una select box con tutte le categorie e le pagine con delle evidenze saranno associate a questa categoria per saper a quale riga dei costi mi devo riferire nella tabella delle evidenze





Devo rigenerare le tabelle Archive perché contengono dati sporchi e ripetuti


create table tblStatsVotArchive_Luigi as
select distinct pagina_id,hotel_id,referer,useragent,created_at
from tblStatsVotArchive
where useragent not like '%bot%' and useragent != '' and useragent not like '%craw%'