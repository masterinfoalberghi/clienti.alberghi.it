@extends('templates.admin')

@section('title')
  <img src="{{ Utility::assetsImage('icons/pieno.png') }}" alt="Mi piace" style="width: 30px; vertical-align: text-bottom;"> {{$count_preferiti}} "Mi piace"
@endsection


@section('onheadclose')
      
	  @if ($dati->count())
	      <!--Load the AJAX API-->
	      <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

	      <script type="text/javascript">

	        // Load the Visualization API and the corechart package.
	        google.charts.load('current', {'packages':['corechart']});

	        // Set a callback to run when the Google Visualization API is loaded.
	        google.charts.setOnLoadCallback(drawChart);

	        // Callback that creates and populates a data table,
	        // instantiates the pie chart, passes in the data and
	        // draws it.

	        function drawChart() {
	                var data = google.visualization.arrayToDataTable([
	                  ['data','like'],
	                  @foreach ($dati as $dato)
	                    ['{{$dato->la_data}}', {{$dato->totale}}],
	                  @endforeach
	                ]);

	                var options = {
	                  title: 'Andamento dei like',
	                  legend: { position: 'bottom' },
	                  colors: ['#940c11'],
	                };


	          // Instantiate and draw our chart, passing in some options.
	          var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

	          chart.draw(data, options);
	        }
	      </script>
  		@endif

@endsection

@section('content')
    
	<!--Div that will hold the pie chart-->
	@if ($dati->count())
		<div id="curve_chart" style="width: 900px; height: 500px"></div>
	@endif
	
@endsection