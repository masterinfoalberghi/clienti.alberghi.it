@extends('templates.admin')

@section('title')
  Listino Standard
@endsection

@section('content')

{!! Form::open(['role' => 'form', 'url'=>['admin/listini-standard/modifica_stato'], 'method' => 'POST', 'class' => 'form-horizontal']) !!}
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
        <div id="tableContainer">
          <table id="tab_list" class="table table-bordered responsive table-striped table-hover {{$class_disattivata}}"> 
            <thead>
              <tr>
              	<th width="10%"><a class="select_all" href="#seleziona">Seleziona tutto</a></th>
                <th width="5%">dal</th>
                <th width="5%">al</th>
                <th width="16%">Solo Dormire</th>
                <th width="16%">Bed & Breakfast</th> 
                <th width="16%">Mezza Pensione</th>
                <th width="16%">Pensione completa</th> 
                <th width="16%">All Inclusive</th>
                <th></th>
              </tr> 
            </thead> 
            <tbody>

            @if ($listini) 
              @foreach ($listini as $listino)

                <tr id="row_{{$listino->id}}">

                  <td><input type="checkbox" class="listino_id_check" name="listino_id_check[]" id="listino_id_check_{{$listino->id}}" value="{{$listino->id}}" /></td>
                  <td><input type="text" value="{{Utility::getLocalDate($listino->periodo_dal, '%d/%m/%Y')}}" id="periodo_dal_{{$listino->id}}" name="periodo_dal" /></td>
                  <td><input type="text" value="{{Utility::getLocalDate($listino->periodo_al, '%d/%m/%Y')}}" id="periodo_al_{{$listino->id}}" name="periodo_al" /></td>

                  @foreach ($columns as $element)

                    <td>
                      <div tabindex="0" id="{{$listino->id}}|{{$element}}" class="click" >
                        {{($listino->$element == '/' || !isset($listino->$element)) ? '' : $listino->$element}}
                      </div>
                    </td>

                  @endforeach

                  <td class="last-right">

                    {!! Form::open(['role' => 'form', 'url'=>['admin/listini-standard/'.$listino->id], 'method' => 'DELETE', 'class' => 'form-horizontal button_to_delete', 'id' =>"form_periodo_$listino->id"]) !!}
                    <input type="hidden" name="listino_id" value="{{$listino->id}}" />
                    <input type="hidden" name="type" value="empty" /> 
                    <button type="button" class="btn btn-red button_delete" data-id="{{$listino->id}}" onclick="assegnaID({{$listino->id}})"> <i class="entypo-trash"></i> </button>
                    {!! Form::close() !!}

                  </td>
                </tr>

              @endforeach
            @endif
            </tbody> 
          </table>
        </div>
        </div>
      </div>
    </div>
    
    <button id="delete_selected" class="btn btn-red" disabled="disabled">Cancella Selezionati</button>
    
    <div class="modal fade" id="modal-confirm-delete" aria-hidden="true" style="display: none;">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h4 class="modal-title">Eliminazione record</h4>
          </div>
          <div class="modal-body">
            Confermi di voler eliminare in maniera definitiva ed irreversibile i/il record?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
            <button type="button" class="btn btn-primary" onclick="delete_row();">Conferma</button>
          </div>
        </div>
      </div>
    </div>
    
     <div class="modal fade" id="modal-confirm-delete-selected" aria-hidden="true" style="display: none;">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h4 class="modal-title">Eliminazione record</h4>
          </div>
          <div class="modal-body">
            Confermi di voler eliminare in maniera definitiva ed irreversibile i record?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
            <button type="button" class="btn btn-primary" onclick="delete_selected();">Conferma</button>
          </div>
        </div>
      </div>
    </div>
    
    <div class="form-group">
    <div class="row"> 
      <div class="col-sm-12">
        {!! Form::open(['role' => 'form', 'route'=>['listini-standard.store'], 'method' => 'POST', 'class' => 'form-horizontal float', "id" => "addNewEmptyRow"]) !!}
          <input type="hidden" name="type" value="empty"> 
          <input type="hidden" name="type" value="empty"> 
          <button type="submit" id="add_top" class="btn btn-blue"><i class="glyphicon glyphicon-plus"></i> Aggiungi periodo vuoto</button>
        {!! Form::close() !!}
        
        @if ($listini->count() > 1)
        
            {!! Form::open(['role' => 'form', 'route'=>['listini-standard.store'], 'method' => 'POST', 'class' => 'form-horizontal float']) !!}
              <input type="hidden" name="type" value="top"> 
              <button type="submit" id="add_top" class="btn btn-gold"><i class="glyphicon glyphicon-arrow-up"></i> Aggiungi periodo all'inizio</button>
            {!! Form::close() !!}

            {!! Form::open(['role' => 'form', 'route'=>['listini-standard.store'], 'method' => 'POST', 'class' => 'form-horizontal  float']) !!}
              <input type="hidden" name="type" value="bottom"> 
              <button type="submit" id="add_bottom" class="btn btn-gold"><i class="glyphicon glyphicon-arrow-down"></i> Aggiungi periodo alla fine</button>
            {!! Form::close() !!}
      
        @endif
      </div>
    </div>
    </div>



  </div>
</div>

@endsection

