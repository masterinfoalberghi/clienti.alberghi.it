<?php 

$count = 0;
$class="";

foreach ($valoriHomepage["macro"] as $ml):
	
	$codice 	= str_replace(" ", "-" , strtolower($ml["nome"]));
	$nome 		= $ml["nome"];
	$link 		= $ml["link"];
	$n_hotel 	= $ml["n_hotels"];
	
	?><article class="col-sm-6 col-xs-4 localita {{$codice}} {{$class}}" >
		<a href="{{$link}}">
	        <div class="padding">
		        <header>
			        <h1>{{$nome}}</h1>
		        </header>
		       	{{$n_hotel}} {{ trans('labels.hp_hotel') }}
	        </div>
		</a>
    </article><?php 

    $class == "" ? $class = "right" : $class = "";
			
	if ($count == 1 && count($template_homepage["items"]) > 0): 
		
		?><section id="megaspot" class="col-xs-12">
			
			<h2 class="hidden">{{trans("title.offerte")}}</h2>
			
			<div id="boxspot">
			<?php foreach($template_homepage["items"] as $th): ?>
				
				<article>
					<a href="{{Utility::getUrlWithLang($locale, $th["link"])}}" title="{{$th["titolo"]}}>">
					
						<figure>
							<img src="{{Utility::asset("images/spothome/310x150/" . $th["image"])}}" alt="{{$th["titolo"]}}" />
						</figure>
						
						<span class="boxspot_date">{{$th["sottotitolo"]}}</span>
						
						<header class="boxspot_title">
							<h3 style="margin-top:0;">{{$th["titolo"]}}</h3>
						</header>
						
						<span class="button_spot">{{trans("labels.scopri_offerta")}}</span>
						
					</a>
				</article>
				
			<?php endforeach; ?>
			</div>
			
		</section><?php 
			
	endif;
	$count++;


	
endforeach;
?>