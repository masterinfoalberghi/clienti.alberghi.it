
<script type="text/javascript" src="//static.info-alberghi.com/vendor/jquery/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
<script type="text/javascript" src="//static.info-alberghi.com/vendor/slick/slick.min.js"></script>
<script type="text/javascript" src="//static.info-alberghi.com/greenbooking/js/main.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAnlpqR8hcNk5SKFDWoSTfknax39kU39ko&callback=window.initMap" async defer></script>

<footer id="footer">
	<div class="container">
		<div class="row">	
				<div class="col-sm-12" style="text-align:center;" >	
				
				<p>Questo progetto è un idea di <a href="https://www.info-alberghi.com" target="_blank">Info Alberghi srl</a><br />Tutti i diritti riservati © <?php echo date("Y"); ?></p>
				
				<p>
					Via Gambalunga, 81/A 47921 - Rimini&nbsp;&nbsp;&nbsp;P.IVA 03479440400<br>
					
					<a href="/informativa-privacy-gdpr.php" class="reverse" target="_blank">Privacy policy</a>&nbsp;-&nbsp;<a href="/cookie-policy-gdpr.php" class="reverse" target="_blank">Cookie policy</a><br/><br />
					
					<a target="_blank" rel="noopener" href="//www.facebook.com/infoalberghi">facebook</a>&nbsp;&nbsp;&nbsp;
					<a target="_blank" rel="noopener" href="//twitter.com/infoalberghi">twitter</a>&nbsp;&nbsp;&nbsp;
					
				</p>
			</div>	
			<div class="clearfix"></div>
		</div>
	</div>
</footer>


	<?php if ( !isset($_COOKIE["eucookielaw"]) ): ?>
	
	<script>
		window.addEventListener("scroll", setCookieLaw);
		
		function removePanelCookieLaw() {
			
			window.removeEventListener("scroll", setCookieLaw);
			var eucookielaw_p = document.getElementById("eucookielaw-container");
			eucookielaw_p.setAttribute("style", "display:none");
			dataLayer.push({ 'event':'eucookielaw' });
			
		}
		
		function setCookieLaw() {
		
			var now = new Date();
			var time = now.getTime();
			var expireTime = time + 2592000000; // 30 giorni
			now.setTime(expireTime);
			document.cookie = 'eucookielaw=true;expires='+now.toGMTString()+';path=/';
			removePanelCookieLaw();
			
		}
	</script>
	<div id="eucookielaw-container">
		<div  class="container">
			<div class="row">
				<div id="eucookielaw-16" class="eucookielaw-banner fixedon-bottom">

						<div class="banner-message">
							<img src="//static.info-alberghi.com/images/logo-small.png"><br><br>				
							Info Alberghi utilizza i cookie per personalizzare i contenuti e gli annunci, fornire le funzioni dei social media e analizzare il traffico.<br />
							Inoltre fornisce informazioni sul modo in cui il navigatore utilizza il sito ai nostri partner che si occupano di analisi dei dati web, pubblicità e social media.<br />
							Cliccando su "Accetto" o "proseguendo la navigazione" scorrendo la pagina acconsenti ad usare i cookie su questo sito.<br/>
						</div>
						<p class="banner-agreement-buttons">
							<a href="#" class="agree-button btn btn-green" onclick="setCookieLaw(); return false">Accetto</a>
							<a href="/cookie-policy-gdpr.php" target="_blank" class="disagree-button btn-danger">Leggi l'informativa completa</a>
						</p>
					</div>
				
				</div>
			</div>
		</div>
	</div>
	
	<?php else: ?>
		
		<script> dataLayer.push({ 'event':'eucookielaw' }); </script>
		
	<?php endif; ?>
	

