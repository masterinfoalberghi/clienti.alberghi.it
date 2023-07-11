# mi metti il random su ogni listing? non più con i pesi

- modificato CmsPagineController@getListing() - riga num. 1359

- modificato Hotel@scopeListingOrderBy() - riga num. 3146


cercare "@Lucio: mi metti il random su ogni listing? non più con i pesi."



__Problema: Paginate random records__

$products = Product::all()->orderBy(DB::raw('RAND()'));
$products->paginate(4);
$products->setPath('products');

Above will ends in duplicate records, because of random order. How can I persist the $products object so that, when a new page request made, it should filter though same/fixed random record set ?

Whe you dive into the documentation of mysql and search for the RAND() functionality you will see you can use a "seed".

By using a seed you will always get the same results that are randomised.

Example:

$products = Product

    ::all()

    ->orderBy(DB::raw('RAND(1234)'))

    ->paginate(4);

You can generate your own seed and store in in a session or something to remember it.


## Però come funziona la paginazione su IA ?


- inizialmente si trovano i clienti


	$clienti = $this->getListing($cms_pagina, $locale, $order, $page, $filter);

 	- qui vengono ordinati ( adesso RAND() )

 	- e qui c'è la cache che NON FA LA QUERY TUTTE LE VOLTE 

 	if (!$clienti = Utility::activeCache($key, "Listing " . $paginate . " " . $cms_pagina->id. " " . $locale. " " .$order_by)..)


- DOPO, se sono in un URL da paginazione, applico la paginazione
	
	if (strpos($cms_pagina->uri, 'italia/hotel_riviera_romagnola') !== false || strpos($cms_pagina->uri, 'riviera-romagnola') !== false )	
		{

		// this basically gets the request's page variable... or defaults to 1
    $page = Paginator::resolveCurrentPage('page') ?: 1;

    $items_per_page = 50;

    // Assume 15 items per page... so start index to slice our array
    $startIndex = ($page - 1) * $items_per_page;

    // Length aware paginator needs a total count of items... to paginate properly
    $total = $clienti->count();

    // Eliminate the non relevant items by slicing the array to page content...
    $results = $clienti->slice($startIndex, $items_per_page);

    $clienti =  new LengthAwarePaginator($results, $total, $items_per_page, $page, [
        'path' => Paginator::resolveCurrentPath(),
        'pageName' => 'page',
    ]);

		}


	__QUINDI__ siccome i clienti sono messi in cache, **il random viene fatto la prima volta** e successivamente la paginazione viene fatta sullo stesso result set.

	
	- provo a verificarlo con un log


	dopo 

	clienti = $this->_getClientiListing($cms_pagina, $locale , $order_by, $filter, $terms); (:1408)

	Log::emergency("\n".'---> Ho fatto la query per i clienti <---'."\n\n");








__Problema: Evidenze non contate come offerte__


sono le pagine listing con listing_parolaChiave_id

il valore slavato nel DB è listing_count

"listing_count" => $n_hotel,

$n_hotel = $valori_listing["clienti_count"];





dopo aver trovato i clienti (elenco che ha anche i VOT) 

> $clientiPerConteggio = $this->getListingCount($cms_pagina, $locale);

e 

> $valori_listing["clienti_count"] = $clientiPerConteggio->count();


qui secondo me c'è l'errore











