<footer id="footer" class="container">
			
		<div class="row">
			<div class="col-xs-12">
				<div class="socials">
					
				</div><br/>
				
					<img src="{{ Utility::assetsImage('/others/logo-ia.svg', true) }}" alt="Info Alberghi srl">
					
					<p>
						Via Gambalunga, 81/A 47921 - Rimini<br />
						{{ trans('labels.footer_tel_what') }} 0541 29187<br />
  					    Email box[at]info-alberghi.com<br />
						P.IVA 03479440400<br /><br />

						<a href="/"><img src="{{Utility::assetsImage("others/blank.gif", true)}}" class="flag flag-it" alt="Italiano" /></a>
						<a href="/ing/"><img src="{{Utility::assetsImage("others/blank.gif", true)}}" class="flag flag-en" alt="English" /></a>
						<a href="/fr/"><img src="{{Utility::assetsImage("others/blank.gif", true)}}" class="flag flag-fr" alt="Français" /></a>
						<a href="/ted/"><img src="{{Utility::assetsImage("others/blank.gif", true)}}" class="flag flag-de" alt="Deutsch" /></a>
						<br />

						{{ trans('hotel.diritti') }}<br />
							
						@if ($locale == 'it')
							<a href="{{Utility::getUrlWithLang($locale, "/informativa-privacy-gdpr.php")}}" class="reverse" target="_blank">Privacy policy</a>&nbsp;&nbsp;&nbsp;
							<a href="{{Utility::getUrlWithLang($locale, "/cookie-policy-gdpr.php")}}" class="reverse" target="_blank">Cookie policy</a>&nbsp;&nbsp;&nbsp;
							<a class="reverse" href="{{Utility::getUrlWithLang($locale, "/informazioni.php")}}">Altre info</a>
						@endif
					</p>
			</div>
		</div>
	
</footer>
				
				
				
				