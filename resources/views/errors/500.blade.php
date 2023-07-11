<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>500 - {{$titolo}}</title>
    <meta name="description" content="">
    <link href="{{Utility::assets('/vendor/fontello/animation.min.css', true)}}" rel="stylesheet" type="text/css" />	
	<link href="{{Utility::assets('/vendor/fontello/fontello.min.css', true)}}" rel="stylesheet" type="text/css" />
    <link href="{{Utility::assets("desktop/css/adobe.min.css", true)}}" rel="stylesheet" type="text/css">
    <link href="{{Utility::assets("desktop/css/500.min.css", true)}}" rel="stylesheet" type="text/css">
  </head>
  
<body class="class-page-home">
	<div id="logo">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="logo">
						<a href="{{Utility::getUrlWithLang($locale, '/')}}" class="logo_link">
							<img src="{{ Utility::assetsImage('others/logo.png', true) }}" alt="Info Alberghi srl" />
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
    <main id="main-content" class="padding-bottom">
        <div class="container">
	        <div class="row">
		        <div class="alert500">
			        <header>
				        <img src="{{ Utility::assets('/icons/death.png', true) }}" style="display: inline-block; " />
				        <h1>{{$titolo}}</h1>
			        </header>
					<p>{!! $desc2 !!}</p>
			        @if(Utility::isDebuglUrl("https://" . Request::server("HTTP_HOST")))
						<div>{{preg_replace('/#(.*)/', "<span>#$1</span>" , $e)}}</div>
					@endif
		        </div>
				<div class="clearfix"></div>
	        </div>
        </div>
    </main>
    @include('composer.footer')
</body>
</html>
