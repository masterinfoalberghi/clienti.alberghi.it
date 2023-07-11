@extends('templates.admin')

@section('title')
  @if ($gruppo->exists)
    Modifica gruppo {{ $gruppo->nome }}   
  @else
    Nuovo gruppo
  @endif
@endsection

@section('content')

@if ($gruppo->exists)
  {!! Form::model($gruppo, ['role' => 'form', 'route'=>['gruppo-hotels.update',$gruppo->id], 'method' => 'PUT', 'class' => 'form-horizontal']) !!}
@else
   {!! Form::open(['role' => 'form', 'route'=>['gruppo-hotels.store'], 'method' => 'POST', 'class' => 'form-horizontal']) !!}
@endif


  {!! csrf_field() !!}

  <input type="hidden" name="id" value="<?=($gruppo->exists ? $gruppo->id : 0)?>">


  <div class="form-group">
    {!! Form::label('nome', 'Nome', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
      {!! Form::text('nome', null, ['placeholder' => 'Nome', 'class' => 'form-control']) !!}
    </div>    
  </div>  

  <div class="form-group">
    {!! Form::label('note', 'Note', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
      {!! Form::textarea('note', null, ['placeholder' => 'Note', 'class' => 'form-control']) !!}
    </div>
  </div>

  <div class="form-group">
    {!! Form::label('hotels', 'Hotel nel gruppo', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">

       <select class="select2" multiple="" name="hotels[]">
          @foreach ($hotels as $id => $value)
            <option value="{{$id}}" @if ( is_array(old('hotels')) && in_array($id, old('hotels')) || isset($associated_hotels) && in_array($id, $associated_hotels) ) selected="" @endif>
                {{ $value }}
            </option>
          @endforeach
       </select>
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

<link href="{{Utility::assets('/vendor/neon/css/neon-forms.css')}}" rel="stylesheet" type="text/css" />
@endsection


@section('onbodyclose')

<link href="{{Utility::assets('/vendor/neon/js/select2/select2-bootstrap.css')}}" rel="stylesheet" type="text/css" />
<link href="{{Utility::assets('/vendor/neon/js/select2/select2.css')}}" rel="stylesheet" type="text/css" />
<script src="{{Utility::assets('/vendor/neon/js/select2/select2.min.js')}}"></script>
@endsection