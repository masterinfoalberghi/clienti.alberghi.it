@if (isset($prezzo) && $cliente == "")
	<var class="price">{!!Utility::setPriceFormat($prezzo)!!}</var> 
@elseif (isset($percentuale) && $cliente == "")
	<var class="price">- {{$percentuale}}%</var>
@elseif (isset($cliente->prezzo_min) && $cliente->prezzo_min != "0.00")

	<div class="price">
            <div class="col-xs-1"></div>
            <div class="col-xs-5">
                <small>{{trans("labels.hp_prezzo_stagione_bassa")}}</small><br />
                <var>{!!Utility::setPriceFormat($cliente->prezzo_min)!!}</var>
            </div>
            
            <div class="col-xs-5">
                <small>{{trans("labels.hp_prezzo_stagione_alta")}}</small><br />
                <var>{!!Utility::setPriceFormat($cliente->prezzo_max)!!}</var>
            </div>
            <div class="col-xs-2"></div>
        <div class="clearfix"></div>
	</div>

  
@else 
	<div class="price">
		<small>&nbsp;</small><br>
		<var>&nbsp;</var>
	</div>
@endif