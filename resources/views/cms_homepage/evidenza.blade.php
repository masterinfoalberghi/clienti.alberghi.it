@php 
	$t = 0;
	$class= "";
	$margin = "";
@endphp 

@if (count($template_homepage["items"]))
	
	@if (count($template_homepage["items"]) == 1)
		
		@php $class="col-sm-12";  $hide = ""; @endphp
		
	@elseif (count($template_homepage["items"]) == 2)
			
		@php $class="col-sm-6";  $hide = ""; @endphp
		
	@elseif (count($template_homepage["items"]) == 3)
			
		@php $class="col-sm-4"; $hide = "display:none;"; @endphp
	
	@elseif (count($template_homepage["items"]) > 3)
			
		@php $class="col-sm-6"; $hide = "display:none;"; @endphp

	@endif	
	
	<section id="in_evidenza" >
			
		<header>
			<h2>{{trans("title.evidenza")}}</h2>
		</header>
		
		@foreach($template_homepage["items"] as $th)
			
			@if ($t == 0 && count($template_homepage["items"]) == 1)
				@php $margin = ""; @endphp
			@elseif ($t == 0 && count($template_homepage["items"]) == 2)
				@php $margin = "margin-right-6"; @endphp 
			@elseif ($t == 1 && count($template_homepage["items"]) == 2)
				@php $margin = "margin-left-6"; @endphp 
			@elseif ($t == 0 && count($template_homepage["items"]) == 3)
				@php $margin = "margin-right-6"; @endphp 
			@elseif ($t == 1 && count($template_homepage["items"]) == 3)
				@php $margin = "margin-left-6 margin-right-6"; @endphp 
			@elseif ($t == 2 && count($template_homepage["items"]) == 3)
				@php $margin = "margin-left-6"; @endphp 
			@elseif (($t == 0 || $t == 2) && count($template_homepage["items"]) > 3)
				@php $margin = "margin-right-6"; @endphp 
			@elseif (($t == 1 || $t == 3) && count($template_homepage["items"]) > 3)
				@php $margin = "margin-left-6"; @endphp 
			@endif
			
			<div class="{{$class}}">
				<article class="in_evidenza slick-item click_all {{$margin}} margin-bottom-6 margin-top-6  " data-page-edit="{{$th["id_page"]}}" style="background-image: url('{{Utility::asset("images/pagine/775x225/" . $th["image"])}}')">
					
					<div class="in_evidenza_content padding-2 animation ">
						
						<div class="pellicola ">
							
							
							<div class="in_evidenza_text">
								<header class="in_evidenza_header">
									<h3 class="in_evidenza_title">{{$th["titolo"]}}</h3>
									<small class="in_evidenza_subtitle padding-bottom-6">{{$th["sottotitolo"]}}</small>
								</header>
								
								@if($th["testo"])
									<p class="page-edit" style="{{$hide}}">{!! $th["testo"] !!}</p>
								@endif
								
							</div>
							<a href="{{Utility::getUrlWithLang($locale, $th["link"])}}" class="offer_button"><b>{{trans("labels.scopri_offerta")}}</b> <i class="icon-right"></i></a>
						</div>
						
					</div>
					
				</article>
			</div>
			
			@php $t++; @endphp
		@endforeach
		<div class="clearfix"></div>
	</section>
	
@endif


