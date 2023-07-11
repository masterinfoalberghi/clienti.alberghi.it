@extends('templates.admin')

@section('icon')
	<i class="glyphicon glyphicon-map-marker" style="color:#ddd;"></i>&nbsp;&nbsp;
@endsection

@section('title')
	Vetrine
@endsection

@section('content')
	@if(isset($stats_chart)) 
		@if(!$raggruppa)
			
			<div class="row">
				
				<div class="col-md-10">
					<div id="chart_div" style="width: 100%; height: 500px;"></div>
				</div>
				
				<div class="col-md-2">
					<div class="tile-stats tile-red stat-tile" style="height:auto; min-height:auto;"><h3>{{$totali_click}}</h3><p>Totale click</p></div>
					<div class="tile-stats tile-orange stat-tile" style="height:auto; min-height:auto;"><h3>{{$number_day}}</h3><p>Numero giorni</p></div>
					<div class="tile-stats tile-green stat-tile" style="height:auto; min-height:auto;"><h3>{{count($vetrine)}}</h3><p>Numero vetrine</p></div>
					<div class="tile-stats tile-blue stat-tile" style="height:auto; min-height:auto;"><h3>{{round($totali_click/$number_day,2)}}</h3><p>Media click al giorno</p></div>
				</div>
				
			</div>
		@else 
			
			<div class="row">
				<div class="col-md-3"><div class="tile-stats tile-red stat-tile" style="height:auto; min-height:auto;"> <h3>{{$totali_click}}</h3> <p>Totale click</p></div></div>
				<div class="col-md-3"><div class="tile-stats tile-orange stat-tile" style="height:auto; min-height:auto;"> <h3>{{$number_day}}</h3> <p>Numero giorni</p> </div></div>
				<div class="col-md-3"><div class="tile-stats tile-green stat-tile" style="height:auto; min-height:auto;"> <h3>{{count($vetrine)}}</h3> <p>Numero vetrine</p></div></div>
				<div class="col-md-3"><div class="tile-stats tile-blue stat-tile" style="height:auto; min-height:auto;"> <h3>{{round($totali_click/$number_day,2)}}</h3> <p>Media click al giorno</p></div>
				</div>
			</div>
			
		@endif
	@endif
	
	{!! Form::open(array('class' => 'form-horizontal')) !!}

		{!! csrf_field() !!}

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
						{!! Form::label('vetrina_id', 'Seleziona la vetrina', array( 'class' => 'control-label') ) !!}<br />
						{!! Form::select('vetrina_id', ["" => "Seleziona la vetrina"] + $data['vetrine'], null, ['class' => 'form-control']) !!}
					</div>

					<div class=" col-sm-3">
						<div class="control-label">&nbsp;</div>
						<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-ok"></i> Calcola</button>
					</div>
					
					<div class="clearfix"></div>
					
					<div class="col-sm-12" style="padding:15px;">
						<label>
							{!! Form::checkbox('raggruppa', "1", null) !!} Raggruppa i risultati per mesi
						</label>
					</div>
					 
				</div>
						
			</div>
			
	</div>

	{!! Form::close() !!}
	
	@if(isset($stats))
		
		@if(!$raggruppa)
			
			@foreach($stats as $anno_k_stats => $anno_stats)
				@foreach($anno_stats as $mese_k_stats => $mese_stats)
					
					@php 
						$totali=[];
					@endphp
					
					<div class="panel panel-default">
					
						<table class="table table-striped table-hover">
							
							<thead>
								
								<tr>
									<th width="50%"><b>(Giorno) / {{$mesi[intval($mese_k_stats)]}} / {{$anno_k_stats}}</b></th>
									
									@foreach($vetrine as $vetrina_id)		
										<th>
										 	@if(isset($data["vetrine"][$vetrina_id])) 
												{{ $data["vetrine"][$vetrina_id] }} 
											@else 
												#{{ $vetrina_id }}
											@endif 
										</th>
									@endforeach	
									
									@if(count($vetrine) >1 )
										<th width="50%">
											Totale vetrine
										</th>
									@endif
									
								</tr>
								
							</thead>

							<tbody>

								@foreach($mese_stats as $giorno_k_stats => $vot)
									
									@php $temp_totale = 0; @endphp
									
									<tr>	
										<td>{{ $giorno_k_stats }}</td>
										
										@foreach($vetrine as $vetrina)
											@if (!isset($totali[$vetrina]))
												@php $totali[$vetrina] = 0 @endphp
											@endif
											<td>
												@if(isset($vot[$vetrina]))
													{{$vot[$vetrina]}}
													@php 
														$temp_totale += $vot[$vetrina]; 
														$totali[$vetrina] += $vot[$vetrina]; 
													@endphp
												@else
													0
												@endif
											</td> 
										@endforeach 
										
										@if (count($vetrine) > 1)
											<td>
												<span style="color:#000">{{$temp_totale}}</span>
											</td>
										@endif		
										
									</tr>
									
								@endforeach
														
							</tbody> 
							
							<tfoot>
								<tr>
									<th></th>
									@php $totale_mese = 0; @endphp
									@foreach($vetrine as $vetrina)
										<th>
											@if (isset($totali[$vetrina]))
												{{$totali[$vetrina]}}
												@php $totale_mese += $totali[$vetrina]; @endphp
											@else
												0
											@endif
										</th>
									@endforeach 
									@if (count($vetrine) > 1)
									<th>
										{{$totale_mese}}
									</th>
									@endif
								</tr>
							</tfoot>
												
						</table>
					
					</div>
			
				@endforeach		
			@endforeach		
		
		@else
			
			@foreach($stats as $anno_k_stats => $anno_stats)
						
				@php 
					$totali=[];
				@endphp
							
				<div class="panel panel-default">
				
					<table class="table table-striped table-hover">
						
						<thead>
							
							<tr>
								<th width="50%"><b>( Mesi ) / {{$anno_k_stats}}</b></th>
								
								@foreach($vetrine as $vetrina_id)		
									<th>
									 	@if(isset($data["vetrine"][$vetrina_id])) 
											{{ $data["vetrine"][$vetrina_id] }} 
										@else 
											#{{ $vetrina_id }}
										@endif 
									</th>
								@endforeach	
								
								@if(count($vetrine) >1 )
									<th width="50%">
										Totale vetrine
									</th>
								@endif
								
							</tr>
							
						</thead>

						<tbody>
							
							@foreach($anno_stats as $mese_k_stats => $vot)
								
								@php $temp_totale = 0; @endphp
								
								<tr>
									
									<td>{{ \Carbon\Carbon::createFromFormat("m",$mese_k_stats)->format("M") }}</td>
									
									@foreach($vetrine as $vetrina)
										@if (!isset($totali[$vetrina]))
											@php $totali[$vetrina] = 0 @endphp
										@endif
										<td>
											@if(isset($vot[$vetrina]))
												{{$vot[$vetrina]}}
												@php 
													$temp_totale += $vot[$vetrina]; 
													$totali[$vetrina] += $vot[$vetrina]; 
												@endphp
											@else
												0
											@endif
										</td> 
									@endforeach 
									
									@if (count($vetrine) > 1)
										<td>
											<span style="color:#000">{{$temp_totale}}</span>
										</td>
									@endif		
								
									
								</tr>
								
							@endforeach
													
						</tbody>
						
						<tfoot>
							<tr>
								<th></th>
								@php $totale_anno = 0; @endphp
								@foreach($vetrine as $vetrina)
									<th>
										@if (isset($totali[$vetrina]))
											{{$totali[$vetrina]}}
											@php $totale_anno += $totali[$vetrina]; @endphp
										@else
											0
										@endif
									</th>
								@endforeach 
								@if (count($vetrine) > 1)
								<th>
									{{$totale_anno}}
								</th>
								@endif
							</tr>
						</tfoot>
											
					</table>
				
				</div>
					
				
			@endforeach			
		@endif
	@endif
	


