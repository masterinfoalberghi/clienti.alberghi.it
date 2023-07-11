
	{{--  scheda hotel --}}
  <style>

		#covid {
			background-color: #FF9326;
			border: 0px solid #f24a46;
			margin-top:50px;
			margin-bottom: -2px;
			display: flex;
		}

		#covid .icona {
			color: #fff;
			padding-top: 5px; 
		}
		
		#covid #alert-description {
			color: #fff;
			padding: 5px; 
			line-height: 20px;
		}

		#covid #alert-description span#alert {
			padding: 1px 3px;
			background-color: #f24a46;
			color: #fff;
			margin-right: 10px;
		}

		#covid #alert-description a {
			color: #fff;
			text-decoration: underline;
		}

		#covid #alert-description a:hover {
			color: #1A5C87;
			text-decoration: none;
		}
		
  </style>

<div id="covid">
	{{-- <i class="icona icon-info-circled-1"><span style="display:none;">Info</span></i> --}}
	<div id="alert-description">
		<span id="alert">COVID-19</span>
		<a href="{{asset('note/covid-19')}}" target="_blank" class="alert-link">Scopri cosa c’è da sapere per chi viaggia in Riviera Romagnola.</a>
	</div>
</div>


