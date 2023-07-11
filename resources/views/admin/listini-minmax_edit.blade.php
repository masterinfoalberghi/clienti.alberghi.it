@extends('templates.admin')

@section('title')
  Listino Prezzi min & max
@endsection

@section('content')

  {!! Form::open(['role' => 'form', 'url' => ['admin/listini-minmax/modifica_stato'], 'method' => 'POST', 'class' => 'form-horizontal']) !!}
  <div class="row">

    @if ($listini->first()->attivo)
      <?php $class_disattivata = ''; ?>

      <div class="col-md-12">
        <div class="alert alert-success nobg border">
          Il listino è <strong>ATTIVO !</strong>
          <span style="margin-right:20px; float:right; clear:right; margin-top:-5px;">
            <input type="hidden" name="type" value="disattiva_listino">
            <button type="submit" class="btn btn-red btn-icon">DISATTIVA<i class="entypo-cancel"></i> </button>
          </span>
        </div>
      </div>

    @else
      <?php $class_disattivata = 'disattiva'; ?>

      <div class="col-md-12">
        <div class="alert alert-danger border">
          Il listino è <strong>DISATTIVATO !</strong>
          <span style="margin-right:20px; float:right; clear:right; margin-top:-5px;">
            <input type="hidden" name="type" value="attiva_listino">
            <button type="submit" class="btn btn-green btn-icon">ATTTIVA<i class="entypo-check"></i> </button>
          </span>
        </div>
      </div>

    @endif
  </div>
  {!! Form::close() !!}



  <div class="row">
    <div class="col-lg-12">
      <div class="form-group">
        <div class="row">
          <div class="col-md-12">
            <table class="table table-bordered responsive table-striped table-hover {{ $class_disattivata }}">
              <thead>
                <tr>
                  <th width="5%">dal</th>
                  <th width="5%">al</th>
                  <th width="18%">Solo Dormire</th>
                  <th width="18%">Bed & Breakfast</th>
                  <th width="18%">Mezza Pensione</th>
                  <th width="18%">Pensione completa</th>
                  <th width="18%">All Inclusive</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>

                @foreach ($listini as $listino)


                  {!! Form::open(['role' => 'form', 'url' => ['admin/listini-minmax/' . $listino->id], 'method' => 'DELETE', 'class' => 'form-horizontal float button_to_delete', 'id' => "form_periodo_$listino->id"]) !!}

                  <input type="hidden" name="listino_id" value="{{ $listino->id }}">
                  <tr>
                    <td><input type="text" value="{{ Utility::getLocalDate($listino->periodo_dal, '%d/%m/%Y') }}"
                        id="periodo_dal_{{ $listino->id }}" name="periodo_dal"></td>
                    <td><input type="text" value="{{ Utility::getLocalDate($listino->periodo_al, '%d/%m/%Y') }}"
                        id="periodo_al_{{ $listino->id }}" name="periodo_al"></td>
                    <?php $count = 0; ?>
                    {{-- OCCHIO a ACCESSOR E MUTATOR definiti nella MODEL !!!! --}}
                    @foreach ($columns as $element)
                      @if ($count % 2 == 0)
                        <td style="position: relative;">
                          <div class="prezzo prezzo_min">min:</div>
                          <div tabindex="0" id="{{ $listino->id }}|{{ $element }}" class="click">
                            {{ $listino->$element == '/' ? ' ' : $listino->$element }}</div>
                        @else
                          <div class="prezzo prezzo_max">max:</div>
                          <div tabindex="0" id="{{ $listino->id }}|{{ $element }}" class="click click_p_max">
                            {{ $listino->$element == '/' ? ' ' : $listino->$element }}</div>
                        </td>
                      @endif

                      <?php $count++; ?>

                    @endforeach

                    <td class="last-right">
                      <input type="hidden" name="type" value="empty">
                      <button type="button" onclick="jQuery('#modal-confirm-delete-{{$listino->id}}').modal('show');"
                        class="btn btn-red"> <i class="entypo-trash"></i> </button>
                    </td>
                  </tr>
                  {!! Form::close() !!}


                  <div class="modal fade" id="modal-confirm-delete-{{$listino->id}}" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                          <h4 class="modal-title">Eliminazione record</h4>
                        </div>
                        <div class="modal-body">
                          Confermi di voler eliminare in maniera definitiva ed irreversibile il record?
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
                          <button type="button" class="btn btn-primary"
                            onclick="jQuery('#form_periodo_'+{{$listino->id}}).submit();">Conferma</button>
                        </div>
                      </div>
                    </div>
                  </div>

                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="form-group">
        <div class="row">
          <div class="col-sm-12">
            {!! Form::open(['role' => 'form', 'route' => ['listini-minmax.store'], 'method' => 'POST', 'class' => 'form-horizontal float']) !!}
            <input type="hidden" name="type" value="empty">
            <button type="submit" id="add_top" class="btn btn-blue"><i class="glyphicon glyphicon-plus"></i> Aggiungi
              periodo vuoto</button>
            {!! Form::close() !!}

            @if ($listini->count() > 1)
              {!! Form::open(['role' => 'form', 'route' => ['listini-minmax.store'], 'method' => 'POST', 'class' => 'form-horizontal float']) !!}
              <input type="hidden" name="type" value="top">
              <button type="submit" id="add_top" class="btn btn-gold"><i class="glyphicon glyphicon-arrow-up"></i>
                Aggiungi periodo all'inizio</button>
              {!! Form::close() !!}

              {!! Form::open(['role' => 'form', 'route' => ['listini-minmax.store'], 'method' => 'POST', 'class' => 'form-horizontal  float']) !!}
              <input type="hidden" name="type" value="bottom">
              <button type="submit" id="add_bottom" class="btn btn-gold"><i class="glyphicon glyphicon-arrow-down"></i>
                Aggiungi periodo alla fine</button>
              {!! Form::close() !!}
            @endif
          </div>
        </div>
      </div>



    </div>
  </div>

