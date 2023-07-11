@extends('templates.admin')

@section('icon')
	<i class="glyphicon glyphicon-envelope" style="color:#ddd;"></i>&nbsp;&nbsp;
@endsection

@section('title')
	
	Rapporto Contatti 
	@if ($rapporto)
		dal {{$date_range[0]}} al {{$date_range[1]}} 
	@endif
	
	
	
@endsection

@section('content')
	
	{!! Form::open(array('action' => 'Admin\StatsController@rapportoMailResult', 'class' => 'form-horizontal')) !!}

	  {!! csrf_field() !!}

	  <div class="panel panel-default">
	  	
	  	<div class="panel-heading">
	  		<div class="panel-title">Selezionare i parametri di ricerca</div>
	  		<div class="panel-options"></div>
	  	</div>

	  	<div class="panel-body">
	  		
			<div class="form-group">
				
				<div class="col-sm-4">
					{!! Form::label('date_range', 'Scegli le date', array( 'class' => 'control-label') ) !!}
					{!! Form::text('date_range', null, array('class' => 'daterange daterange-inline', 'data-format' => 'DD/MM/YYYY')) !!}
					<i class="entypo-calendar"></i>
				</div>

				<div class="col-sm-3">
					{!! Form::label('hotel', 'Hotel (obbligatorio)', array( 'class' => ' control-label') ) !!}<br />
					{!! Form::text('hotel', null, array('class' => 'form-control typeahead ricerca-hotel', 'data-local' => implode(',', Utility::getUsersHotels()), 'autocomplete' => 'off', 'placeholder' => "Scrivi l'id o il nome dell'hotel") ) !!}
				</div>
				
				<div class="col-sm-2">
					{!! Form::label('tipocontatto', 'Tipo di contatto', array( 'class' => 'control-label') ) !!}<br />
					{!! Form::select('tipocontatto', ["dirette" => "Email dirette", "multiple" => "Email multiple", "chiamate" => "Intenti di chiamata"], null, ['class' => 'form-control']) !!}<br />
			    </div>
							
				<div class="col-sm-2">
					<div class="control-label">&nbsp;</div>
					<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-ok"></i> Calcola</button>
				</div>
			  
			</div>
		</div>
	</div>

	{!! Form::close() !!}
	
	@if ($rapporto)
	
		@if ($tipocontatto == "dirette")
			
			@if (isset($listaEmail))
			<h3>Email dirette ( {{ Utility::conta($listaEmail["oggi"]) + Utility::conta($listaEmail["archivio"]) }} ) 
			<a href="{{ route('export_rapporto-mail-diretta') }}" target="_blank" class="btn btn-green btn-icon">
				export CSV
				<i class="entypo-export"></i>
			</a>
			</h3>

			<div class="panel panel-default">
			   	 <div class="panel-body no-padding">
					 <table class="table table-striped table-hover" >
						 <thead>
							 <tr>
								 <th width="150">Data invio</th>
								 <th width="100">Tipo email</th>
								 <th width="300">Riferimenti</th>
								 <th width="500">Camere</th>
								 <th>Richiesta</th>
							 </tr>
						 </thead>
						 <tbody>	 
						@if ( Utility::conta($listaEmail["oggi"]) )
							
							@foreach($listaEmail["oggi"] as $email)
								<tr>
									<td>{!!App\Utility::getDateEasy($email->data_invio)!!}</td>
									<td>{{$email->tipologia}}</td>
									<td>{{$email->nome}}<br /><a href="mailto:{{$email->email}}">{{$email->email}}</a>@if ($email->telefono) - {{$email->telefono}}@endif</td>
									<td>
										
										@php
											$t = 2;
										@endphp
										
										<table>
											<tr>
												<th style="padding:0 5px 5px;" width="20"></th>
												<th style="padding:0 5px 5px;" width="100">Arrivo</th>
												<th style="padding:0 5px 5px;" width="100">Partenza</th>
												<th style="padding:0 5px 5px;" width="100">Fless.</th>
												<th style="padding:0 5px 5px;" width="100">Ospiti</th>
												<th style="padding:0 5px 5px;" width="100">Trattamento</th>
											</tr>
											<tr>
												<td style="padding:0 5px 5px;" >1)</td>
												<td style="padding:0 5px 5px;" >{{Carbon\Carbon::createFromFormat("Y-m-d", $email->arrivo)->format("d/m/Y")}}</td>
												<td style="padding:0 5px 5px;" >{{Carbon\Carbon::createFromFormat("Y-m-d", $email->partenza)->format("d/m/Y")}}</td>
												<td style="padding:0 5px 5px;" >@if ($email->date_flessibili) Si @else No @endif</td>
												<td style="padding:0 5px 5px;" >
													{{$email->adulti}} Ad + {{$email->bambini}} Ba 
													@if ($email->bambini > 0) 
														( {{ $email->eta_bambini }} ) 
													@endif
												</td>
												<td style="padding:0 5px 5px;" >{{Utility::trattamentoShort($email->trattamento)}}</td>
											</tr>
											
										@foreach( $email->camereAggiuntive as $camera)
											<tr>
												<td style="padding:0 5px 5px;" >{{$t}})</td>
												<td style="padding:0 5px 5px;" >{{Carbon\Carbon::createFromFormat("Y-m-d", $camera->arrivo)->format("d/m/Y")}}</td>
												<td style="padding:0 5px 5px;" >{{Carbon\Carbon::createFromFormat("Y-m-d", $camera->partenza)->format("d/m/Y")}}</td>
												<td style="padding:0 5px 5px;" >@if ($camera->date_flessibili) Si @else No @endif</td>
												<td style="padding:0 5px 5px;" >
													{{$camera->adulti}} Ad + {{$camera->bambini}} Ba 
													@if ($camera->bambini > 0) 
													(	{{ $camera->eta_bambini }} ) 
													@endif
												</td>
												<td style="padding:0 5px 5px;" >{{Utility::trattamentoShort($camera->trattamento)}}</td>
											</tr>
											@php $t++; @endphp
										@endforeach
										</table>
									</td>
									<td>{{$email->richiesta}}</td>
								</tr>
							@endforeach
							
						@elseif ( Utility::conta($listaEmail["archivio"]) > 0)
							
							<tr>
								<td colspan="5" style="padding:30px">
									<div class="alert alert-info alert-dismissable" style="margin:0">
	                					Nessuna <b>email diretta</b> spedita nelle ultime 24 ore
									</div>
								</td>
							</tr>
							
						@endif
						
						@php $c = 0; @endphp
						
						@if (Utility::conta($listaEmail["archivio"]))
							
							<tr>
								<td colspan="5" style="padding:15px 30px;"><b>Già archiviate</b></td>
							</tr>
							
							
							@foreach($listaEmail["archivio"] as $email)
								
								@php
									try {
								@endphp

								<tr @if ($c > 5) style="display:none;" class="itemListaEMail" @endif>
									<td>{!!App\Utility::getDateEasy($email->data_invio)!!}</td>
									<td>{{$email->tipologia}}</td>
									<td>{{$email->nome}}<br /><a href="mailto:{{$email->email}}">{{$email->email}}</a>@if ($email->telefono) - {{$email->telefono}}@endif</td>
									<td>
										@php
											$camere = json_decode($email->camere);
											$t = 1;
										@endphp
										<table>
											<tr>
												<th style="padding:0 5px 5px;" width="20"></th>
												<th style="padding:0 5px 5px;" width="100">Arrivo</th>
												<th style="padding:0 5px 5px;" width="100">Partenza</th>
												<th style="padding:0 5px 5px;" width="100">Fless.</th>
												<th style="padding:0 5px 5px;" width="100">Ospiti</th>
												<th style="padding:0 5px 5px;" width="100">Trattamento</th>
											</tr>
										@foreach( $camere as $camera)
											@if (isset($camera->arrivo))
											<tr>
												<td style="padding:0 5px 5px;" >{{$t}})</td>
												<td style="padding:0 5px 5px;" >{{Carbon\Carbon::createFromFormat("Y-m-d", $camera->arrivo)->format("d/m/Y")}}</td>
												<td style="padding:0 5px 5px;" >{{Carbon\Carbon::createFromFormat("Y-m-d", $camera->partenza)->format("d/m/Y")}}</td>
												<td style="padding:0 5px 5px;" >@if ($camera->date_flessibili) Si @else No @endif</td>
												<td style="padding:0 5px 5px;" >
													{{$camera->adulti}} Ad  
													@if ( isset($camera->bambini) ) 
														+ {{$camera->bambini}} Ba 
														@if ($camera->bambini > 0) 
															( {{ $camera->eta_bambini }} ) 
														@endif 
													@endif
												</td>
												<td style="padding:0 5px 5px;" >{{Utility::trattamentoShort($camera->trattamento)}}</td>
											</tr>
											@else
											<tr>
												<td style="padding:0 5px 5px;" >{{$t}})</td>
												<td style="padding:0 5px 5px;" >{{Carbon\Carbon::createFromFormat("Y-m-d", $camera->checkin)->format("d/m/Y")}}</td>
												<td style="padding:0 5px 5px;" >{{Carbon\Carbon::createFromFormat("Y-m-d", $camera->checkout)->format("d/m/Y")}}</td>
												<td style="padding:0 5px 5px;" >@if ($camera->flex_date) Si @else No @endif</td>
												<td style="padding:0 5px 5px;" >
													{{$camera->adult}} Ad 
													@if ( isset($camera->children) && is_array($camera->children) && count($camera->children)  > 0 ) 
														+ {{count($camera->children)}} Ba 
														@if ( is_array($camera->children) && count($camera->children)  > 0) 
															( {{ implode(',', $camera->children) }} ) 
														@endif 
													@endif
												</td>
												<td style="padding:0 5px 5px;" >{{Utility::trattamentoShort($camera->meal_plan)}}</td>
											</tr>
											@endif
											@php $t++; @endphp

									@endforeach
										
										</table>
									</td>
									<td>{{$email->richiesta}}</td>
								</tr>
								@php $c++; @endphp
								
								@php
										} 
										catch (\Exception $e) 
										{
										//dd($camera->children);		
										}
								@endphp
								
							@endforeach

						@elseif ( Utility::conta($listaEmail["oggi"]) == 0 && Utility::conta($listaEmail["archivio"]) == 0)
							
							<tr>
								<td colspan="5" style="padding:30px">
									<div class="alert alert-info alert-dismissable" style="margin:0">
	                					Nessuna <b>email diretta</b> spedita nell'arco di tempo selezionato
									</div>
								</td>
							</tr>
							
						@endif
						
						</tbody>
					</table>
					
					@if ($c > 5)
						<button id="itemListaEMail" class="btn btn-small btn-default" style="margin:0 30px 15px;">Vedi altri risultati</button>
						<script>
							jQuery("#itemListaEMail").click(function () {
								jQuery(".itemListaEMail").show();
								jQuery(this).hide();
							});
						</script>
					@endif
					
				 </div>
			 </div>
			@endif
		
		@endif
		
		@if ($tipocontatto == "multiple")
			
			@if (isset($listaEmailMultiple))
				
				<h3>Email multiple ( {{ Utility::conta($listaEmailMultiple["oggi"]) + Utility::conta($listaEmailMultiple["archivio"]) }} )
				<a href="{{ route('export_rapporto-mail-multipla') }}" target="_blank" class="btn btn-green btn-icon">
					export CSV
					<i class="entypo-export"></i>
				</a>
				</h3>

				<div class="panel panel-default">
				   	 <div class="panel-body no-padding">
						 <table class="table table-striped table-hover" >
							 <thead>
								 <tr>
									 <th width="150">Data invio</th>
									 <th width="100">Tipo email</th>
									 <th width="300">Riferimenti</th>
									 <th width="500">Camere</th>
									 <th>Richiesta</th>
								 </tr>
							 </thead>
							 <tbody>	 
								 
								@if ( Utility::conta($listaEmailMultiple["oggi"]) )
								
									@foreach($listaEmailMultiple["oggi"] as $email)
										<tr>
											<td>{!!App\Utility::getDateEasy($email->data_invio)!!}</td>
											<td>{{$email->tipologia}}</td>
											<td>{{$email->nome}}<br /><a href="mailto:{{$email->email}}">{{$email->email}}</a></td>
											<td>
												@php
													$t = 2;
												@endphp
												
												<table>
													<tr>
														<th style="padding:0 5px 5px;" width="20"></th>
														<th style="padding:0 5px 5px;" width="100">Arrivo</th>
														<th style="padding:0 5px 5px;" width="100">Partenza</th>
														<th style="padding:0 5px 5px;" width="100">Fless.</th>
														<th style="padding:0 5px 5px;" width="100">Ospiti</th>
														<th style="padding:0 5px 5px;" width="100">Trattamento</th>
													</tr>
													<tr>
														<td style="padding:0 5px 5px;" >1)</td>
														<td style="padding:0 5px 5px;" >{{Carbon\Carbon::createFromFormat("Y-m-d", $email->arrivo)->format("d/m/Y")}}</td>
														<td style="padding:0 5px 5px;" >{{Carbon\Carbon::createFromFormat("Y-m-d", $email->partenza)->format("d/m/Y")}}</td>
														<td style="padding:0 5px 5px;" >@if ($email->date_flessibili) Si @else No @endif</td>
														<td style="padding:0 5px 5px;" >
															{{$email->adulti}} Ad + {{$email->bambini}} Ba 
															@if ( $email->bambini > 0 ) 
																( {{ $email->eta_bambini }} ) 
															@endif
														</td>
														<td style="padding:0 5px 5px;" >{{Utility::trattamentoShort($email->trattamento)}}</td>
													</tr>
													
												@foreach( $email->camereAggiuntive as $camera)
													<tr>
														<td style="padding:0 5px 5px;" >{{$t}})</td>
														<td style="padding:0 5px 5px;" >{{Carbon\Carbon::createFromFormat("Y-m-d", $camera->arrivo)->format("d/m/Y")}}</td>
														<td style="padding:0 5px 5px;" >{{Carbon\Carbon::createFromFormat("Y-m-d", $camera->partenza)->format("d/m/Y")}}</td>
														<td style="padding:0 5px 5px;" >@if ($camera->date_flessibili) Si @else No @endif</td>
														<td style="padding:0 5px 5px;" >
															{{$camera->adulti}} Ad + {{$camera->bambini}} Ba 
															@if ($camera->bambini > 0) 
																( {{ $camera->eta_bambini }} ) 
															@endif
														</td>
														<td style="padding:0 5px 5px;" >{{Utility::trattamentoShort($camera->trattamento)}}</td>
													</tr>
													@php $t++; @endphp
												@endforeach
												</table>
											</td>
											<td>{{$email->richiesta}}</td>
										</tr>
									@endforeach
									
								@elseif ( Utility::conta($listaEmailMultiple["archivio"]) > 0)
									
									<tr>
										<td colspan="5" style="padding:30px">
											<div class="alert alert-info alert-dismissable" style="margin:0">
												Nessuna <b>email multipla</b> spedita nelle ultime 24 ore
											</div>
										</td>
									</tr>
									
								@endif
								
								@php $c = 0; @endphp
								
								@if ( Utility::conta($listaEmailMultiple["archivio"]) )
								
									<tr>
										<td colspan="5" style="padding:15px 30px;"><b>Già archiviate</b></td>
									</tr>
									
									
									@foreach($listaEmailMultiple["archivio"] as $email)
										
										<tr @if ($c > 5) style="display:none;" class="itemListaEMailMultiple" @endif>
											<td>{!!App\Utility::getDateEasy($email->data_invio)!!}</td>
											<td>{{$email->tipologia}}</td>
											<td>{{$email->nome}}<br /><a href="mailto:{{$email->email}}">{{$email->email}}</a></td>
											<td>
												@php
													$camere = json_decode($email->camere);
													$t = 1;
												@endphp
												<table>
													<tr>
														<th style="padding:0 5px 5px;" width="20"></th>
														<th style="padding:0 5px 5px;" width="100">Arrivo</th>
														<th style="padding:0 5px 5px;" width="100">Partenza</th>
														<th style="padding:0 5px 5px;" width="100">Fless.</th>
														<th style="padding:0 5px 5px;" width="100">Ospiti</th>
														<th style="padding:0 5px 5px;" width="100">Trattamento</th>
													</tr>
													@if (isset($camere))
												@foreach( $camere as $camera)
													@if (isset($camera->arrivo))
													<tr>
														<td style="padding:0 5px 5px;" >{{$t}})</td>
														<td style="padding:0 5px 5px;" >{{Carbon\Carbon::createFromFormat("Y-m-d", $camera->arrivo)->format("d/m/Y")}}</td>
														<td style="padding:0 5px 5px;" >{{Carbon\Carbon::createFromFormat("Y-m-d", $camera->partenza)->format("d/m/Y")}}</td>
														<td style="padding:0 5px 5px;" >@if ($camera->date_flessibili) Si @else No @endif</td>
														<td style="padding:0 5px 5px;" >
															{{$camera->adulti}} Ad + {{$camera->bambini}} Ba 
															@if ($camera->bambini > 0) 
																( {{ $camera->eta_bambini }} ) 
															@endif
														</td>
														<td style="padding:0 5px 5px;" >{{Utility::trattamentoShort($camera->trattamento)}}</td>
													</tr>
													@else
													<tr>
														<td style="padding:0 5px 5px;" >{{$t}})</td>
														<td style="padding:0 5px 5px;" >{{Carbon\Carbon::createFromFormat("Y-m-d", $camera->checkin)->format("d/m/Y")}}</td>
														<td style="padding:0 5px 5px;" >{{Carbon\Carbon::createFromFormat("Y-m-d", $camera->checkout)->format("d/m/Y")}}</td>
														<td style="padding:0 5px 5px;" >@if ($camera->flex_date) Si @else No @endif</td>
														<td style="padding:0 5px 5px;" >
															{{$camera->adult}} Ad 
															@if ( isset($camera->children) && is_array($camera->children) && count($camera->children) > 0 ) 
																+ {{count($camera->children)}} Ba 
																@if ( is_array($camera->children) && count($camera->children) > 0 ) 
																	( {{ implode(',', $camera->children) }} ) 
																@endif  
															@endif
														</td>
														<td style="padding:0 5px 5px;" >{{Utility::trattamentoShort($camera->meal_plan)}}</td>
													</tr>
													@endif											
													@php $t++; @endphp
												@endforeach
												@endif
												</table>
											</td>
											<td>{{$email->richiesta}}</td>
										</tr>
										@php $c++; @endphp
										
									@endforeach
								
								@elseif ( Utility::conta($listaEmailMultiple["oggi"]) == 0 && Utility::conta($listaEmailMultiple["archivio"]) == 0)
									
									<tr>
										<td colspan="5" style="padding:30px">
											<div class="alert alert-info alert-dismissable" style="margin:0">
			                					Nessuna <b>email multipla</b> spedita nell'arco di tempo selezionato
											</div>
										</td>
									</tr>
									
								@endif
							
							</tbody>
						</table>
						
						@if ($c > 5)
							<button id="itemListaEMailMultiple" class="btn btn-small btn-default" style="margin:0 30px 15px;">Vedi altri risultati</button>
							<script>
								jQuery("#itemListaEMailMultiple").click(function () {
									jQuery(".itemListaEMailMultiple").show();
									jQuery(this).hide();
								});
							</script>
						@endif
						
					 </div>
				 </div>
				 
			@endif
		@endif
		
		@if ($tipocontatto == "chiamate")
		<h3>Intenti di chiamata</h3>
		
		<div class="panel panel-default">
			 <div class="panel-body no-padding">
				<table class="table table-striped table-hover" >
					
					<thead>
						<tr>
							<th>Data chimata</th>
						</tr>
					</thead>
					
					<tbody>	
						
						@if (count($listaTelefonate["oggi"]))
							
							@foreach($listaTelefonate["oggi"] as $telefonate)
								<tr>
									<td>{!!App\Utility::getDateEasy($telefonate->created_at)!!}</td>
								</tr>
							@endforeach
							
						@elseif (count($listaTelefonate["archivio"]) > 0)
							
							<tr>
								<td colspan="5" style="padding:30px">
									<div class="alert alert-info alert-dismissable" style="margin:0">
										Nessun <b>intento di chiamata</b> nelle ultime 24 ore
									</div>
								</td>
							</tr>
							
						@endif
						
						@php $c = 0; @endphp
						
						@if (count($listaTelefonate["archivio"]))
							
							<tr>
								<td colspan="5" style="padding:15px 30px;"><b>Già archiviate</b></td>
							</tr>
							
							@foreach($listaTelefonate["archivio"] as $telefonate)
								
								<tr @if ($c > 5) style="display:none;" class="itemListaTelefonate" @endif>
									<td>{!!App\Utility::getDateEasy($telefonate->created_at)!!}</td>
								</tr>
								@php $c++; @endphp
								@endforeach
							
							@elseif (Utility::conta($listaEmailMultiple["oggi"]) == 0 && Utility::conta($listaEmailMultiple["archivio"]) == 0)
								
								<tr>
									<td colspan="5" style="padding:30px">
										<div class="alert alert-info alert-dismissable" style="margin:0">
											Nessun <b>intento di chiamata</b> nell'arco di tempo selezionato
										</div>
									</td>
								</tr>
								
							@endif
					</tbody>
					
				</table>
				
				@if ($c > 5)
					<button id="itemListaTelefonate" class="btn btn-small btn-default" style="margin:0 30px 15px;">Vedi altri risultati</button>
					<script>
						jQuery("#itemListaTelefonate").click(function () {
							jQuery(".itemListaTelefonate").show();
							jQuery(this).hide();
						});
					</script>
				@endif
			</div>
		</div>
		
		@endif
				
	@endif
	

@endsection

@section('onheadclose')
	
	{{-- {!! HTML::style('neon/js/daterangepicker/daterangepicker-bs3.css'); !!} --}}
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
	
@endsection

@section('onbodyclose')
	
	{{-- {!! HTML::script('neon/js/daterangepicker/moment.js'); !!} --}}
	{{-- {!! HTML::script('neon/js/daterangepicker/daterangepicker.js'); !!} --}}
	
	<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
	
@endsection