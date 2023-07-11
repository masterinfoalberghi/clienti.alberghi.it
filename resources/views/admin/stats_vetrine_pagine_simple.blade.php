@extends('templates.admin')

@section('icon')
	<i class="glyphicon glyphicon-star-empty" style="color:#ddd;"></i>&nbsp;&nbsp;
@endsection

@section('title')
  @if(Request::is('admin/stats/vetrine/vaatSimple'))
    Vetrine Bambini Gratis Top
  @elseif(Request::is('admin/stats/vetrine/votSimple'))
    Vetrine Offerte Top
  @elseif(Request::is('admin/stats/vetrine/vstSimple'))
    Vetrine Servizi Top
  @elseif(Request::is('admin/stats/vetrine/vttSimple'))
    Vetrine Trattamenti Top
  @endif
@endsection

@section('content')

	@if($stats_chart)
		<div id="chart_div" style="width: 100%; height: 500px;"></div>
	@endif

{!! Form::open(array('class' => 'form-horizontal')) !!}

<div class="panel panel-default">

	<div class="panel-heading">
		<div class="panel-title">Selezionare i parametri di ricerca</div>
		<div class="panel-options"></div>
	</div>
	
	<div class="panel-body">
	
		<div class="form-group">
		
		<div class="col-sm-3">
			{!! Form::label('anno', 'Anno', ['class' => 'control-label']) !!}<br />
			{!! Form::selectYear('anno',$min_val_year,$max_val_year, date('Y'),["id" => "anno_select", "class"=>"form-control"]) !!}
		</div>
		 
		<div class="col-sm-3">
			{!! Form::label('mese', 'A partire dal mese di', ['class' => 'control-label']) !!}<br />
			{!! Form::selectMonth('mese',null,["id" => "mese_select", "class"=>"form-control"]) !!}
		</div>
	 
		<div class="col-sm-3">
			{!! Form::label('hotel', 'Hotel', array( 'class' => 'control-label') ) !!}<br />
			{!! Form::text('hotel', null, array('class' => 'form-control typeahead ricerca-hotel', 'data-local' => implode(',', Utility::getUsersHotels()), 'autocomplete' => 'off', 'placeholder' => "Scrivi l'id o il nome dell'hotel") ) !!}
		</div>

		<div class="col-sm-3">
			<div class="control-label">&nbsp;</div>
			<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-ok"></i> Calcola</button>
		</div>
		
		<div class="clearfix"></div>
		{!! Form::hidden('raggruppa', "0", null) !!}
		{{-- @if (isset($hotel_id) && $hotel_id != 0)
			
		@else
			<div class="col-sm-12" style="padding:15px;">
				<label>
					{!! Form::checkbox('raggruppa', "1", null) !!} Raggruppa per hotel
				</label>
			</div>
		@endif --}}
		
		</div>
		
	</div>
	
</div>

{!! Form::close() !!}

@if ($stats)

	<div class="row">
		<div class="col-sm-12">
			<div class="alert alert-warning">
				ATTENZIONE: Le statistiche mostrate hanno <b>granularità mensile</b> ed i conteggi sono <b>raggruppati per pagine senza filtro sulla lingua</b>
			</div>
		</div>
	</div>

	@foreach($stats as $pagina_url => $pagina_stats)
	 
	<h2>{{ $pagina_url }}</h2>
	  
    <div class="panel panel-default" data-collapsed="0">
     	  
        
          <table class="table table-striped table-hover">
            <thead>
              <tr>
                <th width="50%">Mese Anno</th>
                <th>Click</th>
              </tr>
            </thead>
            <tbody>
				
				@foreach($pagina_stats as $periodo => $val)
					
					@if($devo_raggruppare)
						
						<table class="table table-striped table-hover">
							<thead>
								<tr>
									<th width="50%">{{ $periodo }}</th>
									<th></th>
								</tr>
							</thead>
							
							@if (isset($gruppi[$pagina_url]))
								
								@if (isset($gruppi[$pagina_url][$periodo]) && count($gruppi[$pagina_url][$periodo]))
									@foreach ($gruppi[$pagina_url][$periodo] as $nome_hotel => $click)
										<tr><td>{{$nome_hotel}}</td><td>{{$click}}</td></tr>
									@endforeach
								@endif

								<tfoot>
									<tr>
										<th width="50%">Totali</th>
										<th>{{$val}}</th>
									</tr>
								</tfoot>
								
							@endif

						</table><br /><br />
						
					@else
						
						<tr>
							<td width="50%">{{ $periodo }}</td>
							<td>{{$val}}</td>
						</tr>
						
					@endif
					
				@endforeach
			  
			</tbody>

			@if(!$devo_raggruppare)
				 
				 <tr>
					 <th width="50%">Totale</th>
					 <th>{{$totali[$pagina_url]}}</th>
				 </tr>
				 
			@endif 
				
          </table>
    
    </div>
  @endforeach

@endif

@endsection

@section('onheadclose')
	
	{{-- {!! HTML::style('neon/js/daterangepicker/daterangepicker-bs3.css'); !!} --}}
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
	
	{{-- @if(isset($pagina_stats))
	{{dd($pagina_stats)}}
	@endif --}}
	
	@if($stats_chart)
		
		<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
		<script type="text/javascript">
			
			google.charts.load('current', {'packages':['corechart']});
			google.charts.setOnLoadCallback(drawChart);
			
			function drawChart() {
				
				var data = new google.visualization.DataTable();
				data.addColumn('string', '');
				data.addColumn('number', 'Click totale su tutte le pagine');
				
		
				@foreach($stats_chart as $periodo => $val)
					data.addRows([
						['{{$periodo}}', {{$val}}]
					]);
				@endforeach
			
			
				var options = {
					title: '',
					hAxis: {title: '',titleTextStyle: {color: '#333'}},
					vAxis: {minValue: 0},
					legend: {position: 'top', maxLines: 5},
					isStacked: true,
				};
				
				var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
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