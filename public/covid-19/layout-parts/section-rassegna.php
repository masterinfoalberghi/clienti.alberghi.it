<?php 
require 'functions/rassegna.php'; 
?>
<section id="rassegna-stampa"  class="">
		<div class="container">
			<div class="row">
				<div class="col-sm-12">
					<header>
						<h2>Rassegna Stampa</h2>
					</header>
				</div>
			</div>
		</div>
		
		<div class="gallery-items">
<?php 

			/*rassegna_stampa([
				'data' 					=> '10 Aprile, 2020',
				'titolo' 				=> 'Hotel e coronavirus. L\'OMS pubblica le linee guida: non sarà semplice adeguarsi',
				'riassunto' 		=> 'Le linee guida provvisorie per le strutture ricettive redatte dall’OMS',
				'url_immagine' 	=> '',
				'fonte' 				=> 'newsrimini.it',
				'link_fonte' 		=> 'https://www.newsrimini.it/2020/04/hotel-e-coronavirus-loms-pubblica-le-linee-guida-non-sara-semplice-adeguarsi/'
			]);

			rassegna_stampa([
				'data' 					=> '14 Aprile, 2020',
				'titolo' 				=> 'Steward e pasti sotto l\'ombrellone: la Regione prova a immaginare la nuova spiaggia',
				'riassunto' 		=> 'Possibili scenari per l\'estate 2020 in Riviera Romagnola',
				'url_immagine' 	=> '',
				'fonte' 				=> 'newsrimini.it',
				'link_fonte' 		=> 'https://www.newsrimini.it/2020/04/steward-e-pasti-sotto-lombrellone-la-regione-prova-a-immaginare-la-nuova-spiaggia/'
			]);

			rassegna_stampa([
				'data' 					=> '15 Aprile, 2020',
				'titolo' 				=> 'Il sindaco di Rimini: "Salviamo l\'estate, ma non col plexiglass',
				'riassunto' 		=> 'Andrea Gnassi: "A rischio 4 miliardi. Inventiamoci gli Health Safety Hotel"',
				'url_immagine' 	=> '',
				'fonte' 				=> 'bologna.repubblica.it',
				'link_fonte' 		=> 'https://bologna.repubblica.it/cronaca/2020/04/15/news/il_sindaco_di_rimini_salviamo_l_estate_ma_non_col_plexiglass_-254057576/'
			]);

			rassegna_stampa([
				'data' 					=> '19 Aprile, 2020',
				'titolo' 				=> 'Coronavirus, spiagge. "Da giugno Riviera aperta',
				'riassunto' 		=> 'Renata Tosi, sindaco di Riccione: "Noi romagnoli accettiamo le sfide: numero chiuso e meno aggregazioni, ma sarà vacanza"',
				'url_immagine' 	=> '',
				'fonte' 				=> 'ilrestodelcarlino.it',
				'link_fonte' 		=> 'https://www.ilrestodelcarlino.it/cronaca/coronavirus-spiagge-1.5114916'
			]);

			rassegna_stampa([
				'data' 					=> '20 Aprile, 2020',
				'titolo' 				=> 'Covid-19, presidente Bonaccini: "Pronti a riaprire il 27 aprile, massima sicurezza e responsabilità"',
				'riassunto' 		=> 'Per il turismo, "il plexiglass in spiaggia non è la soluzione". Servizio in camera e ombrelloni distanziati',
				'url_immagine' 	=> '',
				'fonte' 				=> 'altarimini.it',
				'link_fonte' 		=> 'https://www.altarimini.it/News133958-covid-19-presidente-bonaccini-pronti-a-riaprire-il-27-aprile-con-la-massima-sicurezza.php'
			]);

			rassegna_stampa([
				'data' 					=> '22 Aprile, 2020',
				'titolo' 				=> 'Coronavirus, Rimini dirà addio alla "zona rossa" da lunedì',
				'riassunto' 		=> 'Stop ai divieti speciali già da lunedì 27 aprile',
				'url_immagine' 	=> '',
				'fonte' 				=> 'libertas.sm',
				'link_fonte' 		=> 'http://www.libertas.sm/rimini/notizie/2020/04/22/coronavirus-rimini-dir-addio-alla-zona-rossa-da-luned.html'
			]);

			rassegna_stampa([
				'data' 					=> '24 Aprile, 2020',
				'titolo' 				=> 'Pranzi sotto gli ombrelloni distanziati, moneta elettronica e sconti per i turisti: la "ricetta" per l\'estate 2020',
				'riassunto' 		=> 'Dalla Protezione civile in spiaggia all’immunità legale per il salvataggio, fino al rigido protocollo per tutto il personale',
				'url_immagine' 	=> '',
				'fonte' 				=> 'ravennatoday.it',
				'link_fonte' 		=> 'http://www.ravennatoday.it/economia/estate-2020-coronavirus-ricetta-bagnini-distanza-ombrelloni-spiaggia.html'
			]);

			rassegna_stampa([
				'data' 					=> '24 Aprile, 2020',
				'titolo' 				=> 'Rimini Open space: la Città si attrezza per la “Fase 2”',
				'riassunto' 		=> 'Le idee che il Comune di Rimini sta mettendo in campo dalla spiaggia, al centro, ai negozi di vicinato, ai parchi',
				'url_immagine' 	=> '',
				'fonte' 				=> 'comune.rimini.it',
				'link_fonte' 		=> 'https://www.comune.rimini.it/archivio-notizie/rimini-open-space-la-citta-si-attrezza-la-fase-2'
			]);	


			rassegna_stampa([
				'data' 					=> '26 Aprile, 2020',
				'titolo' 				=> 'Spot tv e campagne web per far ripartire il turismo',
				'riassunto' 		=> 'A giugno fa sapere la Regione partiranno campagne tv e web per promuovere le vacanze in regione, con testimonial d’eccezione.',
				'url_immagine' 	=> '',
				'fonte' 				=> 'newsrimini.it',
				'link_fonte' 		=> 'https://www.newsrimini.it/2020/04/spot-tv-e-campagne-web-per-far-ripartire-il-turismo/'
			]);
				

			rassegna_stampa([
				'data' 					=> '29 Aprile, 2020',
				'titolo' 				=> 'Fase 2 Coronavirus, a Rimini spiagge aperte dall\'alba a mezzanotte',
				'riassunto' 		=> 'Gnassi: "Saremo la prima meta protetta per trascorrere le vacanze estive, anche i parchi e gli alberghi saranno organizzati in sicurezza"',
				'url_immagine' 	=> '',
				'fonte' 				=> 'ilrestodelcarlino.it',
				'link_fonte' 		=> 'https://www.ilrestodelcarlino.it/rimini/cronaca/fase-2-coronavirus-spiagge-1.5127426'
			]);
					
			*/


			rassegna_stampa([
				'data' 					=> '1 Maggio, 2020',
				'titolo' 				=> 'Spiaggia e Coronavirus, tutte le regole per andare al mare in sicurezza',
				'riassunto' 		=> 'I bagnini riminesi presentano un protocollo alla Regione: "Il distanziamento personale è la prima misura che tutti dovranno osservare"',
				'url_immagine' 	=> '',
				'fonte' 				=> 'ilrestodelcarlino.it',
				'link_fonte' 		=> 'https://www.ilrestodelcarlino.it/rimini/cronaca/spiaggia-coronavirus-1.5131186'
			]);	


			rassegna_stampa([
				'data' 					=> '5 Maggio, 2020',
				'titolo' 				=> 'Coronavirus Riccione stagione balneare. "Via da giugno"',
				'riassunto' 		=> 'Amministrazione e categorie chiedono al governo una soluzione per il turismo: "Vogliamo aprire, ma ci servono aiuti"',
				'url_immagine' 	=> '',
				'fonte' 				=> 'ilrestodelcarlino.it',
				'link_fonte' 		=> 'https://www.ilrestodelcarlino.it/rimini/cronaca/riccione-apertura-stagione-1.5136709'
			]);	

			
			rassegna_stampa([
				'data' 					=> '8 Maggio, 2020',
				'titolo' 				=> 'Prove di estate. Parte #InSpiaggiaColSorriso',
				'riassunto' 		=> 'Annunciata qualche giorno fa la campagna 2020 di Piacere Spiaggia Rimini “In Spiaggia Col Sorriso”',
				'url_immagine' 	=> '',
				'fonte' 				=> 'newsrimini.it',
				'link_fonte' 		=> 'https://www.newsrimini.it/2020/05/prove-di-estate-parte-inspiaggiacolsorriso/'
			]);	

			
			rassegna_stampa([
				'data' 					=> '10 Maggio, 2020',
				'titolo' 				=> 'In spiaggia "liberi con qualche nuova abitudine". Il nuovo video Visit Rimini',
				'riassunto' 		=> 'Nuovo video della campagna di Visit Rimini, in attesa di certezze su date e modalità',
				'url_immagine' 	=> '',
				'fonte' 				=> 'newsrimini.it',
				'link_fonte' 		=> 'https://www.newsrimini.it/2020/05/in-spiaggia-liberi-con-qualche-nuova-abitudine-il-nuovo-video-visit-rimini/'
			]);	

			rassegna_stampa([
				'data' 					=> '10 Maggio, 2020',
				'titolo' 				=> 'Spiagge Emilia Romagna, 1,5 metri tra i lettini. Tutte le regole della Fase 2',
				'riassunto' 		=> 'Il protocollo della Regione con le misure di sicurezza per l’estate: niente concerti e balli, giro di vite per gli sport di gruppo',
				'url_immagine' 	=> '',
				'fonte' 				=> 'ilrestodelcarlino.it',
				'link_fonte' 		=> 'https://www.ilrestodelcarlino.it/cronaca/spiagge-emilia-romagna-regole-1.5142610'
			]);	

			rassegna_stampa([
				'data' 					=> '11 Maggio, 2020',
				'titolo' 				=> 'Parchi divertimento Romagna, quando riaprono',
				'riassunto' 		=> 'Dall’Acquario all’Italia in Miniatura, non hanno intenzione di saltare l’estate. Ecco l’anticipazione sui tempi e sulle misure anticontagio',
				'url_immagine' 	=> '',
				'fonte' 				=> 'ilrestodelcarlino.it',
				'link_fonte' 		=> 'https://www.ilrestodelcarlino.it/rimini/cosa%20fare/parchi-divertimento-quando-riaprono-1.5143646'
			]);	

			rassegna_stampa([
				'data' 					=> '12 Maggio, 2020',
				'titolo' 				=> 'Spiaggia e mare, l\'Emilia-Romagna riparte lunedì 18 maggio con nuove regole per un\'estate in sicurezza. Ecco le linee guida balneari',
				'riassunto' 		=> '',
				'url_immagine' 	=> '',
				'fonte' 				=> 'regione.emilia-romagna.it',
				'link_fonte' 		=> 'https://www.regione.emilia-romagna.it/notizie/attualita/spiaggia-e-mare-lemilia-romagna-riparte-lunedi-18-maggio-con-nuove-regole-per-unestate-in-sicurezza-ecco-le-linee-guida-balneari'
			]);

			rassegna_stampa([
				'data' 					=> '13 Maggio, 2020',
				'titolo' 				=> 'Alberghi, campeggi, residence, villaggi turistici e marina resort: l\'Emilia-Romagna pronta ad accogliere i propri ospiti in sicurezza',
				'riassunto' 		=> 'Ecco le linee guida per le strutture ricettive',
				'url_immagine' 	=> '',
				'fonte' 				=> 'regione.emilia-romagna.it',
				'link_fonte' 		=> 'https://www.regione.emilia-romagna.it/notizie/attualita/alberghi-campeggi-residence-villaggi-turistici-e-marina-resort-l-emilia-romagna-pronta-ad-accogliere-i-propri-ospiti-in-sicurezza'
			]);	

			
			rassegna_stampa([
				'data' 					=> '14 Maggio, 2020',
				'titolo' 				=> 'Bandiere Blu 2020, l\'Emilia Romagna conferma 7 spiagge eccellenti. Ecco quali',
				'riassunto' 		=> 'Si va da Comacchio a Bellaria, passando per Cervia e Cesenatico: la Riviera vanta un mare pulito e rispetto per l\'ambiente',
				'url_immagine' 	=> '',
				'fonte' 				=> 'ilrestodelcarlino.it',
				'link_fonte' 		=> 'https://www.ilrestodelcarlino.it/cronaca/bandiere-blu-2020-1.5148773'
			]);	


			rassegna_stampa([
				'data' 					=> '17 Maggio, 2020',
				'titolo' 				=> 'Romagnoli Dop con Paolo Cevoli. La seconda stagione alla griglia di partenza',
				'riassunto' 		=> 'Paolo Cevoli è tornato sul web per promuovere la Riviera (e il suo entroterra) nel post Covid-19',
				'url_immagine' 	=> '',
				'fonte' 				=> 'newsrimini.it',
				'link_fonte' 		=> 'https://www.newsrimini.it/2020/05/romagnoli-dop-con-paolo-cevoli-la-seconda-stagione-alla-griglia-di-partenza/'
			]);	
			

			rassegna_stampa([
				'data' 					=> '17 Maggio, 2020',
				'titolo' 				=> 'Tutte le attività che ripartono in Emilia-Romagna, protocolli di sicurezza condivisi',
				'riassunto' 		=> 'Nuova ordinanza del presidente Bonaccini: dal 18 maggio negozi, commercio, ristorazione, alberghi, servizi alla persona. Resta l\'obbligo della mascherina',
				'url_immagine' 	=> '',
				'fonte' 				=> 'regione.emilia-romagna.it',
				'link_fonte' 		=> 'https://www.regione.emilia-romagna.it/notizie/attualita/tutte-le-attivita-che-ripartono-in-emilia-romagna'
			]);

			rassegna_stampa([
				'data' 					=> '22 Maggio, 2020',
				'titolo' 				=> 'Dalla Regione nuovi protocolli operativi condivisi per riaprire lunedì 25 maggio parchi tematici e acquatici, giardini zoologici, luna-park',
				'riassunto' 		=> 'Nuove attività ripartono lunedì prossimo, 25 maggio',
				'url_immagine' 	=> '',
				'fonte' 				=> 'regione.emilia-romagna.it',
				'link_fonte' 		=> 'http://www.regione.emilia-romagna.it/notizie/attualita/dalla-regione-nuovi-protocolli-operativi-condivisi-per-riaprire-lunedi-25-maggio-parchi-tematici-e-acquatici-giardini-zoologici-luna-park-circhi'
			]);

			rassegna_stampa([
				'data' 					=> '22 Maggio, 2020',
				'titolo' 				=> 'Cosa si può fare o non fare in spiaggia: ecco l’ultima ordinanza della Regione Emilia Romagna',
				'riassunto' 		=> 'La Regione Emilia Romagna ha approvato l’ordinanza balneare 2020 con misure straordinare per il contenimento del coronavirus',
				'url_immagine' 	=> '',
				'fonte' 				=> 'chiamamicitta.it',
				'link_fonte' 		=> 'https://www.chiamamicitta.it/cosa-si-puo-fare-o-non-fare-in-spiaggia-ecco-lultima-ordinanza-della-regione-emilia-romagna/'
			]);

			rassegna_stampa([
				'data' 					=> '22 Maggio, 2020',
				'titolo' 				=> 'Ecco la nuova spiaggia di Rimini: la nuova ordinanza balneare comunale dilata spazi e tempi di utilizzo',
				'riassunto' 		=> ' Possibilità di rimanere in spiaggia con servizi aperti fino alle 22, più superficie tra un ombrellone e l’altro, delivery e cene sotto l’ombrellone',
				'url_immagine' 	=> '',
				'fonte' 				=> 'comune.rimini.it',
				'link_fonte' 		=> 'https://www.comune.rimini.it/archivio-notizie/ecco-la-nuova-spiaggia-di-rimini-la-nuova-ordinanza-balneare-comunale'
			]);


			rassegna_stampa([
				'data' 					=> '23 Maggio, 2020',
				'titolo' 				=> 'Primo weekend in spiaggia: si prende il sole sui teli e si passeggia sulla riva',
				'riassunto' 		=> 'Complice il bel tempo tanti riminesi - e non solo - sabato mattina hanno raggiunto il lungomare e la spiaggia',
				'url_immagine' 	=> '',
				'fonte' 				=> 'riminitoday.it',
				'link_fonte' 		=> 'https://www.riminitoday.it/cronaca/primo-weekend-in-spiaggia-si-prende-il-sole-sui-teli-e-si-passeggia-sulla-riva.html'
			]);

			rassegna_stampa([
				'data' 					=> '28 Maggio, 2020',
				'titolo' 				=> 'Dal 2 giugno torna la ruota panoramica al porto',
				'riassunto' 		=> 'Anche per questa attrazione, tra le ruote più grandi d’Europa con i suoi 55 metri d’altezza, saranno attivate le misure di sicurezza anti contagio',
				'url_immagine' 	=> '',
				'fonte' 				=> 'newsrimini.it',
				'link_fonte' 		=> 'https://www.newsrimini.it/2020/05/dal-2-giugno-torna-la-ruota-panoramica-al-porto/'
			]);


			rassegna_stampa([
				'data' 					=> '6 Giugno, 2020',
				'titolo' 				=> 'Uno spot in tv per le vacanze in Romagna. Gnassi: sicurezza, ospitalità e sorriso',
				'riassunto' 		=> '“Spiagge grandi come il nostro cuore, mare aperto come i nostri abbracci, terre sicure, come il nostro amore”',
				'url_immagine' 	=> '',
				'fonte' 				=> 'newsrimini.it',
				'link_fonte' 		=> 'https://www.newsrimini.it/2020/06/uno-spot-in-tv-per-promuovere-le-vacanze-in-romagna-gnassi-sicurezza-ospitalita-e-sorriso/'
			]);


			rassegna_stampa([
				'data' 					=> '8 Giugno, 2020',
				'titolo' 				=> 'A Riccione passerelle e corde in spiaggia libera, ma non serve prenotazione',
				'riassunto' 		=> 'A Riccione arrivano le indicazioni per le 17 spiagge libere aperte dal prossimo fine settimana',
				'url_immagine' 	=> '',
				'fonte' 				=> 'newsrimini.it',
				'link_fonte' 		=> 'https://www.newsrimini.it/2020/06/a-riccione-passerelle-e-corde-in-spiaggia-libera-ma-non-serve-prenotazione/'
			]);

			rassegna_stampa([
				'data' 					=> '9 Giugno, 2020',
				'titolo' 				=> 'Spiagge libere a Rimini, si parte con gli steward',
				'riassunto' 		=> 'Partirà dal 13 giugno l\'apertura "ufficiale" delle spiagge libere del comune di Rimini',
				'url_immagine' 	=> '',
				'fonte' 				=> 'newsrimini.it',
				'link_fonte' 		=> 'https://www.newsrimini.it/2020/06/spiagge-libere-a-rimini-si-parte-con-gli-steward/'
			]);


?>

	</div>
</section>
