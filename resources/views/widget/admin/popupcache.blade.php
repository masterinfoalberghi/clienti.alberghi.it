{{-- popup modale avviso elimninazione cache --}}
<div class="modal fade" id="modal-confirm-delete-cache" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title">Eliminazione cache</h4>
			</div>
			<div class="modal-body">
				La cache <b>di questa scheda</b> verrà eliminata. Confermi?
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
				<button type="button" class="btn btn-primary" onclick="jQuery('#delete-cache').submit();">Conferma</button>
			</div>
		</div>
	</div>
</div>