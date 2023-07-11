@extends('templates.admin')

@section('title')
  Statistiche offerte
@endsection

@section('content')
  <div class="alert alert-warning" style="font-size: 15px; text-align:center;"><strong>ATTENZIONE CALCOLO COSTI!</strong> Ogni anno è necessario importare i costi dalla piattaforma CRM (rinominando quelli passati) per avere le statistiche corrette.</div>
	<form method="post" action="/admin/statsv2/offerte/generale">

		{!! csrf_field() !!}
		
		<div class="panel panel-default">
			
			<div class="panel-heading">
				<div class="panel-title">Modifica i filtri</div>
			</div>
			
			<div class="panel-body">
				
				<div class="row form-group">
				
					<div class="col-sm-2">
	    				{!! Form::label('date_range', 'Scegli le date', array( 'class' => 'control-label') ) !!}
	    				{!! Form::text('date_range', null, array('class' => 'daterange daterange-inline', 'data-format' => 'DD/MM/YYYY', 'data-start-date' => $date_from->format('d/m/Y'), 'data-end-date' => $date_to->format('d/m/Y') )) !!}
	    				<i class="entypo-calendar"></i>
	    			</div>
					
					<div class="col-sm-2">
						{!! Form::label('table', 'Tipo vetrina', array( 'class' => 'control-label')) !!}
						{!! Form::select('table', $tables, isset($table) ? $table : null, ['class' => 'form-control', 'id' => 'tipo_vetrina_select']) !!}
					</div>

					<div class="col-sm-2">
    				{!! Form::label('device', 'Dispositivo', array( 'class' => 'control-label') ) !!}
    				{!! Form::select('device', ["" => "Tutti"] + $devices, isset($device) ? $device : null, ['class' => 'form-control']) !!}
    			</div>

				</div>

				<div class="row form-group">

					
					<div class="col-sm-2">
						{!! Form::label('lang', 'Seleziona la lingua', array( 'class' => 'control-label') ) !!}<br />
						{!! Form::select('lang', ["it" => "IT", "en" => "EN", "fr" => "FR", "de" => "DE"] , isset($lang) ? $lang : null, [ 'style'=> 'display:inline-block; width:70px', 'id' => 'lang_select', 'class' => 'form-control']) !!} 
					</div>
					
					<div class="col-sm-3">
    				{!! Form::label('macro', 'Località', array( 'class' => 'control-label') ) !!}
    				{!! Form::select('macro', $macros, isset($macro) ? $macro : null, ['class' => 'form-control', 'id' => 'macro_select']) !!}
    			</div>

					<div class="col-sm-7">
						
						{!! Form::label('pagina_id', 'Seleziona la pagina', array( 'class' => 'control-label') ) !!}<br />

						{!! Form::select('pagina_id', $pages, isset($pagina_id) ? $pagina_id : null, ['style'=> 'display:inline-block; width:auto; min-width:300px', 'id' => 'pagina_select', 'data-allow-clear' => 'true', 'data-placeholder' => 'Select one city...', 'class' => 'form-control']) !!}	
						
						&nbsp;&nbsp;&nbsp;
						<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-ok"></i> Calcola</button>
					</div>
					
				</div>
			</div>
		</div>
	
	</form>

	
	<div class="row"> 
		
		<div class="col-sm-3 col-xs-6">
			<div class="tile-stats tile-aqua">
				<div class="icon">
					<i class="entypo-chart-bar"></i>
				</div> 
				<div class="num">{{number_format($Anno_ClickTotali->conteggio)}} click totali</div>
				<h3>{{number_format($AllOfferPerPeriod)}} hotel univoci cliccati almento 1 volta</h3>
				<p>
					@if ($AllOfferPerPeriod > 0)
						@foreach ($q_AllOfferPerPeriod as $item)
								<b>{{$item->hotel_id}}</b> click <b>{{$item->somma}}</b><br/>
						@endforeach
					@endif

				</p>
			</div>
		</div>
		
		<div class="col-sm-3 col-xs-6">
			
		</div>
		
		<div class="col-sm-3 col-xs-6">
			<div class="tile-stats tile-red">
				<div class="icon">
					<i class="entypo-chart-bar"></i>
				</div> 
				<div class="num">{{number_format($Anno_ClickTotali2->conteggio)}} click totali</div>
				<h3>{{number_format($AllOfferPerPeriod2)}} hotel univoci cliccati almento 1 volta{{--  (1 anno prima del perido selezionato) --}}</h3> 
				<p>
					@if ($AllOfferPerPeriod2 > 0)
						@foreach ($q_AllOfferPerPeriod2 as $item)
								<b>{{$item->hotel_id}}</b> click <b>{{$item->somma}}</b><br/>
						@endforeach
					@endif
				</p>
			</div>
		</div>
		
		<div class="col-sm-3 col-xs-6">
			
		</div>
		
	</div>
	
	<div class="row"> 
		
		<div class="col-sm-3 col-xs-6">
			<div class="panel panel-info" data-collapsed="0">
				<div class="panel-heading">
					<div class="panel-title">Percentuali click per mese</div>
				</div> 
				<div class="panel-body">
					
					<table class="table table-striped">
						<thead>
							<tr>
								<th width="60%">Mese</th>
								<th width="20%">Click</th>
								<th>Percentuale</th>
							</tr>
						</thead>
						<tbody>
							@foreach($Anno_mediaClickPerMese as $item)
							<tr>
								<td>{{$item->mese}}</td>
								<td>{{number_format($item->conteggio)}}</td>
								<td>{{$item->percentuale}}%</td> 
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
		
		<div class="col-sm-3 col-xs-6">
			<div class="panel panel-info" data-collapsed="0">
				<div class="panel-heading">
					<div class="panel-title">Percentuali click per localita (in base agli hotel)</div>
				</div> 
				<div class="panel-body">
					
					<table class="table table-striped">
						<thead>
							<tr>
								<th width="60%">Località</th>
								<th width="20%">Click</th>
								<th width="20%">%</th>
							</tr>
						</thead>
						<tbody>
							@foreach($Anno_mediaClickPerLocalitaArr as $item)
							<tr>
								<td>{{$item["nome"]}} ({{$item["hotels"]}})</td>
								<td>{{$item["conteggio"]}}</td>
								<td>{{round(($item["conteggio"]/$Anno_ClickTotali->conteggio*100),2)}}%</td> 
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
		
		<div class="col-sm-3 col-xs-6">
			<div class="panel panel-danger" data-collapsed="0">
				<div class="panel-heading">
					<div class="panel-title">Percentuali click per mese</div>
				</div> 
				<div class="panel-body">
					
					<table class="table table-striped">
						<thead>
							<tr>
								<th width="60%">Mese</th>
								<th width="20%">Click</th>
								<th>Percentuale</th>
							</tr>
						</thead>
						<tbody>
							@foreach($Anno_mediaClickPerMese2 as $item)
							<tr>
								<td>{{$item->mese}}</td>
								<td>{{number_format($item->conteggio)}}</td>
								<td>{{$item->percentuale}}%</td> 
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
		
		<div class="col-sm-3 col-xs-6">
			<div class="panel panel-danger" data-collapsed="0">
				<div class="panel-heading">
					<div class="panel-title">Percentuali click per localita (in base agli hotel)</div>
				</div> 
				<div class="panel-body">
					
					<table class="table table-striped">
						<thead>
							<tr>
								<th width="60%">Località</th>
								<th width="20%">Click</th>
								<th width="20%">%</th>
							</tr>
						</thead>
						<tbody>
							@foreach($Anno_mediaClickPerLocalitaArr2 as $item)
							<tr>
								<td>{{$item["nome"]}} ({{$item["hotels"]}})</td>
								<td>{{$item["conteggio"]}}</td>
								<td>{{round(($item["conteggio"]/$Anno_ClickTotali2->conteggio*100),2)}}%</td> 
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
		
		
		
  </div>
  


  @if ($costi_non_attendibili)
       <div class="alert alert-danger"><strong>ATTENZIONE COSTI NON ATTENDIBILI</strong> I costi non sono attendibili perché non esiste la tabella costi per l'anno selezionato oppure il periodo copre più anni</div>
  @else
        <div class="alert alert-success"><strong>TABELLA COSTI:</strong> {{ $tabella_costi }}</div>
  @endif

	@if ($costo_per_click > 0)
		<div class="row">
			<div class="col-sm-3 col-xs-6">
				<div class="panel panel-info" data-collapsed="0">
					<div class="panel-heading">
						<div class="panel-title">Costo totale</div>
					</div> 
					<div class="panel-body">
						{{number_format((float)$costo, 2, ',', '')}}
					</div>
				</div>
			</div>
			<div class="col-sm-3 col-xs-6">
				<div class="panel panel-info" data-collapsed="0">
					<div class="panel-heading">
						<div class="panel-title">Costo per click</div>
					</div> 
					<div class="panel-body">
						{{number_format((float)$costo_per_click, 2, ',', '')}}
					</div>
				</div>
			</div>
		</div>
	@elseif($costo_per_click == -1)
		<div class="row">
			<div class="col-sm-3 col-xs-6">
				<div class="panel panel-info" data-collapsed="0">
					<div class="panel-heading">
						<div class="panel-title">Costo totale</div>
					</div> 
					<div class="panel-body">
						Nessun click rilevato nel periodo selezionato!!
					</div>
				</div>
			</div>
			<div class="col-sm-3 col-xs-6">
				<div class="panel panel-info" data-collapsed="0">
					<div class="panel-heading">
						<div class="panel-title">Costo per click</div>
					</div> 
					<div class="panel-body">
						Nessun click rilevato nel periodo selezionato!!
					</div>
				</div>
			</div>
		</div>
	@endif
		
