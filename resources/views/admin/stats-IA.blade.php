@extends('templates.admin')

@section('title')
Mail Schede Giornaliere totali
@endsection

@section('content')

	{!! Form::open(array('action' => 'Admin\StatsController@statsIAResult', 'class' => 'form-horizontal')) !!}

	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="panel-title">Modifica le date per il confronto</div>
	    </div>
		  
		<div class="panel-body">
			<div class="form-group">
	  			<div class="col-sm-3">
	  				  {!! Form::label('date_range', 'Scegli le date', array( 'class' => 'control-label') ) !!}
	  				  {!! Form::text('date_range', null, array('class' => 'daterange_1 daterange-inline', 'data-format' => 'DD/MM/YYYY')) !!}
	  				  <i class="entypo-calendar"></i>
	  			  </div>
				  <div class="col-sm-3">
					  <div class="control-label">&nbsp;</div>
					  <button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-ok"></i> Calcola</button>
				  </div>
			  </div>
		  </div>
		  
	</div>
	
	<div class="row">
	@if($email_dirette_totali_this_year)
		<div class="col-md-6 col-xs-12">
		<h2>Email dirette (Andamento)</h2>
		<div id="email_dirette_andamento" style="width: 100%; height: 300px;"></div>
	</div>
	@endif

	@if($email_multiple_totali_this_year)
		<div class="col-md-6 col-xs-12">
		<h2>Email multiple (Andamento)</h2>
		<div id="email_multiple_andamento" style="width: 100%; height: 300px;"></div>
	</div>
	@endif
	
	@if($stats_dirette)
		<div class="col-md-6 col-xs-12">
		<h2>Email dirette</h2>
		<div id="email_dirette" style="width: 100%; height: 300px;"></div>
	</div>
	@endif
	
	@if($stats_multiple)
		<div class="col-md-6 col-xs-12">
		<h2>Email multiple</h2>
		<div id="email_multiple" style="width: 100%; height: 300px;"></div>
	</div>
	@endif
	</div>
	


{!! Form::close() !!}

@endsection


