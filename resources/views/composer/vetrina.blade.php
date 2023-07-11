<?php
// visualizzo la vetrina
// @parameters : $slots (collection slot)

?>
	
	@if ($slots->count())
		@foreach ($slots as $slot)
			@if (!is_null($slot->cliente))
	    		@include('draw_item_cliente.slotVetrina',['slot' => $slot])    				
			@endif
		@endforeach
	@endif
	
	<div class="clearfix"></div>
