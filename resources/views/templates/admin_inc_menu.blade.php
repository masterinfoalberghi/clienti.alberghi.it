@if (Auth::user()->hasRole(["root", "admin", "operatore"]))
	
    <ul id="main-menu" class="main-menu">

        <!-- add class "multiple-expanded" to allow multiple submenus to open -->
        <!-- class "auto-inherit-active-class" will automatically add "active" class for parent elements who are marked already with class "active" -->

        <li>
            <a href="{{ url("admin") }}">
                <i class="entypo-home"></i>
                <span class="title">Home</span>
            </a>
        </li>

        <li @if (in_array('utenti',Request::segments()) || in_array('gruppo-hotels',Request::segments()) ) class="opened" @endif>

            <a href="javascript:void()">
                <i class="entypo-users"></i>
                <span class="title">Utenti</span>
            </a>

            <ul>
                <li>
                    <a href="{{ url("admin/utenti") }}">
                        <span class="title">Elenco</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url("admin/utenti/create") }}">
                        <span class="title">Inserimento</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url("admin/gruppo-hotels") }}">
                        <span class="title">Gruppi di Hotel</span>
                    </a>
                </li>
            </ul>

        </li>

        <li @if (in_array('hotels',Request::segments())) class="opened" @endif>

            <a href="javascript:void()">
                <i class="glyphicon glyphicon-header"></i>
                <span class="title">Hotels</span>
            </a>

            <ul>

                <li>
                    <a href="{{ url("admin/hotels") }}">
                        <span class="title">Elenco</span>
                    </a>
                </li>

                <li>
                    <a href="{{ url("admin/hotels/create/romagna") }}">
                        <span class="title">Nuovo Hotel <b><span style="color: white">I</span><span style="color: #3A95DF">A</span> Romagna</b></span>
                    </a>
                </li>

                 <li>
                    <a href="{{ url("admin/hotels/create/italia") }}">
                        <span class="title">Nuovo Hotel <b><span style="color: white">I</span><span style="color: #3A95DF">A</span> Italia</b></span>
                    </a>
                </li>

            </ul>

        </li>
        
        <li @if (in_array('rating',Request::segments())) class="opened" @endif>

            <a  href="javascript:void()">
                <i class="glyphicon glyphicon-star"></i>
                <span class="title">Rating</span>
            </a>

            <ul>
                
                <li>
                    <a href="{{ url("admin/rating/exportRating") }}">
                        <span class="title">Esporta tutti gli hotel</span>
                    </a>
                </li>

                <li>
                    <a href="{{ url("admin/rating/exportNewRating") }}">
                        <span class="title">Esporta i nuovi hotel</span>
                    </a>
                </li>

                <li>
                    <a href="{{ url("admin/rating/importRating") }}">
                        <span class="title">Importa rating</span>
                    </a>
                </li>

                {{-- <li>
                    <a href="{{ url("admin/rating/calcola") }}">
                        <span class="title">Aggiorna rating</span>
                    </a>
                </li> --}}
            </ul>
        </li>

        <li>
            <a href="{{ url("admin/menus") }}">
                <i class="entypo-list"></i>
                <span class="title">Menu tematici</span>
            </a>
        </li>

        <li @if (in_array('menus',Request::segments()) || in_array('pages',Request::segments()) ) class="opened" @endif>
            <a href="javascript:void()">
                <i class="entypo-newspaper"></i>
                <span class="title">Pagine statiche</span>
            </a>
            <ul>

                <li>
                    <a href="{{ url("admin/pages") }}">
                        <span class="title">Pagine</span>
                    </a>
                </li>

                <li>
                    <a href="{{ url("admin/pages/create") }}">
                        <span class="title">Inserimento pagina</span>
                    </a>
                </li>

                

            </ul>
        </li>

        <li @if (in_array('menus',Request::segments()) || in_array('pages',Request::segments()) || in_array('listing',Request::segments()) ) class="opened" @endif>

            <a href="javascript:void()">
                <i class="entypo-newspaper"></i>
                <span class="title">Listing</span>
            </a>

            <ul>
                
                <li>
                    <a href="{{ url("admin/listing") }}">
                        <span class="title">Localita / Listing / Stradario</span>
                    </a>
                </li>

                <li>
                    <a href="{{ url("admin/listing/create") }}">
                        <span class="title">Nuova pagina listing</span>
                    </a>
                </li>

            </ul>

        </li>
        
        {{-- VETRINE --}}
        
        <li @if (in_array('vetrine-principali',Request::segments()) || in_array('vetrine-limitrofe',Request::segments()) || in_array('vetrine',Request::segments())) class="opened" @endif>
            <a href="javascript:void()">
                <i class="entypo-window"></i>
                <span class="title">Vetrine</span>
            </a>
            <ul>
                <li>
                    <a href="{{ url("admin/vetrine-principali") }}">
                        <span class="title">Principali</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url("admin/vetrine-limitrofe") }}">
                        <span class="title">Limitrofe</span>
                    </a>
                </li>
            </ul>
        </li>

        {{-- SERVIZI --}}
        
        <li @if (in_array('servizi',Request::segments())) class="opened" @endif>
            <a href="javascript:void()">
                <i class="entypo-tools"></i>
                <span class="title">Servizi</span>
            </a>
            <ul>
                <li>
                    <a href="{{ url("admin/servizi/create") }}">
                        <span class="title">Inserimento/modifica</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url("admin/servizi") }}">
                        <span class="title">Traduci</span>
                    </a>
                </li>
            </ul>

        </li>


        {{-- PUNTI DI INTERESSE --}}
        
        <li @if (in_array('poi',Request::segments())) class="opened" @endif>
            <a href="javascript:void()">
                <i class="entypo-info"></i>
                <span class="title">Punti di Interesse</span>
            </a>
            <ul>
                <li>
                    <a href="{{ url("admin/poi/create") }}">
                        <span class="title">Gestione POI/Località </span>
                    </a>
                </li>
                <li>
                    <a href="{{ url("admin/poi") }}">
                        <span class="title">Traduci</span>
                    </a>
                </li>
            </ul>

        </li>


        {{-- MAPPA RICERCA: PUNTI DI INTERESSE --}}
                
        <li>
            <a href="{{ url("admin/mappa-ricerca-poi") }}">
                <i class="entypo-map"></i>
                <span class="title">Mappa ricerca: POI</span>
            </a>
        </li>
        

        <li>
            <a href="{{ url("admin/motivazioni") }}">
                <i class="entypo-list"></i>
                <span class="title">Motivazioni</span>
            </a>
        </li>

        <!-- Parole chiave -->
        <li>
            <a href="{{ url("admin/parole-chiave") }}">
                <i class="entypo-key"></i>
                <span class="title">Parole Chiave</span>
            </a>
        </li>


        <!-- Punti di forza chiave -->
        <li>
            <a href="{{ url("admin/punti-forza-chiave") }}">
                <i class="entypo-share"></i>
                <span class="title">Punti di forza Chiave</span>
            </a>
        </li>


        {{-- Link alle newsletter --}}
        <li @if (in_array('newsletterLink',Request::segments())) class="opened" @endif>
            <a href="javascript:void()">
                <i class="entypo-newspaper"></i>
                <span class="title">Newsletter Link</span>
            </a>
            <ul>
                <li>
                    <a href="{{ url("admin/newsletterLink") }}">
                        <span class="title">Elenco</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url("admin/newsletterLink/create") }}">
                        <span class="title">Inserimento</span>
                    </a>
                </li>
            </ul>
        </li>


        <!-- Statistiche -->

        <li @if (in_array('stats',Request::segments())) class="opened" @endif>
            
			<a href="javascript:void()">
                <i class="entypo-chart-line"></i>
                <span class="title">Statistiche</span>
            </a>
			
             <ul>
                <li>
                    <a href="{{ url("admin/stats/index") }}">
                        <span class="title">Statistiche</span>
                    </a>
                </li>
                            
				<li>
                    <a href="{{ url("admin/stats/rapporto-mail") }}">
                        <span class="title">Rapporto Contatti</span>
                    </a>
                </li>

                <li>
                    <a href="{{ url("admin/stats/hotels/rating") }}">
                        <span class="title">Rating hotels</span>
                    </a>
                </li>
				
            </ul>
			
        </li>
		
		<!-- Nuove statistiche -->
    	<li @if (in_array('statsv2',Request::segments())) class="opened" @endif>
    		
    		<a href="#">
    			<i class="entypo-chart-line"></i>
    			<span class="title">Nuove Statistiche IA</span>
    		</a>
    		
    		<ul>
    			<li>
    				<a href="{{ url("admin/statsv2/offerte/generale") }}">
    					<span class="title">Evidenze <sup>TOP</sup></span>
    				</a>
                </li> 
            </ul>
        
    		
    	</li>
		
		
        	
			
		
        
        <!-- Form -->
               
        <li id="form_impersonifica_hotel">
            <form method="post" action="{{ url("admin/seleziona-hotel") }}" style="padding:20px 0 0 20px; width: 90%">
                {!! csrf_field() !!}
                <div class="input-group">
                    <input value="{{ Auth::user()->getUiEditingHotel() }}" class="form-control typeahead ricerca-hotel" id="ricerca-hotel" name="ui_editing_hotel" type="text" autocomplete="off" data-local="{{ implode(',', Utility::getUsersHotels()) }}">
                    <span class="input-group-addon">
                        <button type="submit" class="btn btn-default btn-xs" style="position: relative; top: 0px;">OK</button>
                    </span>
                </div>
            </form>
            <a></a>
        </li>
        
        @if (Auth::user()->getUiEditingHotelId())
            
      

            <li @if (in_array('whatsapp-non-rubirca',Request::segments())) class="opened blocco_whatsapp" @else class="blocco_whatsapp" @endif>
                <a href="{{ route('whatsapp-non-rubirca') }}">
                    <i class="icon-whatsapp"></i>
                    <span class="title">WhatsApp</span>
                </a>
            </li>



            <li @if (in_array('recensioni',Request::segments())) class="opened" @endif>
                <a href="/admin/recensioni">
                    <i class="entypo-star"></i>
                    <span class="title">Abilita le recensioni</span>
                </a>
            </li>

           


            <li @if (in_array('politiche-cancellazione',Request::segments())) class="opened" @endif>
                <a href="javascript:void()">
                    <i class="entypo-credit-card"></i>
                    <span class="title">Politiche di cancellazione</span>
                </a>
                <ul>
                    <li>
                        <a href="{{ url("admin/politiche-cancellazione") }}">
                            <span class="title">Elenco</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url("admin/politiche-cancellazione/create") }}">
                            <span class="title">Inserimento</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li @if (in_array('trattamenti',Request::segments())) class="opened" @endif>
                <a href="{{ url("admin/trattamenti") }}">
                    <i class="glyphicon glyphicon-cutlery"></i>
                    <span class="title">Trattamenti</span>
                </a>
            </li>

			<li @if (in_array('stats',Request::segments())) class="opened" @endif>
			   <a href="javascript:void()">
				   <i class="entypo-chart-line"></i>
				   <span class="title">Statistiche</span>
			   </a>
			   <ul>
				   <li>
					   <a href="{{ url("admin/stats/outbound-links") }}">
						   <i class="entypo-link"></i>
						   <span class="title">Visite al sito</span>
					   </a>
				   </li>
				   <li>
					   <a href="{{ url("admin/stats/hotel") }}">
						   <i class="entypo-eye"></i>
						   <span class="title">Visite alla scheda IA</span>
					   </a>
				   </li>        
				   <li>
					   <a href="{{ url("admin/stats/hotel-like") }}">
						   <i class="entypo-heart"></i>
						   <span class="title">Like</span>
					   </a>
				   </li>
			   </ul>
		   </li>

			<li @if (in_array('scheda-hotel',Request::segments())) class="opened" @endif>
	            <a href="{{ url("admin/scheda-hotel") }}"><i class="entypo-menu"></i><span class="title">Testo scheda e video</span></a>
	        </li>

            <li @if (in_array('google-poi',Request::segments())) class="opened" @endif>
	            <a href="{{ url("admin/google-poi") }}"><i class="entypo-info"></i><span class="title">Google POI</span></a>
	        </li>
		
            <h3>Schede aggiuntive</h3>
            
            <li @if (in_array('info-piscina',Request::segments())) class="opened" @endif>
                <a href="{{ url("admin/info-piscina") }}"><i class="entypo-air"></i><span class="title">Piscina</span></a>
            </li>

            <li @if (in_array('info-benessere',Request::segments())) class="opened" @endif>
                <a href="{{ url("admin/info-benessere") }}"><i class="entypo-water"></i><span class="title">Centro benessere</span></a>
            </li>

		    <h3>
                Listini @if(Auth::user()->getUiEditingHotelPriceList())<span style="float:right; margin-right:20px;" class="badge badge-danger badge-roundless right">disattivo per i clienti</span>@endif
            </h3>
            

            <li @if (in_array('listini-custom',Request::segments())) class="opened" @endif >
                <a href="javascript:void()" >
                    <i class="entypo-book-open"></i>
                    <span class="title">Listini personalizzati</span>
                </a>
                <ul>
                    <li>
                        <a href="{{ url("admin/listini-custom") }}">
                            <span class="title">Elenco</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url("admin/listini-custom/create") }}">
                            <span class="title">Inserimento</span>
                        </a>
                    </li>
                </ul>
            </li>   

            <li @if (in_array('listini-standard',Request::segments())) class="opened" @endif>
                <a href="{{ url("admin/listini-standard") }}"><i class="entypo-doc-text-inv"></i><span class="title">Listino standard</span></a>
            </li>

            <li @if (in_array('listini-minmax',Request::segments())) class="opened" @endif>
                <a href="{{ url("admin/listini-minmax") }}"><i class="glyphicon glyphicon-sort"></i><span class="title">Listino min/max</span></a>
            </li>
            
            <li>
                <a href="{{ url("admin/note-listino/") }}"><i class="entypo-comment"></i><span class="title">Note listino</span></a>
            </li>

       
		

		<h3>Gallery</h3>
		
		<li @if (in_array('immagini-gallery',Request::segments())) class="opened" @endif>
	            <a href="javascript:void()">
	                <i class="entypo-picture"></i>
	                <span class="title">Immagini gallery</span>
	            </a>
	            <ul>
	                <li>
	                    <a href="{{ url("admin/immagini-gallery") }}">
	                        <span class="title">Elenco</span>
	                    </a>
	                </li>
	                <li>
	                    <a href="{{ url("admin/immagini-gallery/create") }}">
	                        <span class="title">Inserimento</span>
	                    </a>
	                </li>
	            </ul>
	        </li>

			
			
		<h3>Servizi</h3>
			
        <li @if (in_array('servizi',Request::segments())) class="opened" @endif>
            <a href="{{ url("admin/servizi/associa-servizi") }}"><i class="entypo-flow-cascade"></i><span class="title">Associa i servizi</span></a>
        </li>

        {{-- <li @if (in_array('servizi-covid',Request::segments())) class="opened blocco_servizi_covid" @else class="blocco_servizi_covid" @endif>
            <a href="{{ route('servizi-covid') }}">
                <i class="entypo-plus-squared"></i>
                <span class="title">Covid-19</span>
            </a>
        </li> --}}
    
        <li @if (in_array('servizi-in-out',Request::segments())) class="opened blocco_servizi_inout" @else class="blocco_servizi_inout" @endif>
            <a href="{{ route('servizi-in-out') }}">
                <i class="entypo-switch"></i>
                <span class="title">Servizi CheckIn / CheckOut</span>
            </a>
        </li>

        <li @if (in_array('punti-forza',Request::segments())) class="opened" @endif>
            <a href="{{ url("admin/punti-forza/") }}"><i class="entypo-megaphone"></i><span class="title">I 9 Punti di Forza</span></a>
        </li>

		<h3>Offerte</h3>

		<li @if (in_array('offerte',Request::segments())) class="opened" @endif>
            <a href="javascript:void()">
                 <i class="entypo-alert"></i>
                <span class="title">Offerte</span>
            </a>
            <ul>
                <li>
                    <a href="{{ url("admin/offerte/create") }}">
                        <span class="title">Inserimento</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url("admin/offerte") }}">
                        <span class="title">Attive</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url("admin/offerte/archiviate") }}">
                        <span class="title">Archiviate</span>
                    </a>
                </li>
            </ul>
        </li>

        <li @if (in_array('bambini-gratis',Request::segments())) class="opened" @endif>
            <a href="javascript:void()">
                <i class="entypo-cc-by"></i>
                <span class="title">Bambini Gratis</span>
            </a>
            <ul>
                <li>
                    <a href="{{ url("admin/bambini-gratis") }}">
                        <span class="title">Elenco</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url("admin/bambini-gratis/create") }}">
                        <span class="title">Inserimento</span>
                    </a>
                </li>
            </ul>
        </li>
        
         <li @if (in_array('prenota-prima',Request::segments())) class="opened" @endif>
            <a href="javascript:void()">
                <i class="entypo-hourglass"></i>
                <span class="title">"Prenota Prima"</span>
            </a>
            <ul>
                <li>
                    <a href="{{ url("admin/prenota-prima/create") }}">
                        <span class="title">Inserimento</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url("admin/prenota-prima") }}">
                        <span class="title">Attivi</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url("admin/prenota-prima/archiviati") }}">
                        <span class="title">Archiviati</span>
                    </a>
                </li>
            </ul>
        </li>
        
         <li @if (in_array('last',Request::segments())) class="opened" @endif>
            <a href="javascript:void()">
                <i class="entypo-flight"></i>
                <span class="title">Last minute</span>
            </a>
            <ul>
                <li>
                    <a href="{{ url("admin/last/create") }}">
                        <span class="title">Inserimento</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url("admin/last") }}">
                        <span class="title">Attivi</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url("admin/last/archiviati") }}">
                        <span class="title">Archiviati</span>
                    </a>
                </li>
            </ul>
        </li>

		 <h3>Evidenze <sup>Top</sup></h3>

        <li @if (in_array('vetrine-offerte-top',Request::segments())) class="opened" @endif>
            <a href="javascript:void()">
                <i class="entypo-star"></i>
                <span class="title">Offerte</span>
            </a>
            <ul>
                <li>
                    <a href="{{ url("admin/vetrine-offerte-top/create") }}">
                        <span class="title">Inserimento</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url("admin/vetrine-offerte-top") }}">
                        <span class="title">Attivi</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url("admin/vetrine-offerte-top/archiviati") }}">
                        <span class="title">Archiviati</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url("admin/vetrine-offerte-top-hotel") }}">
                        <span class="title">Visibili al cliente</span>
                    </a>
                </li>
            </ul>
        </li>


        <li @if (in_array('vetrine-bg-top',Request::segments())) class="opened" @endif>
            <a href="javascript:void()">
                <i class="entypo-vkontakte"></i>
                <span class="title">Bambini Gratis</span>
            </a>
            <ul>
                <li>
                    <a href="{{ url("admin/vetrine-bg-top/create") }}">
                        <span class="title">Inserimento</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url("admin/vetrine-bg-top") }}">
                        <span class="title">Attivi</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url("admin/vetrine-bg-top/archiviati") }}">
                        <span class="title">Archiviati</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url("admin/vetrine-bg-top-hotel") }}">
                        <span class="title">Visibili al cliente</span>
                    </a>
                </li>
            </ul>
        </li>
        
        <li @if (in_array('vetrine-trattamento-top',Request::segments())) class="opened" @endif>
            <a href="javascript:void()">
                <i class="entypo-tumblr-circled"></i>
                <span class="title">Trattamento</span>
            </a>
            <ul>
                <li>
                    <a href="{{ url("admin/vetrine-trattamento-top/create") }}">
                        <span class="title">Inserimento</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url("admin/vetrine-trattamento-top") }}">
                        <span class="title">Attivi</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url("admin/vetrine-trattamento-top/archiviati") }}">
                        <span class="title">Archiviati</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url("admin/vetrine-trattamento-top-hotel") }}">
                        <span class="title">Visibili al cliente</span>
                    </a>
                </li>
            </ul>
        </li>


        <li @if (in_array('vetrine-servizi-top',Request::segments())) class="opened" @endif>
            <a href="javascript:void()">
                <i class="entypo-smashing"></i>
                <span class="title">Servizi</span>
            </a>
            <ul>
                <li>
                    <a href="{{ url("admin/vetrine-servizi-top/create") }}">
                        <span class="title">Inserimento</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url("admin/vetrine-servizi-top") }}">
                        <span class="title">Attivi</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url("admin/vetrine-servizi-top/archiviati") }}">
                        <span class="title">Archiviati</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url("admin/vetrine-servizi-top-hotel") }}">
                        <span class="title">Visibili al cliente</span>
                    </a>
                </li>
            </ul>
        </li>

        {{--  Link alla scheda Hotel impersonificato --}}
        <li class="has-sub root-level">
            <a href="https://www.info-alberghi.com/hotel.php?id={{Auth::user()->getUiEditingHotelId()}}" target="_blank"><i class="glyphicon glyphicon-header"></i><span class="badge badge-info badge-roundless">Scheda {{Auth::user()->getUiEditingHotel()}}</span></a>
        </li>
       
        @endif
    </ul>
	
		