@section('onheadclose')
	
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
	
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
	   
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

		 var options = {
  			title: '',
  			hAxis: {title: ''},
  			vAxis: {minValue: 0},
  			legend: {position: 'top'},
  			isStacked: true,
  		};
		
		var options_andamenti = {
		   title: '',
		   hAxis: {title: ''},
		   vAxis: {minValue: 0},
		   legend: {position: 'top'},
		   isStacked: false,
	   };

		@if($stats_dirette)

			var data_dirette = new google.visualization.DataTable();
		  	data_dirette.addColumn('string', '')
		  	data_dirette.addColumn('number', 'Desktop');
		  	data_dirette.addColumn('number', 'Mobile');
		  	data_dirette.addColumn('number', 'Doppie');

			@foreach($stats_dirette[0] as $anno_k_stats => $anno_stats)
				@foreach($anno_stats as $mese_k_stats => $mese_stats)
					@foreach($mese_stats as $giorno_k_stats => $conteggio)
							
						@php
						
							$normale 		= isset($conteggio["normale"]) ? $conteggio["normale"] : 0;
							$mobile 		= isset($conteggio["mobile"]) ? $conteggio["mobile"] : 0;
							$doppia 		= isset($conteggio["doppia"]) ? $conteggio["doppia"] : 0;
							
						@endphp
						
						data_dirette.addRows([
							['{{$giorno_k_stats}} {{$mesi[intval($mese_k_stats)]}}', {{$normale}}, {{$mobile}}, {{$doppia}}],
						]);
					
					@endforeach
				@endforeach
			@endforeach
			
	

	        var chart_dirette = new google.visualization.ColumnChart(document.getElementById('email_dirette'));
	        chart_dirette.draw(data_dirette, options);
			
		@endif
		
		options_andamenti.colors = ['#3669C9', '#DA3B21', '#1D9524'];
		
		@if($email_dirette_totali_this_year)
			
			var data_dirette_andamento = new google.visualization.DataTable();
			data_dirette_andamento.addColumn('string', '')		
			data_dirette_andamento.addColumn('number', 'Anno precedente');
			data_dirette_andamento.addColumn('number', 'Range selezionato');
			
			
			@foreach($email_dirette_totali_this_year as $anno_k_stats => $anno_stats)
				@foreach($anno_stats as $mese_k_stats => $mese_stats)
					@foreach($mese_stats as $giorno_k_stats => $conteggio)
						
						data_dirette_andamento.addRows([
							['{{$giorno_k_stats}} {{$mesi[intval($mese_k_stats)]}}',  {{$email_dirette_totali_last_year[(int)($anno_k_stats-1)][$mese_k_stats][$giorno_k_stats]}}, {{$conteggio}}]
						]);
					
					@endforeach
				@endforeach
			@endforeach
			
			var chart_dirette_andamento = new google.visualization.AreaChart(document.getElementById('email_dirette_andamento'));
	        chart_dirette_andamento.draw(data_dirette_andamento, options_andamenti);
			
		@endif

		@if($stats_multiple)
				
			var data_multiple = new google.visualization.DataTable();
		  	data_multiple.addColumn('string', '')
		  	data_multiple.addColumn('number', 'Desktop');
		  	data_multiple.addColumn('number', 'Mobile');
		  	data_multiple.addColumn('number', 'Wishlist');
		  	data_multiple.addColumn('number', 'Doppie');
			
			options_andamenti.colors = ['#3669C9', '#DA3B21', '#FD9827', '#1D9524'];
			
			@foreach($stats_multiple[0] as $anno_k_stats => $anno_stats)
				@foreach($anno_stats as $mese_k_stats => $mese_stats)
					@foreach($mese_stats as $giorno_k_stats => $conteggio)
							
						@php
						
							$normale 		 = isset($conteggio["normale"]) ? $conteggio["normale"] : 0;
							$mobile 		 = isset($conteggio["mobile"]) ? $conteggio["mobile"] : 0;
							$wishlist 		 = isset($conteggio["wishlist"]) ? $conteggio["wishlist"] : 0;
							$doppia 		 = 0;
								isset($conteggio["doppia"]) ? $doppia += $conteggio["doppia"] : "";
								isset($conteggio["doppia-parziale"]) ? $doppia += $conteggio["doppia-parziale"] : "";
										
						@endphp
						
						data_multiple.addRows([
							['{{$giorno_k_stats}} {{$mesi[intval($mese_k_stats)]}}', {{$normale}}, {{$mobile}}, {{$wishlist}}, {{$doppia}}],
						]);
					
					@endforeach
				@endforeach
			@endforeach
			
	        var chart_multiple = new google.visualization.ColumnChart(document.getElementById('email_multiple'));
	        chart_multiple.draw(data_multiple, options);		

		@endif
		
		@if($email_multiple_totali_this_year)
			
			var data_multiple_andamento = new google.visualization.DataTable();
			data_multiple_andamento.addColumn('string', '')
			data_multiple_andamento.addColumn('number', 'Anno precedente');
			data_multiple_andamento.addColumn('number', 'Range selezionato');
			
			
			@foreach($email_multiple_totali_this_year as $anno_k_stats => $anno_stats)
				@foreach($anno_stats as $mese_k_stats => $mese_stats)
					@foreach($mese_stats as $giorno_k_stats => $conteggio)
						
						data_multiple_andamento.addRows([
							['{{$giorno_k_stats}} {{$mesi[intval($mese_k_stats)]}}', {{$email_multiple_totali_last_year[(int)($anno_k_stats-1)][$mese_k_stats][$giorno_k_stats]}}, {{$conteggio}}]
						]);
					
					@endforeach
				@endforeach
			@endforeach
			
			var chart_multiple_andamento = new google.visualization.AreaChart(document.getElementById('email_multiple_andamento'));
	        chart_multiple_andamento.draw(data_multiple_andamento, options_andamenti);
			
		@endif

  	}

</script>
	
@endsection
@section('onbodyclose')
	
	<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

	<script type="text/javascript" charset="utf-8">
		
		var currentYear = new Date().getFullYear() 
		var dateRange = {};
		  dateRange["Oggi"] = [moment(), moment()];
		  dateRange["Ieri"] =  [moment().subtract(1, 'days'), moment().subtract(1, 'days')];
		  dateRange["Ultimi 7 giorni"] =  [moment().subtract(6, 'days'), moment()];

		  dateRange["Anno " + (currentYear)] = [moment().startOf('year'), moment().endOf('year')],
		  dateRange["Anno " + (currentYear - 1)] = [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')];
		  dateRange["Anno " + (currentYear - 2)] = [moment().subtract(2, 'year').startOf('year'), moment().subtract(2, 'year').endOf('year')];

		  var date_from_js = moment();
		  var date_to_js = moment();
		   @isset($date_from_js) 
		   var date_from_js = '{{$date_from_js}}';
		   @endisset

		   @isset($date_to_js) 
		   var date_to_js = '{{$date_to_js}}';
		   @endisset

		jQuery('input[name="date_range"]').daterangepicker({
			locale: {
      	format: 'DD/M/Y'
    	},
			ranges: dateRange,
			showCustomRangeLabel:false,
			startDate:date_from_js,
			endDate:date_to_js
		});
		
	</script>

</script>
	
@endsection