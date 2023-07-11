@extends('templates.admin')

@section('icon')
	<i class="glyphicon glyphicon-envelope" style="color:#ddd;"></i>&nbsp;&nbsp;
@endsection

@section('title')
	Statistiche mail multiple/wishlist/mobile
@endsection

@section('content')

@if($stats_chart && !$raggruppa)
	<div id="chart_div" style="width: 100%; height: 500px;"></div>
@endif

{!! Form::open(array('action' => 'Admin\StatsMailMultiplaController@multipla', 'class' => 'form-horizontal')) !!}

  {!! csrf_field() !!}

  <div class="row">
 	 <div class="col-sm-12">
 		 <div class="alert alert-info">
 			 Attenzione! Da questa statistica sono escluse le email di oggi non ancora archiviate
 		 </div>
 	 </div>
  </div>

  <div class="panel panel-default">
	
	<div class="panel-heading">
		
		<div class="panel-title">Selezionare i parametri di ricerca</div>
		<div class="panel-options"></div>
		
	</div>

	<div class="panel-body">

		<div class="form-group">
  
			<div class="col-sm-3">
				  {!! Form::label('date_range', 'Scegli le date', array( 'class' => 'control-label') ) !!}
				  {!! Form::text('date_range', null, array('class' => 'daterange daterange-inline', 'data-format' => 'DD/MM/YYYY')) !!}
				  <i class="entypo-calendar"></i>
			  </div>
		  
		  <div class="col-sm-3">
			  {!! Form::label('hotel', 'Hotel', array( 'class' => 'control-label') ) !!}<br />
			  {!! Form::text('hotel', null, array('class' => 'form-control typeahead ricerca-hotel', 'data-local' => implode(',', Utility::getUsersHotels()), 'autocomplete' => 'off', 'placeholder' => "Scrivi l'id o il nome dell'hotel") ) !!}
		  </div>
  
			  <div class="col-sm-3">
			  {!! Form::label('tipologia', 'Tipo Mail', array( 'class' => 'control-label') ) !!}<br />
			  {!! Form::select('tipologia', ['-1' => 'Tutte'] + $data['tipologie'] + array("-2" => "Doppie"), null, array('class' => 'form-control' ) ) !!}
		  </div>
		
		  <div class=" col-sm-3">
			  <div class="control-label">&nbsp;</div>
			  <button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-ok"></i> Calcola</button>
		  </div>
		  
		  <div class="clearfix"></div>
		  
		  <div class="col-sm-12" style="padding:15px;">
			  <label>
				  {!! Form::checkbox('raggruppa', "1", null) !!} Raggruppa per mesi senza grafico 
			  </label>
		  </div>
		   
	  </div>
			  
  </div>
  
</div>

{!! Form::close() !!}

