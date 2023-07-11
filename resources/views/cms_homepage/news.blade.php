@if (isset($news_homepage) && $news_homepage["items"])

	<section id="news" class="padding-bottom">
		
		<div class="container">
			<div class="row">
				<div class="col-sm-12">
					<header>
						<h2>{{trans("title.news")}}</h2>
					</header>
				</div>
			</div>
			
			<div class="row">
				
				<?php $c = 0; ?>
				
				@foreach($news_homepage["items"] as $new)
				
					@if ($new['image'])
						
						<div class="col-md-6 col-sm-12 margin-bottom-2">
							<article class="news_spot click_all" data-page-edit="{{$new["id_page"]}}">
								
									<div class="col-md-6 col-sm-4">
										<div class="margin-right">
											<figure class="pellicola">
										    	<img class="news_image animation" src="{{Utility::asset('images/spothome/300x225/' . $new['image'])}}" alt="{{$new['titolo']}}" />
											</figure>
										</div>
									</div>
									
									<div class="col-md-6 col-sm-8">
										<div class="margin-right-2">
									    <header>
										    <hgroup>
											    <small class="news_subtitle">{{str_replace("{CURRENT_YEAR}", date("Y")+Utility::fakeNewYear(), $new['sottotitolo'])}}</small>
											    <a class="news_link" href="{{Utility::getUrlWithLang($locale, '/' . $new['link'])}}"><h3 class="news_title">{{$new['titolo']}}</h3></a>
										    </hgroup>
									    </header>
									    
										<p class="page-edit">{!! $new['testo'] !!}</p>
										</div>
									</div>
								
							</article>
						</div>
					
					@else
					
						<div class="col-md-6 col-sm-12  margin-bottom-2" data-page-edit="{{$new["id_page"]}}">
							<article class="news_spot click_all">
								<div class="margin-right-2">
								    <header>
									    <hgroup>
										    <small class="news_subtitle">{{str_replace("{CURRENT_YEAR}", date("Y")+Utility::fakeNewYear(), $new['sottotitolo'])}}</small>
										    <a class="news_link" href="{{Utility::getUrlWithLang($locale, '/' . $new['link'])}}"><h3 class="news_title">{{$new['titolo']}}</h3></a>
									    </hgroup>
								    </header>
									<p class="page-edit">{!! $new['testo'] !!}</p>
								</div>
							</article>
						</div>
					
					@endif
					
					@if ($c == 1)
						<div class="clearfix"></div>
					@endif
				
					<?php $c == 1 ? $c = 0: $c++; ?>
				
				@endforeach
				
			</div>
		</div>
		
	</section>
	
@endif
