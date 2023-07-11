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
	Hotel tag moderati
@endsection

@section('content')

		{{-- popup modale avviso elimninazione cache --}}
		<div class="modal fade" id="modal-confirm-delete-hotel-tag" aria-hidden="true" style="display: none;">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						<h4 class="modal-title">Eliminazione TUTTI gli hotel tag moderati</h4>
					</div>
					<div class="modal-body">
						Tutta la tabella che contiene gli hotel con tag moderati verrà eliminata. Confermi?
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
						<button type="button" class="btn btn-primary" onclick="jQuery('#hotel_tag_delete').submit();">Conferma</button>
					</div>
				</div>
			</div>
		</div>

		<div style="width:65%; float:left; padding-right:50px;">
			
				@if($hotel_tag->count())
				
				<p style="float: left;">Elenco Hotel per i quali sono stati moderati i tag</p>
				
				{!! Form::open(['id' => 'hotel_tag_delete', 'url' => 'admin/remove_hotel_tag_modificati', 'method' => 'POST']) !!}
				{!! Form::close() !!}
				<button style="margin-bottom: 10px;" type="button" onclick="jQuery('#modal-confirm-delete-hotel-tag').modal('show');" class="btn btn-danger pull-right" ><i class="glyphicon glyphicon-remove"></i> Elimina tutto</button>
				<table class="table table-hover table-bordered table-responsive datatable">
					<thead>
						<tr>
							<th width="50">ID</th>
							<th>Nome</th>
							<th>Commerciale</th>
							<th>Creazione</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($hotel_tag as $h)
							<tr>
								<td>
									<div class="hotel_id">{{$h->hotel_id}}</div>
								</td>
								<td>
									<div class="hotel_id">{{$h->hotel}}</div>
								</td>
								<td>
									<div class="hotel_id">{{$h->commerciale}}</div>
								</td>
								<td>
									<div class="hotel_id">{{$h->created_at->formatLocalized("%x %X")}}</div>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
				<p>&nbsp;</p>
				@endif
			
		</div>
		
@endsection