@if($stats_chart)
	
	@foreach($stats_chart as $anno_k_stats => $anno_stats)
		
		@if ($raggruppa)
			
			<div class="panel panel-default">
				<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th width="50%"><b>( Mesi ) / {{$anno_k_stats}}</b></th>
							<th width="50%">Email inviate</th>
						</tr>
					</thead>

		@endif
		
		@foreach($anno_stats as $mese_k_stats => $mese_stats)
			@if (isset($totali[$anno_k_stats][$mese_k_stats]))
				
				@if (!$raggruppa)
					<div class="panel panel-default">
						
						<table class="table table-striped table-hover">
							
							<thead>
								
								<tr>
									<th width="50%"><b>(Giorno) / {{$mesi[intval($mese_k_stats)]}} / {{$anno_k_stats}}</b></th>
									<th>Normale</th>
									<th>Wishlist</th>
									<th>Mobile</th>
									<th>Doppie</th>
									<th>Totale</th>
								</tr>
								
							</thead>

							<tbody>
								
								@php 
									$hotel_count_col_tot =[];
									$hotel_count_col_tot["normale"] = 0;
									$hotel_count_col_tot["wishlist"] = 0;
									$hotel_count_col_tot["mobile"] = 0;
									$hotel_count_col_tot["doppia"] = 0;
								@endphp
								
								@foreach($mese_stats as $giorno_k_stats => $hotel_count)
									
									@php  
										$hotel_count_col_tot["normale"] += $hotel_count["normale"];
										$hotel_count_col_tot["wishlist"] += $hotel_count["wishlist"];
										$hotel_count_col_tot["mobile"] += $hotel_count["mobile"];
										$hotel_count_col_tot["doppia"] += $hotel_count["doppia"];
										$hotel_count_row_tot = $hotel_count["normale"] + $hotel_count["wishlist"] + $hotel_count["mobile"] + $hotel_count["doppia"];
									@endphp
									
									@if ($hotel_count_row_tot > 0 )
										<tr>	
											<td>{{$giorno_k_stats}}</td>
											<td>{{$hotel_count["normale"]}}</td>
											<td>{{$hotel_count["wishlist"]}}</td>
											<td>{{$hotel_count["mobile"]}}</td>
											<td>{{$hotel_count["doppia"]}}</td>
											<td>{{$hotel_count_row_tot}}</td>
										</tr>
									@endif
									
								@endforeach
														
							</tbody> 
							
							<tfoot>
								<tr>
									<th>Totali</th>
									<th>{{$hotel_count_col_tot["normale"]}}</th>
									<th>{{$hotel_count_col_tot["wishlist"]}}</th>
									<th>{{$hotel_count_col_tot["mobile"]}}</th>
									<th>{{$hotel_count_col_tot["doppia"]}}</th>
									<th>{{$totali[$anno_k_stats][$mese_k_stats]}}<th>
								</tr>
							</tfoot>
												
						</table>
					
					</div>
					
				@else
					
					<tbody>

						@if ($totali[$anno_k_stats][$mese_k_stats] > 0 )
							<tr>	
								<td>{{$mesi[(int)$mese_k_stats]}}</td>
								<td>{{$totali[$anno_k_stats][$mese_k_stats]}}</td>
							</tr>
						@endif
												
					</tbody> 
					
				@endif
			@endif
		@endforeach
		
		@if ($raggruppa) 
			<tfoot>
				<tr>
					<th>Totali</th>
					<th>{{$totali_raggruppati[$anno_k_stats]}}</th>
				</tr>
			</tfoot>
		</table> 
		
	@endif
			
	@endforeach
	
	
	
@endif

@endsection


@section('onheadclose')

	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
	
	@if($stats_chart)
		
		<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
		<script type="text/javascript">
			
			google.charts.load('current', {'packages':['corechart']});
			google.charts.setOnLoadCallback(drawChart);

			function drawChart() {
				
				var data = new google.visualization.DataTable();
				data.addColumn('string', '')
				data.addColumn('number', 'Normali');
				data.addColumn('number', 'Wishlist');
				data.addColumn('number', 'Mobile');
				data.addColumn('number', 'Doppie');
								
				@foreach($stats_chart as $anno_k_stats => $anno_stats)
					@foreach($anno_stats as $mese_k_stats => $mese_stats)
						@foreach($mese_stats as $giorno_k_stats => $hotel)
						
							data.addRows([
							 	['{{$giorno_k_stats}} {{$mesi[intval($mese_k_stats)]}}', {{$hotel["normale"]}},{{$hotel["wishlist"]}},{{$hotel["mobile"]}} , {{$hotel["doppia"]}}],
							]);
						
						@endforeach
					@endforeach
				@endforeach
				
				var options = {
					title: '',
					hAxis: {title: '' },
					vAxis: {minValue: 0},
					legend: {position: 'top'},
					isStacked: true,
				};
				
				var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
				chart.draw(data, options);
			}
		</script>
	@endif

@endsection

@section('onbodyclose')

	<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

@endsection