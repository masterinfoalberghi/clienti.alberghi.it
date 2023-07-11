
<form id="search_first" class="container" action="" >

	<input type="hidden" value="1" name="search_first_active" />
	
    <div class="col-12">

		<div id="testo-top-2">{{ trans("title.dove_andare")}}</div>
		
		<header id="testo-top" class="home">
			<h1>{{$valoriHomepage["n_hotel_tot"]}} {{ trans('labels.hp_fascia_blu_mobile') }}</h1>
		</header>
        <div class="col-form">
			<label class="cyan-fe">{{ trans("listing.sel_localita")}}</label>
			<select name="macrolocalita" id="macrolocalita" onchange="changeMacrolocalitaSearchFirst(this)">
				<option value="11">...</option>
				@foreach($macrolocalita_search_first as $macrolocalita_option_first)
			<option value="{{$macrolocalita_option_first->id}}"  data-url="{{$macrolocalita_option_first->linked_file}}">{{$macrolocalita_option_first->nome}}</option>
				@endforeach
			</select>							
		</div>
	</div>
	
	<div id="roomHome" class="room" style="margin-left:15px; margin-right:15px;">
		
		@php
			
			$lowlimit = Carbon\Carbon::now()->format("d/m/Y");

			$today 	   = Utility::ePrimaDiOggi($prefill["rooms"][0]["checkin"]);
			$tomorrow  = Utility::ePrimaDiOggi($prefill["rooms"][0]["checkout"],1);

			$adulti_select  = \App\MailMultipla::$adulti_select;
			$bambini_select = \App\MailMultipla::$bambini_select;
			$adulti_over_select = \App\MailMultipla::$adulti_over_select;

		@endphp
		
		@include('widget.form.formDatePicker', 	array('order_number' => 0, 'tomorrow' => $tomorrow, 'today' => $today, "lowlimit" => $lowlimit ))

	</div>

	<div class="col-xs-6">
        <div class="col-form arrow_tooltip">
			<label class="cyan-fe">{{ trans("listing.adulti")}}</label>
			<select class="numP num_adulti" id="num_adulti_0" name="adulti">
				@for( $t=1; $t<7; $t++)
					<option value="{{$t}}" @if ($t == $prefill["rooms"][0]["adult"]) selected @endif>{{$t}} @if ($t == 1) {{trans("labels.adulto")}} @else {{trans("labels.adulti")}} @endif</option>
				@endfor
			</select>
		</div>
	</div>
	
    <div class="col-xs-6">
		<div class="col-form arrow_tooltip">
			<label class="cyan-fe">{{ trans("listing.bambini")}}</label>
			<select class="numP num_bambini" id="num_bambini_0" name="bambini">
				@for( $t=0; $t<7; $t++)
					<option value="{{$t}}" @if ($t == $prefill["rooms"][0]["children"]) selected @endif>{{$t}} @if ($t == 1) {{trans("labels.bambino")}} @else {{trans("labels.bambini")}} @endif</option>
				@endfor
			</select>
		</div>
	</div>

	<div class="col-xs-12" style="text-align: center;">
		<button class="button green" style="margin:0">{{trans("labels.cerca")}}</button>
	</div>
	
</form>
