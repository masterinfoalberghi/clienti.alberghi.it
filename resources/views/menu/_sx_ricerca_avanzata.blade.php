<?php if(!isset($nome_hotel)) $nome_hotel = ""; ?>
<div class="ricerca">
	<h3>Ricerca</h3>
	{!! Form::open(['url' => Utility::getUrlWithLang($locale,"/trova_hotel.php"), 'class' => 'form-wrapper cf']) !!}
	    {!! Form::text('nome_hotel',$nome_hotel,["placeholder"=> trans('labels.menu_cerca'), 'required']) !!}
	    <button type="submit"><img src="/images/cerca.png" /></button>
	{!! Form::close() !!}
	@if ($locale == 'it')
	<a class="ricercaAvanzata" href="{{Utility::getUrlWithLang($locale,"/ricerca_avanzata.php")}}">{{ trans('labels.menu_ric') }} &#8594;</a>
	@endif
</div>  
