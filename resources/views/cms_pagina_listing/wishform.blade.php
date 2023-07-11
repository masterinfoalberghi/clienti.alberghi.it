<div class="titlewish">

	{{trans("listing.scrivi_qualcuno")}}
	<img id="close_wishlist" src="{{Utility::asset("images/delete.png")}}" width="20" height="20" />
	<p class="numerohotelwish">{!! trans("listing.hotel_numero") !!}</p>
	
</div>
	
<div class="actionWish">
	
	<?php if (!isset($id_to_send)) $id_to_send = ""; ?>
			
	{!! Form::open([ 'url' => '{{Utility::getUrlWithLang($locale, "/compare")}', 'style'=>'display:inline', 'onSubmit'=>'return window.whenChecked()', 'method' => 'get']) !!}
	{!! Form::hidden('ids_send_mail',$id_to_send,['id'=>'ids_send_mail_compare']) !!}
	
	@if (is_null($cms_pagina->ancora))
		{!! Form::hidden('title', 'precedente' ) !!}
	@else
		{!! Form::hidden('title', $cms_pagina->ancora ) !!}
	@endif
	
	{!! Form::submit(trans('listing.confronta'),["id"=>"CompareSubmit", "class"=>"button green"]) !!}
	{!! Form::close() !!}
	
	{!! Form::open([ 'url' => '{{Utility::getUrlWithLang($locale, "/wishlist")}}', 'style'=>'display:inline','onSubmit'=>'return window.whenChecked()']) !!}
	{!! Form::hidden('ids_send_mail',$id_to_send,['id'=>'ids_send_mail']) !!}
	{!! Form::hidden('wishlist',1) !!}
	{!! Form::hidden('no_execute_prefill_cookie',1) !!}
	{!! Form::submit(trans('listing.scrivi_email'),["id"=>"WishlistSubmit", "class"=>"button orange"]) !!}
	{!! Form::close() !!}
		
</div>