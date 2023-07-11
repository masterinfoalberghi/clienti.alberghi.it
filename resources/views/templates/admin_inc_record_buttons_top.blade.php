@if ( (isset($data["record"]) && $data["record"]->exists) || isset($showButtons) )
	
    <button type="button" class="btn btn-primary" id="js-salva-top"><i class="glyphicon glyphicon-ok"></i> Salva</button>
    <button type="button" onclick="jQuery('#modal-confirm-delete').modal('show');" class="btn btn-danger pull-right" ><i class="glyphicon glyphicon-remove"></i> Elimina</button>
   
    @if( isset($reset) && $reest = "si")  
   		<button type="button" onclick="jQuery('#modal-confirm-reset').modal('show');" class="btn btn-gold pull-right " style=" margin-right:15px; "><i class="glyphicon glyphicon-remove"></i> Cancella contenuti</button>
   	
    @endif
    
@else

    <button type="button" class="btn btn-primary" id="js-salva-top"><i class="glyphicon glyphicon-ok"></i> Salva</button>
    
@endif

