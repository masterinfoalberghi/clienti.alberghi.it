<div class="rooms" style="margin-top:5px;">	

	<div class="col-sm-6">
		<a href="#" id="addCamera" class="btn btn-small btn-verde"><i class="icon-plus-circled-1"></i> {{ trans('labels.add_rooms') }}</a>
	</div>

	<div class="col-sm-6 pull-right" style="text-align:right;">
		<a href="#" id="delCamera" class="btn btn-small btn-rosso" style=" @if($numero_camere<2) display: none; @endif"><i class="icon-cancel-circled-1"></i> {{ trans('labels.del_rooms') }}</a>
	</div>

	<div class="clearfix"></div>

</div>
