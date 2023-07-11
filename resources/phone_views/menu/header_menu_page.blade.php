<div class="fixed">
	<a class="backbutton" href="javascript:history.go(-1)"><img src="{{ Utility::assetsImage('/others/back.svg') }}"></a>
    <div id="tab-bar">
    	<a href="{{Utility::getUrlWithLang($locale, '/')}}" class="logomobile">
    		<img src="{{ Utility::assetsImage('/mobile/img/logo-ia.svg') }}" alt="Info Alberghi srl">
    	</a>
    </div>
    <a class="menubutton" href="#"><img src="{{ Utility::assetsImage('others/menu.png') }}"></a>
</div>

<nav id="menu-mobile">
	<div class="menu-mobile">
		<div class="menu-mobile-inside">
			
			<h1 class="hidden" >{{trans("title.navigazione")}}</h1>
			
			<div class="breadcrumb">
			
				<span>@if (isset($selezione_localita) && $selezione_localita != "" ) <img src="{{Utility::asset('/mobile/img/loc-listing.svg')}}"   />{{$selezione_localita}} @endif</span>
				<a href="#" id="cambialocalita" data-txt="{!!trans("labels.cambia_localita")!!}" class="small button cyan">{!!trans("labels.cambia_localita")!!}</a>
				<div class="clear"></div>
				
			</div>
			
			<div class="menu-localita">
				
				{!! Utility::getMenuLocalitaMobileHotel() !!}
				<div class="clear"></div>
							
			</div>
				
					    	
		   @include('menu.menu_tematico_riviera_romagnola',array('locale' => $locale))

		   <div class="clear"></div>
		    
		   
		</div>
	</div>
</nav>


