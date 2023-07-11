@if ( (isset($data["record"]) && $data["record"]->exists) || isset($showButtons) )
	
    @if (isset($showSaveWithoutEmail) && $showSaveWithoutEmail == 1)
		<button name="submit" type="submit" class="btn btn-primary" value="save_no_email"><span class="glyphicon glyphicon-ok"></span> Salva senza inviare email</button>
	    <button name="submit" type="submit" class="btn btn-info" value="save"><i class="glyphicon glyphicon-envelope"></i> Salva con invio email</button>
	@else
		<button name="submit" type="submit" class="btn btn-primary" value="save" id="js-salva-top-submit"><i class="glyphicon glyphicon-ok"></i> Salva</button>
    @endif

    <button type="button" onclick="jQuery('#modal-confirm-delete').modal('show');" class="btn btn-danger pull-right"><i class="glyphicon glyphicon-remove"></i> Elimina</button>
        
    @if( isset($reset) && $reest = "si")
    <button type="button" onclick="jQuery('#modal-confirm-reset').modal('show');" class="btn btn-gold pull-right" style=" margin-right:15px; "><i class="glyphicon glyphicon-remove"></i> Cancella contenuti</button>
    @endif

    <div class="modal fade" id="modal-confirm-delete" aria-hidden="true" style="display: none;">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h4 class="modal-title">Eliminazione record</h4>
          </div>
          <div class="modal-body">
            Confermi di voler eliminare in maniera definitiva ed irreversibile il record?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
            <button type="button" class="btn btn-primary" onclick="jQuery('#record_delete').submit();">Conferma</button>
          </div>
        </div>
      </div>
    </div>

    @if (isset($showArchivia) && $showArchivia == 1)
        <button type="submit" class="btn btn-default" onclick="jQuery('#archiviazione').val(1);"><i class="glyphicon glyphicon-hdd"></i> Archivia</button>
    @endif
    
    @if( isset($reset) && $reest = "si")
     <div class="modal fade" id="modal-confirm-reset" aria-hidden="true" style="display: none;">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h4 class="modal-title">Eliminazione record</h4>
          </div>
          <div class="modal-body">
            Confermi di voler eliminare tutti i contenuti?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
            <button type="button" class="btn btn-primary reset-content" onclick="jQuery('#modal-confirm-reset').modal('hide');">Conferma</button>
          </div>
        </div>
      </div>
    </div>
    @endif
    
@else
    
    <button type="submit" class="btn btn-primary" id="js-salva-top-submit"><i class="glyphicon glyphicon-ok"></i> Salva</button>
    
@endif

