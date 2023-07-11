@extends('templates.admin')

@section('title')
  Inserisci un nuovo servizio
@endsection


@section('content')

    @if (isset($servizio))
      {!! Form::open(['id' => 'record_delete', 'url' => 'admin/servizi/'.$servizio->id, 'method' => 'DELETE']) !!}
      {!! Form::close() !!}
    @endif

    @if (isset($servizio))
      {!! Form::model($servizio, ['role' => 'form', 'route'=>['servizi.update',$servizio->id], 'method' => 'PUT', 'class' => 'form-horizontal', 'files' => true]) !!}
    @else
      {!! Form::open(['role' => 'form', 'route'=>['servizi.store'], 'method' => 'POST', 'class' => 'form-horizontal', 'files' => true]) !!}
    @endif
      <div class="form-group">
        {!! Form::label('gruppo_id', 'Gruppo', ['class' => 'col-sm-2 control-label']) !!}
          <div class="col-sm-5">
            <div class="input-group">
                 {!! Form::select('gruppo_id', $gruppi, isset($servizio) ? $servizio->gruppo_id : null,["class"=>"form-control"]) !!}
            </div>
          </div>
      </div>

      <div class="form-group">
        {!! Form::label('categoria_id', 'Categoria', ['class' => 'col-sm-2 control-label']) !!}
          <div class="col-sm-5">
            <div class="input-group">
                 {!! Form::select('categoria_id',$categorie,isset($servizio) ? $servizio->categoria_id : null,["class"=>"form-control"]) !!}
            </div>
          </div>
      </div>

      <div class="form-group">
          {!! Form::label('nome', 'Nome', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
          @if (isset($servizio))
            {!! Form::label('nome', $servizio->translate->first()->nome, ['class' => 'control-label']) !!}
          @else
          {!! Form::text('nome', null, ['class' => 'form-control']) !!}
          @endif
        </div>
    </div>

    <div class="form-group">
      <div class="col-sm-12">
        @include('templates.admin_inc_record_buttons')
      </div>
    </div>
    {!! Form::close() !!}
    
    <hr/><hr/>

    {{-- VIsualizzo i servizi raggruppati per categoria --}}
    <div class="row">
      <div class="col-md-4">
      {{-- 
      LA FORMATTAZIONE DEVE ESSERE UGUALE A QUELLA DEL FOGLIO CARTACEO
      CHE RIEMPIONO I COMMERCIALI, ALTRIMENTI NON RIESCONO AD INSERIRE !!!???!!!
       --}}

      {{-- 
        Ogni categoria di servizi diventa un'area sortable
      --}}
      <?php $count = 1; ?>
      @foreach ($servizi_per_categorie as $categoria)
        @if ($count == 3 || $count == 6)
          </div>
          <div class="col-md-4">   
        @endif
        <div class="panel-heading">
          <div class="panel-title" style="margin-top: 20px;">
            {!!$categoria->nome!!}
          </div>
        </div>
        <div class="panel-body">
          <ul id="sortable_{{$categoria->id}}">
          @foreach ($categoria->servizi as $servizio)
            <li id="recordsArray_{{$servizio->id}}|{{$categoria->id}}" class="sortable">
            <div class="form-group"> 
                <div class="col-sm-12"> 
                  <div> 
                    <a href='{{url("admin/servizi/$servizio->id/edit")}}'>{{ $servizio->servizi_lingua->first()->nome}}</a> 
                    @if (!is_null($servizio->gruppo))
                      ({{$servizio->gruppo->nome}})
                    @endif
                  </div> 
                </div> 
            </div>
            </li>
          @endforeach
          </ul>
        </div> {{-- / .panel-body --}}
      <?php $count ++; ?>
      @endforeach
      </div>
    </div>
@endsection



@section('onbodyclose')
  
  <script type="text/javascript">
    jQuery(document).ready(function(){ 

      // update: sortupdate This event is triggered when the user stopped sorting and the DOM position has changed.
      
      // serialize: String Serializes the sortable's item ids into a form/ajax submittable string. Calling this method produces a hash that can be appended to any url to easily submit a new item order back to the server. 
      // It works by default by looking at the id of each item in the format "setname_number", and it spits out a hash like "setname[]=number&setname[]=number". 
      // For example, a 3 element list with id attributes "foo_1", "foo_5", "foo_2" will serialize to "foo[]=1&foo[]=5&foo[]=2". 


      jQuery(function() {


        @foreach ($categorie as $id => $nome)
     
          jQuery("#sortable_{{$id}}").sortable({ placeholder: "ui-state-highlight",forcePlaceholderSize: true, opacity: 0.6, cursor: 'crosshair', update: function() {
            var token = "{{ Session::token() }}";
            var order = jQuery(this).sortable("serialize") + '&_token='+token;
            var baseUrl = "{{ url('/') }}"; 
            jQuery.post(
                baseUrl+"/admin/servizi/order_ajax", 
                order, 
                function(theResponse){}
                );                                
          }                 
          });

        @endforeach


      });

    }); 
  </script>
@stop

