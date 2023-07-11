
@extends('templates.admin')

@section('title')
Gallery ({{$data["records"]->count()}} immagini)
@endsection

@section('content')

@if(count($data["records"]) === 0)
	
	  <div class="row">
	    <div class="col-lg-12">
	      <div class="alert alert-info">
	        <i class="fa fa-info-circle"></i> Non hai ancora creato nessuna <em>immagine</em>, <a href="{{ url("admin/immagini-gallery/create") }}">creane una ora</a>.
	      </div>
	    </div>
	  </div>
	  
@else

		<div class="row">

			@if ($data["locale"] == 'it')
				<div class="col-md-6">
					<div id="delete_gallery">
						{!! Form::open(['id' => 'delete-all', 'url' => 'admin/immagini-gallery/delete-all', 'method' => 'POST']) !!}
						{!! Form::close() !!}
						<button type="button" onclick="jQuery('#modal-confirm-delete-all').modal('show');" class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i> ELIMINA TUTTA LA GALLERY</button>
						
						<button type="button" id="del_checked" class="btn btn-orange"><i class="glyphicon glyphicon-ok"></i> Elimina selezionati </button>
					
					</div>
					
					
				</div>
				
				<div class="col-md-6" style="text-align:right;">
					<input id="sortaction"  type="checkbox" data-toggle="toggle"> Ordinamento foto
				</div>
				
			@endif
			
		</div>

		<div class="row">
			<div class="col-md-12">
				<ul class="nav nav-tabs" role="tablist" style="position:relative;">
					@foreach (Langs::getAll() as $lang)
						<li role="presentation" @php echo $lang === $data["locale"] ? 'class="active"' : null@endphp >
							<a href="{{url('admin/immagini-gallery/'.Utility::getLocaleUrl($lang))}}" aria-controls="profile" role="tab" data-toggle="tab">
								<img src="{{ Langs::getImage($lang) }}" alt="{{ $lang }}"> {{ Langs::getName($lang) }}
							</a>
						</li>
					@endforeach
				</ul>
			</div>		
		</div>

		<div id="contentLeft">
			<ul id="sortable">

        		@php $count = 0 @endphp

					@foreach($data["records"] as $immagine)

						@php 
							
							$count += 1;
							$class="min-h";
					
							if (!$immagine->immaginiGallery_lingua->isEmpty()):
								if ($immagine->immaginiGallery_lingua->first()->moderato):
									$class .=" moderato";
								else:
									$class .=" non_moderato";
								endif;
							endif
							
						@endphp

		  				<li class="ui-state-default {{$class}}" id="recordsArray_{{$immagine->id}}">

					        @if ($immagine->immaginiGallery_lingua->isEmpty())
								@php $rimasti = $data["limit"];@endphp
					        @else
								@php $rimasti = $data["limit"] - strlen($immagine->immaginiGallery_lingua->first()->caption); @endphp
					        @endif

				          <div class="immagine_gallery_list">
								<span class="badge badge-success chat-notifications-badge">#{{$count}}</span> {{$immagine->foto}}
								@if ($data["locale"] == 'it')
								<span class="check_del"><input type="checkbox" id="{{$immagine->id}}"></span>
								@endif
					        </div>
            
				          <div class="immagine_agg_img">
								
										<a href="{{$immagine->getImg('720x400')}}" target="_blank">
											<img src="{{ $immagine->getImg('720x400')}}" class="img2" alt="">
										</a>	
							  
				          </div>
			
            
							<div class="immagine_agg_del">

								{!! Form::open(['id' => 'item_delete_'.$immagine->id, 'url' => 'admin/immagini-gallery/'.$immagine->id, 'method' => 'DELETE']) !!}
								{!! Form::close() !!}
								
								<button type="button" onclick="jQuery('#modal-confirm-delete_{{$immagine->id}}').modal('show');" class="btn btn-danger pull-right"> <i class="entypo-trash"></i></button>

								@if( $data["foto_listing"] != $immagine->foto)
									{!! Form::open(['id' => 'create_img_listing_'.$immagine->id, 'url' => 'admin/immagini-gallery/create_img_listing/'.$immagine->id, 'method' => 'POST']) !!} {!! Form::close() !!}
									<button type="button" onclick="jQuery('#modal-confirm-create_img_listing_{{$immagine->id}}').modal('show');" class="btn btn-blue pull-right" style="margin-right: 5px;"><i class="entypo-picture"></i> </button>
								@else
									<b style="display: block; padding:6px; color:green;" class="pull-right">IMMAGINE LISTING <i class="entypo-check"></i></b>
								@endif

							</div>

				            <div style="float:right;" class="charcount">
				              <span style="padding:5px; color: #666; background-color: #fff;">
				             	 <input readonly type="text" name="countdown_titolo_{{$immagine->id}}" size="3" value="{{$rimasti}}" class="countdown_titolo_{{$immagine->id}} caratteri_rimasti">caratteri rimasti
				              </span>
				            </div>

							<div class="caption">
								<input type="text" name="foto_{{$immagine->id}}" placeholder="inserisci descrizione foto" data-id="{{$immagine->id}}" onfocus="this.placeholder=''" onblur="this.placeholder='inserisci descrizione foto'" class="click immagini_caption_text" style="width:100%;  height:30px; color:#222;"  maxlength="{{$data['limit']}}" value="@php if (!$immagine->immaginiGallery_lingua->isEmpty()) echo $immagine->immaginiGallery_lingua->first()->caption; @endphp" />
							</div>

							@if($data["locale"] == 'it')
								<div class="listing">
									{!! Form::select('gruppo_id',$data['gruppi'],$immagine->gruppo_id,["class"=>"form-control gruppo-listing", "data-id-immagine"=>$immagine->id, "data-nome-immagine"=>$immagine->foto]) !!}
								</div>
							@endif
			
							{{-- popup modale avviso creazione immagine listing da prima immagine della gallery --}}
							<div class="modal fade" id="modal-confirm-create_img_listing_{{$immagine->id}}" aria-hidden="true" style="display: none;">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											<h4 class="modal-title">Immagine listing</h4>
										</div>
										<div class="modal-body">
											Confermi di voler creare (eventualmente sovrascrivere) l'immagine del listing di questa gallery? 
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
											<button type="button" class="btn btn-primary" onclick="jQuery('#create_img_listing_{{$immagine->id}}').submit();">Conferma</button>
										</div>
									</div>
								</div>
							</div>

							{{-- popup modale avviso elimninazione singola immagine --}}
							<div class="modal fade" id="modal-confirm-delete_{{$immagine->id}}" aria-hidden="true" style="display: none;">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											<h4 class="modal-title">Eliminazione record</h4>
										</div>
										<div class="modal-body">
											Confermi di voler eliminare in maniera definitiva ed irreversibile l'immagine?
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
											<button type="button" class="btn btn-primary" onclick="jQuery('#item_delete_{{$immagine->id}}').submit();">Conferma</button>
										</div>
									</div>
								</div>
							</div>
						
						</li>
			
        			@endforeach
		
				</ul> {{-- sortable --}}
			</div> {{-- contentLeft --}}
			
		
		<div style="clear:both;"></div>



  
	<div id="img_listing" style="display:inline-block">
		{!! Form::open(['id' => 'approve_titles', 'url' => 'admin/immagini-gallery/approve_titles', 'method' => 'POST']) !!}
		{!! Form::close() !!}
		<button type="button" onclick="jQuery('#modal-confirm-approve_title_img_listing').modal('show');" class="btn btn-green btn-icon">APPROVA TUTTI I TITOLI<i class="entypo-picture"></i> </button>
	</div>


	<div id="img_listing" style="display:inline-block">
		{!! Form::open(['id' => 'notify_approved_titles', 'url' => 'admin/immagini-gallery/notify_approved_titles', 'method' => 'POST']) !!}
		{!! Form::close() !!}
		<button type="button" onclick="jQuery('#modal-confirm-notify').modal('show');" class="btn btn-gold btn-icon">NOTIFICA VIA EMAIL IL CLIENTE CHE ALCUNI TITOLI SONO STATI MODIFICATI<i class="entypo-inbox"></i> </button>
	</div>


	{{-- popup modale avviso elimninazione intera gallery --}}
	<div class="modal fade" id="modal-confirm-delete-all" aria-hidden="true" style="display: none;">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title">Eliminazione gallery</h4>
				</div>
				<div class="modal-body">
					Confermi di voler eliminare in maniera definitiva ed irreversibile l'intera gallery?
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
					<button type="button" class="btn btn-primary" onclick="jQuery('#delete-all').submit();">Conferma</button>
				</div>
			</div>
		</div>
	</div>

 

	{{-- popup modale avviso approvazione di tutti i titoli delle immagini della gallery --}}
	<div class="modal fade" id="modal-confirm-approve_title_img_listing" aria-hidden="true" style="display: none;">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title">Titoli immagini</h4>
				</div>
				<div class="modal-body">
					Confermi di voler approvare tutti i titoli delle immagini della gallery?
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
					<button type="button" class="btn btn-primary" onclick="jQuery('#approve_titles').submit();">Conferma</button>
				</div>
			</div>
		</div>
	</div>

	{{-- popup modale avviso notifica cliente --}}
	<div class="modal fade" id="modal-confirm-notify" aria-hidden="true" style="display: none;">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title">Titoli immagini</h4>
				</div>
				<div class="modal-body">
					Confermi di voler notificare il cliente con una mail?
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
					<button type="button" class="btn btn-primary" onclick="jQuery('#notify_approved_titles').submit();">Conferma</button>
				</div>
			</div>
		</div>
	</div>

