<?php ?>

	<section id="testimonials">
		<div class="container">
			<div class="row"> 
					
					<header>
						<h2>Obiettivi raggiunti</b></h2>
					</header>
					
					<div class="col-lg-3 col-md-2"></div>
					<div class="col-lg-6 col-md-8">
						<p>Le tabelle qui sotto illustrano tutti gli interventi compiuti dal Green Booking in questi anni: il numero di alberi piantati per ciascuna edizione dell'iniziativa, i luoghi di destinazione, le essenze prescelte.</p>
					</div>
					<div class="col-lg-3 col-md-2"></div>
					
					<div class="clearfix"></div>
					
					<ul class="button-filter" data-referer="testimonials">
						<li class="btn btn-tag btn-grey selected" data-filter="2019">2019</li>
						<li class="btn btn-tag btn-grey" data-filter="2018">2018</li>
						<li class="btn btn-tag btn-grey" data-filter="2017">2017</li>
						<li class="btn btn-tag btn-grey" data-filter="2016">2016</li>
					</ul>

					<?php require('tabelle/table_2019.php'); ?>

					<?php require('tabelle/table_2018.php'); ?>

					<?php require('tabelle/table_2017.php'); ?>

					<?php require('tabelle/table_2016.php'); ?>

					
			</div>
		</div>
	</section>

	
	<section id="map">
		<div class="container">
			<div class="row">
				
				<header>
					<h2 class="hide">Mappa piantumazioni</h2>
				</header>
				
				<div class="col-sm-12">
					<div class="legend-item"><img src="//static.info-alberghi.com/greenbooking/img/marker_2019.png"> Piantumazioni <b>2019</b></div>
					<div class="legend-item"><img src="//static.info-alberghi.com/greenbooking/img/marker_2018.png"> Piantumazioni <b>2018</b></div>
					<div class="legend-item"><img src="//static.info-alberghi.com/greenbooking/img/marker_2017.png"> Piantumazioni <b>2017</b></div>
					<div class="legend-item"><img src="//static.info-alberghi.com/greenbooking/img/marker_2016.png"> Piantumazioni <b>2016</b></div>
				</div>
				
			</div>
		</div>
		<!-- Inizio mappa -->
		<div id="gmap"></div>
		<!-- Fine mappa  -->
	</section>

