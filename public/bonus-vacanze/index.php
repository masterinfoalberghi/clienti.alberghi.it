<?php 

//$path = "../../static";
$path = "";

?>
<!DOCTYPE html>
<html lang="it">

<!-- HEAD START -->
<?php 

	$title="Bonus Vacanze Estate 2020 - Fino a 500€";
	
	$metadescription="Cos'è il bonus vacanze, come si richiede? Scopri tutto sullo sconto per le vacanze nelle strutture che lo accettano in Italia. (Aggiornato 07/07/2020).";

require('./layout-parts/include-head.php'); 
?>
<!-- HEAD STOP -->
<body>

	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WRM3P9"
	height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->
	
	<div id="brand-bambini" >
		<div class="container">
			<div class="row">
				<div class="logo">
		    		<a href="/" title="Info Alberghi srl">
						<img src="//static.info-alberghi.com/images/logo.png" alt="Info Alberghi srl">
					</a>
				</div>
			</div>
		</div>
	</div>
	
	<header id="hero-bambini">
		<div class="container">
			<div class="row">
				
				<div class="col-12">
					<?php include('./layout-parts/section-intro.php'); ?>
				</div>
				
				<!--<div class="col-lg-7 col-md-12">
					<iframe id="video-preentazione" width="550" height="320" src="//www.youtube.com/embed/WH29lrOQ4C0?rel=0" frameborder="0" allowfullscreen></iframe>
				</div>	
				!-->
				
			</div>		
		</div>
		
		<button id="menu-button"><i class="icon-menu"></i></button>
		<nav id="menu-principale">
			
			<header>
				<h4 class="hide">Menu di Navigazione</h4>
			</header>
			
			<ul>

				<li><a href="#about">Cosa c'è da sapere</a></li>
				<li><a href="#app">Richiedi il bonus</a></li>
				<li><a href="#hotel">Strutture ricettive</a></li>
				<li><a href="#faq">FAQ</a></li>
				<!-- <li><a href="#form-domande">Fai una domanda</a></li> -->
				<li><a href="https://www.info-alberghi.com/covid-19/">Informazioni Covid-19</a></li>
				<li><a href="https://www.info-alberghi.com/bonus-vacanze-covid19/riviera-romagnola.php">Hotel per Bonus Vacanze</a></li>

			</ul>
		</nav>
		
	</header>

<!-- START About -->
	<a name="about"></a>
	<?php include('./layout-parts/sections/section-about.php'); ?>
<!-- STOP About -->


<!-- START come richiederlo -->

			<div class="container">
			<div class="row">
				<div class="col-md-10" style="margin: 0 auto; float: none;">

					<div
						class="canva-embed"
						data-design-id="DAED7baoot0"
						data-height-ratio="2.5000"
						style="padding:250.0000% 5px 5px 5px;background:rgba(0,0,0,0.03);border-radius:8px;"
					></div>
					<script async src="https:&#x2F;&#x2F;sdk.canva.com&#x2F;v1&#x2F;embed.js"></script>

				</div>
			</div>
		</div>

<!-- STOP come richiederlo -->


<!-- START App -->
	<?php include('./layout-parts/sections/section-app.php'); ?>
<!-- STOP App -->

<!-- START – Section HOTEL -->
	<a name="hotel"></a>
	<?php include('./layout-parts/sections/section-hotel.php'); ?>
<!-- START – Stop HOTEL -->


<!-- START – Section FAQ -->
	<?php include('./layout-parts/sections/section-faq.html'); ?>
<!-- START – Stop FAQ -->



<!-- START - Section FORM DOMANDE -->
	<a name="form-domande"></a>
	 <?php include('./layout-parts/sections/section-form.php'); ?> 
<!--  STOP - Section FORM DOMANDE  -->




	<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
	<script type="text/javascript" src="//static.info-alberghi.com/vendor/slick/slick.min.js"></script>
	<script type="text/javascript" src="//static.info-alberghi.com/greenbooking/js/main.min.js"></script>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAnlpqR8hcNk5SKFDWoSTfknax39kU39ko&callback=window.initMap" async defer></script>
	
<!-- FOOTER E COOKIE POLICY -->

	<?php include('./layout-parts/footer.php'); ?>

<!--  End Footer Section  -->

</html>
