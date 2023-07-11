@extends('templates.admin')

@section('title')
    Politiche di modifica/cancellazione prenotazione
@endsection

@section('content')

@if ($politiche->count())

    <div class="tableContainer">
        <table class="table table-bordered datatable dataTable">
            <thead>
                <tr>
                    @if (!$is_hotel)
                      <td>Option</td>
                    @endif
                    <th>Dal</th>
                    <th>Al</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($politiche as $politica)
                    @php
                        if ($politica->option == 1){
													$option_text = "<b>" . __("labels.option_1_short") . "</b><br />" . __("labels.option_1");
													$class="green";
												}
                        elseif ($politica->option == 2) {
													$option_text ="<b>" .  __("labels.option_2_short")  . "</b><br />" . __("labels.option_2");
													$class="green";
													
												}
                        elseif ($politica->option == 3) {
													$option_text = "<b>" . __("labels.option_3_short")  . "</b><br />" . __("labels.option_3");
													$class="orange";
												}
                        elseif ($politica->option == 4) {
													$option_text = "<b>" . str_replace(["$1","$2"], [$politica->day_before, $politica->perc] , __("labels.option_4_short"))  . "</b><br />" . str_replace(["$1","$2"], [$politica->day_before, $politica->perc] , __("labels.option_4"));
													$class="orange";
												
												}
                        elseif ($politica->option == 5) {
													$option_text = "<b>" . __("labels.option_5_short")  . "</b><br />" . __("labels.option_5");
													$class="red";

												}
                        elseif ($politica->option == 6) {
													$option_text = "<b>" .  str_replace(["$1","$2"], [$politica->day_before, $politica->month_after] , __("labels.option_6_short"))  . "</b><br />" . str_replace(["$1","$2"], [$politica->day_before, $politica->month_after] , __("labels.option_6"));
													$class="red";

												}
                        elseif ($politica->option == 7) {
													$option_text = "<b>" . str_replace(["$1"], [$politica->day_before] , __("labels.option_7_short")) . "</b><br />" .  str_replace(["$1", "$2"], [$politica->day_before] , __("labels.option_7"));
													$class="green";
													
												}
                        elseif ($politica->option == 8) {
													$option_text = "<b>" . str_replace(["$1"], [$politica->day_before] , __("labels.option_8_short")) . "</b><br />" .  str_replace(["$1", "$2"], [$politica->day_before, $politica->perc] , __("labels.option_8"));
													$class="red";

												}
                        elseif ($politica->option == 9) {
													$option_text = "<b>" . str_replace(["$1"], [$politica->day_before] , __("labels.option_8_short")) . "</b><br />" .  str_replace(["$1", "$2"], [$politica->day_before,  $politica->month_after] , __("labels.option_9"));
													$class="orange";

												}
                        elseif ($politica->option == 10) {
													$option_text = "<b>" . str_replace(["$1"], [$politica->perc] , __("labels.option_10_short")) . "</b><br />" .  str_replace(["$1"], [$politica->perc] , __("labels.option_10"));
													$class="red";

												}
                        elseif ($politica->option == 11) {
													$option_text = "<b>" . __("labels.option_11_short")  . "</b><br />" .  str_replace(["$1","$3","$2"], [$politica->day_before,  $politica->month_after,$politica->perc] , __("labels.option_11"));
													$class="red";

												}
                        elseif ($politica->option == 12) {
													$option_text = "<b>" . str_replace(["$1"], [$politica->day_before] , __("labels.option_12_short")) . "</b><br />" .  str_replace(["$1","$2"], [ $politica->day_before, $politica->perc] , __("labels.option_12"));
													$class="red";
                        }
                        elseif ($politica->option == 13) {
													$option_text = "<b>" . str_replace(["$1"], [$politica->day_before] , __("labels.option_13_short")) . "</b><br />" .  str_replace(["$1", "$2"], [$politica->day_before, $politica->perc] , __("labels.option_13"));
													$class="red";

												}
                        
                    @endphp  
										<tr 
											id="item_{{$politica->id}}" 
											@if (!$is_hotel && $politica->to < $today) class="danger" @else class="{{$class}}"  @endif
										>
                        @if (!$is_hotel)
                          <td>{{$politica->option}}</td>
                        @endif
                        <td style="width:80px">{{\Carbon\Carbon::parse($politica->from)->format("d/m/Y")}}</td>
                        <td style="width:80px">{{\Carbon\Carbon::parse($politica->to)->format("d/m/Y")}}</td>
                        <td>{!! $option_text !!}</td>
                        <td class="text-center buttons" style="width:170px">
                            
                            <button onClick="enabled(this, {{$politica->id}},{{$politica->isAttiva($for_js=1)}})" class="btn @if ($politica->enabled == 1) btn-success @else btn-warning @endif"><i class="@if($politica->enabled == 1) entypo-check @else entypo-block @endif"></i></button>
                            <a href="{{url("admin/politiche-cancellazione/" . $politica->id . "/edit")}}" class="btn btn-info"><i class="entypo-pencil"></i></a>
                            <button onClick="_delete({{$politica->id}})" class="btn btn-danger"><i class="entypo-cancel"></i></button>

                        </td>
                    </tr>
                @endforeach
            </tbody>
				</table>

				@if (!$is_hotel)
          @if ($label_caparra->exists)
            <form action="{{ route('cancellazione-delete-label',[$label_caparra->id]) }}" id="form_del_{{$label_caparra->id}}" method="POST">
              @csrf
            </form>
			<form action="{{ route('cancellazione-update-label',[$label_caparra->id]) }}" method="POST">

            <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist" >
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
<textarea class="form-control" style="min-height: initial!important;" name="testo_{{ $lang }}" id="testo_{{ $lang }}" rows="4" required>
@if ($label_caparra->exists)
{{$label_caparra->getTestoBrToNl($lang)}}
@endif
</textarea>
                        </div>
                    @endforeach
                    </div> {{-- .tab-content --}}
                
                </div>
            </div>
            </div>

		  @else
          <form action="{{ route('label-not-labelable') }}" id="form_not_labelable" method="POST">
            @csrf
          </form>
			<form action="{{ route('cancellazione-create-label') }}" method="POST">
              <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="">Etichetta</label>