@endif

@endsection

@section('onheadclose')

	{!! HTML::script('/js/jquery.jeditable.min.js'); !!}
	
	<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
	<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
	
	<script type="text/javascript">
	  
		jQuery(function() {
		
		    jQuery(".nav-tabs a").click(function (e) {
			    
		      e.preventDefault();
		      e.stopPropagation();
		      document.location.href = jQuery
		      (this).attr("href");
		      
		    })
		
			jQuery(".immagini_caption_text").keyup(function (e){
		
				var $me = jQuery(this);
				var $co = jQuery(".countdown_titolo_" + $me.data("id")) //$me.find("caratteri_rimasti");
				$co.val( ""+{{$data["limit"]}} - $me.val().length)
		
			})
		
			jQuery(".click").blur(function() {
		
				var val = this.value;
				var $me = jQuery(this);
				var id = $me.data("id");
				var locale = '{{$data["locale"]}}';
				
				jQuery.ajax({
				       url: '@php echo url("admin/immagini-gallery/crea_caption_ajax") @endphp',
				       type: "post",
				       async: false,
				       data : {
				              'id': id,
				              'value': val,
				              'locale':locale,
				              '_token': jQuery('input[name=_token]').val()
				              },
				      success: function(data) {}
				  });
		
		    });
		});
	
	</script>

