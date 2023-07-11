<?php

// visualizzo le info sul coupon
// @parameters : $coupon (collection di coupon)
//               $titolo (header titolo oppure '')

?>

@if ($nc = $coupon->count())

<?php
$msg = $titolo;
$coupon = $coupon->first();
?>
<div class="row">   

	<div class="col-xs-12"  id="daticoupon">
		
		<h2>{{ trans('hotel.buono_sconto_1') }} {{ trans('hotel.buono_sconto_2') }} {{$coupon->valore}} &euro;</h2>
	
		<ul > 
			<li><img src='{{Utility::asset("images/punti.png")}}'  />{{ trans('hotel.coupon_valido_dal') }} <b class="red-fe">{{Utility::getLocalDate($coupon->periodo_dal, '%d/%m/%y')}} {{ trans('hotel.coupon_valido_al') }} {{Utility::getLocalDate($coupon->periodo_al, '%d/%m/%y')}}</b></li>
			<li><img src='{{Utility::asset("images/punti.png")}}'  />{{ trans('hotel.coupon_valido_per_gg') }} <b class="red-fe">{{$coupon->durata_min}}</b></li>
			<li><img src='{{Utility::asset("images/punti.png")}}'  />{{ trans('hotel.coupon_min_persone') }} <b class="red-fe">{{$coupon->adulti_min}}</b></li>
			<li><img src='{{Utility::asset("images/punti.png")}}'  />{{ trans('hotel.buono_sconto_dispo') }} <b class="red-fe">{{$coupon->disponibile()}} coupon</b></li>
		</ul>
	
		<small>{{ trans('hotel.buono_sconto_leggere_1') }} {{ trans('hotel.buono_sconto_leggere_2') }} {{ trans('hotel.buono_sconto_leggere_3') }}<br/> {{ trans('hotel.buono_sconto_leggere_4') }}</small>
	</div>
            
    <div class="col-xs-12 orange"  id="coupon">
			
			<div id="errors"></div>
			
            <div class="coupontesto">
            	<p>{{ trans('hotel.buono_sconto_ricevi_subito') }} </p>
            </div>
            
            {!!Form::open(["id"=>"form_coupon", 'url' => 'scaricaCoupon'])!!}
            
            <div class="col-form">
            {!! Form::label('email_coupon', 'email', ["class"=>"cyan-fe"]) !!}
            {!! Form::text('email_coupon',null,["id"=> "email_coupon", "placeholder"=>"INSERISCI LA TUA MAIL", "required"]) !!}
            </div>
            
            {!! Form::hidden('coupon_id',$coupon->id)!!}
            {!! Form::hidden('hotel_id',$coupon->cliente->id)!!}
            
             <div class="col-button"  >
				{!! Form::checkbox('accetto', "1", true,["class"=>"beautiful_checkbox",]) !!} <label class="cyan-fe" style="display:inline-block; background:transparent; border:none; "><a href=" {{url('trattamento-dati-privacy.php')}}" target="_blank" style="color:#fff;">{{ trans('labels.dati_pers') }}</a></label>
				{!! Form::submit( strtoupper(trans('hotel.invia')), ["class" => "inviacoupon button green invia_button "]) !!}
			</div>
            
            {!!Form::close()!!}
          
                   

    </div>{{-- / #coupon --}}
         
</div><!-- FINE ACCORDION -->



@endif


