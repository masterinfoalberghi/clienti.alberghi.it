@extends('templates.admin')

@section('title')
  CHECK-IN/CHECK-OUT HOTEL
@endsection


@section('content')
  <form id="form-servizi-in-out" action="{{ route('servizi-in-out.store') }}" method="POST"
    enctype="multipart/form-data">
    @csrf
    @foreach ($gruppi_servizi_inout as $gruppo)
      <div class="row">
        <div class="col-md-2">
          <p class="nome_gruppo">{{ $gruppo->nome_it }}</p>
        </div>
      </div>

      @foreach ($gruppo->servizi as $servizio)
        {{-- Per il gruppo 1 "Orari"
         metto "dalle ore", "alle ore" davanti alle date
         tranne per il servizio 9 --}}
        @php
          $dal = null;
          $al = null;
        @endphp
        @if ($gruppo->id == 1)
          @if ($servizio->to_fill_1 && $servizio->id != 9)
            @php
              $dal = 'dalle ore ';
            @endphp
          @endif

          @if ($servizio->to_fill_2)
            @php
              $al = ' alle ore ';
            @endphp
          @endif
        @endif

        <div class="row" style="margin-top: 15px;">
          <div @if ($gruppo->id == 1) class="col-md-8" @else class="col-md-4" @endif>
            @if ($gruppo->id == 1 && $loop->index == 0)
              <h3>RECEPTION</h3>
            @elseif($gruppo->id == 1 && $loop->index == 2)
              <h3>CHECK-IN</h3>
            @elseif($gruppo->id == 1 && $loop->index == 4)
              <h3>CHECK-OUT</h3>
            @elseif($gruppo->id == 1 && $loop->index == 6)
              <h3>CONSEGNARE LE CAMERE</h3>
            @elseif($gruppo->id == 1 && $loop->index == 8)
              <h3>LASCIARE LE CAMERE</h3>
            @endif

            <input type="checkbox" name="servizio_inout[]" id="{{ $servizio->id }}" value="{{ $servizio->id }}"
              @if ($gruppo->id == 1 && $loop->index == 0)
            class="reception_24"
      @endif
      @if ($gruppo->id == 1 && $loop->index == 1)
        class="reception_orario"
      @endif
      @if ($gruppo->id == 1 && $loop->index == 2)
        class="checkin_24"
      @endif
      @if ($gruppo->id == 1 && $loop->index == 3)
        class="checkin_orario"
      @endif
      @if ($gruppo->id == 1 && $loop->index == 4)
        class="checkout_24"
      @endif
      @if ($gruppo->id == 1 && $loop->index == 5)
        class="checkout_orario"
      @endif
      @if ($gruppo->id == 1 && $loop->index == 6)
        class="consegna_da_a"
      @endif
      @if ($gruppo->id == 1 && $loop->index == 7)
        class="consegna_dalle"
      @endif

      @if ($gruppo->id == 1 && $loop->index == 8)
        class="rilascio"
      @endif

      {{ array_key_exists($servizio->id, $servizi_ids) ? 'checked' : '' }}>
      <label for="{{ $servizio->id }}">
        {{ ucfirst($servizio->nome_it) }}
      </label>
      @if ($servizio->to_fill_1)
        {{ $dal }}
        {!! Form::select('da_ora_' . $servizio->id, ['' => ''] + Utility::VentiQuattro(), isset($servizi_ids[$servizio->id]) ? $servizi_ids[$servizio->id]['valore_1_ora'] : null, ['class' => 'orario_select dalle', 'data-id' => $servizio->id]) !!}:{!! Form::select('da_minuti_' . $servizio->id, ['' => ''] + ['00' => '00', '30' => '30'], isset($servizi_ids[$servizio->id]) ? $servizi_ids[$servizio->id]['valore_1_minuti'] : null, ['class' => 'orario_select dalle', 'data-id' => $servizio->id]) !!}
      @endif

      {{-- scrivo in poi --}}
      @if ($servizio->id == 8)
        in poi
      @else
        &nbsp;&nbsp;&nbsp;
      @endif

      @if ($servizio->to_fill_2)
        {{ $al }}
        {!! Form::select('a_ora_' . $servizio->id, ['' => ''] + Utility::VentiQuattro(), isset($servizi_ids[$servizio->id]) ? $servizi_ids[$servizio->id]['valore_2_ora'] : null, ['class' => 'orario_select alle', 'data-id' => $servizio->id]) !!}:{!! Form::select('a_minuti_' . $servizio->id, ['' => ''] + ['00' => '00', '30' => '30'], isset($servizi_ids[$servizio->id]) ? $servizi_ids[$servizio->id]['valore_2_minuti'] : null, ['class' => 'orario_select alle', 'data-id' => $servizio->id]) !!}
      @endif
      </div>

      @if ($servizio->options)

        @php
          $options_arr = explode(';', $servizio->options);
        @endphp

        <div class="col-md-3">
          <select class="form-control" name="options_{{ $servizio->id }}" id="options_{{ $servizio->id }}">
            @foreach ($options_arr as $option)
              <option @if (isset($servizi_ids[$servizio->id]) && $option == $servizi_ids[$servizio->id]['opzione']) selected @endif>{{ $option }}</option>
            @endforeach
          </select>
        </div>
      @endif
      </div>
    @endforeach
    @if ($gruppo->id == 1)
      <hr style="margin: 32px 0 0 0">
    @endif
    @endforeach
    <input type="submit" id="submit" name="submit" value="Salva" class="btn btn-info" style="margin-top: 25px;">
  </form>
