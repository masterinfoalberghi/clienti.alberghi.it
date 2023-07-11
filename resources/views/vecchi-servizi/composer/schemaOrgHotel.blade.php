


{{-- https://developers.google.com/search/docs/data-types/faqpage --}}

{{-- 

- dati che provengono da altri composer

$cat_servizi : da 'App\Http\ViewComposers\ServiziComposer vedi app/Providers/ComposerServiceProvider.php

 --}}

@php
// Parcheggio tra i servizi_in_hotel
$parcheggio = [];
foreach ($cat_servizi['Servizi in hotel'] as $nome_servizio) 
	{
	if (stripos($nome_servizio,'parcheggio') !== FALSE) 
		{
		$parcheggio[] = str_replace('"', "'", $nome_servizio);
		} 
	}

// Servizi Ristorazione
$ristorazione = [];
if (!empty($cat_servizi['Ristorazione'])) 
	{
	$ristorazione = array_map("trim", str_replace('"', "'", $cat_servizi['Ristorazione']));
	} 


// Servizi in hotel
$servizi_hotel = [];
if (!empty($cat_servizi['Servizi in hotel'])) 
	{
	$servizi_hotel = array_map("trim", str_replace('"', "'", $cat_servizi['Servizi in hotel']));
	} 



// Servizi per Bambini
$servizi_bambini = [];
if (!empty($cat_servizi['Servizi per bambini'])) 
	{
	$servizi_bambini = array_map("trim", str_replace('"', "'", $cat_servizi['Servizi per bambini']));
	}

// Piscina
$servizi_piscina = [];
if (!empty($cat_servizi['servizi piscina'])) 
	{
	$servizi_piscina = array_map("trim", str_replace('"', "'", $cat_servizi['servizi piscina']));
	}


// Benessere
$servizi_benessere = [];
if (!empty($cat_servizi['servizi benessere'])) 
	{
	$servizi_benessere = array_map("trim", str_replace('"', "'", $cat_servizi['servizi benessere']));
	}

//dd($cat_servizi);

@endphp


<script type="application/ld+json">
{
	"@context": "http://schema.org",
	"@type": "FAQPage",
	"mainEntity": [
	{
		"@type": "Question",
		"name": "Qual è il periodo di apertura di {{$cliente->nome}}?",
		"acceptedAnswer": {
			"@type": "Answer",
			"text": "{!! implode("&nbsp;&nbsp;&nbsp;",$apertura) !!}"
		}
	},
	@if ($posizione != '')
		{
			"@type": "Question",
			"name": "Dove si trova {{$cliente->nome}}?",
			"acceptedAnswer": {
				"@type": "Answer",
				"text": "{!! $posizione !!}"
			}
		},
	@endif
	@if (!empty($parcheggio))
	{
		"@type": "Question",
		"name": "{{$cliente->nome}} dispone di Parcheggio?",
		"acceptedAnswer": {
			"@type": "Answer",
			"text": "{!! implode("&nbsp;&nbsp;&nbsp;",$parcheggio) !!}"
		}
	},
	@endif
	@if ($camere !== '')
		{
			"@type": "Question",
			"name": "Che tipo di camere sono disponibili presso {{$cliente->nome}}?",
			"acceptedAnswer": {
				"@type": "Answer",
				"text":  "{!! $camere !!}"
			}
		},
	@endif
	@if (!empty($ristorazione))
		{
			"@type": "Question",
			"name": "C'è il servizio di cucina con ristorante?",
			"acceptedAnswer": {
				"@type": "Answer",
				"text": "{!! implode(", ",$ristorazione) !!}"
			}
		},
	@endif
	@if (!empty($servizi_hotel))
	{
		"@type": "Question",
		"name": "Quali servizi sono disponibili presso {{$cliente->nome}}?",
		"acceptedAnswer": {
			"@type": "Answer",
			"text": "{!! implode(", ",$servizi_hotel) !!}"
		}
	},
	@endif
	@if (!empty($servizi_bambini))
	{
		"@type": "Question",
		"name": "Sono disponibili servizi per bambini presso {{$cliente->nome}}?",
		"acceptedAnswer": {
			"@type": "Answer",
			"text": "{!! implode(", ",$servizi_bambini) !!}"
		}
	},
	@endif

	@if (!empty($servizi_piscina))
	{
		"@type": "Question",
		"name": "{{$cliente->nome}} dispone di una Piscina?",
		"acceptedAnswer": {
			"@type": "Answer",
			"text": "{!! implode(", ",$servizi_piscina) !!}"
		}
	},
	@endif

	@if (!empty($servizi_benessere))
	{
		"@type": "Question",
		"name": "{{$cliente->nome}} dispone di un Centro benessere?",
		"acceptedAnswer": {
			"@type": "Answer",
			"text": "{!! implode(", ",$servizi_benessere) !!}"
		}
	},
	@endif

	@if ($spiaggia !== '')
	{
		"@type": "Question",
		"name": "{{$cliente->nome}} ha una convenzione spiaggia?",
		"acceptedAnswer": {
			"@type": "Answer",
			"text": "{!! $spiaggia !!}"
		}
	},
	@endif

	@if ($orari !== '')
	{
		"@type": "Question",
		"name": "Quali sono gli orari di check-in check-out di {{$cliente->nome}}?",
		"acceptedAnswer": {
			"@type": "Answer",
			"text": "{!! $orari !!}"
		}
	}
	@endif
	
	]
}
</script>