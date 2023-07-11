{{--
NOTE:
--}}
@extends('templates.page')

@section('content')

<script src="{{ Utility::asset('js/jquery-ui.min.js') }}" type="text/javascript"></script>
<script src="{{ Utility::asset('js/jquery.ui.datepicker-it.js') }}" type="text/javascript"></script>


<div class="panel-body" id="body-hotel">
  @include('errors')



  {!! Form::open(['url' => Utility::getLocaleUrl($locale).'richiesta_ricerca_avanzata.php']) !!}


    <div class="row">
        <p class="message success"></p>
        <div id="target"></div>
    </div>
    <div class="form-group">
      {!! Form::label('localita',trans('listing.localita').': ') !!}
      @include('composer.mailMultiplaSelectLocalita')
    </div>
      
    <div class="form-group">
      
      {!! Form::label('annuale',trans('listing.annuale').': ') !!}
      {!! Form::checkbox("annuale",1,false,["id"=>"annuale"]) !!}
      
    </div>


    <div class="form-group">
      
      {!! Form::label('categoria',trans('listing.categoria').': ') !!}
      @foreach ($stelle as $id => $nome)
        {!! Form::checkbox("categorie[]",$id,false,["id"=>"cat_".$id]) !!} {!! $nome !!} &nbsp;&nbsp;
      @endforeach
      
    </div>
  
  <hr> 

  <div class="form-group">
    {!! Form::label('trattamento',trans('listing.trattamento').'*') !!}
    {!! Form::select('trattamento',Utility::Trattamenti(trans('listing.seleziona').'....'),null,["class"=>"form-control","id"=>"trattamento"]) !!}
  </div>
   


  {{-- SLIDER fascia di prezzo --}}
  <div class="form-group">
      {!! Form::label('f_prezzo',trans('labels.fasce_prezzo').': ') !!} 
      <input type="text" name="f_prezzo" id="f_prezzo" readonly style="border:0; color:#d19405; font-weight:bold;">
      <div id="slider-range-min"></div>
  </div>


  <span class="form-inline">
    <div class="form-group">
      {!! Form::label('a_partire_da',trans('labels.a_partire_da').': ') !!}
      {!! Form::input('text','a_partire_da',null,["class"=>"form-control datepicker", "id" => "a_partire_da","size"=>"8"]) !!}
    </div>
    <div style="display:inline; margin-left:15px; font-size: smaller; font-weight: bolder;">
        ({{ trans('labels.spiega_a_partire_da') }})
    </div>
  </span>

  <hr> 
  <hr>


  {{-- SLIDER distanza dal centro --}}
  <div class="form-group">
    {!! Form::label('distanza_centro',trans('labels.distanza_centro').': ') !!}
    <input type="text" name="distanza_centro" id="distanza_centro" readonly style="border:0; color:#d19405; font-weight:bold;">
    <div id="slider-distanza-centro"></div>
  </div>
 

  {{-- SLIDER distanza dalla stazione --}}
  <div class="form-group">
    {!! Form::label('distanza_stazione',trans('labels.distanza_stazione').': ') !!}
    <input type="text" name="distanza_stazione" id="distanza_stazione" readonly style="border:0; color:#d19405; font-weight:bold;">
    <div id="slider-distanza-stazione"></div>
  </div>
  

  {{-- SLIDER distanza dalla spiaggia --}}
  <div class="form-group">
    {!! Form::label('distanza_spiaggia',trans('labels.distanza_spiaggia').': ') !!}
    <input type="text" name="distanza_spiaggia" id="distanza_spiaggia" readonly style="border:0; color:#d19405; font-weight:bold;">
    <div id="slider-distanza-spiaggia"></div>
  </div>
  
    <div style="padding: 20px 0;">
    {!! Form::label('servizi',trans('hotel.servizi').': ') !!}
    </div>

    <div class="servizio_ricerca_avanzata">
    <?php $count = 1; ?>
    @foreach ($categorie as $categoria)
      
      @if ($count == 3 || $count == 6)
        </div>
        <div class="servizio_ricerca_avanzata">   
      @endif
      
      <div class="categoria">{!!$categoria->nome!!}</div class="categoria">
      
      <div>
        @foreach ($categoria->servizi as $servizio)
          <div> 
              <div> 
                <div class="checkbox"> 
                  {!! Form::checkbox('servizi[]', $servizio->id, false,["id"=>"servizio_".$servizio->id]) !!}&nbsp;<label for="servizio_{{$servizio->id}}">{{ $servizio->servizi_lingua->first()->nome}}</label>
                </div> 
              </div> 
          </div>
        @endforeach
      </div>
      <?php $count ++; ?>
    
    @endforeach

  </div>
  <div class="clear"></div>

  <hr> 
  <hr> 

  
  @include('esca_snippet')
  
  {!! Form::hidden('locale',$locale) !!}
  {!! Form::hidden('IP',isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '')  !!}
  {!! Form::hidden('referer',isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : '' )!!}
  {{-- Questo campo serve alla validazione del campo trattamento che deve essere different da il valore di un altro campo --}}
  {!! Form::hidden('seleziona',0) !!}
  
  {{-- ATTENZIONE gli hidden devon essere valorizzati al default delle slider (campo 'value') --}}
  {!! Form::hidden('f_prezzo_real',0,["id" => "f_prezzo_real"]) !!}
  {!! Form::hidden('distanza_centro_real',0,["id" => "distanza_centro_real"]) !!}
  {!! Form::hidden('distanza_stazione_real',0,["id" => "distanza_stazione_real"]) !!}
  {!! Form::hidden('distanza_spiaggia_real',0,["id" => "distanza_spiaggia_real"]) !!}
  
  {!! Form::submit('Invia',["class"=>"fbtn btn-default"]) !!}
  {!! Form::close() !!}

