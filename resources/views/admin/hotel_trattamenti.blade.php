{{--
Questa PAGINA DOVRA' ESTENDERE UN ALTRO TEMPLATE (admin_home ad esempio)
che ha 2 sezioni separate per il titolo della pagine (tag <title>)
ed il contenuto prima del content !!!
tra le altreo cose tolgo <h2> dal template ma sarà chi lo estende a decidere come formattare il contenuto che viene messo !!!
 --}}



@extends('templates.admin')

@section('title')
	Hotel con trattamenti da moderare
@endsection

@section('content')


		<div style="width:65%; float:left; padding-right:50px;">
			
				@if($hotel_trattamenti->count())
				
				<p style="float: left;">Elenco Hotel</p>
				
				<table class="table table-hover table-bordered table-responsive datatable">
					<thead>
						<tr>
							<th width="50">ID</th>
							<th>Nome</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($hotel_trattamenti as $id => $nome)
							<tr>
								<td>
									<div class="hotel_id"><a href="{{ route('elenco-trattamenti',$id) }}" target="_blank">{{$id}}</a></div>
								</td>
								<td>
									<div class="hotel_id"><a href="{{ route('elenco-trattamenti',$id) }}" target="_blank">{{$nome}}</a></div>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
				<p>&nbsp;</p>
				@else
				<p style="float: left;">Nessun Hotel con trattamenti da moderare</p>
				@endif
			
		</div>
		
@endsection