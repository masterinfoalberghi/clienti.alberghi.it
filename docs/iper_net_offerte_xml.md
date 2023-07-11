**Struttura file XML delle offerte**

all'interno del tag <promotions> ogni offerta è dentro il tag <promotion>

<promotions>
<promotion>...</promotion>
<promotion>...</promotion>
<promotion>...</promotion>
<promotion>...</promotion>
<promotion>...</promotion>
<promotion>...</promotion>
<promotion>...</promotion>
<promotion>...</promotion>
</promotions>


Per ogni <promotion>



__vincolo sul numero massimo minimo di adulti e bambini per cui è prenotabile l'offerta__
<maxadults/>
<maxchildren/>
<minchildren>0</minchildren>
<minadults>1</minadults>


__lunghezza minima/massima del soggiorno per potere usufruire dell'offerta__
<maxlos>1</maxlos>
<minlos>1</minlos>



__intervalli di date in cui è possibile prenotare l'offerta__
<bookingperiods>
<bookingperiod>
<to>20170731</to>
<from>20161222</from>
</bookingperiod>
</bookingperiods>
> con queti campi determino se l'offerta che importo è attiva oppure no !!



__intervalli di date in cui si deve soggiornare per usufruire dell'offerta__
<stayperiods>
<stayperiod>
<to>20171231</to>
<from>20161222</from>
</stayperiod>
</stayperiods>
> valido dal al PRENDO SOLO IL PRIMO !!




__id: codice dell'offerta in iperbooking (univoco)__

<id>10377</id>

> lo utilizzo per non importare n volte la stessa offerta





__tipologia di offerta (Testuale, Sconto, Early Booking, Lunghi Soggiorni, Last Minute, Piano Famiglia)__

<type>Last Minute</type>

> Testuale, Lunghi Soggiorni: "Offerta"
> Early Booking: "Prenota prima"
> Last Minute: "Last Minute"

> Il piano famiglia ??
> Sconto ??
> Early Booking, Lunghi Soggiorni, Last Minute hanno una gestioen degli sconti più complessa della nostra; noi in "Prenota prima" abbiamo solo sconto tot% se prenoti entro
> loro posso avere

> Easly Booking/Last Minute/Lunghi Soggiorni
<config>
	<conditions>
		<condition>
			<days>20</days>
			<discount>5</discount>
			<discountcode>P</discountcode>
		</condition>
		<condition>
			<days>40</days>
			<discount>12</discount>
			<discountcode>P</discountcode>
		</condition>
	</conditions>
</config> 


> Possiamo "tradurre" questa cosa e metterla nelle note oppure nel testo ??



__sigle dei trattamenti per cui è attiva l'offerta (AI, FB, HB, BB, B)__

<boardtypes>
<boardtype>BB</boardtype>
</boardtypes>

> una o tutti
> c'è solo per Offerte e Last, non c'è in PrenotaPrima




__testi__

<descriptions>

<description>
<extended/>
<brief>
Non aspettare oltre, approfitta delle nostre offerte Last Minute! Risparmi il 20%!
</brief>
<language>IT</language>
<title>Offerta Last Minute</title>
</description>

<description>
<extended>Testo Tedesco</extended>
<brief>Testo Tedesco</brief>
<language>DE</language>
<title>Last minute Testo Tedesco</title>
</description>

<description>
<extended>Testo Francese</extended>
<brief>Testo Francese</brief>
<language>FR</language>
<title>Last minute Testo Francese</title>
</description>

<description>
<extended>Testo inglese</extended>
<brief>Testo inglese</brief>
<language>en</language>
<title>llast Minute Testo inglese</title>
</description>

</descriptions>


> per ogni lingua c'è: titolo,excerpt,descrizione
> l'excerpt lo generiamo noi .... però siccome ci può essere anche solo l'excerpt, in quel caso la prendo come testo







__campo config__

va trattato in modo diverso in base al tipo di offerta, perché HA CAMPI DIVERSI in base alla tipologia dell'offerta a cui appartiene











__CAMPI IGNORATI__
- roomtypes: elenco dei listini di iperbooking a cui è collegata l'offerta
- occupations: capacità delle camere per cui è valida l'offerta
- pictures: immagini dell'offerta (attualmente ne restituiamo una)
- arrivaldays: giorni in cui è permesso l'arrivo (mon, tue, weds, thur, fri, sat, sun)
- departuredays: giorni in cui è permesso la partenza



id_iper [UNIQUE]
processata [boolean]
minchildren
minadults
maxchildren
maxadults
minlos
maxlos
type
attiva [boolean]
valida_dal
valida_al
boardtypes [BB,HB,FB]
titolo_IT
descrizione_IT
titolo_EN
descrizione_EN
titolo_FR
descrizione_FR
titolo_DE
descrizione_DE
configurazione