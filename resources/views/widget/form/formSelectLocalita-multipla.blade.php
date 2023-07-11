@if ($preventivo == "mail-multipla")
	
	<h4>{{ trans('listing.localita') }} / {{ trans('listing.categoria') }}</h4>
	
	@include('composer.mailMultiplaSelectLocalita')

	@if (isset($stelle))

		<div class="col-sm-3 col-right">
			<span class="select_icon">
				{!! Form::select('categoria',['1'=>$stelle[1],'2'=>$stelle[2],'3'=>$stelle[3],'6'=>$stelle[6],'4'=>$stelle[4],'5'=>$stelle[5]],(array_key_exists('categoria', $prefill)) ? $prefill['categoria'] : null,["class"=>"form-control", "style"=> "margin-left:10px;"]) !!}
				<i class="icon-down-open"></i>
			</span>
		</div>

		<div class="clearfix"></div>
	@endif
	
@endif