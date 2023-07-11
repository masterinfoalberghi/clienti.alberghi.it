@extends('templates.admin')

@section('icon')
	<i class="glyphicon glyphicon-envelope" style="color:#ddd;"></i>&nbsp;&nbsp;
@endsection

@section('title')
    Messaggi Whatsapp
@endsection


@section('content')

	@if($stats)
		<div class="row">
				
				<div class="col-md-10">
					<div id="chart_div" style="width: 100%; height: 500px;"></div>
				</div>
				
				<div class="col-md-2">
					<div class="tile-stats tile-red stat-tile" style="height:auto; min-height:auto;"><h3>{{$super_totale}}</h3><p>Totale messaggi</p></div>
				</div>
				
			</div>
	@endif

{!! Form::open(array('class' => 'form-horizontal')) !!}

	<div class="panel panel-default">
		
		<div class="panel-heading">
			<div class="panel-title">Selezionare l'hotel, il mese e l'anno di partenza</div>
			<div class="panel-options"></div>
		</div>

		<div class="panel-body">
			
			<div class="form-group">
		      	
				<div class="col-sm-4">
					{!! Form::label('date_range', 'Scegli le date', array( 'class' => 'control-label') ) !!}
					{!! Form::text('date_range', null, array('class' => 'daterange daterange-inline', 'data-format' => 'DD/MM/YYYY')) !!}
					<i class="entypo-calendar"></i>
				</div>

				<div class="col-sm-4">
					{!! Form::label('hotel', 'Hotel', array( 'class' => 'control-label') ) !!}<br />
					{!! Form::text('hotel', null, array('class' => 'form-control typeahead ricerca-hotel', 'data-local' => implode(',', Utility::getUsersHotels()), 'autocomplete' => 'off', 'placeholder' => "Scrivi l'id o il nome dell'hotel") ) !!}
				</div>

				<div class="col-sm-4">
					<div class="control-label">&nbsp;</div>
					<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-ok"></i> Calcola</button>
				</div>
				
		    </div>
					
		</div>
		
	</div>

{!! Form::close() !!}

@if($stats)
	
@foreach($stats as $anno_k_stats => $anno_stats)
	@foreach($anno_stats as $mese_k_stats => $mese_stats)
	
		<div class="panel panel-default" data-collapsed="0">
	 		<div class="panel-body no-padding">	 
		 		<table class="table table-striped table-hover" >
					<thead>
						<tr>
							<th width="50%"><b>(Giorno) / {{$mese_k_stats}} / {{$anno_k_stats}}</b></th>
							<th width="50%">Messaggi</th>
						</tr>
					</thead>
					
			 		<tbody>
						
		 				@foreach($mese_stats["giorni"] as $giorno => $messaggi)
								@if($messaggi > 0)
									<tr>
										<td>{{$giorno}}</td>
										<td>{{$messaggi}}</td>
									</tr>
								@endif
		 				@endforeach
		 			
					</tbody>
					
					<tfoot>
						<tr>
							<th>Totale</td>
							<th><b>{{$mese_stats["totale"]}}</b></th>
						</tr>
					</tfoot>
				
				</table>
			</div>
		</div>

	@endforeach
@endforeach
	
@endif

@endsection

@section('onheadclose')
	
	{{-- {!! HTML::style('neon/js/daterangepicker/daterangepicker-bs3.css'); !!} --}}
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
	
	@if($stats)
		<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

		<script type="text/javascript">
			google.charts.load('current', {'packages':['corechart']});
			google.charts.setOnLoadCallback(drawChart);

			function drawChart() {
				var data = google.visualization.arrayToDataTable([
					['', 'Visite'],
					
					@foreach($stats as $anno_key => $anno)
						@foreach($anno as $mese => $chiamate)
							@if (isset($chiamate['giorni']))
								@foreach($chiamate['giorni'] as $giorno => $calls)
							  		['{{$giorno}} {{$mese}}',  {{$calls}} ],
								@endforeach
							@endif
						@endforeach
					@endforeach
					
				]);

				var options = {
					title: '',
					hAxis: {title: '',  titleTextStyle: {color: '#333'}},
					vAxis: {minValue: 0},
					legend: {position: 'top'}
				};
				

				var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
				chart.draw(data, options);
			}
		</script>
	@endif
	
@endsection

@section('onbodyclose')
	
	<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
	
@endsection
