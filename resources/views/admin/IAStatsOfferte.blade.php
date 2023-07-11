@extends('templates.admin')

@section('title')
	Statistiche offerte
@endsection

@section('content')
	
	<form method="POST" action="/admin/statsv2/offerte/generale">

		{!! csrf_field() !!}
		
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="panel-title">Modifica i filtri</div>
			</div>
			<div class="panel-body">
				
				<div class="form-group">
				
				<div class="col-sm-4">
    				{!! Form::label('date_range', 'Scegli le date', array( 'class' => 'control-label') ) !!}
    				{!! Form::text('date_range', null, array('class' => 'daterange daterange-inline', 'data-format' => 'DD/MM/YYYY')) !!}
    				<i class="entypo-calendar"></i>
    			</div>
				
				<div class="col-sm-2">
					{!! Form::label('language', 'Seleziona la lingua', array( 'class' => 'control-label') ) !!}<br />
					{!! Form::select('lang', ["it" => "IT - Italiano", "ing" => "EN - Inglese", "fr" => "FR - Francese", "ted" => "DE - Tedesco"] , isset($lang) ? $lang : null, [ 'id' => 'lang_select', 'class' => 'form-control']) !!} 
				</div>
				
				<div class="col-sm-3">
					{!! Form::label('pagina_id', 'Seleziona la pagina', array( 'class' => 'control-label') ) !!}<br />
					{!! Form::select('pagina_id', ["" => "Caricamento..."], isset($pagina_id) ? $pagina_id : null, ['id' => 'pagina_select', 'data-allow-clear' => 'true', 'data-placeholder' => 'Select one city...', 'class' => 'form-control']) !!}	
				</div>
				
				
				
				<div class="col-sm-3">
					<div class="control-label">&nbsp;</div>
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
				<div class="num">{{number_format($Anno_ClickTotali->conteggio)}}</div>
				<h3>Click totali sulle evidenze</h3> 
				<p>{{$yearSelected}}</P>
			</div>
		</div>
		
		<div class="col-sm-3 col-xs-6"></div>
		
		<div class="col-sm-3 col-xs-6">
			<div class="tile-stats tile-red">
				<div class="icon">
					<i class="entypo-chart-bar"></i>
				</div> 
				<div class="num">{{number_format($Anno_ClickTotali2->conteggio)}}</div>
				<h3>Click totali sulle evidenze</h3> 
				<p>{{$yearReferer}}</P>
			</div>
		</div>
		
		<div class="col-sm-3 col-xs-6"></div>
		
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
					<div class="panel-title">Percentuali click per localita</div>
				</div> 
				<div class="panel-body">
					
					<table class="table table-striped">
						<thead>
							<tr>
								<th width="60%">Mese</th>
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
					<div class="panel-title">Percentuali click per localita</div>
				</div> 
				<div class="panel-body">
					
					<table class="table table-striped">
						<thead>
							<tr>
								<th width="60%">Mese</th>
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
		
@endsection

@section('onheadclose')
	
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
	
@endsection

@section('onbodyclose')
	
	<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
	
	<script>
		
		jQuery(function ($) {
			
			var $origine = [];
			$origine["it"] = [];
			$origine["fr"] = [];
			$origine["ted"] = [];
			$origine["ing"] = [];
			
			@if (isset($pagina_id) && $pagina_id != "")
				var pagina_id = {{$pagina_id}};
			@else
				var pagina_id = 0;
			@endif
						
			@foreach ($pages as $key => $uri)
				@php
					$l = "it";	
					if (strpos($uri , "ing" ) === 0) {
						$l = "ing";
					}
					if (strpos($uri ,"fr" ) === 0) {
						$l = "fr";
					}
					if (strpos($uri ,"ted") === 0) {
						$l = "ted";
					}
				@endphp
				$origine["{{$l}}"][{{$key}}] = "{{$uri}}";
			@endforeach
			
			$("#lang_select").change(function () {
				
				$("#pagina_select").empty();
				$("#pagina_select").append('<option value="">Seleziona una pagina</option>');
				
				$origine[$(this).val()].forEach(function(element, key) {
					
					if (key == pagina_id) {
				   		$("#pagina_select").append('<option selected value="'+key+'">' + element + '</option>');
					} else {
						$("#pagina_select").append('<option value="'+key+'">' + element + '</option>');
					}
				});
				
			});
			
			$("#lang_select").trigger("change");
			
			
		});
		
	</script>
	
@endsection