
<!--googleoff: index-->

@if(!strpos(Request::url(), "gdpr"))
	
	@if ( !isset($_COOKIE["eucookielaw"]) && !\Browser::isBot() )
		
		{{-- Attivo subito i cookie se non ho il blocco --}}
		
		@if (!Config::get("privacy.blocco_cookie"))
			<script> dataLayer.push({ 'event':'eucookielaw' }); </script>
		@endif
			
		<script>
				
			function removePanelCookieLaw() {
				
				window.removeEventListener("scroll", setCookieLaw);
				window.removeEventListener("touchmove", setCookieLaw, false);
				
				var eucookielaw_p = document.getElementById("eucookielaw-container");
				var eucookielaw_b = document.getElementById("eucookielaw-background");
				
				eucookielaw_p.setAttribute("style", "display:none");
				eucookielaw_b.setAttribute("style", "display:none");
				
				{{-- Attivo i cookie se ho il blocco --}}
				
				@if (Config::get("privacy.blocco_cookie"))
					dataLayer.push({ 'event':'eucookielaw' });
				@endif
				
			}
			
			function setCookieLaw() {

				var now = new Date();
				var time = now.getTime();
				var expireTime = time + 2592000000; // 30 giorni
				now.setTime(expireTime);
				document.cookie = 'eucookielaw=true;expires='+now.toGMTString()+';path=/';
				removePanelCookieLaw();
				
			}

			function setCookieLawFalse() {

				var now = new Date();
				var time = now.getTime();
				var expireTime = time + 2592000000; // 30 giorni
				now.setTime(expireTime);
				document.cookie = 'eucookielaw=false;expires='+now.toGMTString()+';path=/';
				removePanelCookieLaw();
				
			}
			
			window.addEventListener("touchmove", setCookieLaw, false);
			window.addEventListener("scroll", setCookieLaw, false);

		</script>
		
		<div id="eucookielaw-container">
			<div  class="container">
				<div class="row">
					<div id="eucookielaw-16" class="eucookielaw-banner fixedon-bottom">
						
						<div class="well">
							<div class="banner-message">
								{!! trans('labels.cl_msg') !!}
							</div>
							<p class="banner-agreement-buttons">
								<a href="#" class="agree-button btn btn-primary" onclick="setCookieLaw(); return false">{{ trans("labels.cl_ok") }}</a>
								
								@if ($locale == "it")
									@php $lingua = ""; @endphp
								@else
									@php $lingua = "/ing"; @endphp
								@endif
								
								<span class="separatore-bottoni">&nbsp|&nbsp &nbsp&nbsp</span>
								<a href="#" class="disagree-button btn-danger" onclick="setCookieLawFalse(); return false">{{ trans("labels.cl_not") }}</a> 

							  <a href="{{$lingua}}/cookie-policy-gdpr.php" target="_blank" class="disagree-button btn-danger informativa" style="float:right;" >{{ trans("labels.cl_info") }}</a>

							</p>
						</div>
					
					</div>
				</div>
			</div>
		</div>
		<div id="eucookielaw-background" onclick="setCookieLaw(); return false">></div>
		
	@else
		
		{{-- Passo dritto e attico subito i cookie perchè hogià accettato la privacy --}}
		
		@if (!\Browser::isBot())
			<script> dataLayer.push({ 'event':'eucookielaw' });	</script>
		@endif
		
	@endif
	
@endif

<!--googleon: index-->