@endsection


@section('onbodyclose')
  <script type="text/javascript">
    jQuery(document).ready(function($) {

      $(".orario_select").change(function() {
        let id = $(this).data('id');
        $('#' + id).prop('checked', true).change(); // chiamo change() in modo da triggerare gli onChange sottostanti
      });


      $('.reception_24').change(function() {
        if (this.checked) {
          $('.reception_orario').prop('checked', !this.checked);
        }
      });
      $('.reception_orario').change(function() {
        if (this.checked) {
          $('.reception_24').prop('checked', !this.checked);
        }
      });


      $('.checkin_24').change(function() {
        if (this.checked) {
          $('.checkin_orario').prop('checked', !this.checked);
        }
      });
      $('.checkin_orario').change(function() {
        if (this.checked) {
          $('.checkin_24').prop('checked', !this.checked);
        }
      });

      $('.checkout_24').change(function() {
        if (this.checked) {
          $('.checkout_orario').prop('checked', !this.checked);
        }
      });
      $('.checkout_orario').change(function() {
        if (this.checked) {
          $('.checkout_24').prop('checked', !this.checked);
        }
      });


      $('.consegna_da_a').change(function() {
        if (this.checked) {
          $('.consegna_dalle').prop('checked', !this.checked);
        }
      });

      $('.consegna_dalle').change(function() {
        if (this.checked) {
          $('.consegna_da_a').prop('checked', !this.checked);
        }
      });


      $("#submit").click(function(e) {


        let $valid = true;

        // reception_orario
        if ($(".reception_orario").is(":checked")) {
          // per ogni fratello con la classe "orario_select"
          // per ogni select box di fianco
          // può avere solo "dalle alle"
          $(".reception_orario").siblings(".orario_select").each(function() {
            if ($(this).val() == '') {
              $valid = false;
              alert('Selezionare un orario per la reception');
              $(".reception_orario").focus();
              e.preventDefault();
              return false;
            }
          });

          if (!$valid) {
            return false;
          }
        }

        // checkin_orario
        if ($(".checkin_orario").is(":checked")) {
          // per ogni fratello con la classe "orario_select"
          // per ogni select box di fianco
          // può avere "dalle" oppure "dalle alle"
          $(".checkin_orario").siblings(".dalle").each(function() {
            if ($(this).val() == '') {
              $valid = false;
              alert('Selezionare un orario per il check-in');
              $(".checkin_orario").focus();
              e.preventDefault();
              return false;
            }
          });

          if (!$valid) {
            return false;
          }
        }

        // checkout_orario
        if ($(".checkout_orario").is(":checked")) {
          // per ogni fratello con la classe "orario_select"
          // per ogni select box di fianco
          // può avere "alle" oppure "dalle alle"
          $(".checkout_orario").siblings(".alle").each(function() {
            if ($(this).val() == '') {
              $valid = false;
              alert('Selezionare un orario per il check-out');
              $(".checkout_orario").focus();
              e.preventDefault();
              return false;
            }
          });

          if (!$valid) {
            return false;
          }
        }



        // consegna dalle alle 
        if ($(".consegna_da_a").is(":checked")) {
          // per ogni fratello con la classe "orario_select"
          // per ogni select box di fianco
          // può avere solo "dalle alle"
          $(".consegna_da_a").siblings(".orario_select").each(function() {
            if ($(this).val() == '') {
              $valid = false;
              alert('Selezionare un orario per la cosegna delle camere');
              $(".consegna_da_a").focus();
              e.preventDefault();
              return false;
            }
          });
          if (!$valid) {
            return false;
          }
        }

        // consegna dalle 
        if ($(".consegna_dalle").is(":checked")) {
          // per ogni fratello con la classe "orario_select"
          // per ogni select box di fianco
          // può avere solo "dalle alle"
          $(".consegna_dalle").siblings(".orario_select").each(function() {
            if ($(this).val() == '') {
              $valid = false;
              alert('Selezionare un orario per la cosegna delle camere');
              $(".consegna_dalle").focus();
              e.preventDefault();
              return false;
            }
          });
          if (!$valid) {
            return false;
          }
        }

        // lasciare le camere
        if ($(".rilascio").is(":checked")) {
          // per ogni fratello con la classe "orario_select"
          // per ogni select box di fianco
          // può avere solo "dalle alle"
          $(".rilascio").siblings(".orario_select").each(function() {
            if ($(this).val() == '') {
              $valid = false;
              alert('Selezionare un orario entro il quale lasciare le camere');
              $(".rilascio").focus();
              e.preventDefault();
              return false;
            }
          });
          if (!$valid) {
            return false;
          }
        }


        $("form#form-servizi-in-out").submit();

      }); // submit

    }); // jQuery(document).ready
  </script>
@endsection
