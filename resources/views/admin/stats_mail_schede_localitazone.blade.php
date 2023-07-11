@extends('templates.admin')

@section('title')
	Mail Scheda Località/Zone, Statistiche mail schede totali annuali per Località/Zone
@endsection

@section('content')

<form method="POST" action="/admin/stats/mail_scheda_localitazone" accept-charset="UTF-8" class="form-horizontal">

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
  		  
		 <div class="col-sm-4">
  	      	{!! Form::label('tipo', 'Tipo raggruppamento', array( 'class' => ' control-label') ) !!}
  	        {!! Form::select( 'tipo', ['localita' => 'Località', 'macrolocalita' => 'Macrolocalità'], null, ['class' => 'form-control'] ) !!}
  	      </div>

  		  <div class="col-sm-2">
  			  <div class="control-label">&nbsp;</div>
  			  <button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-ok"></i> Calcola</button>
  		  </div>
  	  
  	  </div>
  	  
    </div>

   </div>

</form>

@if($stats)
	
	<div class="panel panel-default" data-collapsed="0">
	  <div class="panel-body no-padding">
		<table id="stats_results" class="table table-striped table-hover">
		  <thead>
			<th>Località</th>
			<th>Email dirette</th>
			<th>Email multiple</th>
		  </thead>
		
		  <tbody>
			  @foreach ($stats as $row_stats)
				  <tr>
					<td>{{ $row_stats['nome'] }}</td>
					<td>{{ $row_stats["dirette"] }}</td>
					<td>{{ $row_stats["multiple"] }}</td>
				  </tr>
			  @endforeach
		  </tbody>
			
		</table>
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

    <script type="text/javascript" src="{{Utility::assets('/vendor/neon/js/jquery.dataTables.min.js')}}"></script>
<script type="text/javascript" src="{{Utility::assets('/vendor/neon/js/datatables/TableTools.min.js')}}"></script>
	
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			var dt_stats = $('#stats_results').dataTable({
				"paging": false,
				"info": false
			});
			dt_stats.closest( '.dataTables_wrapper' ).find( 'select' ).select( {
			minimumResultsForSearch: -1
			});
		});
	</script>
@endsection