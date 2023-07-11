<section id="mappa" class="padding-bottom @if (isset($onlymap)) ifr @endif"> 
	
		<div class="container @if (isset($tipo) && $tipo == "stradario") hidden @endif">
			<div class="row">
				<header>
					<h2 class="title-menu-main content-section padding-bottom-2">{{trans("title.mappa")}}</h2>					
				</header>
			</div>
		</div>
	
		<article>
		
			<header>
				<h4 class="title hidden">{{trans('hotel.dove_siamo')}}</h4>
			</header>
			
			<div id="map-container" class="container">
				

				@if (!isset($tipo) || $tipo != "stradario")
					<div class="row">
						<div class="col-xs-12">
							<ul class="search-place">
								<li><label><input type="checkbox" data-item="restaurant|meal_takeaway" class="google-place" name="dove_mangiare" value="1">Dove mangiare</label></li>
								<li><label><input type="checkbox" data-item="bar|cafe" class="google-place" name="Banca" value="1">Bar</label></li>
								<li><label><input type="checkbox" data-item="liquor_store" class="google-place" name="Pub" value="1">Pub</label></li>
								<li><label><input type="checkbox" data-item="bank" class="google-place" name="Banca" value="1">Banca</label></li>
								<li><label><input type="checkbox" data-item="car_repair" class="google-place" name="Meccanico" value="1">Meccanico</label></li>


								<li><label><input type="checkbox" data-item="church" class="google-place" name="Chiesa" value="1">Chiesa</label></li>
								<li><label><input type="checkbox" data-item="gas_station" class="google-place" name="Benzinaio" value="1">Benzinaio</label></li>

								<li><label><input type="checkbox" data-item="hospital" class="google-place" name="Ospedale="1">Ospedale</label></li>
								<li><label><input type="checkbox" data-item="dentist" class="google-place" name="Dentista="1">Dentista</label></li>
								<li><label><input type="checkbox" data-item="doctor" class="google-place" name="Dottore" value="1">Dottore</label></li>
								<li><label><input type="checkbox" data-item="pharmacy" class="google-place" name="Farmacia" value="1">Farmacia</label></li>
								<li><label><input type="checkbox" data-item="physiotherapist" class="google-place" name="Fisioterapista" value="1">Fisioterapista</label></li>
								<li><label><input type="checkbox" data-item="veterinary_care" class="google-place" name="Veterinario" value="1">Veterinario</label></li>
							</ul>
						</div>
					</div>
				@endif

				<div class="row">
					<div class="col-xs-12">
						<div id="map"></div>
						<div id="infowindow_container"></div>
					</div>
				</div>
			</div>
			
		</article>
	
	@if (!isset($map_source))
		@include('composer.puntiDiInteresse') 
	@endif

</section>

@if (isset($map_lat_lon))
	<script>
		@php $coords = explode(",",$map_lat_lon); $lat = $coords[0]; $lon = $coords[1]; @endphp
		var __lat = "{{$lat}}";
		var __lon = "{{$lon}}";
	</script>
@endif