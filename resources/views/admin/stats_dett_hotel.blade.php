@extends('templates.admin')


@section('icon')
	<i class="glyphicon glyphicon-stats" style="color:#ddd;"></i>&nbsp;&nbsp;
@endsection

@section('title')
Dettaglio visite alla scheda Hotel
@endsection

@section('content')

	{{-- @if (Auth::user()->hasRole(["admin", "operatore", "commerciale"]))
	<div class="row">
	  <div class="col-sm-12">
		<div class="alert alert-info">
		  <ul>
			<li>le statistiche mostrate in questa pagina si aggiornano una volta al giorno, alle ore 01:00</li>        
			<li>a partire dal 28/01/2016 la visita ad una scheda hotel viene registrata solo se l'hotel in quel momento risulta attivo</li>
			<li>prima del 28/01/2016 le visite venivano conteggiate quale che fosse lo stato in quel momento dell'hotel</li>
			<li>queste statistiche vanno solo a vedere la presenza o meno della visita, senza fare valutazioni sullo stato (presente o passato) dell'hotel</li>
		  </ul>
		</div>
	  </div>
	</div>

	@else
	  
	<div class="row">
	  <div class="col-sm-12">
		<div class="alert alert-info">
		  Le statistiche mostrate in questa pagina si aggiornano una volta al giorno, alle ore 01:00
		</div>
	  </div>
	</div>  

	@endif --}}
	
	@if(isset($stats)) 
		
		<div class="row">
			
			<div class="col-md-10">
				<div id="chart_div" style="width: 100%; height: 500px;"></div>
			</div>
			
			<div class="col-md-2">
				<div class="tile-stats tile-red stat-tile" style="height:auto; min-height:auto;"><h3>{{$totali_click}}</h3><p>Totale click</p></div>
				<div class="tile-stats tile-orange stat-tile" style="height:auto; min-height:auto;"><h3>{{$number_day}}</h3><p>Numero giorni</p></div>
				<div class="tile-stats tile-green stat-tile" style="height:auto; min-height:auto;"><h3>{{round($totali_click/$number_day,2)}}</h3><p>Media visite al giorno</p></div>
			</div>
			
		</div>
		
	@endif
	

{!! Form::open(array('action' => 'Admin\StatsHotelController@dettaglio', 'class' => 'form-horizontal')) !!}

  {!! csrf_field() !!}
	

	
  <div class="panel panel-default">
    
    <div class="panel-heading">
  	  <div class="panel-title">Selezionare i parametri di riferimento</div>
  	  <div class="panel-options"></div>
    </div>

	<div class="panel-body">
		
		<div class="form-group">
			
			<div class="col-sm-4">
				{!! Form::label('date_range', 'Scegli le date', array( 'class' => 'control-label') ) !!}
				{!! Form::text('date_range', null, array('class' => 'daterange daterange-inline', 'data-format' => 'DD/MM/YYYY')) !!}
				<i class="entypo-calendar"></i>
			</div>
			
			@if(Auth::user()->hasRole(["admin", "operatore", "commerciale"]))
			
				<div class="col-sm-3">
					{!! Form::label('hotel', 'Hotel (obbligatorio)', array( 'class' => ' control-label') ) !!}<br />
					{!! Form::text('hotel', null, array('class' => 'form-control typeahead ricerca-hotel', 'data-local' => implode(',', Utility::getUsersHotels()), 'autocomplete' => 'off', 'placeholder' => "Scrivi l'id o il nome dell'hotel") ) !!}
				</div>
			
			@else 
				
				
				{!! Form::hidden('hotel', Auth::user()->hotel_id) !!}
				
			@endif

			<div class="col-sm-2">
				<div class="control-label">&nbsp;</div>
				<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-ok"></i> Calcola</button>
			</div>
		
		</div>
		
	</div>
  
   </div>


{!! Form::close() !!}

@if(isset($stats) ) 

			 
	 @foreach($stats as $anno_key => $anno )
	 @foreach($anno as $mese => $chiamate)
		 
		 @if (isset($chiamate['totale']) && $chiamate['totale'] > 0)
		 <div class="panel panel-default" data-collapsed="0">
			 <div class="panel-body no-padding">	 
				 <table class="table table-striped table-hover" >
					 <thead>
						 <tr>
							<th width="50%"><b>(Giorno) / {{$mese}} / {{$anno_key}}</b></th>
							<th width="50%">Visite</th>
						 </tr>
					 </thead>
					 <tbody>
			 			
			 				@foreach($chiamate['giorni'] as $giorno => $calls)
								@if($calls > 0)
								<tr>
									<td>{{$giorno}}</td>
									<td>{{$calls}}</td>
								</tr>
								@endif
			 				@endforeach
			 			
					</tbody>
					<tfoot>
						<tr>
							<th>Totale</td>
							<th><b>{{$chiamate["totale"]}}</b></th>
						</tr>
					</tfoot>
				
				</table>
			</div>
		</div>
		@endif
		
	@endforeach
	@endforeach
	
@endif

@endsection

@section('onheadclose')
	
	{{-- {!! HTML::style('neon/js/daterangepicker/daterangepicker-bs3.css'); !!} --}}
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

	
	@if(isset($stats) ) 
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
				
			

				var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
				chart.draw(data, options);
			}
		</script>
	@endif

@endsection

@section('onbodyclose')
	
	{{-- {!! HTML::script('neon/js/daterangepicker/moment.js'); !!} --}}
	{{-- {!! HTML::script('neon/js/daterangepicker/daterangepicker.js'); !!} --}}
	
	<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
	
@endsection