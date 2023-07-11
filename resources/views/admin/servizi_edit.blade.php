@extends('templates.admin')

@section('title')
Servizi
@endsection

@section('content')
<div class="row">
  <div class="col-lg-12">
    

    @if (count($data))


    <p>Modifica i servizi che dovranno essere associati agli hotel</p>


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
      {!! Form::open(['role' => 'form', 'url'=>['admin/servizi/updateLingua'], 'method' => 'POST', 'class' => 'form-horizontal']) !!}
        
        <div class="row">
          <div class="col-lg-1">
            <button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-ok"></i> Salva</button>
          </div>
          <div class="col-lg-1 col-offset-5">
            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" type="checkbox" name="traduci" value="1">
                Traduci
              </label>
            </div>
          </div>
        </div>
        
        <div class="tab-content">        
          @foreach (Langs::getAll() as $lang)
            <div role="tabpanel" class="tab-pane <?=( $lang === "it" ? 'active' : null)?>" id="{{ $lang }}">            

              <h2>{{ Langs::getName($lang) }}</h2>
              
              {{-- 
              array:4 [▼
                "it" => array:2 [▼
                  100 => "parcheggio"
                  101 => "wifi"
                ]
                "en" => array:2 [▼
                  100 => "parking"
                  101 => "wifi"
                ]
                "fr" => array:2 [▼
                  100 => "parking"
                  101 => "Wifi"
                ]
                "de" => array:2 [▼
                  100 => "Parkplatz"
                  101 => "W-lan"
                ]
              ]
               --}}
                  
                                   
                    @foreach ($data[$lang] as $id_servizio => $servizio)
                      <?php 
                      list($nome,$categoria) = explode('|',$servizio);
                      ?> 
                      <div class="form-group">
                        <label for="servizio{{ $lang }}{{ $id_servizio }}">{{$categoria}}</label>
                        <input type="text" class="form-control" id="servizio{{ $lang }}{{ $id_servizio }}" name="servizio{{ $lang }}{{ $id_servizio }}" value="{{ $nome }}">
                      </div>

                    @endforeach

            </div>
          @endforeach  
          <div class="row">
            <div class="col-lg-1">
              <button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-ok"></i> Salva</button>
            </div>
            <div class="col-lg-1 col-offset-5">
              <div class="form-check">
                <label class="form-check-label">
                  <input class="form-check-input" type="checkbox" name="traduci" value="1">
                  Traduci
                </label>
              </div>
            </div>
          </div>

        </div> {{-- /.tab-content --}}
      
      {!! Form::close() !!}


      @else
      
         <p>Nessun servizio attivo</p>
    
      @endif

    </div>
  </div>


@endsection