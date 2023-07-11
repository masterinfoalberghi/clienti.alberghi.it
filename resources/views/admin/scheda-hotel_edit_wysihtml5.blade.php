@extends('templates.admin')

@section('title')
Scheda Hotel
@endsection

@section('content')
<div class="row">
  <div class="col-lg-12">
    

    @if (count($data))


    <p>Modifica i testi della scheda hotel</p>

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
      {!! Form::open(['role' => 'form', 'url'=>['admin/scheda-hotel/updateLingua'], 'method' => 'POST', 'class' => 'form-horizontal']) !!}

        <div class="tab-content">        
          @foreach (Langs::getAll() as $lang)
            <div role="tabpanel" class="tab-pane <?=( $lang === "it" ? 'active' : null)?>" id="{{ $lang }}">            

              <h2>{{ Langs::getName($lang) }}</h2>
              
              {{-- 
              array:4 [▼
                "it" => array:1 [▼
                  11 => "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Vitae fuga reiciendis harum provident quo fugit laudantium, tempora molestias odit eius unde totam nulla nisi et, ipsum. Natus enim perspiciatis pariatur."
                ]
                "en" => array:1 [▼
                  11 => "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Vitae fuga reiciendis harum provident quo fugit laudantium, tempora molestias odit eius unde totam nulla nisi et, ipsum. Natus enim perspiciatis pariatur."
                ]
                "fr" => array:1 [▼
                  11 => "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Vitae fuga reiciendis harum provident quo fugit laudantium, tempora molestias odit eius unde totam nulla nisi et, ipsum. Natus enim perspiciatis pariatur."
                ]
                "de" => array:1 [▼
                  11 => "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Vitae fuga reiciendis harum provident quo fugit laudantium, tempora molestias odit eius unde totam nulla nisi et, ipsum. Natus enim perspiciatis pariatur."
                ]
              ]
               --}}
                  
                                   
                    @foreach ($data[$lang] as $id_descrizione => $testo)
                    
                      <div class="form-group">
                        <label for="testo{{ $lang }}{{ $id_descrizione }}">Servizio</label>
                        <textarea class="form-control wysihtml5" id="field-ta" data-stylesheet-url="{{Utility::assets('/vendor/neon/css/wysihtml5-color.css')}}" placeholder="Scheda hotel" name="testo{{ $lang }}{{ $id_descrizione }}">{{ $testo }}</textarea>
                      </div>

                    @endforeach

            </div>
          @endforeach  
          <button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-ok"></i> Salva</button>

        </div> {{-- /.tab-content --}}
      
      {!! Form::close() !!}


      @else
      
         <p>Nessuna descrizione inserita</p>
    
      @endif

    </div>
  </div>


@endsection


@section('onbodyclose')

<link rel="stylesheet" type="text/css" href="{{Utility::assets('/vendor/neon/js/wysihtml5/bootstrap-wysihtml5.css')}}" />
<script type="text/javascript" src="{{Utility::assets('/vendor/neon/js/wysihtml5/wysihtml5-0.4.0pre.min.js')}}"></script>
<script type="text/javascript" src="{{Utility::assets('/vendor/neon/js/wysihtml5/bootstrap-wysihtml5.js')}}"></script>
@endsection