@elseif (Auth::user()->hasRole("hotel"))

    <ul id="main-menu" class="main-menu">
        <!-- add class "multiple-expanded" to allow multiple submenus to open -->
        <!-- class "auto-inherit-active-class" will automatically add "active" class for parent elements who are marked already with class "active" -->
        <li>
            <a href="{{ url("admin") }}">
                <i class="entypo-home"></i>
                <span class="title">Home</span>
            </a>
        </li>

        

        <li @if (in_array('recensioni',Request::segments())) class="opened" @endif>
            <a href="/admin/recensioni">
                <i class="entypo-star"></i>
                <span class="title">Abilita le recensioni</span>
                
            </a>
        </li>

        <li @if (in_array('whatsapp-non-rubirca',Request::segments())) class="opened blocco_whatsapp" @else class="blocco_whatsapp" @endif>
            <a href="{{ route('whatsapp-non-rubirca') }}">
                <i class="icon-whatsapp"></i>
                <span class="title">WhatsApp</span>
            </a>
        </li>

        <li @if (in_array('stats',Request::segments())) class="opened" @endif>
            <a href="javascript:void()">
                <i class="entypo-chart-line"></i>
                <span class="title">Statistiche</span>
            </a>
            <ul>
				<li>
                    <a href="{{ url("admin/stats/outbound-links") }}">
                        <i class="entypo-link"></i>
                        <span class="title">Visite al sito</span>
                    </a>
                </li>
				<li>
                    <a href="{{ url("admin/stats/hotel") }}">
                        <i class="entypo-eye"></i>
                        <span class="title">Visite alla scheda IA</span>
                    </a>
                </li>        
                <li>
                    <a href="{{ url("admin/stats/hotel-like") }}">
                        <i class="entypo-heart"></i>
                        <span class="title">Like</span>
                    </a>
                </li>
            </ul>
        </li>
		
        <li @if (in_array('servizi-hotel',Request::segments())) class="opened" @endif>
            <a href="{{ route('vedi-servizi') }}">
                <i class="entypo-tools"></i>
                <span class="title">I tuoi Servizi</span>
            </a>
        </li>

        {{-- <li @if (in_array('servizi-covid',Request::segments())) class="opened blocco_servizi_covid" @else class="blocco_servizi_covid" @endif>
            <a href="{{ route('servizi-covid') }}">
                <i class="entypo-plus-squared"></i>
                <span class="title">Covid-19</span>
            </a>
        </li> --}}

        <li @if (in_array('servizi-in-out',Request::segments())) class="opened blocco_servizi_inout" @else class="blocco_servizi_inout" @endif>
            <a href="{{ route('servizi-in-out') }}">
                <i class="entypo-switch"></i>
                <span class="title">Servizi CheckIn / CheckOut</span>
            </a>
        </li>

        <li @if (in_array('punti-forza',Request::segments())) class="opened" @endif>
            <a href="{{ url("admin/punti-forza/") }}">
                <i class="entypo-megaphone"></i>
                <span class="title">I 9 Punti di Forza</span>
            </a>
        </li>
        

        @if(Auth::user()->hotel->hide_price_list == false)

            <li @if (in_array('listini-custom',Request::segments())) class="opened" @endif>
                <a href="javascript:void()">
                    <i class="entypo-book-open"></i>
                    <span class="title">Listini personalizzati</span>
                </a>
                <ul>
                    <li>
                        <a href="{{ url("admin/listini-custom") }}">
                            <span class="title">Elenco</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url("admin/listini-custom/create") }}">
                            <span class="title">Inserimento</span>
                        </a>
                    </li>
                </ul>
            </li>
        
            <li @if (in_array('listini-standard',Request::segments())) class="opened" @endif>
                <a href="{{ url("admin/listini-standard") }}"><i class="entypo-doc-text-inv"></i><span class="title">Listino standard</span></a>
            </li>

            <li @if (in_array('listini-minmax',Request::segments())) class="opened" @endif>
                <a href="{{ url("admin/listini-minmax") }}"><i class="glyphicon glyphicon-sort"></i><span class="title">Listino min/max</span></a>
            </li>
                    
            <li>
                <a href="{{ url("admin/note-listino/") }}"><i class="entypo-comment"></i><span class="title">Note listino</span></a>
            </li>
        
        @endif
		
        <li @if (in_array('immagini-gallery',Request::segments())) class="opened" @endif>

            <a href="javascript:void()">
                <i class="entypo-picture"></i>
                <span class="title">Immagini gallery</span>
            </a>

            <ul>
                <li>
                    <a href="{{ url("admin/immagini-gallery-titoli") }}">
                        <span class="title">Gestisci titoli</span>
                    </a>
                </li>
            </ul>

        </li>

        <li @if (in_array('politiche-cancellazione',Request::segments())) class="opened" @endif>

            <a href="javascript:void()">
                <i class="entypo-credit-card"></i>
                <span class="title">Politiche cancellazione</span>
            </a>

            <ul>
                <li>
                    <a href="{{ url("admin/caparra") }}">
                        <span class="title">Elenco</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url("admin/caparra/create") }}">
                        <span class="title">Inserimento</span>
                    </a>
                </li>
            </ul>

        </li>

        {{-- <li @if (in_array('trattamenti',Request::segments())) class="opened" @endif>
            <a href="{{ url("admin/trattamenti") }}">
                <i class="glyphicon glyphicon-cutlery"></i>
                <span class="title">Trattamenti</span>
            </a>
        </li> --}}

        <h3>Offerte</h3>

        <li @if (in_array('bambini-gratis',Request::segments())) class="opened" @endif>
            <a href="javascript:void()">
                <i class="entypo-cc-by"></i>
                <span class="title">Offerte Bambini Gratis</span>
            </a>
            <ul>
                <li>
                    <a href="{{ url("admin/bambini-gratis") }}">
                        <span class="title">Elenco</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url("admin/bambini-gratis/create") }}">
                        <span class="title">Inserimento</span>
                    </a>
                </li>
            </ul>
        </li>

        <li @if (in_array('offerte',Request::segments())) class="opened" @endif>
            <a href="javascript:void()">
                 <i class="entypo-alert"></i>
                <span class="title">Offerte</span>
            </a>
            <ul>
                <li>
                    <a href="{{ url("admin/offerte/create") }}">
                        <span class="title">Inserimento</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url("admin/offerte") }}">
                        <span class="title">Attive</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url("admin/offerte/archiviate") }}">
                        <span class="title">Archiviate</span>
                    </a>
                </li>
            </ul>
        </li>

        <li @if (in_array('last',Request::segments())) class="opened" @endif>
            <a href="javascript:void()">
                <i class="entypo-flight"></i>
                <span class="title">Last minute</span>
            </a>
            <ul>
                <li>
                    <a href="{{ url("admin/last/create") }}">
                        <span class="title">Inserimento</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url("admin/last") }}">
                        <span class="title">Attivi</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url("admin/last/archiviati") }}">
                        <span class="title">Archiviati</span>
                    </a>
                </li>
            </ul>
        </li>

        <li @if (in_array('prenota-prima',Request::segments())) class="opened" @endif>
            <a href="javascript:void()">
                <i class="entypo-hourglass"></i>
                <span class="title">Offerte "Prenota Prima"</span>
            </a>
            <ul>
                <li>
                    <a href="{{ url("admin/prenota-prima/create") }}">
                        <span class="title">Inserimento</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url("admin/prenota-prima") }}">
                        <span class="title">Attivi</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url("admin/prenota-prima/archiviati") }}">
                        <span class="title">Archiviati</span>
                    </a>
                </li>
            </ul>
        </li>
        
        <h3>Evidenze <sup>Top</sup></h3>

        <li>
            <a href="{{ url("admin/vetrine-offerte-top-hotel") }}">
                <span class="title">Offerte</span>
            </a>
        </li>

        <li>
            <a href="{{ url("admin/vetrine-bg-top-hotel") }}">
                <span class="title">Bambini Gratis</span>
            </a>
        </li>

        <li>
            <a href="{{ url("admin/vetrine-trattamento-top-hotel") }}">
                <span class="title">Trattamento</span>
            </a>
        </li>

        <li>
            <a href="{{ url("admin/vetrine-servizi-top-hotel") }}">
                <span class="title">Servizi</span>
            </a>
        </li>

        <li class="has-sub root-level">
            <a href="https://www.info-alberghi.com/hotel.php?id={{Auth::user()->hotel_id}}" target="_blank"><i class="glyphicon glyphicon-header"></i><span class="badge badge-info badge-roundless">Vai alla tua scheda hotel sul sito</span></a>
        </li>
   
    </ul>

