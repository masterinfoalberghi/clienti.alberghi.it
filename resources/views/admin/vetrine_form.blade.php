@extends('templates.admin')

@section('title')
  @if (isset($vetrina))
    Modifica vetrina
  @else
    Nuova vetrina
  @endif
@endsection

@section('content')

  @if (isset($vetrina))
    {!! Form::open(['id' => 'record_delete', 'url' => 'admin/vetrine/'.$vetrina->id, 'method' => 'DELETE']) !!}
    {!! Form::close() !!}
  @endif


  @if (isset($vetrina))
    {!! Form::model($vetrina, ['role' => 'form', 'route'=>['vetrine.update',$vetrina->id], 'method' => 'PUT', 'class' => 'form-horizontal']) !!}
  @else
    {!! Form::open(['role' => 'form', 'route'=>['vetrine.store'], 'method' => 'POST', 'class' => 'form-horizontal']) !!}
  @endif 


    <div class="form-group">
          {!! Form::label('nome', 'Nome', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
          {!! Form::text('nome', null, ['class' => 'form-control']) !!}
        </div>
    </div> 
    
    <div class="form-group">
      {!! Form::label('descrizione', 'Descrizione', ['class' => 'col-sm-2 control-label']) !!}
      <div class="col-sm-10">
      {!! Form::textarea("descrizione", null, ["class" => "form-control", "rows" => 5]) !!}
      </div>
    </div>

    <div class="form-group">
      {!! Form::label('tipo', 'Tipo', ['class' => 'col-sm-2 control-label']) !!}
      <div class="col-sm-10">
         {!! Form::select('tipoVetrina_id', $tipo, null, ['class' => 'form-control']) !!}
      </div>
    </div>

    <div class="form-group">
        {!! Form::label('attiva', 'Attiva', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-3">
            <div class="input-group">
                  {{-- 
                  You need this just before your checkbox definition: 
                  This will make sure that a value of 0 is sent to the application when the checkbox is not checked.
                  --}}
                  <input name='attiva' type='hidden' value='0'>
                 {!! Form::checkbox('attiva', "1", null) !!}
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