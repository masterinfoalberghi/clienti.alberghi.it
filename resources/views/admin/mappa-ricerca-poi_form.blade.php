@extends('templates.admin')

@section('title')
  @if (isset($poi))
    Modifica il punto di interesse per la mappa
  @else
    Inserisci un nuovo punto di interesse per la mappa
  @endif
@endsection

@section('onheadclose')
  <link type="text/css" rel="stylesheet" href="{{ Utility::assets('/vendor/oldbrowser/css/multiselect.min.css') }}"  />
  <script type="text/javascript" src="{{Utility::assets('/vendor/neon/js/icheck/icheck.min.js')}}"></script>
@stop

@section('content')
    
    @if (isset($poi))
      {!! Form::open(['id' => 'record_delete', 'url' => 'admin/mappa-ricerca-poi/'.$poi->id, 'method' => 'DELETE']) !!}
      {!! Form::close() !!}
    @endif

    @if (isset($poi))
      {!! Form::model($poi, ['role' => 'form', 'route'=>['mappa-ricerca-poi.update',$poi->id], 'method' => 'PUT', 'class' => 'form-horizontal']) !!}
    @else
      {!! Form::open(['role' => 'form', 'route'=>['mappa-ricerca-poi.store'], 'method' => 'POST', 'class' => 'form-horizontal']) !!}
    @endif
    

    @if (!isset($poi))
      <input type="hidden" id="colore" name="colore" value="#000">
      <div class="form-group">
          {!! Form::label('nome', 'Nome', ['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-4"> 
            {!! Form::text('nome', null, ['placeholder' => 'Es: Italia in Miniatura', 'class' => 'form-control']) !!}
        </div>
      </div>
      
      <div class="form-group">
          {!! Form::label('lat', 'Latitudine', ['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-4">       
          {!! Form::text('lat', null, ['placeholder' => 'Es: 44.090255', 'class' => 'form-control']) !!}
        </div>
      </div>

      <div class="form-group">
          {!! Form::label('long', 'Longitudine', ['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-4">        
          {!! Form::text('long', null, ['placeholder' => 'Es: 12.513640', 'class' => 'form-control']) !!}
        </div>
      </div>

      
    @else

      <div class="form-group">
        <div class="col-sm-4 col-sm-offset-3">
          <label  class="tag label label-info" style="padding:10px 20px; font-weight: bold;">{{ $poiLingua['it'][0]['nome'] }}</label>
        </div>
      </div>

      <div class="form-group">
          {!! Form::label('lat', 'Latitudine', ['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-4">       
          {!! Form::text('lat', $poi->lat, ['placeholder' => 'Es: 44.090255', 'class' => 'form-control']) !!}
        </div>
      </div>

      <div class="form-group">
          {!! Form::label('long', 'Longitudine', ['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-4">        
          {!! Form::text('long', $poi->long, ['placeholder' => 'Es: 12.513640', 'class' => 'form-control']) !!}
        </div>
      </div>
    
    @endif
    
    

    @if (isset($poi))
      <input type="hidden" id="colore" name="colore" value="{{ $inverted_codici_colori[$poi->colore] }}">
      <input type="hidden" name="poi_id" value="{{$poi->id}}">
      
      <div class="form-group">
          {!! Form::label('colore', 'Colore', ['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-4"> 
          <div class="icheck-skins">
              @foreach ($colori as $c)
                <a href="#" @if ($codici_colori[$c] == $poi->colore) class="current" @endif data-color-class="{{$c}}"></a> 
              @endforeach
          </div>    
        </div>
      </div>

      <div class="form-group">
        <div class="col-lg-12">
         <!-- Nav tabs -->
        <ul class="nav nav-tabs col-lg-offset-2" role="tablist" >
          @foreach (Langs::getAll() as $lang)
            <li role="presentation" <?php echo $lang === "it" ? 'class="active"' : null?>>
              <a class="tab_lang" data-id="{{ $lang }}" href="#{{ $lang }}" aria-controls="profile" role="tab" data-toggle="tab">
                <img src="{{ Langs::getImage($lang) }}" alt="{{ $lang }}"> {{ Langs::getName($lang) }}
              </a>
            </li>
          @endforeach
        </ul>

         <div class="tab-content">
            {{-- visualizzo in tutte lingue TITOLO e TESTO --}}
            @foreach (Langs::getAll() as $lang)
              <div role="tabpanel" class="tab-pane <?php echo $lang === "it" ? 'active' : null?>" id="{{ $lang }}">      
                
                @foreach ($poiLingua[$lang] as $poi_lingua)

                    <div class="form-group">
                        {!! Form::label('nome'.$lang, 'Nome', ['class' => 'col-sm-3 control-label']) !!}
                      <div class="col-sm-4"> 
                          {!! Form::text('nome'.$lang, $poi_lingua['nome'], ['placeholder' => 'Es: Italia in Miniatura', 'class' => 'form-control']) !!}
                      </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('info_titolo'.$lang, 'Titolo Info window', ['class' => 'col-sm-3 control-label']) !!}
                      <div class="col-sm-4"> 
                          {!! Form::text('info_titolo'.$lang, $poi_lingua['info_titolo'], ['placeholder' => 'Titolo Info window', 'class' => 'form-control']) !!}
                      </div>
                    </div>
                    
                    <div class="form-group">
                        {!! Form::label('info_desc'.$lang, 'Descrizione Info window', ['class' => 'col-sm-3 control-label']) !!}
                      <div class="col-sm-4"> 
                        {!! Form::textarea('info_desc'.$lang, $poi_lingua['info_desc'], ['placeholder' => 'Descrizione Info window', 'class' => 'form-control']) !!}
                      </div>
                    </div>

                 
                @endforeach

              </div>
            @endforeach
          </div> {{-- .tab-content --}}
          </div> {{-- .col-lg-12 --}}
      </div> {{-- .row --}}
      <div class="form-group">
        <div class="col-sm-4 col-sm-offset-2">
          <div class="checkbox">
             <label>
             {!! Form::checkbox('traduci',1) !!} Traduci automaticamente dalla lingua italiana e sovrascrivi le traduzioni già presenti !!
             </label>
           </div>
           <hr />
          </div>
        </div>
    @else
      
       <div class="form-group">
          {!! Form::label('colore', 'Colore', ['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-4"> 
          <div class="icheck-skins">
              @foreach ($colori as $c)
                <a href="#" data-color-class="{{$c}}"></a> 
              @endforeach
          </div>    
        </div>
      </div>

      <div class="form-group">
          {!! Form::label('info_titolo', 'Titolo Info window', ['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-4"> 
            {!! Form::text('info_titolo', null, ['placeholder' => 'Titolo Info window', 'class' => 'form-control']) !!}
        </div>
      </div>
      
      <div class="form-group">
          {!! Form::label('info_desc', 'Descrizione Info window', ['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-4"> 
          {!! Form::textarea('info_desc', null, ['placeholder' => 'Descrizione Info window', 'class' => 'form-control']) !!}
        </div>
      </div>

    @endif

  
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
          <a style="text-decoration: underline;" href="{{ url('admin/mappa-ricerca-poi/'.$poi->id.'/edit') }}">{{$poi->poi_lingua->first()->nome}}</a>
          <div style="background-color: {{$poi->colore}}; width: 50px; height: 10px; display: inline-block; margin-left: 10px; margin-bottom: -1px;">
          </div>
        </li>
        <div class="bootstrap-tagsinput" style="width:70%; margin-left: 20px;">
            <span class="tag label label-info">{{$poi->lat}}</span> 
            <span class="tag label label-info">{{$poi->long}}</span> 
            <span class="tag label label-info">{{$poi->poi_lingua->first()->info_titolo}}</span>
            @if (!empty($poi->poi_lingua->first()->info_desc))
              <span class="tag label label-info">{{Str::words(strip_tags($poi->poi_lingua->first()->info_desc),5)}}</span> 
             @endif 
        </div>
      @endforeach
    </ul>
    
@endsection


@section('onbodyclose') 

  <script type="text/javascript">
  jQuery(document).ready(function($)
    {
    
    var icheck_skins = $(".icheck-skins a");
    icheck_skins.click(function(ev)
      {
      ev.preventDefault();
      icheck_skins.removeClass('current');
      $(this).addClass('current');
      updateiCheckSkinandStyle();
      });
    
    $("#icheck-style").change(updateiCheckSkinandStyle);
    
    
    function updateiCheckSkinandStyle()
      {
      var skin = $(".icheck-skins a.current").data('color-class'),
      style = $("#icheck-style").val();
      $("#colore").val(skin);
      var cb_class = 'icheckbox_' + style + (skin.length ? ("-" + skin) : ''),
      rd_class = 'iradio_' + style + (skin.length ? ("-" + skin) : '');
      if(style == 'futurico' || style == 'polaris')
        {
        cb_class = cb_class.replace('-' + skin, '');
        rd_class = rd_class.replace('-' + skin, '');
        }
        
        $('input.icheck-2').iCheck('destroy');
        
        $('input.icheck-2').iCheck({
          checkboxClass: cb_class,
          radioClass: rd_class
        });
      };

    });
  </script>

@stop