@section('onheadclose')

  <script type="text/javascript" src="{{Utility::assets('/vendor/oldbrowser/js/jquery.jeditable.min.js')}}"></script>
  <link href="{{ Utility::assets('/vendor/oldbrowser/css/jquery-ui.min.css') }}" rel="stylesheet">

  <script src="{{ Utility::assets('/vendor/oldbrowser/js/jquery-ui.min.js') }}" type="text/javascript"></script>
  <script src="{{ Utility::assets('/vendor/oldbrowser/js/jquery.ui.datepicker-it.js') }}" type="text/javascript"></script>

  <script type="text/javascript">
	
	function delete_row() {
		
		var id = jQuery('#modal-confirm-delete').data("id");
		jQuery("#form_periodo_" + id).submit();
		
	}
	
	function assegnaID (id) {
		
		
		jQuery('#modal-confirm-delete').modal('show');
		jQuery('#modal-confirm-delete').data("id", id);
			
	}
	
	/* -- */
	
	function delete_selected () {
	
		var listino_id_check = jQuery('input[name="listino_id_check[]"]:checked').map(function(){
			return this.value;
		}).get();
		
		jQuery.ajax({
			
			url: '<?=url("admin/listini-standard/delete_selected") ?>',
			type: "post",
			async: false,
			data : { 
				'value': listino_id_check,
				'_token': '{{ csrf_token() }}'
			},
			success: function(data) {
								
				/* 
        $tabella = jQuery(data).find("#tableContainer");
				jQuery("#tableContainer").html($tabella.html());
				jQuery('#modal-confirm-delete-selected').modal('hide');
				jQuery('.wating').hide();
				jQuery('.submit').show();
				*/
        location.reload();
			
			}
		
		});
	
	 }
	
    jQuery(function() {
		
		// Ajax
		
		jQuery(".select_all").click(function (e) {
			
			e.preventDefault();
			if (jQuery(this).text() == "Seleziona tutto") {
				jQuery(this).text("Deseleziona tutto");
				jQuery(".listino_id_check").prop("checked", "checked");
        jQuery("#delete_selected").prop("disabled", "");
			} else {
				jQuery(this).text("Seleziona tutto");
				jQuery(".listino_id_check").prop("checked", "");
        jQuery("#delete_selected").prop("disabled", "disabled");

			}
				
		});
	
		jQuery(".listino_id_check").click(function (e) {
			
			var s = jQuery(".listino_id_check:checked").length;
			if (s > 0) {
				jQuery("#delete_selected").prop("disabled", "");
			} else {
				jQuery("#delete_selected").prop("disabled", "disabled");
			}
			
		});
		
		jQuery("#delete_selected").click(function (e) {
			
			e.preventDefault();
			
			var s = jQuery(".listino_id_check:checked").length;
			if (s > 0) {
				jQuery('#modal-confirm-delete-selected').modal('show');
			} 
			
		});
		
		
      jQuery('.click').editable(function(value, settings) {
          // imposto val come VARIABILE GLOBALE al valore che c'è già
           var val = value;
           
           jQuery.ajax({
                   url: '<?=url("admin/listini-standard/salva_prezzo_ajax") ?>',
                   type: "post",
                   async: false,
                   data : { 
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

           return(val);
        }, {
           type    : 'text',
           height  : '30',
           width   : '100',
           event   : "focusin",
           onblur : "submit",
           /*Can be either a string or function returning a string. Can be useful when you need to alter the text before editing.*/
           data: function(value) {
             return (value == '&nbsp;&nbsp;&nbsp;') ? '' : value;
           }
       });


      // DATEPICKER
      
      // Set all date pickers to have Italian text date.
      jQuery.datepicker.setDefaults(jQuery.datepicker.regional["it"]);
     
      @foreach ($listini as $listino)

        jQuery('#periodo_dal_{{$listino->id}}').datepicker({
            defaultDate: "+0d",
            numberOfMonths: 1,
            autoSize: true,
            showAnim: "clip",
            dateFormat: "dd/mm/yy",
            onSelect: function(selectedDate) {
                jQuery("#periodo_al_{{$listino->id}}" ).datepicker("option", "minDate", selectedDate);
                jQuery("#periodo_al_{{$listino->id}}").datepicker("option", "defaultDate", selectedDate);
            },
            onClose: function(dateText, inst){
                if (dateText != "" && jQuery('#periodo_al_{{$listino->id}}').val() != "" ) {
                         //ATTENZIONE NON POSSO serializzare il form perché ho definito il
                         //controller come resource e questo è un form per il DELETE, quindi non va
                         // nella action corretta
                        var data = {
                                      listino_id: '{{$listino->id}}',
                                      '_token': jQuery('input[name=_token]').val(),
                                      periodo_dal: jQuery('#periodo_dal_{{$listino->id}}').val(),
                                      periodo_al: jQuery('#periodo_al_{{$listino->id}}').val()
                                    };
                        jQuery.ajax({
                                   url: '<?=url("admin/listini-standard/salva_data_ajax") ?>',
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


        jQuery('#periodo_al_{{$listino->id}}').datepicker({
            //defaultDate: "+0d",
            numberOfMonths: 1,
            autoSize: true,
            showAnim: "clip",
            dateFormat: "dd/mm/yy",
            onSelect: function( selectedDate ) {
                jQuery( "#periodo_dal_{{$listino->id}}" ).datepicker("option", "maxDate", selectedDate);
            },
            onClose: function(dateText, inst){
                if (dateText != "") {
                         //ATTENZIONE NON POSSO serializzare il form perché ho definito il
                         //controller come resource e questo è un form per il DELETE, quindi non va
                         // nella action corretta
                         var data = {
                                      listino_id: '{{$listino->id}}',
                                      '_token': jQuery('input[name=_token]').val(),
                                      periodo_dal: jQuery('#periodo_dal_{{$listino->id}}').val(),
                                      periodo_al: jQuery('#periodo_al_{{$listino->id}}').val()
                                    };
                        jQuery.ajax({
                                    url: '<?=url("admin/listini-standard/salva_data_ajax") ?>',
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


<script type="text/javascript">

</script>

@endsection
