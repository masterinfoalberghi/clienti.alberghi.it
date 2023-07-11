@extends('templates.admin')

@section('title')
Scheda Hotel
@endsection

@section('content')

<style>
	.blue-background-class { background: #ff0000; }
	.revision { border-left: 5px solid #ddd; padding-left:15px;}
	.revision.selected { border-left: 5px solid #f44336; padding-left:15px;}
	.revision.online { border-left: 5px solid #8BC34A; padding-left:15px;}
	.selected a { font-weight:bold;}
	.resision_title { border:none; font-size: 26px; padding:8px 0 !important; border-bottom:1px dashed #ddd; margin:17px 0 24px;}
</style>

<div class="row">
	<div class="col-lg-2"></div>
	<div class="col-lg-8">

		@if (count($data))
		
			{!! Form::open(['role' => 'form', 'url'=>['admin/scheda-hotel/updateLingua'], 'method' => 'POST', 'class' => 'form-horizontal', 'id' => 'form']) !!}
				
				<div style="display:none">
				<input class="form-control resision_title" value="{{$data["revisions_name_selected"]}}" name="revision_name" />
				<input value="{{$data["revisions_id_selected"]}}" name="resivion_id" type="hidden" />
				</div>

				<h3>Video</h3>
				
				<div class="form-group">
					<div class="col-sm-12">
						{!! Form::text('video_url', $data['video_url'] , ['placeholder' => 'es: https://www.youtube.com/embed/jufTl78Xsxg - dove "jufTl78Xsxg" è il codice della condivisione di youtube', 'class' => 'form-control']) !!}
						<p class="help-block">prefisso video youtube <b>https://www.youtube.com/embed/</b></p>
					</div>
				</div>
				
				<h3>Modifica i testi della scheda hotel</h3>
				
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
				<div class="tab-content">
					
					@foreach (Langs::getAll() as $lang)
						
						<?php $cont = count($data[$lang]) ? count($data[$lang]) : 1; ?>
						
						<div role="tabpanel" data-index="{{$cont}}" class=" tab-pane {{$lang}} <?=( $lang === "it" ? 'active' : null)?>" id="{{$lang}}">

							<?php $i = 1; ?>

							<button class="orderContentButton btn btn-primary" type="button" onclick="orderContent();" style="float:left; margin-right: 5px; ">Ordina i paragrafi</button>
							<button class="orderContentButtonEnd btn btn-danger" type="button" onclick="endOrderContent();" style="float:left; margin-right: 5px; display:none;">Fine ordinamento</button>
							<a id="paste-button-{{$lang}}" href="#" data-lang="{{$lang}}"  class="paste-button btn btn-blue" style="float:right; margin-left: 5px; ">Incolla il testo</a>
							<a id="create-button-{{$lang}}" href="#" data-lang="{{$lang}}" class="create-button btn btn-blue" style="float:right;  margin-left: 5px;  ">Crea i paragrafi singolarmente</a>
							
							<div style="clear:both"></div>
							<br />
								
							<div id="pastetext_{{$lang}}" style="display:none;">
								<textarea id="" name="pastetext_{{$lang}}" class="form-control" style="height:400px; "></textarea>
								<br />
							</div>
							
							<div id="paragrafi_{{$lang}}" class="sortable">
							
								@if ($data[$lang])
									
									@foreach ($data[$lang] as $testo)
										
										@php
											
											!isset($testo->piscina) ? $testo->piscina = "" : "";
											!isset($testo->spa) ? $testo->spa = "" : "";
										
										@endphp

										<div id="{{$lang}}_{{$i}}" class="panel panel-default " data-collapsed="0">
											
											<div class="panel-heading">
												<div class="panel-title">
													@if ($testo->title || $testo->subtitle)
														{{$testo->title}} {{$testo->subtitle}}
													@else
														Paragrafo 
													@endif
												</div>
											</div>
											
											<div class="panel-body">
											
													<div class="form-group">
														{!! Form::label('title', 'Titolo', ['class' => 'col-sm-2 control-label']) !!}
														<div class="col-sm-10">
															<input placeholder="Titolo paragrafo" class="form-control" name="title[{{ $lang }}][]" type="text"  value="{{$testo->title}}">
														</div>
													</div>

													<div class="form-group">
														{!! Form::label('subtitle', 'Sottotitolo', ['class' => 'col-sm-2 control-label']) !!}
														<div class="col-sm-10">
															<input placeholder="Sottotitolo paragrafo" class="form-control" name="subtitle[{{ $lang }}][]" type="text"  value="{{$testo->subtitle}}">
														</div>
													</div>

													<div class="form-group">
														{!! Form::label('specchietto', 'Specchietto ( mobile )', ['class' => 'col-sm-2 control-label']) !!}
														<div class="col-sm-10">
															<input placeholder="Specchietto paragrafo mobile" class="form-control" name="mirror[{{ $lang }}][]" type="text" value="@if (isset($testo->mirror)){{$testo->mirror}}@endif">
															<small>Puoi metter qui le 20 parole dello psecchietto mobile. Se lasci vuoto il campo le 20 parole saranno prese in automatico dal testo</small>
														</div>
													</div>
											
													<div class="form-group">
														{!! Form::label('testo', 'Testo', ['class' => 'col-sm-2 control-label']) !!}
														<div class="col-sm-10">
															<textarea id="textarea_{{$lang}}_{{$i}}" class="form-control ckeditor" placeholder="Scheda hotel" name="testo[{{ $lang }}][]">{{$testo->testo}}</textarea>
														</div>
													</div>
												
													<div class="form-group">
														<div class="col-sm-10 col-sm-offset-2">

															{{-- && $hotel->infoPiscina->sup > 0 --}}
															@if (!is_null($hotel->infoPiscina))
																<label><input  type="checkbox" name="piscina[{{ $lang }}][]"  value="{{$testo->piscina}}" @if ($testo->piscina) checked @endif /> Tabella piscina</label><br/>
															@endif

															{{-- && $hotel->infoBenessere->sup > 0 --}}
															@if (!is_null($hotel->infoBenessere))
																<label><input  type="checkbox" name="spa[{{ $lang }}][]" value="{{$testo->spa}}" @if ($testo->spa) checked @endif /> Tabella SPA</label>
															@endif

														</div>
													</div>
												
													<div class="form-group">
														<div class="col-sm-10 col-sm-offset-2">
															<button class="delContentButton btn btn-danger" type="button" onclick="delContentSecondary('{{$lang}}_{{$i}}');">Elimina questo contenuto</button>
														</div>
													</div>
											
											</div>

										</div><!-- panel-default -->
										
										<?php $i++; ?>
															
									@endforeach 
									
								@else
									
									<div id="{{$lang}}_{{$i}}" class="panel panel-default " data-collapsed="0">

										<div class="panel-heading">
											<div class="panel-title">Paragrafo</div>
										</div>

										<div class="panel-body">

											<div class="form-group">
												{!! Form::label('title', 'Titolo', ['class' => 'col-sm-2 control-label']) !!}
												<div class="col-sm-10">
													<input placeholder="Titolo paragrafo" class="form-control" name="title[{{ $lang }}][]" type="text"  value="">
												</div>
											</div>

											<div class="form-group">
												{!! Form::label('subtitle', 'Sottotitolo', ['class' => 'col-sm-2 control-label']) !!}
												<div class="col-sm-10">
													<input placeholder="Sottotitolo paragrafo" class="form-control" name="subtitle[{{ $lang }}][]" type="text"  value="">
												</div>
											</div>

											<div class="form-group">
												{!! Form::label('specchietto', 'Specchietto ( mobile )', ['class' => 'col-sm-2 control-label']) !!}
												<div class="col-sm-10">
													<input placeholder="Specchietto paragrafo mobile" class="form-control" name="mirror[{{ $lang }}][]" type="text" value="">
													<small>Inserisci 20 parole per la didascalia di anteprima mobile, se il campo è vuoto vengono prese in automatico le prime 20 parole del testo</small>
												</div>
											</div>

											<div class="form-group">
												{!! Form::label('testo', 'Testo', ['class' => 'col-sm-2 control-label']) !!}
												<div class="col-sm-10">
													<textarea id="textarea_{{$lang}}_{{$i}}" class="form-control ckeditor" placeholder="Scheda hotel" name="testo[{{ $lang }}][]"></textarea>
												</div>
											</div>

											<div class="form-group">
												<div class="col-sm-10 col-sm-offset-2">

													@if (!is_null($hotel->infoPiscina) && $hotel->infoPiscina->sup > 0)
														<label><input  type="checkbox" name="piscina[{{ $lang }}][]" value="0" /> Tabella piscina</label><br/>
													@endif

													@if (!is_null($hotel->infoBenessere) && $hotel->infoBenessere->sup > 0)
														<label><input  type="checkbox" name="spa[{{ $lang }}][]"  value="0" /> Tabella SPA</label>
													@endif

												</div>
											</div>

											<div class="form-group">
												<div class="col-sm-10 col-sm-offset-2">
													<label><input  type="checkbox" name="piscina[{{ $lang }}][]" value="0" /> Tabella piscina</label><br/>
													<label><input  type="checkbox" name="spa[{{ $lang }}][]"  value="0" /> Tabella SPA</label>
												</div>
											</div>

											<div class="form-group">
												<div class="col-sm-10 col-sm-offset-2">
													<button class="delContentButton btn btn-danger" type="button" onclick="delContentSecondary('{{$lang}}_{{$i}}');">Elimina questo contenuto</button>
													<button class="delContentButton btn btn-danger" type="button" onclick="delContentSecondary('{{$lang}}_{{$i}}');">Elimina paragrafo</button>
												</div>
											</div>

										</div>

									</div><!-- panel-default -->
									
									<?php $i++; ?>
								
								@endif
								
							</div><!-- paragrafi -->
								
						</div><!-- sortable -->
						
					@endforeach  
					
				</div><!-- tab-content -->
				
				<div class="aggiungiContenuto" style="text-align: center; ">
					<div style="padding:50px; ">
						<button class="addContentButton btn btn-info" data-index="1" type="button" onclick="addContentSecondary();">Aggiungi paragrafo</button>
					</div>
				</div>
				
				<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-ok"></i> Salva</button>
				
			{!! Form::close() !!}
			
			
		@else
			
			<p>Nessuna descrizione inserita</p>
		
		@endif
				
	</div>
	<div class="col-lg-2"></div>
	{{--<div class="col-lg-4">
		<h2>Revisioni</h2>

		@foreach($data['revisions'] as $revision)
			<div class="revision @if($revision["online"] == 1)) online @endif @if ($data["revisions_id_selected"] == $revision["id"]) selected @endif">
				<a href="" onclick="loadRevision({{$revision["id"]}})" ><b>
					{{$revision["name"]}} @if($revision["online"] == 1)(online)@endif
				</b></a><br/>
					<small>
					Creata il <b>{{\Carbon\Carbon::parse($revision["date"])->format("d/m/Y")}}</b><br />
					Ultimo aggiornamento <b>{{\Carbon\Carbon::parse($revision["update"])->format("d/m/Y")}}</b>
				</small>
				<br />
				<br />
				@if ($data["revisions_id_selected"] != $revision["id"])
					<a href="?revision={{$revision["id"]}}" class="btn btn-small btn-blue">Seleziona</a>
				@endif
				<button class="btn btn-small btn-info" onclick="duplicateRevision({{$revision["id"]}}, '{{trim($revision["name"])}}')">Duplica</button>
				@if($revision["online"] != 1)
					<button class="btn btn-small btn-primary" onclick="onlineState({{$revision["id"]}}, {{$data["hotel_id"]}})">Metti online</button>
				@endif

			</div>
		<hr />
	@endforeach

	</div>--}}
</div>

<div class="modal fade" id="modal-duplicate" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content"> 
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title">Duplica descrizione</h4>
			</div>
			<div class="modal-body">
				<label>Inserisci il nome della revisione</label>
				<input type="text" value="" placeholder="es: covid-19" class="form-control" />
			</div>
		<div class="modal-footer"> 
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			<button type="button" class="btn btn-info" onclick="duplicateRevisionAction()">Save changes</button>
		</div>
	</div>
</div>

 
 
@endsection


@section('onbodyclose')

	<script type="text/javascript">
				
		jQuery("#form").submit(function (e) {
			
			//e.preventDefault();
			
			var c = 0;
			jQuery("[type=checkbox]").each(function () {
				
				var $me = jQuery(this);
				$me.hide();
				
				if (!$me.is(":checked")) {
					$me.val("0").prop("checked", "checked");
				} else {
					$me.val("1");
				}
				
			});
			
			//jQuery(this).trigger("");
			
		});
		
		jQuery(".paste-button").click(function (e) {
			
			e.preventDefault();
			var l = jQuery(this).data("lang");
			
			jQuery("#paragrafi_" + l).hide();
			jQuery("#pastetext_" + l + " textarea").val("");
			jQuery("#pastetext_" + l).show();
			jQuery(".aggiungiContenuto").hide();
			
		});
		
		jQuery(".create-button").click(function (e) {
			
			e.preventDefault();
			var l = jQuery(this).data("lang");
			
			jQuery("#paragrafi_" + l).show();
			jQuery("#pastetext_" + l).hide();
			jQuery(".aggiungiContenuto").show();
			
			
		});

		function orderContent() {

			 jQuery(".sortable .panel-default").attr("data-collapsed", "1");
			 jQuery(".orderContentButtonEnd").show();
			 jQuery(".orderContentButton").hide();

			 jQuery(".sortable").sortable({ 
					
				placeholder: "ui-state-highlight",
				forcePlaceholderSize: true, 
				opacity: 0.6, 
				draggable: ".panel-default",
				ghostClass: 'blue-background-class',
				
				start: function (e, ui) {
				  jQuery('textarea').each(function () {
				     tinymce.execCommand('mceRemoveEditor', false, jQuery(this).attr('id'));
				  });
				},
				stop: function (e, ui) {
				  jQuery('textarea').each(function () {
				     tinymce.execCommand('mceAddEditor', true, jQuery(this).attr('id'));
				  });
				}							
			});
			
			jQuery(".sortable").addClass("init");
		}

		function endOrderContent() {

			 jQuery(".sortable .panel-default").attr("data-collapsed", "0");
			 jQuery(".orderContentButtonEnd").hide();
			 jQuery(".orderContentButton").show();

			jQuery(".sortable").sortable("destroy");

		}
		
		function delContentSecondary (id) {
			
			jQuery("#" + id).remove();
			
		}
		
		function initTiny() {
			
			tinymce.init({
				selector: '.ckeditor',
				height: 200,
				menubar:false,
				forced_root_block : false,
				plugins: [
					"advlist autolink lists link image charmap print preview anchor",
					"searchreplace visualblocks code fullscreen",
					"insertdatetime media table contextmenu paste imagetools jbimages code filemanager"
				],
				image_advtab: true,
				style_formats: [ 
					{title: 'Evidenziato', inline: 'span', classes: "evidenziato", styles: {"font-weight:normal; border-bottom": '4px solid #f7f1c4'}}
				],
				toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright | bullist numlist outdent indent | link anchor code",
				relative_urls: false
			});
			
		}
		
		initTiny();
		
		function addContentSecondary() {
			
			var panel = jQuery(".tab-pane.active");
			var lang = panel.attr("id");
			var index = parseInt(panel.attr("data-index")) + 1;
			panel.attr("data-index", index);
			
			var html = "";
			
			html  = '<div id="'+lang+'_'+index+'" class="panel panel-default" data-collapsed="0">';
			html += '	<div class="panel-heading">';
			html += '		<div class="panel-title">Paragrafo</div>';
			html += '	</div>';
			html += '	<div class="panel-body">';
			html += '		<div class="form-group">';
			html += '			<label for="title" class="col-sm-2 control-label">Titolo</label>';
			html += '			<div class="col-sm-10">';
			html += '				<input placeholder="Titolo paragrafo" class="form-control" name="title['+lang+'][]" type="text" id="title" value="">';
			html += '			</div>';
			html += '		</div>';
			html += '		<div class="form-group">';
			html += '			<label for="sottotitolo" class="col-sm-2 control-label">Sottotitolo</label>';
			html += '			<div class="col-sm-10">';
			html += '				<input placeholder="Sottotitolo paragrafo" class="form-control" name="subtitle['+lang+'][]" type="text" id="title" value="">';
			html += '			</div>';
			html += '		</div>';
			html += '		<div class="form-group">';
			html += '			<label for="sottotitolo" class="col-sm-2 control-label">Sottotitolo</label>';
			html += '			<div class="col-sm-10">';
			html += '				<input placeholder="Specchietto paragrafo mobile" class="form-control" name="mirror['+lang+'][]" type="text" value="">';
			html += '				<small>Puoi metter qui le 20 parole dello psecchietto mobile. Se lasci vuoto il campo le 20 parole saranno prese in automatico dal testo</small>';
			html += '			</div>';
			html += '		</div>';
			html += '		<div class="form-group">';
			html += '			<label for="testo" class="col-sm-2 control-label">Testo</label>';
			html += '			<div class="col-sm-10">';
			html += '				<textarea class="form-control ckeditor" placeholder="Scheda hotel" name="testo['+lang+'][]"></textarea>';
			html += '			</div>';
			html += '		</div>';
			html += '		<div class="form-group">';
			html += '			<div class="col-sm-10 col-sm-offset-2">';
			html += '				<label><input type="checkbox" name="piscina['+lang+'][]" value="0" /> Tabella piscina</label><br/>';
			html += '				<label><input type="checkbox" name="spa['+lang+'][]" value="0" /> Tabella SPA</label>';
			html += '			</div>';
			html += '		</div>';
			html += '		<div class="form-group">';
			html += '			<div class="col-sm-10 col-sm-offset-2">';
			html += '				<button class="delContentButton btn btn-danger" type="button" onclick="delContentSecondary("'+lang+'_'+index+'");">Elimina questo contenuto</button>';
			html += '			</div>';
			html += '		</div>';
			html += '	</div>';
			html += '</div>';

			
			panel.append(html);
			initTiny();
			{{-- initOrder(); --}}
			
		}

		function duplicateRevision(id, name) {
			jQuery("#modal-duplicate").attr("data-id-revision", id);
			jQuery("#modal-duplicate").attr("data-name-revision", name);
			jQuery('#modal-duplicate').modal('show');
		}

		function onlineState(revision_id, hotel_id) {

			data_options = {
                "_token": '{{ csrf_token() }}',
                "revision_id": revision_id,
                "hotel_id": hotel_id
			}

			jQuery.ajax(
				{
					url: "/admin/scheda-hotel/online",
					type: "post",
                    async: false,
                    data: data_options,
					success: function(result){
						document.location.href= '/admin/scheda-hotel?revision=' + result.revision_id;
					}
				}
			);

		}

		function duplicateRevisionAction() {

			var revision_id = jQuery("#modal-duplicate").attr("data-id-revision");
			var revision_old_name = jQuery("#modal-duplicate").attr("data-name-revision");
			var revision_name = jQuery("#modal-duplicate input").val();

			if (revision_name == "") { revision_name = "Copia di " + revision_old_name; }

			data_options = {
                "_token": '{{ csrf_token() }}',
                "revision_id": revision_id,
                "revision_name": revision_name
			}

			jQuery.ajax(
				{
					url: "/admin/scheda-hotel/duplicate",
					type: "post",
                    async: false,
                    data: data_options,
					success: function(result){
						jQuery('#modal-duplicate').modal('hide');
						jQuery('.main-content').text("Salvataggio in corso...");
						document.location.href= '/admin/scheda-hotel?revision=' + result.id;
					}
				}
			);


		}

			
    </script>
	
@endsection

@section('onheadclose')
<script type="text/javascript" src="{{Utility::assets('/vendor/neon/js/tinymce/tinymce.min.js')}}"></script>
  
@endsection