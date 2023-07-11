<?php

// visualizzo le info sul coupon
// @parameters : $coupon (collection di coupon)
//               $titolo (header titolo oppure '')

?>

@if ($coupon->count())
	
	<?php
    $msg = $titolo;
    $coupon = $coupon->first();
  	?>
	
	<section id="coupon">
		
		<header>  	
		  	<hgroup>
			  	<a name="coupon"></a>
			  	<h2>{{trans("title.coupon")}} {{ trans('hotel.buono_sconto_2') }}: <b>{{$coupon->disponibile()}} COUPON {{ trans('hotel.buono_sconto_dispo') }}</b></h2>
		  	</hgroup>
		</header>
		
		<article  class="box-coupon">
			
			<header>
				<h4 class="box-coupon-title hidden">{{trans("title.coupon")}} {{ trans('hotel.buono_sconto_2') }}: <b>{{$coupon->disponibile()}} COUPON {{ trans('hotel.buono_sconto_dispo') }}</b><</h4>
			</header>
			
			<div class="box-coupon-content">
			
			<div class="col-sm-5 box-coupon-price box-coupon-content" >
	
				
					
					<div class="box-coupon-price-title padding-bottom">
						{{ trans('hotel.buono_sconto_1') }}<br/>
						{{ trans('hotel.buono_sconto_2') }}
					</div>
					
					<div class="box-coupon-price-value">
						{{$coupon->valore}} &euro;<br/><br/>
					</div>
					
			
			
			</div>
			
			<div class="col-sm-7 box-coupon-content content-scheda">
				<div class="box-coupon-content-left">
							
					{{ trans('hotel.buono_sconto_leggere_1') }} {{ trans('hotel.buono_sconto_leggere_2') }} {{ trans('hotel.buono_sconto_leggere_3') }}<br/> 
					{{ trans('hotel.buono_sconto_leggere_4') }}<br />
					
					<ul>
						<li>{{ trans('hotel.coupon_valido_dal') }} <b>{{Utility::getLocalDate($coupon->periodo_dal, '%e %B %Y')}}</b> {{ trans('hotel.coupon_valido_al') }} <b>{{Utility::getLocalDate($coupon->periodo_al, '%e %B %Y')}}</b></li>
						<li>{{ trans('hotel.coupon_valido_per_gg') }} <b>{{$coupon->durata_min}}</b></li>
						<li>{{ trans('hotel.coupon_min_persone') }} <b>{{$coupon->adulti_min}}</b></li>
					</ul>
					
					<div class="box-coupon-content-left-send">		
					@if ($errors->any() && Session::has('validazione') && (Session::get('validazione') == 'coupon'))
						@include('errors')
					@endif
				
					{!!Form::open(['url' => Utility::getUrlWithLang($locale, '/scaricaCoupon')]) !!}
						{!! Form::text('email_coupon',(array_key_exists('email', $prefill)) ? $prefill['email'] : null,["class"=> "full","placeholder"=> trans('labels.coupon_mail'), "required"]) !!}
						{!! Form::hidden('coupon_id',$coupon->id)!!}
						{!! Form::hidden('hotel_id',$coupon->cliente->id)!!}<br />
						
						<label class="label_checkbox">
							{!! Form::checkbox('newsletter', "1", (array_key_exists('newsletter', $prefill)) ? $prefill['newsletter'] : false,["class"=>" beautiful_checkbox", "id" => "newsletter_check"]) !!}
							<span>{{ trans('labels.newsletter') }}</span>
						</label><br />
						
						<label class="label_checkbox">
							{!! Form::checkbox('accettazione', "1", true,["class"=>" beautiful_checkbox privacy_accept", "checked"=>"checked"]) !!} 
							<span>{{ trans('labels.dati_pers') }} <a target="_top" href="/trattamento-dati-privacy.php">privacy e cookie policy</a></span>
						</label><div class="clearfix"></div>	<br />
											
						<button type="submit" class="sendForm btn btn-cyano"><i class="icon-mail-alt"></i> {{trans('hotel.invia')}}</button>
	
					{!!Form::close()!!}
					</div>
				</div>
			</div>
				
			
			
			<div class="clearfix"></div>
		
		</div>
		
		
  </section>

@endif


