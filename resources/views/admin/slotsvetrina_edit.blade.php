@extends('templates.admin')

@section('title')
	@if ($data["record"]->exists)
		Modifica Slot vetrina<br /><small>{{ $vetrina->nome }} - {{ $vetrina->descrizione }}</small>
	@else
		Nuovo Slot vetrina<br /><small>{{ $vetrina->nome }} - {{ $vetrina->descrizione }}</small>
	@endif
@endsection

@section('content')

	@if ($data["record"]->exists)
		
		{!! Form::open(['id' => 'record_delete', 'url' => 'admin/vetrine/'.$vetrina->id.'/slots/'.$data["record"]->id, 'method' => 'DELETE']) !!}
		{!! Form::close() !!}
	
	@endif

@if ($data["record"]->exists)
  {!! Form::model($data["record"], ['role' => 'form', 'route'=>['vetrine.slots.update',$vetrina->id,$data["record"]->id], 'method' => 'PUT', 'class' => 'form-horizontal']) !!}
@else
  {!! Form::open(['role' => 'form', 'route'=>['vetrine.slots.store',$vetrina->id], 'method' => 'POST', 'class' => 'form-horizontal']) !!}
@endif 

	<br />
	<input type="hidden" name="id" value="<?php echo $data["record"]->exists ? $data["record"]->id : 0 ?>">
	

	<div class="form-group">
	    @if ($data["record"]->exists)
	    
			<div class="col-sm-2"></div>
			<div class="col-sm-5">
				<div class="panel-title">Vetrina {{$data["record"]->cliente->nome}} {{$data["record"]->cliente->stelle->nome}} - {{$data["record"]->cliente->localita->nome}}</div>
				<input type="hidden" name="hotel_id" value="<?php echo $data["record"]->exists ? $data["record"]->hotel_id : 0 ?>">
			</div>
	
	    @else
	
			{!! Form::label('hotel_id', 'Hotel', ['class' => 'col-sm-2 control-label']) !!}
			<div class="col-sm-5">
				<div class="input-group">
					{!! Form::select('hotel_id',$hotels,null,["class"=>"form-control"]) !!}
				</div>
			</div>
		
		@endif
	
	</div>
	
	<div class="form-group">
	    {!! Form::label('data_scadenza', 'Scadenza', ['class' => 'col-sm-2 control-label']) !!}
	    <div class="col-sm-3">
	        <div class="input-group">
	            <input name="data_scadenza" class="form-control datepicker" data-format="dd/mm/yyyy" type="text" value="@if($data["record"]->exists){{$data["record"]->data_scadenza->format("d/m/Y")}}@endif">
	            <div class="input-group-addon">
	                <a class="entypo-calendar" href="#" style="font-style: italic"></a>
	            </div>
	        </div>
	    </div>
	</div>
	
	<div class="form-group">
	    {!! Form::label('attiva', 'Attivo', ['class' => 'col-sm-2 control-label']) !!}
	    <div class="col-sm-3">
	        <div class="input-group">
	              {{--
	              You need this just before your checkbox definition:
	              This will make sure that a value of 0 is sent to the application when the checkbox is not checked.
	              --}}
	              <input name='attiva' type='hidden' value='0'>
	             {!! Form::checkbox('attiva', "1", $data["record"]->exists ? $data["record"]->attiva : 0, ["id" => "check_attiva"] ) !!}
	        </div>
	    </div>
	</div>
	
	<div class="form-group">
		<div class="col-sm-12">
			@include('templates.admin_inc_record_buttons')
		</div>
	</div>

{!! Form::close() !!}

@endsection

@section('onheadclose')

@endsection

@section('onbodyclose')
  <script type="text/javascript" src="{{Utility::assets('/vendor/neon/js/bootstrap-datepicker.js')}}"></script>
	
	<script type="text/javascript">
		jQuery( document ).ready(function() {
			
			jQuery('#check_attiva').change(function() {
	        if(!this.checked) {
	            var returnVal = confirm("In questo modo la vetrina NON SARÀ VISIBILE. Continuare ?");
	            jQuery(this).prop("checked", !returnVal);
	        }
	        jQuery('#textbox1').val(this.checked);        
	    });


		});
	</script>
@endsection