@extends('templates.admin')

@section('title')
	Inserisci i servizi dei tuoi trattamenti
@endsection

@section('content')
<div>
	
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
	
	<form action="{{ route('salva-trattamenti') }}" method="post">
		@csrf
		<div class="tab-content">
			        
			@foreach (Langs::getAll() as $lang)
				<div role="tabpanel" class="tab-pane <?=( $lang === "it" ? 'active' : null)?>" id="{{ $lang }}">
					@foreach ($note[$lang] as $nota => $contenuto_nota)
						<div class="row form-group">		
							<div class="col-sm-2">
									
									<label>
										{!!$nomi_trattamenti[$nota]!!}
									</label>
								
							</div>
							<div class="col-sm-10">
									{!! Form::textarea($nota, $contenuto_nota, ['class' => 'form-control','style' => 'height: 100px!important; min-height:100px!important; width: 600px;', 'placeholder' => 'Elenca i servizi inclusi in '. Utility::getLanguage($lang)[0] ]) !!}
							</div>
						</div>
					@endforeach

					@if (isset($is_hotel) && !$is_hotel)
						<hr>	
						<hr>	
						<div class="row form-group">		
							<div class="col-sm-2">
									
									<label>
										<b>Altro trattamento</b>
									</label>
								
							</div>
							@foreach ($altro_trattamento[$lang] as $a_trattamento => $contenuto_altro_trattamento)
								<div class="col-sm-10">
										{!! Form::textarea($a_trattamento, $contenuto_altro_trattamento, ['class' => 'form-control','style' => 'height: 100px!important; min-height:100px!important; width: 600px;', 'placeholder' => 'Elenca altri trattamenti']) !!}
								</div>
							@endforeach  
						</div>
						<hr>	
						<div class="row form-group">		
							<div class="col-sm-2">
									
									<label>
										<b>Altro</b>
									</label>
								
							</div>
							@foreach ($note_altro[$lang] as $nota_altro => $contenuto_nota_altro)
								<div class="col-sm-10">
										{!! Form::textarea($nota_altro, $contenuto_nota_altro, ['class' => 'form-control','style' => 'height: 100px!important; min-height:100px!important; width: 600px;', 'placeholder' => 'Tutti i trattamenti indicati sopra includono anche: ....']) !!}
								</div>
							@endforeach  
						</div>	
					@endif
										
				</div> {{-- /tabpanel --}}
			@endforeach  
			
			<div style="display:flex; justify-content:space-between;">
				<button type="submit" class="btn btn-primary" value="salva" name="salva"><i class="glyphicon glyphicon-ok"></i> Salva</button>
				@if (Auth::user()->hasRole(["admin", "operatore"]))
					<label class="custom-control custom-checkbox">
						<input type="checkbox" name="invia_mail" id="" value="1" class="custom-control-input"> Invia mail di notifica al cliente
					</label>
				@endif
				<button type="submit" class="btn btn-blue"  value="traduci" name="salvatraduci"><i class="glyphicon glyphicon-transfer"></i> Salva e forza la traduzione</button>
			</div>
		</div> {{-- /tab-content --}}
	
	</form>
@endsection
</div>

@section('onheadclose')

	<script type="text/javascript">

		jQuery(document).ready(function($) {
			
		});

	</script>

@endsection