</div>

@include('widget.script_for_multi_localita')

<script type="text/javascript">
  
  $(document).ready(function() {
    
      // jquery user interface mail multipla
      // Set all date pickers to have..... 
      // DATEPICKER
      
      // Set all date pickers to have Italian text date.
      $.datepicker.setDefaults($.datepicker.regional["it"]);
      // $( "#arrivo" ).datepicker($.datepicker.regional["it"]);
      //$( "#arrivo" ).datepicker();
      $( "#a_partire_da" ).datepicker({
        defaultDate: "+1d",
        changeMonth: true,
        numberOfMonths: 1
      });
      // FINE DATEPICKER
      

      // SLIDER FASCIA DI PREZZO
      $( "#slider-range-min" ).slider({
            range: "min",
            value: 0,
            step: 10,
            min: 0,
            max: 160,
            slide: function( event, ui ) {
              if (ui.value == 0) 
                {
                  $( "#f_prezzo" ).val( "{{ trans('labels.qualsiasi_prezzo')  }} !!" );
                } 
              else
                {
                  $( "#f_prezzo" ).val( "{{ trans('labels.fino')  }}  € " + ui.value );
                }
              
              $( "#f_prezzo_real" ).val( ui.value );
            }
      });

      if ($( "#slider-range-min" ).slider( "value" ) == 0) 
      {
        $( "#f_prezzo" ).val( "{{ trans('labels.qualsiasi_prezzo')  }} !!" );
      } 
      else
      {
        $( "#f_prezzo" ).val( "{{ trans('labels.fino')  }}  € " + $( "#slider-range-min" ).slider( "value" ) );
      };
      

      // FINE SLIDER FASCIA DI PREZZO


      // SLIDER DISTANZA DAL CENTRO
      $( "#slider-distanza-centro" ).slider({
            range: "min",
            value: 0,
            step: 1,
            min: 0,
            max: 10,
            slide: function( event, ui ) {
              if (ui.value == 0) 
                {
                  $( "#distanza_centro" ).val( "{{ trans('labels.qualsiasi_dist')  }} !!");
                } 
              else
                {
                  $( "#distanza_centro" ).val( "{{ trans('labels.entro')  }}  Km " + ui.value );
                }
              
              $( "#distanza_centro_real" ).val( ui.value );
            }
      });
      if ($( "#slider-distanza-centro" ).slider( "value" ) == 0) 
      {
        $( "#distanza_centro" ).val( "{{ trans('labels.qualsiasi_dist')  }} !!");
      } 
      else
      {
        $( "#distanza_centro" ).val( "{{ trans('labels.entro')  }}  Km " + $( "#slider-distanza-centro" ).slider( "value" ) ); 
      };
      // FINE SLIDER DISTANZA DAL CENTRO


      // SLIDER DISTANZA DALLA STAZIONE
      $( "#slider-distanza-stazione" ).slider({
            range: "min",
            value: 0,
            step: 1,
            min: 0,
            max: 10,
            slide: function( event, ui ) {
                if (ui.value == 0) 
                  {
                    $( "#distanza_stazione" ).val( "{{ trans('labels.qualsiasi_dist')  }} !!" );
                  } 
                else 
                  {
                    $( "#distanza_stazione" ).val( "{{ trans('labels.entro')  }}  Km " + ui.value );  
                  }
                
                
                $( "#distanza_stazione_real" ).val( ui.value );
            }
      });

      if ($( "#slider-distanza-stazione" ).slider( "value" ) == 0) 
        {
          $( "#distanza_stazione" ).val( "{{ trans('labels.qualsiasi_dist')  }} !!" );
        } 
      else
        {
          $( "#distanza_stazione" ).val( "{{ trans('labels.entro')  }}  Km " + $( "#slider-distanza-stazione" ).slider( "value" ) );
        };
      
      // FINE SLIDER DISTANZA DALLA STAZIONE



      // SLIDER DISTANZA DALLA SPIAGGIA
      $( "#slider-distanza-spiaggia" ).slider({
            range: "min",
            value: 0,
            step: 50,
            min: 0,
            max: 800,
            slide: function( event, ui ) {
               if (ui.value == 0) 
                {
                  $( "#distanza_spiaggia" ).val( "{{ trans('labels.qualsiasi_dist')  }} !!" );
                } 
              else
                {
                  $( "#distanza_spiaggia" ).val( "{{ trans('labels.entro')  }}  m. " + ui.value );
                };
                

                $( "#distanza_spiaggia_real" ).val( ui.value );
            }
      });

      if ($( "#slider-distanza-spiaggia" ).slider( "value" ) == 0) 
      {
        $( "#distanza_spiaggia" ).val( "{{ trans('labels.qualsiasi_dist')  }} !!" );
      } 
      else 
      {
        $( "#distanza_spiaggia" ).val( "{{ trans('labels.entro')  }}  m. " + $( "#slider-distanza-spiaggia" ).slider( "value" ) );
      }
      
      // FINE SLIDER DISTANZA DALLA SPIAGGIA

  }); 
    

</script>



@endsection