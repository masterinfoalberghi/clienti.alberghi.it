@extends('templates.admin')

@section('title')
  Inserisci un nuovo punto di interesse
  
@endsection

{{-- @php
  foreach($macro as $key => $m) {
    if (gettype(json_decode($m->nome)) == 'object') {
        $macro[$key]->nome = json_decode($m->nome)->it;
    }
  }
@endphp --}}

@section('onheadclose')
  <link type="text/css" rel="stylesheet" href="{{ Utility::assets('/vendor/oldbrowser/css/multiselect.min.css') }}"  />
@stop

@section('content')

    @if (isset($poi))
      {!! Form::open(['id' => 'record_delete', 'url' => 'admin/poi/'.$poi->id, 'method' => 'DELETE']) !!}
      {!! Form::close() !!}
    @endif

    @if (isset($poi))
      {!! Form::model($poi, ['role' => 'form', 'route'=>['poi.update',$poi->id], 'method' => 'PUT', 'class' => 'form-horizontal']) !!}
    @else
      {!! Form::open(['role' => 'form', 'route'=>['poi.store'], 'method' => 'POST', 'class' => 'form-horizontal']) !!}
    @endif


    <div class="form-group">
        {!! Form::label('nome', 'Nome', ['class' => 'col-sm-3 control-label']) !!}
      <div class="col-sm-4"> 
        @if (isset($poi))
          {!! Form::text('nome', $poi_nome, ['class' => 'form-control']) !!}
        @else
          {!! Form::text('nome', null, ['class' => 'form-control']) !!}
        @endif  
      </div>
    </div>
    <div class="form-group">
      {{-- @foreach ($icone as $icona)
          <i class="{{$icona}}"></i>
      @endforeach --}}
        {!! Form::label('icona', 'Icona', ['class' => 'col-sm-3 control-label']) !!}
      <div class="col-sm-4">       
         <select class="form-control icon_poi" name="icona">
            @foreach ($icone as $unicode => $icona)
              <option value="{{$icona}}" @if (isset($poi) && $icona == $poi->icona) selected @endif>
                &#x{{$unicode}} - {{$icona}}
              </option>
            @endforeach
          </select>
      </div>
    </div>
    <div class="form-group">
        {!! Form::label('categoria_id', 'Categoria', ['class' => 'col-sm-3 control-label']) !!}
      <div class="col-sm-4">       
         {!! Form::select('categoria_id', $categories, isset($poi) ? $poi->categoria_id : null, ['class' => 'form-control' ]) !!}
      </div>
    </div>
    <div class="form-group">
        {!! Form::label('lat', 'Latitudine', ['class' => 'col-sm-3 control-label']) !!}
      <div class="col-sm-4">       
        {!! Form::text('lat', null, ['class' => 'form-control']) !!}
      </div>
    </div>
    <div class="form-group">
        {!! Form::label('long', 'Longitudine', ['class' => 'col-sm-3 control-label']) !!}
      <div class="col-sm-4">        
        {!! Form::text('long', null, ['class' => 'form-control']) !!}
      </div>
    </div>

    <div class="row" style="margin-top: 30px;">
      <div class="col-md-offset-2 col-md-8">
        <div class="form-group">
          <div class="col-form force">
            <label>Seleziona a quali località è associato questo POI</label>
            <div class="drop"><select name="localita[]" id="multiple_loc" multiple="multiple">
            @foreach ($macro as $m)
              @if(gettype(json_decode($m->nome)) == 'object')
                <optgroup label="{{json_decode($m->nome)->it}}">
              @else
                <optgroup label="{{$m->nome}}">
              @endif
              
              @foreach ($m->localita()->orderBy('nome', 'asc')->get() as $l)
                @if (in_array($l->id, $localita_poi_arr))
                  @if(gettype(json_decode($l->nome)) == 'object')
                    <option value="{{$l->id}}"" selected='selected'>{{json_decode($l->nome)->it}}</option>
                  @else
                    <option value="{{$l->id}}"" selected='selected'>{{$l->nome}}</option>
                  @endif
                @else
                  @if(gettype(json_decode($l->nome)) == 'object')
                    <option value={{$l->id}}>{{json_decode($l->nome)->it}}</option>
                  @else
                    <option value={{$l->id}}>{{$l->nome}}</option>
                  @endif
                @endif

              @endforeach
              
              </optgroup>

            @endforeach
            </select></div>

            <div class="button_select" style="float: right;"">
              <a class="button_select_link" href='#' id='deselect-all'>{!! trans("labels.deseleziona_tutto") !!}</a>
            </div>
            
            <div class="clear"></div>
          </div>
        </div>
      </div>
    </div>

   <div class="form-group">
      <div class="col-sm-12">
        @include('templates.admin_inc_record_buttons')
      </div>
    </div>
    
    {!! Form::close() !!}
    
    <hr/><hr/>
    
    <ul class="poi">
      @foreach ($poi_list as $poi)
        <li>
          <a href="{{ url('admin/poi/'.$poi->id.'/edit') }}">
            {{$poi->poi_lingua->first()->nome}} ( {{ optional($poi->categoria)->nome_it }}) <i class="{{$poi->icona}}"></i>
          </a>
        </li>
        <div class="bootstrap-tagsinput" style="width:70%; margin-left: 20px;">
          @foreach ($poi->localita as $l)
            <span class="tag label label-info">{{$l->nome}}</span> 
          @endforeach
        </div>
      @endforeach
    </ul>

    <hr/><hr/>
    @foreach ($macro as $m)
    <p><a href="{{ url('admin/poi/hotels-macrolocalita/'.$m->id) }}">{{$m->nome}}</a></p>
    <ul>
      @foreach ($m->localita()->orderBy('nome', 'asc')->get() as $l)
      <li class="localita_poi"> 
      <a href="{{ url('admin/poi/hotels-localita/'.$l->id) }}">Aggiorna i POI e le relative distanze di tutti gli hotel di {{$l->nome}}</a>
      @if ($l->updated_at >= $today)
        <span class="badge badge-info badge-roundless">aggiornato oggi</span> 
      @endif
      </li>
      @endforeach
    </ul>
    @endforeach


@endsection


@section('onbodyclose')

  <script type="text/javascript" src="{{Utility::assets('/vendor/oldbrowser/js/jquery.multiselect.min.js')}}"></script>

  <script type="text/javascript">
    jQuery('#multiple_loc').multiSelect({ selectableOptgroup: true });
    
    jQuery('#select-all').click(function(e){
      e.preventDefault();
      jQuery('#multiple_loc').multiSelect('select_all');
    });
    
    jQuery('#deselect-all').click(function(e){
      e.preventDefault();
      jQuery('#multiple_loc').multiSelect('deselect_all');
    });
  </script>

@stop

