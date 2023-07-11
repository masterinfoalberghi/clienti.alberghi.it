<div class="row">
	
@if ($statisticheMese )

	<div class="col-lg-6 col-sm-12 col-xs-12"> 
		<div class="dashboard tile-stats tile-orange"> 
			<div class="icon"><i class="entypo-chart-bar"></i></div> 
			<h3>Statistiche ultimi 30 giorni:</h3> <br />
			<div class="row">
				<div class="col-lg-4 col-sm-4 col-xs-12"><p  style="font-size:16px">Visite alla scheda</p><div class="num" style="font-size:38px">{{$statisticheMese["visite"]}}</div> </div>
				<div class="col-lg-4 col-sm-4 col-xs-12"><p style="font-size:16px">Email</p><div class="num" style="font-size:38px">{{$statisticheMese["email"]}}</div></div> 
				<div class="col-lg-4 col-sm-4 col-xs-12"><p style="font-size:16px">Media Email/visite</p><div class="num" style="font-size:38px">{{$statisticheMese["media"]}}</div></div> 
			</div>
		</div> 
	</div>  

@endif



<div class="col-lg-6 col-sm-6 col-xs-12"> 
	<div class="dashboard tile-stats tile-aqua"> 
		<div class="icon"><i class="entypo-mail"></i></div> 
		
		@if (count(explode(',', $hotel->email)) > 1)
			<h3>Le mail a cui ricevi i preventivi sono:</h3> 
			<div class="num" style="font-size:16px !important">
				{!! implode('<br>', explode(',', $hotel->email) ) !!}
			</div> 
		@else
			<h3>La mail a cui ricevi i preventivi è:</h3> 
			<div style="font-size:16px !important">
				{!!$hotel->email!!}
			</div>
		@endif
		</div> 
</div>  

<div class="col-lg-6 col-sm-6 col-xs-12"> 
	<div class="dashboard tile-stats tile-green"> 
		<div class="icon"><i class="entypo-phone"></i></div> 
		<h3>Il numero di telefono utilizzato nel portale per le chiamata alla struttura <br> (e di cui ricevi le statistiche) è:</h3> 
			<div class="num">
				{{$hotel->telefoni_mobile_call}}
			</div> 
		</div> 
</div>  
 
</div>

