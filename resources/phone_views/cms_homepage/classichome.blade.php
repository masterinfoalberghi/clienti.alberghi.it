<?php 
$class="";
foreach ($valoriHomepage["macro"] as $ml):
	
	$codice 	= str_replace(" ", "-" , strtolower($ml["nome"]));
	$nome 		= $ml["nome"];
	$link 		= $ml["link"];
	$n_hotel 	= $ml["n_hotels"];
	
	?><article class="col-xs-6 col-sm-4 localita {{$codice}} {{$class}}">
		<a href="{{$link}}">
	        <header>
		        <h1>{{$nome}}</h1>
	        </header>
	       	{{$n_hotel}} {{ trans('labels.hp_hotel') }}
		</a>
    </article><?php

    $class == "" ? $class = "right" : $class = "";
	
endforeach;
?>
    