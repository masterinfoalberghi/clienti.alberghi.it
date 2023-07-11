<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>500 - <?php  echo print_r($titolo,1); ?></title>
    <meta name="description" content="">
    
	@include('header')
    
    <style>
    	header { height:auto !important; }section#contenuto { width:100% !important; text-align: center; padding:50px 0; }.link_localita { text-decoration: none !important; }
    	.alert-info p{margin-top: 5px; }.alert-info p:nth-child(odd) { background:#f5f5f5; padding:10px; }.alert-info p:nth-child(even) { background:#ddd; padding:10px; }
    </style>
    
  </head>
<body>
  
  <div id="main-content">
  
	<header>
		<div class="container">
			<div id="logo" style="text-align:center">
				<a href="{{url('/')}}"><img src="{{ Utility::asset('images/logo.png') }}" class="logo" alt="Info Alberghi srl" /></a>
			</div>
		</div>
	</header>
    
  <div class="clearfix"></div>

    <div class="container">
    
        
    <section id="contenuto">
    
   			<img src="/images/500.png" style="width:50px;height:50px;"/>
         	
         	<h1 style="color:#666;"><?php  echo print_r($titolo,1); ?></h1>

           <div class="clearfix"></div>
            
           <div class="panel-body" id="body-hotel">
           
              <div class="content">
                <div class="title">
           </div>
           </div>
           	
			<?php if( Utility::isInternallUrl("https://" . Request::server("HTTP_HOST"))): ?>
				<div align="center">
					<div class="alert-info" style=" padding:50px; text-align:left;">
						<?php echo preg_replace('/#(.*)/', "<p>#$1</p>" , $e); ?>
					</div>
				</div>
			<?php endif; ?>
			
            <p align="center" class="link_interno">
              	<br>
              	<a href="{{url('/')}}"><span class="link_interno"><?php  echo print_r($desc2,1); ?></span></a>
                <br><br>
            </p>
            <p align="center">
            	<?php  echo print_r($desc3,1); ?>
            </p>
		<div class="listaloc">
			<ul>
            	<li><a class="link_localita" href="{{url('/')}}/rimini.php">rimini</a></li>
                <li><a class="link_localita" href="{{url('/')}}/riccione.php">riccione</a></li>
                <li><a class="link_localita" href="{{url('/')}}/cesenatico.php">cesenatico</a></li>
                <li><a class="link_localita" href="{{url('/')}}/cattolica.php">cattolica</a></li>
                <li><a class="link_localita" href="{{url('/')}}/milano-marittima.php">milano marittima</a></li>
                <li><a class="link_localita" href="{{url('/')}}/bellaria.php">bellaria</a></li>
                <li><a class="link_localita" href="{{url('/')}}/hotel/igea-marina.php">igea marina</a></li>
                <li><a class="link_localita" href="{{url('/')}}/cervia.php">cervia</a></li>
                <li><a class="link_localita" href="{{url('/')}}/misano-adriatico.php">misano adriatico</a></li>
                <li><a class="link_localita" href="{{url('/')}}/lidi-ravennati.php">lidi ravennati</a></li>
                <li><a class="link_localita" href="{{url('/')}}/gabicce-mare.php">gabicce mare</a></li>
                <li><a class="link_localita" href="{{url('/')}}/italia/hotel_riviera_romagnola.html">riviera romagnola</a></li>
			</ul>
        </div>
            
           </div> {{-- panel-body  --}}
           <div class="clearfix"></div>

         	
	</section>
        
	</div>{{-- /.container --}}
    
	<div class="clear"></div>
	</div>
	
	<footer>
	
		<div class="copyright">
			<div class="container">
				<p id="copy">
				
					<a href="/" title="Italiano"><img src="/images/it.png" alt="italiano" 		width="34" height="23" /></a> 
					<a href="/ing" title="English"><img src="/images/uk.png" alt="english" 		width="34" height="23"/></a> 
					<a href="/fr" title="Francaise"><img alt="Francaise" src="/images/fr.png" 	width="34" height="23" /></a>
					<a href="/ted" title="Deutsch"><img src="/images/de.png" alt="Deutsch" 		width="34" height="23" /></a>
					<br /> <br />
					{{$diritti}} | <a href="{{$link}}" target="_blank">{{$privacy}}</a>
				</p>
				
				<div class="clear"></div>
			
			</div>
		</div>
		
	</footer>
	
	<link href="{{ Utility::asset('/css/style.min.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ Utility::asset('/css/css-above/above-generale.min.css') }}" rel="stylesheet" />
	
	@include('footer')
	

  </body>
</html>