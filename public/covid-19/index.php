<?php 

//$path = "../../static";
$path = "";

?>
<!DOCTYPE html>
<html lang="it">

<!-- HEAD START -->
<?php 

	$title="Covid-19. Estate 2020 in Riviera Romagnola - InfoAlberghi";
	
	$metadescription="Rimani aggiornato sulla situazione coronavirus e la possibilità di viaggiare in Riviera Romagnola, le aperture hotel e le misure di sicurezza in atto sul territorio della Regione Emilia Romagna.";

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
						<!-- <?php include('./layout-parts/asset-logo.php'); ?>!-->
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
				<li><a href="#about">Da sapere</a></li>
				<li><a href="#bonus">Bonus Vacanze</a></li>  
				<li><a href="#bambini">Bambini</a></li> 
				<li><a href="#faq">Faq</a></li>
				<!--<li><a href="#form-domande">Domande</a></li>-->
				<!-- <li><a href="#storie">Voci dal territorio</a></li> -->
				<!-- <li><a href="#misure">Misure in vigore</a></li> -->
				<li><a href="#lineeguida">Linee guida Hotel</a></li>
				<li><a href="#spiaggia">Spiaggia</a></li>
				<li><a href="#eventi">Eventi</a></li>
				<li><a href="#pratiche">Prevenzione</a></li>
				<li><a href="#rassegna-stampa">Notizie</a></li>
		
				<!-- <li><a href="#solidarieta">Solidarietà</a></li> -->
				

			</ul>
		</nav>
		
	</header>

<!-- START About -->

		<?php include('./layout-parts/section-about.php'); ?>

<!-- STOP About -->

<!-- START - Section Bonus -->
	<a name="bonus"></a>
	 <?php include('./layout-parts/section-bonus.php'); ?> 
<!--  STOP - Section Bonus  -->

<!-- START - Section Hotel 
	<a name="hotel"></a>
	 <?php // include('./layout-parts/section-hotel.php'); ?> 
  STOP - Section Hotel  -->

<!-- START - Section Bambini -->
	<a name="bambini"></a>
	 <?php include('./layout-parts/section-bambini.php'); ?> 
<!--  STOP - Section Bambini  -->

<!-- START – Section FAQ -->
	<?php include('./layout-parts/faq.html'); ?>
<!-- START – Stop FAQ -->

<!-- START - Section FORM DOMANDE -->
	<a name="form-domande"></a>
	 <?php include('./layout-parts/section-form.php'); ?> 
<!--  STOP - Section FORM DOMANDE  -->

<!-- START – Section Storie -->
		 <!--<a name="storie"></a> -->
		<?//php include('./layout-parts/section-storia.php'); ?> 
<!--  STOP - Section Storie  -->

<!-- START Misure e decreti 
		<a name="misure"></a>
		<?php //include('./layout-parts/section-misure.php'); ?>

 STOP Misure  -->

<!-- START Linee Guida -->
		<a name="lineeguida"></a>
		<?php include('./layout-parts/section-lineeguida.php'); ?>

<!-- STOP Linee Guida -->

<!-- START Linee Guida -->
		<a name="spiagge"></a>
		<?php include('./layout-parts/section-spiaggia.php'); ?>

<!-- STOP Linee Guida -->

<!-- START Linee Guida -->
		<a name="eventi"></a>
		<?php include('./layout-parts/section-eventi.php'); ?>

<!-- STOP Linee Guida -->


<!-- START Prevenzione Pratiche -->
		<a name="pratiche"></a>
		<?php include('./layout-parts/section-pratiche.php'); ?>

<!-- STOP Prevenzione Pratiche -->


<!-- START Rassegna Stampa -->

		<a name="rassegna"></a>
		<?php include('./layout-parts/section-rassegna.php'); ?>

<!-- STOP Rassegna Stampa -->


<!-- START - Section Solidarietà -->
	
	<!--<a name="solidarieta"></a>-->
	 <?//php include('./layout-parts/section-solid.php'); ?> 


<!--  STOP - Section Solidarietà  -->





	<script type="text/javascript" src="//static.info-alberghi.com/vendor/jquery/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
	<script type="text/javascript" src="//static.info-alberghi.com/vendor/slick/slick.min.js"></script>
	<script type="text/javascript" src="//static.info-alberghi.com/greenbooking/js/main.min.js"></script>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAnlpqR8hcNk5SKFDWoSTfknax39kU39ko&callback=window.initMap" async defer></script>
	
<!-- FOOTER E COOKIE POLICY -->

	<?php include('./layout-parts/footer.php'); ?>

<!--  End Footer Section  -->

</html>
