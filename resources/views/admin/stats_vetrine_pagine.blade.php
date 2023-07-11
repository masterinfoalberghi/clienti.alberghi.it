@extends('templates.admin')

@section('icon')
	<i class="glyphicon glyphicon-heart" style="color:#ddd;"></i>&nbsp;&nbsp;
@endsection

@section('title')
@if(Request::is('admin/stats/vetrine/vaat'))
	Vetrine Bambini Gratis <sup>Top</sup>
@elseif(Request::is('admin/stats/vetrine/vot'))
	Vetrine Offerte <sup>Top</sup>
@elseif(Request::is('admin/stats/vetrine/vst'))
	Vetrine Servizi <sup>Top</sup>
@elseif(Request::is('admin/stats/vetrine/vtt'))
	Vetrine Trattamenti <sup>Top</sup>
@endif
@endsection

@section('content')

	@if($stats_chart)
		<div id="chart_div" style="width: 100%; height: 500px;"></div>
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
					{!! Form::label('date_range', 'Scegli le date', array( 'class' => ' control-label') ) !!}<br />
					{!! Form::text('date_range', null, array('class' => 'daterange daterange-inline', 'data-format' => 'DD/MM/YYYY')) !!}
					<i class="entypo-calendar"></i>
				</div>
			
				<div class="col-sm-3">
					{!! Form::label('hotel', 'Hotel', array( 'class' => 'control-label') ) !!}<br />
					{!! Form::text('hotel', null, array('class' => 'form-control typeahead ricerca-hotel', 'data-local' => implode(',', Utility::getUsersHotels()), 'autocomplete' => 'off', 'placeholder' => "Scrivi l'id o il nome dell'hotel") ) !!}
				</div>
			 
				<div class="col-sm-3">
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
				
				@php 
					$totali=[];
				@endphp
		
				<div class="panel panel-default">
					
					<div style="overflow-x:auto;">
						
					<table class="table table-striped table-hover">
						
						<thead>
							
							<tr>
								
								<th width="150px"><b>(Giorno) / {{$mesi[intval($mese_k_stats)]}} / {{$anno_k_stats}}</b></th>
								
								@foreach($pagine as $pagina_id)		
									<th>
									 	@if(isset($data["pages"][$pagina_id])) /{{ $data["pages"][$pagina_id] }} @else #{{ $pagina_id }} @endif 
									</th>
								@endforeach	
								
								@if(count($data["pages"]) >1 )
									<th width="150px">
										Totale pagine
									</th>
								@endif
								
							</tr>
							
						</thead>
						
						<tbody>
							
							@foreach($mese_stats as $giorno_k_stats => $vot)
								
								@php $temp_totale = 0; @endphp
								
								<tr>
										
									<td>{{ $giorno_k_stats }}</td>
									
									@foreach($pagine as $pagina)
										@if (!isset($totali[$pagina]))
											@php $totali[$pagina] = 0 @endphp
										@endif
										<td>
											@if(isset($vot[$pagina]))
												{{$vot[$pagina]}}
												@php 
													$temp_totale += $vot[$pagina]; 
													$totali[$pagina] += $vot[$pagina]; 
												@endphp
											@else
												0
											@endif
										</td> 
									@endforeach 
									
									@if (count($pagine) > 1)
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
								@foreach($pagine as $pagina)
									<th>
										@if (isset($totali[$pagina]))
											{{$totali[$pagina]}}
											@php $totale_mese += $totali[$pagina]; @endphp
										@else
											0
										@endif
									</th>
								@endforeach 
								
								@if (count($pagine) > 1)
								<th>
									{{$totale_mese}}
								</th>
								@endif
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
    <link rel="stylesheet" type="text/css" href="{{Utility::assets('vendor/flags/flags.min.css', true)}}" />
	
<style>
.table-horizontal-scroll{
	overflow-x: scroll;
}
th span { display: block}
.table-horizontal-scroll th, .table-horizontal-scroll td{
	white-space: nowrap;
	text-align: right;
}
</style>


	@if($stats_chart)
		
		<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
		<script type="text/javascript">

		google.charts.load('current', {'packages':['corechart']});
		google.charts.setOnLoadCallback(drawChart);

		function drawChart() {

			var data = new google.visualization.DataTable();
			data.addColumn('string', '')

			@foreach($pagine as $pagine_id)	
				data.addColumn('number', @if(isset($data["pages"][$pagine_id])) '{{$data["pages"][$pagine_id]}}' @else 'ID Pagina {{ $pagine_id }}' @endif);
			@endforeach
			
			@foreach($stats_chart as $anno_k_stats => $anno_stats)
				@foreach($anno_stats as $mese_k_stats => $mese_stats)
					@foreach($mese_stats as $giorno_k_stats => $page_stats)
						data.addRows([
							['{{$giorno_k_stats}} {{$mesi[intval($mese_k_stats)]}} {{$anno_k_stats}}', @foreach($pagine as $pagina) @if(isset($page_stats[$pagina])) {{$page_stats[$pagina]}}, @else 0, @endif @endforeach],
						]);
					@endforeach
				@endforeach
			@endforeach
			
			var options = {
				title: '',
				hAxis: {title: '',titleTextStyle: {color: '#333'}},
				vAxis: {minValue: 0},
				legend: {position: 'top', maxLines: 5},
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