@elseif (Auth::user()->hasRole("commerciale"))
	
    <ul id="main-menu" class="main-menu">
        <!-- add class "multiple-expanded" to allow multiple submenus to open -->
        <!-- class "auto-inherit-active-class" will automatically add "active" class for parent elements who are marked already with class "active" -->
        <li>
            <a href="{{ url("admin") }}">
                <i class="entypo-home"></i>
                <span class="title">Home</span>
            </a>
        </li>

        <!-- Statistiche -->
        <li @if (in_array('stats',Request::segments())) class="opened" @endif>
            
			      <a href="javascript:void()">
                <i class="entypo-chart-line"></i>
                <span class="title">Statistiche</span>
            </a>
			
            <ul>
                <li>
                    <a href="{{ url("admin/stats/index") }}">
                        <span class="title">Statistiche</span>
                    </a>
                </li>
                            
				        <li>
                    <a href="{{ url("admin/stats/rapporto-mail") }}">
                        <span class="title">Rapporto Contatti</span>
                    </a>
                </li>
				
            </ul>
			
        </li>

        <li @if (in_array('statsv2',Request::segments())) class="opened" @endif>
            
            <a href="#">
                <i class="entypo-chart-line"></i>
                <span class="title">Nuove Statistiche IA</span>
            </a>
            
            <ul>
                <li>
                    <a href="{{ url("admin/statsv2/offerte/generale") }}">
                        <span class="title">Evidenze <sup>TOP</sup></span>
                    </a>
                </li> 
            </ul>
            
        </li>
        
        {{-- anche il commerciale può ricerca l'hotel SOLO per taggane le foto --}}
        <li id="form_impersonifica_hotel">
            <form method="post" action="{{ url("admin/commerciale-seleziona-hotel") }}" style="padding:20px 0 0 20px; width: 90%">
                {!! csrf_field() !!}
                <div class="input-group">
                    <input value="{{ Auth::user()->getUiEditingHotel() }}" class="form-control typeahead ricerca-hotel" id="ricerca-hotel" name="ui_editing_hotel" type="text" autocomplete="off" data-local="{{ implode(',', Utility::getUsersHotels()) }}">
                    <span class="input-group-addon">
                        <button type="submit" class="btn btn-default btn-xs" style="position: relative; top: 0px;">OK</button>
                    </span>
                </div>
            </form>
            <a></a>
        </li>

        {{--  se il commerciale impersonifica un hotel --}}
        @if (Auth::user()->getCommercialeUiEditingHotelId())

            <!-- Tag delle foto -->
            <li>
                <a href="{{ url("admin/commerciale-immagini-gallery-titoli") }}">
                    <i class="entypo-picture"></i>
                    <span class="title">Gestisci titoli</span>
                </a>
            </li>

        @endif

    </ul>


	
@endif


