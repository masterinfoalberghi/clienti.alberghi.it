{{--
Questa PAGINA DOVRA' ESTENDERE UN ALTRO TEMPLATE (admin_home ad esempio)
che ha 2 sezioni separate per il titolo della pagine (tag <title>)
ed il contenuto prima del content !!!
tra le altreo cose tolgo <h2> dal template ma sarà chi lo estende a decidere come formattare il contenuto che viene messo !!!
 --}}

@if (!isset($hotel_ids))
	<?php $hotel_ids = [] ?>
@endif

@extends('templates.admin')

@section('title')
	Dashboard
@endsection

@section('content')

	@if(Auth::user()->hasRole('hotel'))

		<p>
			<blockquote class="blockquote-gold"> 
				<p><strong>Descrivi le tue foto</strong> con delle didascalie utili agli utenti.<br />
				I testi devono essere <strong>informativi e non contenere spam</strong></p>
			</blockquote>
		</p>
		
	@elseif(Auth::user()->hasRole(["root", "admin", "operatore"]))
	
			@if (isset($dashboard_links))

				<div class="row">
					
					{{-- <div class="col-sm-6 col-xs-12"> 
						<div class="tile-stats tile-red"> 
						<a href="{{ route('sendgrid-bounces') }}" target="_blank"><div class="icon"><i class="entypo-mail"></i></div> </a>
							@if (isset($bounces_count) && isset($bounces_count->count))
								<div class="num" data-start="0" data-end="{{$bounces_count->count}}" data-postfix="" data-duration="1500" data-delay="1200">{{$bounces_count->count}}</div> 
							@endif
						<h3 style="font-weight: bold;color: #fff!important;">SendGrid - Bounces TOTALI</h3>
						<p style="font-size: 14px;">Clcca sulla <strong>busta</strong> per i dettagli</p>  
						</div> 
					</div>

					<div class="col-sm-6 col-xs-12"> 
						<div class="tile-stats tile-aqua"> 
						<a href="{{ route('sendgrid-blocks') }}" target="_blank"><div class="icon"><i class="entypo-mail"></i></div> </a>
							@if (isset($blocks_count) && isset($blocks_count->count))
								<div class="num" data-start="0" data-end="{{$blocks_count->count}}" data-postfix="" data-duration="1500" data-delay="1200">{{$blocks_count->count}}</div> 
							@endif
						<h3 style="font-weight: bold;color: #fff!important;">SendGrid - Blocks TOTALI</h3>  
						<p style="font-size: 14px;">Clcca sulla <strong>busta</strong> per i dettagli</p>  
						</div> 
					</div>
				</div> --}}

				<div class="row">
					@php
						$t = 0;
					@endphp

					@foreach ($dashboard_links as $url => $element)

						<?php list($title,$color,$qta) = explode('|',$element);	?>
						
						<div class="col-xs-3">
							
							<div class="tile-progress  {{$color}}" style="min-height:160px;">
								
								<div class="tile-header">
									<h3><a href="{{$url}}">{{$title}}</a></h3> 
									<span></span> 
								</div> 
								
								<div class="tile-footer"> 
									@if(trim($qta) != "")		
										<h3 style="color: rgba(255,255,255,0.5);">{{$qta}}</h3>
									@else 
										<p>&nbsp;</p>
										<p>&nbsp;</p>
									@endif	
								</div> 

							</div>
						
						</div>
					
						@php
							$t++;
						@endphp

						@if($t == 4)
							@php $t = 0; @endphp<div class="clearfix"></div>
						@endif

					@endforeach
				</div>
					
			@else
				
				@if(count($hotel_ids))
				{!! Form::open() !!}
				<p>Elenco Hotel che hanno almeno una foto da moderare</p>
				<table class="table table-hover table-bordered table-responsive datatable">
					<thead>
						<tr>
							<th width="50">ID</th>
							<th>Nome</th>
							<th>Ultima modifica</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($hotel_ids as $id)
							<tr>
								<td style="cursor: pointer">
									<div data-id="{{$id["id"]}}" class="hotel_id">{{$id["id"]}}</div>
								</td>
								<td style="cursor: pointer">
									<div data-id="{{$id["id"]}}" class="hotel_id">{{$id["nome"]}}</div>
								</td>
								<td style="cursor: pointer">
									<div data-id="{{$id["id"]}}" class="hotel_id">{{$id["modifica_immagine"]}}</div>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
				{!! Form::close() !!}
				<p>&nbsp;</p>
				@endif

			@endif
			
			{{--
			<div class="row">
				@if (isset($sviluppi))
					<div class="col-sm-4">
						<h3>New Feature</h3>
						{!! $sviluppi !!}
					</div>
				@endif
			
				@if (isset($hotfix))
					<div class="col-sm-4">
						<h3>Hotfix</h3>
						{!! $hotfix !!}
					</div>
				@endif
			</div>--}}
				
		
	@endif

@endsection


@section('onheadclose')

	<script type="text/javascript">

	jQuery(function() {

	    jQuery(".hotel_id").click(function() {

	    	var id = jQuery(this).data("id");
	    	var _token = jQuery('input[name=_token]').val();

	        jQuery.ajax({
	                url: '<?php echo url("admin/seleziona-hotel-da-id-ajax") ?>',
	                type: "post",
	                async: false,
	                data : {
	                        'id': id,
	                        '_token': _token
	                        },
                  success: function(data) {
                    
                    // faccio una chiamata ajax per inserire il record nella tabella tblHotelTagModificati
					jQuery.ajax({
						url: '<?php echo url("admin/save-hotel-tag-modificati") ?>',
						type: "post",
						async: true,
						data: {
						   'id': id,
	                       '_token': _token
						}
					});

                    // se la chiamata ajax ha successo val prende il valore resituito dalla chiamata
                    // la chiamata DEVE ESSERE "async: false" perché altrimenti il return di editable
                    // return(val) viene eseguito prima che val prenda il nuovo valore
					
                    location.href = "{{url('admin/immagini-gallery')}}";
                  }
	            });

	      });
	  });

	</script>

@endsection
