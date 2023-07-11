@extends('templates.admin')

@section('title')
Servizi - {{count($servizi_ids)}}
@endsection


@section('content')

@if (count($servizi_evidenze_ids))
  ATTENZIONE: <span class="vetrina_associata">i servizi evidenziati in rosso </span>sono quelli associati alle evidenze. Se li rimuovi le relative evidenze non saranno più visibili.
@endif
<div class="row">
  <div class="col-md-12">

	<ul class="nav nav-tabs" role="tablist" style="position:relative;">
		@foreach (Langs::getAll() as $lang)
			<li role="presentation" <?= ( $lang === $locale ? 'class="active"' : null) ?> >
				<a href="{{url('admin/servizi/associa-servizi/'.Utility::getLocaleUrl($lang))}}" aria-controls="profile" role="tab" data-toggle="tab">
					<img src="{{ Langs::getImage($lang) }}" alt="{{ $lang }}"> {{ Langs::getName($lang) }}
				</a>
			</li>
		@endforeach

		<li  style="position:absolute; top:0; right:0; ">
		{!! Form::open(['role' => 'form', 'url'=>['admin/servizi/dissocia-servizi'], 'method' => 'POST']) !!}
			 {!! Form::hidden('hotel_da_dissociare', $hotel_id) !!}
			 <button type="submit" id="dissocia" class="btn btn-danger" href="#"><i class="glyphicon glyphicon-remove"></i> Dissocia i servizi in tutte le lingue</button>
		{!! Form::close() !!}
		</li>
	  </ul>
	</div>


    {!! Form::open(['role' => 'form', 'url'=>['admin/servizi/associa-servizi'], 'method' => 'POST', 'class' => 'form-horizontal']) !!}
		<div class="row">

        <div class="col-md-4">
        {{--
        LA FORMATTAZIONE DEVE ESSERE UGUALE A QUELLA DEL FOGLIO CARTACEO
        CHE RIEMPIONO I COMMERCIALI, ALTRIMENTI NON RIESCONO AD INSERIRE !!!???!!!
         --}}
        <?php $count = 1; ?>
        @foreach ($categorie as $categoria)

          @if ($categoria->listing && $locale != 'it')
            {{-- do nosting --}}
          @else
            {{-- Le categorie listing per cui deveo insererire solo il numero di metri le faccio vedere solo in it --}}
              @if ($count == 3 || $count == 6)
                </div>
                <div class="col-md-4">
              @endif
              <div class="panel-heading">
                <div class="panel-title" style="margin-top: 20px; color: #000; border: 1px solid #666; padding: 5px;">
                  {!!$categoria->nome!!}
                </div>
              </div>
              <div class="panel-body">
                @foreach ($categoria->servizi as $servizio)
                  <div class="form-group">
                      <div class="col-sm-12">

                        @if ($categoria->listing)
                          <?php $class="clickListing"; ?>
                        @else
                          <?php $class="click"; ?>
                        @endif

                        @if ($categoria->nome == 'Servizi in hotel' && $servizio->servizi_lingua->first()->nome == 'piscina')
                          <?php $checkbox_id = ['id' => 'checkbox_piscina']; ?>
                        @elseif ($categoria->nome == 'Servizi in hotel' && strpos($servizio->servizi_lingua->first()->nome, 'piscina fuori struttura')  !== false )
                           <?php $checkbox_id = ['id' => 'checkbox_piscina_fuori']; ?>
                        @elseif ($categoria->nome == 'Servizi in hotel' && strpos($servizio->servizi_lingua->first()->nome, 'centro benessere')  !== false )
                           <?php $checkbox_id = ['id' => 'checkbox_benessere']; ?>
                        @elseif ($categoria->nome == 'Servizi per disabili' && strpos($servizio->servizi_lingua->first()->nome, 'servizi per disabili')  !== false )
                           <?php $checkbox_id = ['id' => 'checkbox_disabili']; ?>
                        @else
                           <?php $checkbox_id = []; ?>
                        @endif

                        @if ($categoria->tipo == 'disabili')
                          <?php $checkbox_id['class'] = 'conta'; ?>
                          <?php $checkbox_id['data-categoria-id'] = $categoria->id; ?>
                        @endif

                        <div class="checkbox @if (in_array($servizio->id, $servizi_evidenze_ids)) vetrina_associata @endif" @if ($categoria->listing) style="float: left; width:40%;" @endif>
                          <label>{!! Form::checkbox('servizi[]', $servizio->id, in_array($servizio->id, $servizi_ids) or null , $checkbox_id)  !!} {{ $servizio->servizi_lingua->first()->nome}}</label>
                        </div>
