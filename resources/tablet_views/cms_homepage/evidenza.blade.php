@if (count($template_homepage["items"]))

	<section id="in_evidenza" class="col-md-4 col-sm-12">
		<header>
			<h2 class="margin-left-6">{{trans("title.evidenza")}}</h2>
		</header>
		@foreach($template_homepage["items"] as $th)
			<article class="in_evidenza slick-item click_all " data-page-edit="{{$th["id_page"]}}" style=" margin:3px 3px 6px; background-image: url('{{Utility::asset("images/pagine/775x225/" . $th["image"])}}')">
				<div class="in_evidenza_content padding-2 animation">
					<div class="pellicola ">
						<div class="in_evidenza_text">
							<header class="in_evidenza_header">
								<h3 class="in_evidenza_title">{{$th["titolo"]}}</h3>
								<small class="in_evidenza_subtitle padding-bottom-6">{{$th["sottotitolo"]}}</small>
							</header>
							<p class="page-edit">{!! $th["testo"] !!}</p>
							<a href="{{Utility::getUrlWithLang($locale, $th["link"])}}" class="offer_button"><b>{{trans("labels.scopri_offerta")}}</b> <i class="icon-right"></i></a>
						</div>
					</div>
				</div>
			</article>
		@endforeach
    </section>
    <div 
	
@endif
 

