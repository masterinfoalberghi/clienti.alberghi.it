<?php 

//$path = "../../static";
$path = "";

?>
<!DOCTYPE html>
<html lang="it">

<!-- HEAD START -->
<?php 

	$title="Blu Booking - InfoAlberghi";
	
	$metadescription="Blu Booking è un progetto di Info Alberghi srl per ripopolare e creare nuove aree verdi con il contributo degli hotel della Riviera Romagnola.";

require('./layout-parts/include-head.php'); 
?>
<!-- HEAD STOP -->
<body>
	
	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WRM3P9"
	height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->
	
	<div id="brand" >
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
	
	<header id="hero">
		<div class="container">
			<div class="row">
				
				<div class="col-12">
					
					<figure>
						<?php include('./layout-parts/asset-logo.php'); ?>
					</figure>
						<?php include('./layout-parts/titolo-intro.php'); ?>
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
				<li><a href="#about">Il progetto</a></li>
				<li><a href="#counter">L'inquinamento</a></li>
				<li><a href="#mostra">La mostra</a></li>
				<li><a href="#galleria-fotografica">Galleria</a></li>
				<li><a href="#app_features">Buone pratiche</a></li>
				<li><a href="#rassegna-stampa">Rassegna Stampa</a></li>
				<li><a href="#buonepratiche">I numeri</a></li>
				<li><a href="#storia-stampa">Adozioni</a></li>
		    <li><a href="#faq">Faq</a></li>
		<!--		<li><a href="//romagna.info-alberghi.com/greenbooking/rassegna-stampa" target="_blank">Rassegna stampa</a></li> -->
		<!--		<li><a href="/hotel-green-booking.php" target="_blank" class="reverse">Scopri gli hotel</a></li> -->
			</ul>
		</nav>
		
	</header>

<!-- START About -->

		<?php include('./layout-parts/section-about.php'); ?>

<!-- STOP About -->

<!-- START I numeri -->

		<?php include('./layout-parts/section-inumeri.php'); ?>

<!-- STOP I numeri -->

<!-- START Mostra -->

		<?php include('./layout-parts/section-mostra.php'); ?>

<!-- STOP About -->

<!-- START Gallery -->
		<a name="galleria-fotografica"></a>
		<?php include('./layout-parts/section-gallery.php'); ?>

<!-- STOP Gallery -->

<!-- START Features -->

		<?php include('./layout-parts/section-features.php'); ?>

<!-- STOP Features -->

<!-- START Testimonials -->

	 <?php // include('./layout-parts/section-testimonials.php'); ?> 

<!-- STOP Testimonials -->

<!-- START Rassegna Stampa -->

		<a name="rassegna"></a>
		<?php include('./layout-parts/section-rassegna.php'); ?>

<!-- STOP Rassegna Stampa -->

<!-- START Map -->

	<?php // include('./layout-parts/section-map.php'); ?>

<!-- STOP Map -->
<!-- START Secondo Contatore -->
		<a name="buonepratiche"></a>
		<?php include('./layout-parts/section-secondo-contatore.php'); ?>

<!-- STOP Secondo Contatore -->

<!-- START – Section Storia Tartarughe -->

		<?php include('./layout-parts/section-storia.php'); ?>

<!--  STOP - Section Storia Tartarughe  -->

<!-- START – Section FAQ -->

	<?php include('./layout-parts/faq.html'); ?>
	
<!-- START - Section Patrocinio -->

	<?php // include('./layout-parts/section-patrocinio.php'); ?>

<!--  STOP - Section Patrocinio  -->

	<script type="text/javascript" src="//static.info-alberghi.com/vendor/jquery/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
	<script type="text/javascript" src="//static.info-alberghi.com/vendor/slick/slick.min.js"></script>
	<script type="text/javascript" src="//static.info-alberghi.com/greenbooking/js/main.min.js"></script>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAnlpqR8hcNk5SKFDWoSTfknax39kU39ko&callback=window.initMap" async defer></script>
	
<!-- FOOTER E COOKIE POLICY -->

	<?php include('./layout-parts/footer.php'); ?>

<!--  End Footer Section  -->

</html>