<div tabindex="0" id="{{$servizio->id}}|{{$hotel_id}}" class="{{$class}}" @if (!in_array($servizio->id, $servizi_ids)) style="display:none;" @endif>{{ ($array_note[$servizio->id] == '' && ! $categoria->listing ) ? ' ' : $array_note[$servizio->id] }}</div>
                      @if ($categoria->listing)
                       <span class="metri"@if (!in_array($servizio->id, $servizi_ids)) style="display:none;" @endif>&nbsp;{{ trans('labels.metri') }}</span>
                      @endif

                      </div>
                  </div>
                @endforeach

                @if (!$categoria->listing)

                  <strong>Altri servizi</strong>


                  @foreach ($array_servizi_privati[$categoria->id] as $servizi_privati)
                    @if (isset($servizi_privati['id']) && $servizi_privati['nome'] != 'to_translate')
                      <div class="checkbox">
                      {!! Form::checkbox('serviziPrivato[]', $servizi_privati['id'], true) !!}
                      </div>
                      <div class="falseclick">
                {{$servizi_privati['nome']}}
                <a class="del_servizo_privato" id="{{$servizi_privati['id']}}">X</a>
                @if ($locale == 'it')
                  <a class="del_servizo_privato_all" id="{{$servizi_privati['id']}}">
                    <i class="glyphicon glyphicon-trash"></i>
                  </a>
                @endif
                      </div>

                    @elseif (isset($servizi_privati['id']) && $servizi_privati['nome'] == 'to_translate')
                      <div class="checkbox">
                      <input type="checkbox">
                      </div>

                        <div tabindex="0" id="{{$servizi_privati['id']}}" class="click_3 falseclick"> </div>

                    @endif
                  @endforeach

                  <div class="checkbox">
                    <input type="checkbox"><div tabindex="0" id="{{$categoria->id}}|{{$hotel_id}}" class="click_2"> </div>
                  </div>

                @endif

              </div> {{-- / .panel-body --}}
            <?php $count ++; ?>
          @endif {{-- $categoria->listing && $locale != 'it' --}}

        @endforeach

        </div>
		</div>

		<div class="row">
			<div class="panel-body">
				<div class="col-sm-12">
					@include('templates.admin_inc_record_buttons')
				</div>
			</div>
		</div>

      {!! Form::close() !!}




@endsection