@stop


@section('onbodyclose')
	<script type="text/javascript">
		function check_del(){
			var ids = [];
			jQuery('span.check_del > input[type=checkbox]').each(function () {
				if (this.checked) {
					ids.push(this.id); 
				}
			});
			
			if(!jQuery.isEmptyObject(ids)){

				if(window.confirm('Sei sicuro di voler cancellare le immagini selezionate?')){
					
					jQuery.ajax({
								 url: '@php echo url("admin/immagini-gallery/del_checked_ajax") @endphp',
								 type: "post",
								 async: false,
								 data : {
									'ids': ids,
									'hotel_id' : '{{$data["hotel_id"]}}',
												'_token': jQuery('input[name=_token]').val()
												},
								success: function(data) {
									location.reload();
								}
						});

				}
					
			} else {
				alert('Selezionare almeno una immagine!!');
			}
		};
	
		jQuery(document).ready(function(){
		
			// update: sortupdate This event is triggered when the user stopped sorting and the DOM position has changed.
			
			// serialize: String Serializes the sortable's item ids into a form/ajax submittable string. Calling this method produces a hash that can be appended to any url to easily submit a new item order back to the server.
			// It works by default by looking at the id of each item in the format "setname_number", and it spits out a hash like "setname[]=number&setname[]=number".
			// For example, a 3 element list with id attributes "foo_1", "foo_5", "foo_2" will serialize to "foo[]=1&foo[]=5&foo[]=2".
			
			jQuery("#sortaction").change(function (){

				jQuery(this).toggle("active");
				if(jQuery(this).prop('checked') == true) {
					
					jQuery(".immagine_gallery_list, .caption, .listing, .immagine_agg_del, .charcount").hide();
					jQuery(".immagine_agg_img").addClass("big");
					jQuery("#contentLeft ul").sortable( "option", "disabled", false );
					jQuery("#contentLeft").addClass("activeOrder");
					
				} else {
					
					jQuery(".immagine_gallery_list, .caption, .listing, .immagine_agg_del, .charcount").show();
					jQuery(".immagine_agg_img").removeClass("big");
					jQuery("#contentLeft ul").sortable( "option", "disabled", true );
					jQuery("#contentLeft").removeClass("activeOrder");
					
				}
								
			})
			
			jQuery("#contentLeft ul").sortable({ 
				
				placeholder: "ui-state-highlight",
				forcePlaceholderSize: true, 
				opacity: 0.6, 
				cursor: 'crosshair', 
				update: function() {
					
					var token = "{{ Session::token() }}";
					var order = jQuery(this).sortable("serialize") + '&_token='+token;
					var baseUrl = "{{ url('/') }}";
					
					jQuery.post(
						baseUrl+"/admin/immagini-gallery/order_ajax",
						order,
						function(theResponse){}
					);
					
				}
			
			});
			
			jQuery("#contentLeft ul").sortable( "option", "disabled", true );
			
			jQuery( ".gruppo-listing" ).change(function() {
			
				var $me = jQuery(this);
				var id_gruppo = jQuery(this).val();
				var id_immagine = jQuery(this).data("id-immagine");
				var nome_immagine = jQuery(this).data("nome-immagine");
				
				jQuery.ajax({
					url: '@php echo url("admin/immagini-gallery/set_gruppo_immagine_ajax") @endphp',
					type: "post",
					async: false,
					data : {
						'id_gruppo': id_gruppo,
						'id_immagine': id_immagine,
						'nome_immagine': nome_immagine,
						'_token': jQuery('input[name=_token]').val()
					},
					success: function(data) {

						if(data != 'ok') {
							$me.val(0);
							$me.css('backgroundColor',"white");
							$me.css('color',"#222");
						}
						else {
				
							if (id_gruppo == 0)
							{
								$me.css('backgroundColor',"white");
								$me.css('color',"#222");
							}
							else
							{
								$me.css('backgroundColor',"#cc2424");
								$me.css('color','white');
							}
						}

					}
				});
			});
		
			// metto la classe a tutte le select con value != 0
			jQuery( "select.gruppo-listing" ).each(function( index ) {
				
				if( jQuery( this ).val() != 0 ){
					jQuery(this).css('backgroundColor',"#cc2424");
					jQuery(this).css('color','white');
				}
				
			});

			jQuery("#del_checked").click(function() {
					
				check_del();

			});


			

			


	
		}); // documento.ready
		
	</script>
@stop