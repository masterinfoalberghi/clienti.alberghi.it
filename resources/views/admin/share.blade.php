@extends('templates.admin')

@section('icon')
	<i class="glyphicon glyphicon-share-alt" style="color:#ddd;"></i>&nbsp;&nbsp;
@endsection

@section('title')
	Condivisioni
@endsection

@section('content')

<div class="panel panel-default">
	
	<div class="panel-heading">
		<div class="panel-title">Affina la ricerca</div>
		<div class="panel-options"></div>
	</div>

	<div class="panel-body">
				
		<input class="filtro form-control" name="filtro" value="{{$filtro_request}}" style="height:38px; width:300px; margin-left: 10px; display: inline-block; " placeholder="es: hotel.php, rimini.php etc..."/>
		<a title="Calcola" id="calcola" data-url="" class="btn btn-primary">Calcola</a>
		
	</div>
	
</div>

<div class="panel panel-default" data-collapsed="0">
	<div class="panel-body no-padding">
		<table id="records" class="table table-striped table-hover" >
			<thead>
				 <tr>
					 <th>Url condiviso</th>
					 <th>Condivisioni</th>
					 <th>Ritorno Condivisione</th>
					 <th>% ROI</th>
				 </tr>
			</thead>
		 	<tbody>
				@php $t = 0; @endphp
				@foreach($share_data as $sh)
				 
					 <tr @if ($t > 30) class="hide" @endif>
						 <td>{{$sh->uri}}</td>
						 <td>{{$sh->count}}</td>
						 <td>{{$sh->roi}}</td>
						 <td>
							<?php
								 
								 if ($sh->roi == 0) {
								 	echo '<span><i class="glyphicon glyphicon-remove"></i></span>';
								} else if (($sh->roi / $sh->count) == 1) {
								 	echo '<span><i class="glyphicon glyphicon-minus"></i></span>';
								 } else if (($sh->roi / $sh->count) < 1) {
								 	echo '<span style="color:#990000;"><i class="glyphicon glyphicon-arrow-down"></i>&nbsp;&nbsp;'.(round(($sh->roi / $sh->count)*100,2)-100).'%</span>';
								 } else {
								 	echo '<span style="color:#5CBE4A;"><i class="glyphicon glyphicon-arrow-up"></i>&nbsp;&nbsp;+'.(round(($sh->roi / $sh->count)*100,2)).'%</span>';
								 }
								
							?>
						 </td>
					 </tr>
					 
					 @php $t++; @endphp
				 @endforeach
			</tbody>
		</table>
	
	</div>
</div>
		<a id="showall" class="btn btn-primary showall">Vedi tutto</a>
	 
	@endsection

	@section('onheadclose')
		
		<style>
			.hide { display:none !important; }
		</style>

	@endsection

	@section('onbodyclose')
		
		<script>
		
			jQuery(function() {

				jQuery("#showall").click(function(){

					jQuery("tr").removeClass("hide");
					jQuery(this).hide();

				});

				jQuery("#calcola").click(function(){

					var filtro = jQuery(".filtro").val();
					var uri = "?f=" + filtro;
					document.location.href="/admin/stats/share" + uri;

				});
			
			});
		
		</script>
		
	@endsection