@endsection

@section('onheadclose')

  <script type="text/javascript" src="{{ Utility::assets('/vendor/oldbrowser/js/jquery.jeditable.min.js') }}"></script>
  <link href="{{ Utility::assets('/vendor/oldbrowser/css/jquery-ui.min.css') }}" rel="stylesheet">
  <script src="{{ Utility::assets('/vendor/oldbrowser/js/jquery-ui.min.js') }}" type="text/javascript"></script>
  <script src="{{ Utility::assets('/vendor/oldbrowser/js/jquery.ui.datepicker-it.js') }}" type="text/javascript"></script>
  <style>
    .click {
      position: relative;
    }

    .click form {
      width: auto;
      position: absolute;
      top: 0;
      left: 42px;
    }

    .prezzo_min {
      z-index: 1000;
      position: absolute;
      top: 9px;
      left: 9px;
      background: #eee;
      width: 31px;
      text-align: center;
      height: 31px;
      line-height: 32px;
    }

    .prezzo_max {
      z-index: 1000;
      position: absolute;
      top: 46px;
      left: 9px;
      background: #eee;
      width: 31px;
      text-align: center;
      height: 31px;
      line-height: 32px;
    }

    .click {
      padding-left: 46px;
    }

  </style>
  <script type="text/javascript">
    jQuery(function() {

      ///////////////////////////////
      // CHECK ATTIVAZIONE LISTINO //
      ///////////////////////////////

      /*jQuery( "#attivo" ).change(function() {
  		alert( "Handler for .change() called." );
  	  });*/

      jQuery('.click').editable(function(value, settings) {
        // imposto val come VARIABILE GLOBALE al valore che c'è già
        var val = value;

        jQuery.ajax({
          url: '<?= url('admin/listini-minmax/salva_prezzo_ajax') ?>',
          type: "post",
          async: false,
          data: {
            'id': this.id,
            'value': value,
            '_token': jQuery('input[name=_token]').val()
          },
          success: function(data) {
            // se la chiamata ajax ha successo val prende il valore resituito dalla chiamata
            // la chiamata DEVE ESSERE "async: false" perché altrimenti il return di editable
            // return(val) viene eseguito prima che val prenda il nuovo valore

            val = data
          }
        });

        return (val);
      }, {
        type: 'text',
        height: '20',
        width: '70',
        event: "focusin",
        onblur: "submit",
        /*Can be either a string or function returning a string. Can be useful when you need to alter the text before editing.*/
        data: function(value) {
          return (value == '&nbsp;&nbsp;&nbsp;') ? '' : value;
        }
      });


      // DATEPICKER

      // Set all date pickers to have Italian text date.
      jQuery.datepicker.setDefaults(jQuery.datepicker.regional["it"]);

      @foreach ($listini as $listino)
      
        jQuery('#periodo_dal_{{ $listino->id }}').datepicker({
        defaultDate: "+0d",
        numberOfMonths: 1,
        autoSize: true,
        showAnim: "clip",
        dateFormat: "dd/mm/yy",
        onSelect: function(selectedDate) {
        jQuery("#periodo_al_{{ $listino->id }}" ).datepicker("option", "minDate", selectedDate);
        jQuery("#periodo_al_{{ $listino->id }}").datepicker("option", "defaultDate", selectedDate);
        },
        onClose: function(dateText, inst){
        if (dateText != "" && jQuery('#periodo_al_{{ $listino->id }}').val() != "" ) {
        //ATTENZIONE NON POSSO serializzare il form perché ho definito il
        //controller come resource e questo è un form per il DELETE, quindi non va
        // nella action corretta
        var data = {
        listino_id: '{{ $listino->id }}',
        '_token': jQuery('input[name=_token]').val(),
        periodo_dal: jQuery('#periodo_dal_{{ $listino->id }}').val(),
        periodo_al: jQuery('#periodo_al_{{ $listino->id }}').val()
        };
        jQuery.ajax({
        url: '<?= url('admin/listini-minmax/salva_data_ajax') ?>',
        type: 'POST',
        data: data,
        dataType: 'json',
        success: function(data) {
        if (data.msg != "ok") {
        alert(data.msg);
      
        jQuery('#periodo_al_'+data.id_listino).val('');
        };
        }
        });
        }
        } // end onClose
        });
      
      
        jQuery('#periodo_al_{{ $listino->id }}').datepicker({
        //defaultDate: "+0d",
        numberOfMonths: 1,
        autoSize: true,
        showAnim: "clip",
        dateFormat: "dd/mm/yy",
        onSelect: function( selectedDate ) {
        jQuery( "#periodo_dal_{{ $listino->id }}" ).datepicker("option", "maxDate", selectedDate);
        },
        onClose: function(dateText, inst){
        if (dateText != "") {
        //ATTENZIONE NON POSSO serializzare il form perché ho definito il
        //controller come resource e questo è un form per il DELETE, quindi non va
        // nella action corretta
        var data = {
        listino_id: '{{ $listino->id }}',
        '_token': jQuery('input[name=_token]').val(),
        periodo_dal: jQuery('#periodo_dal_{{ $listino->id }}').val(),
        periodo_al: jQuery('#periodo_al_{{ $listino->id }}').val()
        };
        jQuery.ajax({
        url: '<?= url('admin/listini-minmax/salva_data_ajax') ?>',
        type: 'POST',
        data: data,
        dataType: 'json',
        success: function(data) {
        if (data.msg != "ok") {
        alert(data.msg);
      
        jQuery('#periodo_al_'+data.id_periodo).val('');
        }
        }
        });
        }
        }
        });
      @endforeach
      // FINE DATEPICKER

    });
  </script>

@endsection
@section('onbodyclose')
@endsection
