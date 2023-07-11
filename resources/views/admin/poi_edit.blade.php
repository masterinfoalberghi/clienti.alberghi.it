@extends('templates.admin')

@section('title')
Punti di Interesse
@endsection

@section('content')
<div class="row">
  <div class="col-lg-12">
    

    @if (count($data))


    <p>Modifica i POI che dovranno essere associati agli hotel</p>


       <!-- Nav tabs -->
      <ul class="nav nav-tabs" role="tablist">
        @foreach (Langs::getAll() as $lang)
          <li role="presentation" <?=( $lang === "it" ? 'class="active"' : null)?>>
            <a class="tab_lang" data-id="{{ $lang }}" href="#{{ $lang }}" aria-controls="profile" role="tab" data-toggle="tab">
              <img src="{{ Langs::getImage($lang) }}" alt="{{ $lang }}"> {{ Langs::getName($lang) }}
            </a>
          </li>
        @endforeach
      </ul>
      
      <!-- Tab panes -->
      {!! Form::open(['role' => 'form', 'url'=>['admin/poi/updateLingua'], 'method' => 'POST', 'class' => 'form-horizontal']) !!}
        
        <div class="row">
          <div class="col-lg-1">
            <button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-ok"></i> Salva</button>
          </div>
        </div>
        
        <div class="tab-content">        
          @foreach (Langs::getAll() as $lang)
            <div role="tabpanel" class="tab-pane <?=( $lang === "it" ? 'active' : null)?>" id="{{ $lang }}">            

              <h2>{{ Langs::getName($lang) }}</h2>
                                             
                    @foreach ($data[$lang] as $id_poi => $nome)
          
                      <div class="form-group">
                        <label for="poi{{ $lang }}{{ $id_poi }}">{{$nome}}</label>
                        <input type="text" class="form-control" id="poi{{ $lang }}{{ $id_poi }}" name="poi{{ $lang }}{{ $id_poi }}" value="{{ $nome }}">
                      </div>

                    @endforeach

            </div>
          @endforeach  
          <div class="row">
            <div class="col-lg-1">
              <button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-ok"></i> Salva</button>
            </div>
          </div>

        </div> {{-- /.tab-content --}}
      
      {!! Form::close() !!}


      @else
      
         <p>Nessun poi attivo</p>
    
      @endif

    </div>
  </div>


@endsection