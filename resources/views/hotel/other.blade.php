<a id="maps-scroll" name="maps"></a>

<div class="padding-topx2">
	@include('widget.mappa_statica')
</div>

<div class="clearfix"></div>

<div class="container">
	<div class="row">
		<div class="col-md-12 col-sm-12">
				
			<a id="services-scroll" name="services"></a>

			<section id="servizi"  class="padding-bottomx2 padding-top-2">       
			    
			    <header>
			        <h2 class="content-section padding-bottomx2-2">{{trans("title.servizi")}}</h2>
			    </header>
			    
			    @include('composer.servizi', array('titolo' => trans('hotel.servizi')))
			    
			</section>

		</div>
	</div>
</div>
	

