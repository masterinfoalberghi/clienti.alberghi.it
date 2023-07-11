@if ($newsletterLinks->count())
<hr>
<hr>
<h3>Ultime newsletter</h3>
<div class="row">
  <div class="col-lg-12">	
   @foreach ($newsletterLinks as $key => $link)
		<div class="panel-group joined" id="accordion-test-{{$key}}"> 
			<div class="panel panel-default"> 
				<div class="panel-heading">  
	   			<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion-test-{{$key}}" href="#collapseOne-{{$key}}">{{$key+1}}) &nbsp;{{$link->titolo}}</a></h4>
	   		</div>
   		  <div id="collapseOne-{{$key}}" class="panel-collapse collapse @if($key == 0) in @endif">
   		  	<div class="panel-body">
   		  	<ul class="newsletter_list">
   		  		<li style="padding: 5px 0px;"><i class="entypo-calendar"></i>Pubblicata: {{ $link->data_invio->formatLocalized("%d/%m/%y") }}</li>
   		  		<li><i class="entypo-link"></i>&nbsp;<a href="{{url($link->url)}}" target="_blank">Leggi la newsletter</a></li>
   		  		<br>
   		  		<li>{!!nl2br($link->note)!!}</li>
   		  	</ul>
   		  	</div>
   		  </div>
  		</div>
  	</div>
   	@endforeach
</div>
</div>
@endif