@section('onheadclose')

  <script type="text/javascript" src="{{Utility::assets('/vendor/oldbrowser/js/jquery.jeditable.min.js')}}"></script>

  <script type="text/javascript">

    jQuery(function() {

      jQuery(".conta").change( function(e) {

        var map = new Map();
        var categoria_old = 0;
        var conta_categorie = 0;

        jQuery('.conta').each(function(i, elem) {

          var id_categoria = jQuery(this).data('categoria-id');

          if (id_categoria != categoria_old)
           {
           categoria_old = id_categoria;
           conta_categorie++;
           }

          var is_checked = jQuery(this).prop('checked');
          if(is_checked) map.set(id_categoria,1);

        });


        // alla fine la mai mappa conterrà tanti elementi quante sono le categorie che hanno almeno 1 elemento checkato
         if (map.size == conta_categorie)
            { jQuery("#checkbox_disabili").prop('checked', true); }
          else
            { jQuery("#checkbox_disabili").prop('checked', false); }


       /* console.log(map);
        console.log(map.size);
        console.log(conta_categorie);*/

      });





      jQuery("#checkbox_piscina").change( function(e) {

        var ch = jQuery(this), c;
        var ch_fuori = jQuery("#checkbox_piscina_fuori");

        // se tolgo la spunta e piscina_fuori è già senza spunta!!
        if (!ch.is(':checked') && !ch_fuori.is(":checked")) {
            ch.prop('checked', true);
            c = confirm('Togliendo la spunta a questo servizio la sezione Info Piscina sarà subito azzerata anche senza salvare.\nContinuare?');
            if (c) {

                // chiamata ajax per azzerare i campi
                jQuery.ajax({
                  url: '<?=url("admin/azzera-info-piscina/1") ?>',
                  type: "post",
                  data : {
                         '_token': jQuery('input[name=_token]').val()
                         },
                });
                ch.prop('checked', false);

            } // end if c
        } // endif ischecked

      });


       jQuery("#checkbox_piscina_fuori").change( function(e) {

        var ch = jQuery(this), c;
        var ch_piscina = jQuery("#checkbox_piscina");

        // se tolgo la spunta e piscina è già senza spunta!!
        if (!ch.is(':checked') && !ch_piscina.is(":checked")) {
            ch.prop('checked', true);
            c = confirm('Togliendo la spunta a questo servizio la sezione Info Piscina sarà subito azzerata anche senza salvare.\nContinuare?');
            if (c) {

                // chiamata ajax per azzerare i campi
                jQuery.ajax({
                  url: '<?=url("admin/azzera-info-piscina/1") ?>',
                  type: "post",
                  data : {
                         '_token': jQuery('input[name=_token]').val()
                         },
                });
                ch.prop('checked', false);

            } // end if c
        } // endif ischecked

      });


      jQuery("#checkbox_benessere").change( function(e) {

        var ch = jQuery(this), c;

        // se tolgo la spunta
        if (!ch.is(':checked')) {
            ch.prop('checked', true);
            c = confirm('Togliendo la spunta a questo servizio la sezione Info Benessere sarà subito azzerata anche senza salvare.\nContinuare?');
            if (c) {

                // chiamata ajax per azzerare i campi
                jQuery.ajax({
                  url: '<?=url("admin/azzera-info-benessere/1") ?>',
                  type: "post",
                  data : {
                         '_token': jQuery('input[name=_token]').val()
                         },
                });
                ch.prop('checked', false);

            } // end if c
        } // endif ischecked

      });






		jQuery(".nav-tabs a").click(function (e) {
			e.preventDefault();
			e.stopPropagation();
			document.location.href = jQuery
			(this).attr("href");
		})

		jQuery('input[type=checkbox]').change(function (){
			var $me = jQuery(this);
			if ($me.is(":checked")) {
        $me.closest(".form-group").find(".click").show();
        $me.closest(".form-group").find(".clickListing").show();
				$me.closest(".form-group").find(".metri").show();
			} else {
        $me.closest(".form-group").find(".click").show();
				$me.closest(".form-group").find(".clickListing").hide();
        $me.closest(".form-group").find(".metri").hide();
			}
		});


          jQuery('.clickListing').editable(function(value, settings) {

          // imposto val come VARIABILE GLOBALE al valore che c'è già
             var val = value;

             jQuery.ajax({
                     url: '<?=url("admin/servizi/associa_nota_servizio_listing_ajax/".Utility::getLocaleUrl($locale)) ?>',
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
             height  : '20',
             width   : '450',
             event   : "focusin",
             onblur : "submit",
             /*Can be either a string or function returning a string. Can be useful when you need to alter the text before editing.*/
             data: function(value) {
               return (value == ' ') ? '' : value;
             }
         });

        jQuery('.click').editable(function(value, settings) {

            // imposto val come VARIABILE GLOBALE al valore che c'è già
             var val = value;

             jQuery.ajax({
                     url: '<?=url("admin/servizi/associa_nota_servizio_ajax/".Utility::getLocaleUrl($locale)) ?>',
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
             height  : '20',
             width   : '450',
             event   : "focusin",
             onblur : "submit",
             /*Can be either a string or function returning a string. Can be useful when you need to alter the text before editing.*/
             data: function(value) {
               return (value == ' ') ? '' : value;
             }
         });


      jQuery('.click_2').editable(function(value, settings) {
          // imposto val come VARIABILE GLOBALE al valore che c'è già
           var val = value;

           jQuery.ajax({
                   url: '<?=url("admin/servizi/crea_servizio_privato_ajax/".Utility::getLocaleUrl($locale)) ?>',
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
                    location.reload();
                    val = data;
                  }
                 });

           return(val);
        }, {
           type    : 'text',
           height  : '20',
           width   : '450',
           event   : "focusin",
           onblur  :  "submit",
           /*Can be either a string or function returning a string. Can be useful when you need to alter the text before editing.*/
           data: function(value) {
             return (value == ' ') ? '' : value;
           }
       });



      jQuery('.click_3').editable(function(value, settings) {
          // imposto val come VARIABILE GLOBALE al valore che c'è già
           var val = value;

           jQuery.ajax({
                   url: '<?=url("admin/servizi/aggiorna_servizio_privato_ajax/".Utility::getLocaleUrl($locale)) ?>',
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
                    location.reload();
                    val = data;
                  }
                 });

           return(val);
        }, {
           type    : 'text',
           height  : '20',
           width   : '450',
           event   : "focusin",
           onblur  :  "submit",
           /*Can be either a string or function returning a string. Can be useful when you need to alter the text before editing.*/
           data: function(value) {
             return (value == ' ') ? '' : value;
           }
       });



    jQuery('.del_servizo_privato').click(function(){

        jQuery.ajax({
                url: '<?=url("admin/servizi/del_servizio_privato_ajax/".Utility::getLocaleUrl($locale)) ?>',
                type: "post",
                async: false,
                data : {
                       'id_servizio': this.id,
                       '_token': jQuery('input[name=_token]').val()
                       },
               success: function(data) {
                 location.reload();
               }
              });
    });


    jQuery('.del_servizo_privato_all').click(function(){

        if (window.confirm('Verrà eliminato il servizio con tutte le traduzioni.\nContinuare?')) {

            jQuery.ajax({
                    url: '<?=url("admin/servizi/del_servizio_privato_all_ajax/".Utility::getLocaleUrl($locale)) ?>',
                    type: "post",
                    async: false,
                    data : {
                           'id_servizio': this.id,
                           '_token': jQuery('input[name=_token]').val()
                           },
                   success: function(data) {
                     location.reload();
                   }
                  });

        }

    });


    });
  </script>

@stop
