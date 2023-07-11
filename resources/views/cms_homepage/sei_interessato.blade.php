
	<section id="interesato" class="padding-bottom">
		
		<div class="container">
			<div class="row">
				<div class="col-sm-12">
					<header>
						<h2>{{trans("title.interessato")}}</h2>
					</header>
				</div>
			</div>
			
			<div class="row">

				<article>
					<header>
					    <hgroup>
						    <small class="offer_subtitle">{{str_replace("{CURRENT_YEAR}", date("Y")+Utility::fakeNewYear(), $offer['sottotitolo'])}}</small>
						    <a class="offer_link" href="{{Utility::getUrlWithLang($locale, '/' . $offer['link'])}}"><h3 class="offer_title">{{$offer['titolo']}}</h3></a>
					    </hgroup>
				    </header>
				</article>
				
			</div>
		</div>
		
	</section>
