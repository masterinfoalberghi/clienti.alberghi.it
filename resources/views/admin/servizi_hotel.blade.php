@extends('templates.admin')

@section('title')
Servizi - {{count($servizi_ids)}}
@endsection


@section('content')

@if (count($servizi_evidenze_ids))
<div class="alert alert-danger">
	<h4 class="alert-heading">ATTENZIONE</h4><b>i servizi evidenziati in rosso </b>sono quelli associati alle evidenze.<br />Se li rimuovi le relative evidenze non saranno più visibili.
</div>
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

	{!! Form::open(['role' => 'form',  'method' => 'POST', 'class' => 'form-horizontal']) !!}

		<div class="row">
			<div class="col-md-12">

				<?php $count = 1; ?>
				
				@foreach ($categorie as $categoria)

					@if ($categoria->listing && $locale != 'it')
						{{-- do nosting --}}
					@else
						
						<h3>{!!$categoria->nome!!}</h3>
						
						<hr />
						
							@foreach ($categoria->servizi as $servizio)

								<div class="form-group line-servizio" @if (!in_array($servizio->id, $servizi_ids) && $locale != "it")style="opacity:0.5;"@endif>

									<div class="col-sm-4 line-servizio-checkbox">

										@if ($categoria->nome == 'Servizi in hotel' && $servizio->servizi_lingua->first()->nome == 'piscina')
											@php $checkbox_id = ['id' => 'checkbox_piscina']; @endphp
										@elseif ($categoria->nome == 'Servizi in hotel' && strpos($servizio->servizi_lingua->first()->nome, 'piscina fuori struttura')  !== false )
											@php $checkbox_id = ['id' => 'checkbox_piscina_fuori']; @endphp
										@elseif ($categoria->nome == 'Servizi in hotel' && strpos($servizio->servizi_lingua->first()->nome, 'centro benessere')  !== false )
											@php $checkbox_id = ['id' => 'checkbox_benessere']; @endphp
										@elseif ($categoria->nome == 'Servizi per disabili' && strpos($servizio->servizi_lingua->first()->nome, 'servizi per disabili')  !== false )
											@php $checkbox_id = ['id' => 'checkbox_disabili']; @endphp
										@else
											@php $checkbox_id = []; @endphp
										@endif

										@php $checkbox_id['class'] = 'checkbox_servizi'; @endphp
										@php $checkbox_id['onchange'] = 'enableDisableInput(this)'; @endphp
										@php if ($locale != "it") $checkbox_id['style'] = "display:none;"; @endphp

										@if ($categoria->tipo == 'disabili')
											@php $checkbox_id['class'] .= ' conta'; @endphp
											@php $checkbox_id['data-categoria-id'] = $categoria->id; @endphp
										@endif

										@if ($locale == "it")<label>@endif
											{!! Form::checkbox('servizi[]', $servizio->id, in_array($servizio->id, $servizi_ids) or null , $checkbox_id) !!} 
											<span class="@if (in_array($servizio->id, $servizi_evidenze_ids)) vetrina_associata @endif">
											{{ $servizio->servizi_lingua->first()->nome}}
											</span>
										@if ($locale == "it")</label>@endif

									</div>

									<div class="col-sm-8 line-servizio-input">

										<input type="text" name="note[{{$servizio->id}}]" class="form-control @if (!in_array($servizio->id, $servizi_ids)) disabled @endif" value="{{ ($array_note[$servizio->id] == '' && ! $categoria->listing ) ? ' ' : $array_note[$servizio->id] }}"  @if (!in_array($servizio->id, $servizi_ids)) disabled @endif style="width:95% !important; display: inline-block;"/>
										
										@if ($categoria->listing)
											<small class="metri">&nbsp;metri</small>
										@endif

									</div>

								</div>

							@endforeach

							<hr />

							<div class="serizi_privati">
								@if (!$categoria->listing)
									
										@foreach ($array_servizi_privati[$categoria->id] as $servizi_privati)

											<div class="form-group line-servizio">

												@if (isset($servizi_privati['id']) && $servizi_privati['nome'] != '(TRADUZIONE MANCANTE)')

													<div class="col-sm-12 line-servizio-checkbox">

														<label style="display:none">{!! Form::checkbox('serviziPrivati[]', $servizi_privati['id'], true, ['onchange' => 'enableDisableInput(this)']) !!} </label>

														@if(trim($servizi_privati['nome']) != "")
															<input type="input" class="input-checkbox form-control" name="note_private[{{$servizi_privati['id']}}]" style="@if ($locale == 'it')width:95% !important; @endif display: inline-block;" value="{{$servizi_privati['nome']}}">
														@else
															<input type="input" class="input-checkbox form-control" name="note_private[{{$servizi_privati['id']}}]" style="@if ($locale == 'it')width:95% !important; @endif display: inline-block;" value="" placeholder="{{\Utility::getFirstPlaceHolder($servizi_privati['placeholder'])}}">
														@endif

														@if ($locale == 'it')
															<a class="text-danger" id="{{$servizi_privati['id']}}" onclick="deleteServizoPrivatoAll(this, {{$servizi_privati['id']}})">
																<i class="glyphicon glyphicon-trash"></i>
															</a>
														@endif
														
													</div>

												@elseif (isset($servizi_privati['id']) && $servizi_privati['nome'] == '(TRADUZIONE MANCANTE)')

													<div class="col-sm-12 line-servizio-checkbox">

														<label style="display:none">
															<input type="checkbox" name="serviziPrivati[]" checked="checked" value="{{$servizi_privati['id']}}">
														</label>

														<input type="input" class="input-checkbox form-control" name="note_private[{{$servizi_privati['id']}}]" style="@if ($locale == 'it') width:95% !important; @endif display: inline-block;" value="" placeholder="{{\Utility::getFirstPlaceHolder($servizi_privati['placeholder'])}}">
													
														@if ($locale == 'it')
															<a class="text-danger" id="{{$servizi_privati['id']}}" onclick="deleteServizoPrivatoAll(this, {{$servizi_privati['id']}})">
																<i class="glyphicon glyphicon-trash"></i>
															</a>
														@endif

												</div>

												@endif
											</div>
										@endforeach
									
								@endif
								
								<div style="margin:15px 0 90px ">
									@if ($locale == 'it')
										<button type="button" class="btn btn-info" onclick="aggiungiserviziPrivati(this, '{{$categoria->id}}|{{$hotel_id}}')" >aggiungi servizio personalizzato</button>
									@endif			
								</div>
							</div>


					@php $count ++; @endphp
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
	
	<style>
		.form-group input[type="checkbox"] { position: relative; top: 3px; margin:0 6px 0 0 !important}
	</style>

	<script type="text/javascript">

		
		function enableDisableInput(obj) {

			var $ = jQuery;
			var input_value = $(obj).closest(".line-servizio").find(".line-servizio-input input");
			if ($(obj).is(":checked")) {
				input_value.prop("disabled" , "").removeClass("disabled");
			} else {
				input_value.prop("disabled" , "disabled").addClass("disabled");
			}

		}

		function aggiungiserviziPrivati(obj, id) {

			var $ = jQuery;

			$.ajax({
				url: '{{url("admin/servizi/crea_servizio_privato_ajax/".Utility::getLocaleUrl($locale))}}',
				type: "post",
				async: false,
				data : {
					'id': id,
					'_token': jQuery('input[name=_token]').val()
				},
				success: function(data) {

					var html = '<div class="form-group line-servizio" >\n';
						html+= '<div class="col-sm-12 line-servizio-checkbox">\n';
						html+= '<label style="display:none">\n';
						html+= '<input onchange="enableDisableInput(this)" checked="checked" name="serviziPrivati[]" type="checkbox" value="' +data+'">\n';
						html+= '</label>\n';
						html+= '<input type="input" class="form-control" name="note_private['+data+']" style="width:95% !important; display: inline-block;" value="" placeholder="Inserire la descrizione del servizio">\n';
						html+= '<a class="text-danger" id="'+data+'" onclick="deleteServizoPrivatoAll(this, '+data+')"><i class="glyphicon glyphicon-trash"></i></a>\n';
						html+= '</div>\n';
						html+= '</div>\n';
					$(obj).before(html);
				}
			});

		}

		function deleteServizoPrivato(obj, id) {

			var $ = jQuery;

			$.ajax({

				url: '<?=url("admin/servizi/del_servizio_privato_ajax/".Utility::getLocaleUrl($locale)) ?>',
				type: "post",
				async: false,
				data : {
					'id_servizio': id,
					'_token': jQuery('input[name=_token]').val()
				},

				success: function(data) {

					var container = $(obj).closest(".line-servizio");
					container.find(">div").remove();
					container.append('<div class="col-sm-12 line-servizio-checkbox"><input type="checkbox" onchange="enableDisableInput(this)"><input type="input" class="form-control" style="width:95% !important; display: inline-block;" value=""></div>');

				}
			});
			
		}

		function deleteServizoPrivatoAll(obj, id) {

			var $ = jQuery;
			if (window.confirm('Verrà eliminato il servizio con tutte le traduzioni.\nContinuare?')) {

				$.ajax({
					url: '<?=url("admin/servizi/del_servizio_privato_all_ajax/".Utility::getLocaleUrl($locale)) ?>',
					type: "post",
					async: false,
					data : {
						'id_servizio': id,
						'_token': jQuery('input[name=_token]').val()
					},
					success: function(data) {
						$(obj).closest(".line-servizio").remove();
					}
				});

			}

		}

		jQuery(function($) {

			/** Cambio lingue */
			$(".nav-tabs a").click(function (e) {

				e.preventDefault();
				e.stopPropagation();
				document.location.href = $(this).attr("href");

			})

			/**	Azzero la piscina */
			$("#checkbox_piscina").change( function(e) {

				var ch = $(this), c;
				var ch_fuori = $("#checkbox_piscina_fuori");

				// se tolgo la spunta e piscina_fuori è già senza spunta!!
				if (!ch.is(':checked') && !ch_fuori.is(":checked")) {
						ch.prop('checked', true);
						c = confirm('Togliendo la spunta a questo servizio la sezione Info Piscina sarà subito azzerata anche senza salvare.\nContinuare?');
						if (c) {

								// chiamata ajax per azzerare i campi
								$.ajax({
									url: '<?=url("admin/azzera-info-piscina/1") ?>',
									type: "post",
									data : {
										'_token': $('input[name=_token]').val()
									},
								});
								ch.prop('checked', false);

						} // end if c
				} // endif ischecked

			});

			$("#checkbox_piscina_fuori").change( function(e) {

				var ch = $(this), c;
				var ch_piscina = jQuery("#checkbox_piscina");

				// se tolgo la spunta e piscina è già senza spunta!!
				if (!ch.is(':checked') && !ch_piscina.is(":checked")) {
						ch.prop('checked', true);
						c = confirm('Togliendo la spunta a questo servizio la sezione Info Piscina sarà subito azzerata anche senza salvare.\nContinuare?');
						if (c) {

								// chiamata ajax per azzerare i campi
								$.ajax({
									url: '<?=url("admin/azzera-info-piscina/1") ?>',
									type: "post",
									data : {
										'_token': $('input[name=_token]').val()
									},
								});
								ch.prop('checked', false);

						} // end if c
				} // endif ischecked

			});

			/**	Azzero la SPA */
			$("#checkbox_benessere").change( function(e) {

				var ch = $(this), c;

				// se tolgo la spunta
				if (!ch.is(':checked')) {
						ch.prop('checked', true);
						c = confirm('Togliendo la spunta a questo servizio la sezione Info Benessere sarà subito azzerata anche senza salvare.\nContinuare?');
						if (c) {

								// chiamata ajax per azzerare i campi
								$.ajax({
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

			$(".conta").change( function(e) {

				var map = new Map();
				var categoria_old = 0;
				var conta_categorie = 0;

				$('.conta').each(function(i, elem) {

					var id_categoria = $(this).data('categoria-id');

					if (id_categoria != categoria_old)
					{
						categoria_old = id_categoria;
						conta_categorie++;
					}

					var is_checked = $(this).prop('checked');
					if(is_checked) map.set(id_categoria,1);

				});

				// alla fine la mai mappa conterrà tanti elementi quante sono le categorie che hanno almeno 1 elemento checkato
				if (map.size == conta_categorie)
					{ $("#checkbox_disabili").prop('checked', true); }
				else
					{ $("#checkbox_disabili").prop('checked', false); }
			
			});

		});
	</script>

@stop
