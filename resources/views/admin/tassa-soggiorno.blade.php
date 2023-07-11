@extends('templates.admin')

@section('title')
Tassa di soggiorno
@endsection

@section('content')
	
	<div class="row">
		<div class="col-sm-12">

		{!! Form::model($data["record"], ['role' => 'form', 'url' => 'admin/tasse-soggiorno', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
			{!! csrf_field() !!}
			
			{!! Form::hidden( 'id', $data['id'] ) !!}
			
			<div class="form-group">
				 <div class="col-sm-2 ">
					  <div class="checkbox">
						<label>
							{!! Form::checkbox( 'attiva', 1, $data['attiva'], ["id" => "attiva"]) !!} Attiva le infomazioni sulla tassa di soggiorno
						</label>
					</div>
				</div>
			
				 <div class="col-sm-2">
					  <div class="checkbox">
						<label>
							{!! Form::checkbox( 'applicata', 1, $data['applicata'], ["id" => "applicata"]) !!} La tassa di soggiorno è applicata.
						</label>
					</div>
				</div>
			</div>
			
			<div class="panel panel-default attiva applicata" data-collapsed="0" @if (!$data['attiva'] || !$data["applicata"]) style="display:none;" @endif>
				
				<div class="panel-heading">
					<div class="panel-title">Informazioni di base</div>
				</div>  
				
				<div class="panel-body">
						
						<div class="form-group">
							 <div class="col-sm-10 col-sm-offset-3">
		 			              <div class="checkbox">
		 			                <label>
										{!! Form::checkbox( 'inclusa', 1, $data["record"]['inclusa'] ) !!} La tassa di soggiorno è inclusa nel prezzo?
									</label>
								</div>
							</div>
						</div>
						
						<div class="form-group">
							{!! Form::label('valore',  'Valore della tassa', ['class' => 'control-label col-sm-3'] ) !!}
							<div class="col-sm-8">
								{!! Form::text( 'valore', $data["record"]['valore'], ['class' => 'form-control misure'] ) !!}
								 <small>Il valore della tassa in &euro; applicabile a persona. Lasciare vuoto se non si vuole specificare una prezzo.</small>
							</div>
							
						</div>
						
						<div class="form-group">
							{!! Form::label('max_giorni',  'Numero di giorni massimi', ['class' => 'control-label col-sm-3'] ) !!}
							<div class="col-sm-8">
								{!! Form::text( 'max_giorni', $data["record"]['max_giorni'] , ['class' => 'form-control misure']) !!} 
								 <small>Numero massimo di giorni per cui &egrave; applicabile la tassa di soggiorno a persona.</small>
							</div>						
						</div>
						
						<hr />
						
						<div class="form-group">
							 <div class="col-sm-9 col-sm-offset-3">
		 			              <div class="checkbox">
		 			                <label>
										{!! Form::checkbox( 'bambini_esenti', 1, $data["record"]['bambini_esenti'], ["id" => "bambini_esenti"] ) !!} I bambini sono esenti dalla tassa?
									</label>
							  	</div>
							</div>
						</div>
						
						<div class="form-group bambini_esenti" @if (!$data["record"]['bambini_esenti'] ) style="display:none; " @endif>
							{!! Form::label('eta_bambini_esenti',  'fino all\'età di anni', ['class' => 'control-label col-sm-3'] ) !!}
							<div class="col-sm-9">
								{!! Form::text( 'eta_bambini_esenti', $data["record"]['eta_bambini_esenti'] , ['class' => 'form-control misure']) !!} 
								<small>Lasciare vuoto se non si vuole specificare un'et&agrave;</small>
							</div>						
						</div>
						
						<hr />
						
						<div class="form-group">
							 <div class="col-sm-9 col-sm-offset-3">
		 			              <div class="checkbox">
		 			                <label>
										{!! Form::checkbox( 'validita_data', 1, $data["record"]['validita_data'], ["id" => "validita_data"] ) !!} Le tasse di soggiorno sono valide solo per alcune date.?
									</label>
								</div>
							</div>
						</div>
		
						<div class="form-group validita_data" @if (!$data["record"]['validita_data']) style="display:none; " @endif>
												 
							{!! Form::label('valido_dal', 'Per soggiorni dal / al ', ['class' => 'col-sm-3 control-label']) !!}
								<div class="col-sm-9 ">
									{!! Form::text('data_iniziale', (($data["record"]['data_iniziale'] != "2000-01-01" &&  $data["record"]['data_iniziale'] != "0000-00-00") ? Carbon\Carbon::parse($data["record"]['data_iniziale'])->format("d/m/Y") : ""), ['id' => 'data_iniziale', 'class' => ' form-control dateicon', 'style' => 'width:150px;display:inline-block;']) !!}          
								&rarr;
									{!! Form::text('data_finale',  (($data["record"]['data_finale'] != "2000-01-01" && $data["record"]['data_finale'] != "0000-00-00") ? Carbon\Carbon::parse($data["record"]['data_finale'])->format("d/m/Y") : ""), ['id' => 'data_finale',  'class' => ' form-control dateicon', 'style' => 'width:150px;display:inline-block;']) !!}
									<br /><small>Selezionare il periodo relativo a quest'anno. Le date verranno rinnovate negli anni successivi nello stesso arco di tempo.</small>
								</div>
								
						</div>
					
				</div>
			</div>
			
			
			<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-ok"></i> Salva</button>
		
			
		{!! Form::close() !!}

		</div>
	</div>
	
	
@endsection

@section('onbodyclose')

  <link rel="stylesheet" type="text/css"  href="{{Utility::assets('/vendor/datepicker3/datepicker.min.css', true)}}" />
  <script type="text/javascript"          src="{{Utility::assets('/vendor/moment/moment.min.js', true)}}"></script>
  <script type="text/javascript"          src="{{Utility::assets('/vendor/datepicker3/datepicker.min.js', true)}}"></script>
  <script type="text/javascript"          src="{{Utility::assets('/vendor/datepicker3/locales/bootstrap-datepicker.it.min.js')}}"></script>

<script type="text/javascript">

  jQuery(document).ready(function($) {
	  
	  	$("input[type=checkbox]").click(function () {
									
			var item = $(this).attr("id");
			var value = $(this).is(":checked");
			
			if (item == "applicata" || item == "attiva") {
				
				if ($("#attiva").is(":checked") && $("#applicata").is(":checked")) {
					$("." + item).slideDown();
				} else {
					$("." + item).slideUp();
				}
				
			} else {
					
				if (value) {
					$("." + item).slideDown();
				} else {
					$("." + item).slideUp();
				}
				
			}
			
			
		});
			  
		$("#data_iniziale").datepicker({ 

		  format: 'dd/mm/yyyy', 
		  weekStart:1,
		  startDate: moment().startOf('year').format("D/M/Y"),
		  endDate: moment().endOf('year').format("D/M/Y"),
		  language: "it",
		  orientation: "bottom left",
		  todayHighlight: true,
		  autoclose: true

		}).on("changeDate", function(e) {
		  
		  var data_dal = moment($("#data_iniziale").datepicker("getDate"));
		  var data_al = moment($("#data_finale").datepicker("getDate"));

		  if (data_al.isBefore(data_dal)) {

			data_al = data_dal.add(1, 'd');
			$("#data_finale").datepicker("setDate", data_al.format("D/M/Y") );

		  }

		  $("#data_finale").datepicker("setStartDate", moment(e.date).format("D/M/Y") );
		  $("#data_iniziale").datepicker("setRange", [ data_dal, data_al.add("1","d")]);
		  $("#data_finale").datepicker("setRange", [ data_dal, data_al]);
		  $("#data_finale").datepicker("show");

		});
		$("#data_finale").datepicker({ 
		  format: 'dd/mm/yyyy', 
		  weekStart:1,
		  startDate: moment().startOf('year').format("D/M/Y"),
		  endDate: moment().endOf('year').format("D/M/Y"),
		  language: "it",
		  orientation: "bottom left",
		  todayHighlight: true,
		  autoclose: true

		}).on("changeDate", function(e) {

		  var data_dal = moment($("#data_iniziale").datepicker("getDate"));
		  var data_al = moment($("#data_finale").datepicker("getDate"));

		  $("#data_iniziale").datepicker("setRange", [ data_dal, data_al.add("1","d")]);
		  $("#data_finale").datepicker("setRange", [ data_dal, data_al]);

		});
	
	});
	</script>
	
@endsection