@endsection

@section('onheadclose')
	
	{{-- {!! HTML::style('neon/js/daterangepicker/daterangepicker-bs3.css'); !!} --}}
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
	<style>
		.table-horizontal-scroll{
			overflow-x: scroll;
		}
		.table-horizontal-scroll th, .table-horizontal-scroll td{
			white-space: nowrap;
			text-align: right;
		}
	</style>
	
	
	@if(isset($stats_chart) && !$raggruppa)
		
		<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
		<script type="text/javascript">
			
			google.charts.load('current', {'packages':['corechart']});
			google.charts.setOnLoadCallback(drawChart);

			function drawChart() {
				
				var data = new google.visualization.DataTable();
				data.addColumn('string', '')
				
				@foreach($vetrine as $vetrina_id)
					data.addColumn('number', @if(isset($data["vetrine"][$vetrina_id])) '{{ $data["vetrine"][$vetrina_id] }}' @else 'ID Vetrina {{ $vetrina_id }}' @endif);
				@endforeach
				
				@foreach($stats_chart as $anno_k_stats => $anno_stats)
					@foreach($anno_stats as $mese_k_stats => $mese_stats)
						
						@foreach($mese_stats as $giorno_k_stats => $vot)
						
							data.addRows([
							 	['{{$giorno_k_stats}} {{$mesi[intval($mese_k_stats)]}} ', @foreach($vetrine as $vetrina) @if (isset($vot[$vetrina])) {{$vot[$vetrina]}}, @else 0, @endif @endforeach],
							]);
						
						@endforeach
	
					@endforeach
				@endforeach
				
				var options = {
					title: '',
					hAxis: {title: '', ticks: "01 Gen" },
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
	
	{{-- {!! HTML::script('neon/js/daterangepicker/moment.js'); !!} --}}
	{{-- {!! HTML::script('neon/js/daterangepicker/daterangepicker.js'); !!} --}}
	
	<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
	
@endsection