@endsection

@section('onheadclose')
	
	{{-- {!! HTML::style('neon/js/daterangepicker/daterangepicker-bs3.css'); !!} --}}
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
	
@endsection

@section('onbodyclose')
	
	{{-- {!! HTML::script('neon/js/daterangepicker/moment.js'); !!} --}}
	{{-- {!! HTML::script('neon/js/daterangepicker/daterangepicker.js'); !!} --}}
	
	<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
	
	<script>
		
		jQuery(function ($) {


			function fillPages(lang,table, macro) {
					var pagina_id = $("pagina_select").val();
					jQuery.ajax({
					       url: '{{ url("admin/statsv2/offerte/cambia_tipo_vetrina_ajax") }}',
					       type: "post",
					       async: false,
					       data : {
					              'lang': lang,
					              'table': table,
					              'macro': macro,
					              'pagina_id': pagina_id,
					              '_token': jQuery('input[name=_token]').val()
					              },
					      success: function(options) {
					      		$("#pagina_select").empty();
					      		$("#pagina_select").append(options);
					      }
					  });

			}




			$("#lang_select").change(function () {
					var lang = this.value;
					var table = $("#tipo_vetrina_select").val();
					var macro = $("#macro_select").val();
					fillPages(lang,table, macro);
			});

			$("#tipo_vetrina_select").change(function () {
				var table = this.value;
				var lang = $("#lang_select").val();
				var macro = $("#macro_select").val();
				fillPages(lang,table, macro);
			});

			$("#macro_select").change(function () {
				var macro = this.value;
				var lang = $("#lang_select").val();
				var table = $("#tipo_vetrina_select").val();
				fillPages(lang,table, macro);
			});


			
			
			
			
		});
		
	</script>
	
@endsection