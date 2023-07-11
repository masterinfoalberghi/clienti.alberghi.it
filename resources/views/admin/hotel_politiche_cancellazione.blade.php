{{--
Questa PAGINA DOVRA' ESTENDERE UN ALTRO TEMPLATE (admin_home ad esempio)
che ha 2 sezioni separate per il titolo della pagine (tag <title>)
ed il contenuto prima del content !!!
tra le altreo cose tolgo <h2> dal template ma sarà chi lo estende a decidere come formattare il contenuto che viene messo !!!
 --}}



@extends('templates.admin')

@section('title')
	Hotel con politiche di cancellazione da moderare
@endsection

@section('content')

		<div class="row">
			<div class="col-md-6">
						
				@if($hotel_politiche_cancellazione->count())
					
					<p style="float: left;">Elenco Hotel {{$hotel_politiche_cancellazione->count()}}</p>
					
					<table class="table table-hover table-bordered table-responsive datatable">
						<thead>
							<tr>
								<th width="50">ID</th>
								<th>Nome</th>
								<th>Localita</th>
								<th>Option caparre</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($hotel_politiche_cancellazione as $hotel)
								<tr>
									<td>
										<div class="hotel_id"><a href="{{ route('elenco-politiche-cancellazione',$hotel->id) }}" target="_blank">{{$hotel->id}}</a></div>
									</td>
									<td>
										<div class="hotel_id"><a href="{{ route('elenco-politiche-cancellazione',$hotel->id) }}" target="_blank">{{$hotel->nome}}</a></div>
									</td>
									<td>
										<div class="hotel_id">{{$hotel->localita->nome}}</div>
									</td>
									<td>
										<div class="hotel_id">{{ implode(', ',$hotel->caparre->pluck('option')->toArray())}}</div>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
					<p>&nbsp;</p>
				@else
					<p style="float: left;">Nessun Hotel con politiche di cancellazione da moderare</p>
				@endif
			
			</div>
			<div class="col-md-6">
				@if ($hotel_label->count())
					<p style="float: left;">Elenco Hotel con label {{$hotel_label->count()}}</p>
					<table class="table table-hover table-bordered table-responsive datatable">
						<thead>
							<tr>
								<th width="50">ID</th>
								<th>Nome</th>
								<th>Localita</th>
								<th>Label</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($hotel_label as $hotel)
								<tr>
									<td>
										<div class="hotel_id"><a href="{{ route('elenco-politiche-cancellazione',$hotel->id) }}" target="_blank">{{$hotel->id}}</a></div>
									</td>
									<td>
										<div class="hotel_id"><a href="{{ route('elenco-politiche-cancellazione',$hotel->id) }}" target="_blank">{{$hotel->nome}}</a></div>
									</td>
									<td>
										<div class="hotel_id">{{$hotel->localita->nome}}</div>
									</td>
									<td>
										<div class="hotel_id">{{$hotel->labelCaparre->testo_it}}</div>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
					<p>&nbsp;</p>
				@else
					<p style="float: left;">Nessun Hotel con label per la politica di cancellazione</p>
				@endif
			</div>


</div>
		
@endsection