<textarea class="form-control" style="min-height: initial!important;" name="testo_it" id="testo_it" rows="4" required></textarea>
                    </div>
                </div>
            </div>  
          @endif
						@csrf
		
						<div class="row">
							<div class="col-sm-12">
								<input type="submit" class="btn btn-success" @if ($label_caparra->exists) value="Modifica" @else value="Salva" @endif>
                @if ($label_caparra->exists)
                    <a class="btn btn-danger del_label" data-id="{{$label_caparra->id}}">Elimina</a>
                @else
                  <a class="btn btn-warning not_labelable">Non etichettabile</a>
                @endif
							</div>
						</div>
					</form>
				@endif
    </div>
@else
    <p>Nessuna politica inserita</p>
@endif

@if (isset($gruppo_da_connettere))
  <div class="modal" id="pop_group_hotel" tabindex="-1" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">

          <div class="item_connected">
            <i class="entypo-check connected"></i> SEI CONNESSO COME <span class="nominativo">{{{$hotel_connesso->nome}}}</span> 
          </div>
          
          @foreach ($gruppo_da_connettere as $h)
            @if (!is_null($h->user))
                <div style="padding: 20px 20px;">

                    <form id="{{$h->id}}" method="post" action="{{ url("admin/simpleLogin/1") }}">
                        {!! csrf_field() !!}
                        <input type="hidden" name="username" value="{{$h->user()->first()->username}}">
                    </form>
                    <div class="item_to_connect">
                      <span class="nominativo">{{{$h->nome}}}</span>
                      <button type="button" class="btn btn-blue btn-icon icon-left simpleLogin bottone_entra"  data-id="{{$h->id}}">
                      ENTRA <i class="entypo-login"></i> </button>
                    </div>
                  
                </div>
            @endif
          @endforeach
        </div>
        <div class="modal-footer" style="text-align: center !important; margin-top:0; border-top:0; padding-top:0;">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Chiudi</button>
        </div>
      </div> <!-- /.modal-content -->
    </div> <!-- /.modal-dialog -->
</div><!-- /.modal -->
@endif

@endsection

@section('onheadclose')

    <script type="text/javascript">

        function enabled(obj, id, attiva)
        {

            var _token = '{{ csrf_token() }}';
            var obj = jQuery(obj).find("i");

            var attiva = attiva;

            if (obj.hasClass("entypo-block")) {
                var value = "enabled";
            } else{
                var value = "disabled";
            }


            if(attiva) {
              
              if(window.confirm('ATTENZIONE! Disabilitando questa cancellazione gratuita ci dovrà essere una nuova approvazione dello staff di Info Alberghi. Proseguire?')) { 

              } else {
                return;
              }

            }


            jQuery.ajax({

                url: '<?php echo url("admin/politiche-cancellazione") ?>',
                type: "post",
                async: false,
                data : {
                    'id': id,
                    '_token': _token,
                    'action': 'status',
                    'value': value
                },

                success: function(data) {
                    
                    if (value == "enabled") {
                        obj.removeClass("entypo-block").addClass("entypo-check");
                        obj.parent().removeClass("btn-warning").addClass("btn-success");
                    } else {
                        obj.removeClass("entypo-check").addClass("entypo-block");
                        obj.parent().removeClass("btn-success").addClass("btn-warning");
                    }
                }

            });

        }

        function _delete(id)
        {
            
            var _token = '{{ csrf_token() }}';
            var obj = jQuery(obj).find("i");

            jQuery.ajax({

                url: '<?php echo url("admin/politiche-cancellazione") ?>',
                type: "post",
                async: false,
                data : {
                    'id': id,
                    '_token': _token,
                    'action': 'delete',
                    'value': ""
                },

                success: function(data) {

                    console.log("#item_" + id);
                    jQuery("#item_" + id).addClass("danger");
                    jQuery("#item_" + id + " .buttons").empty();

                }

            });
        }


    </script>

@endsection



@section('onbodyclose')
  <script>
      jQuery(document).ready(function() {
        jQuery("#pop_group_hotel").modal({
            backdrop:'static',
            keyboard:'false'
            });
      });

      jQuery(".del_label").click(function(e) {
          e.preventDefault();
          var id = jQuery(this).data('id');
          jQuery("#form_del_"+id).submit();
        });

      jQuery(".not_labelable").click(function(e) {
          e.preventDefault();
          //if (window.confirm('Vuoi rendere queste politiche NON ETICHETTABILI?')) {
            jQuery("#form_not_labelable").submit();
          //}
        });
      
    </script>
@endsection