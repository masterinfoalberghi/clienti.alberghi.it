{{--
Questa PAGINA DOVRA' ESTENDERE UN ALTRO TEMPLATE (admin_home ad esempio)
che ha 2 sezioni separate per il titolo della pagine (tag <title>)
ed il contenuto prima del content !!!
tra le altreo cose tolgo <h2> dal template ma sarà chi lo estende a decidere come formattare il contenuto che viene messo !!!
 --}}



 @extends('templates.admin')

 @section('title')
     Abilita le recensioni sulla tua scheda
 @endsection
 
 @section('content')
              
    <p><b>In questa sezione pui abilitare la media delle recensioni relative alla tua struttura.</b></b>
    <p>Il punteggio viene periodicamente calcolato prendendo in considerazioni le valutazioni ottenute dalla singola struttura alberghiera sui principali siti di recensione online di promozione alberghiera e turistica.<br />Si invita sempre l'utente a verificare le informazioni più aggiornate prima di prenotare il soggiorno.</p>
    
    <h3>Vuoi pubblicare il punteggio medio della tua struttura? </h3>

    <button value="1" style="margin:20px 15px 0 0;" class="btn-option btn @if($hotel->enabled_rating_ia == "1") btn-success @else btn-default @endif">
        <i @if($hotel->enabled_rating_ia == "1")class="entypo-check"@endif></i><b>Si</b>, pubblica la media recensioni
    </button>

    <button value="0" style="margin:20px 0px 0 0;"  class="btn-option btn @if($hotel->enabled_rating_ia == "0") btn-danger @else btn-default @endif">
    <i @if($hotel->enabled_rating_ia == "-1")class="entypo-check"@endif></i><b>No</b>, non pubblicare la media recensioni
    </button>

         
 @endsection

 @section('onheadclose')

	<script type="text/javascript">

		jQuery(document).ready(function($) {

			$(".btn-option").click(function () {
				
				$(".btn-option")
					.removeClass("btn-success")
					.removeClass("btn-danger")
					.addClass("btn-default");

				$(".btn-option i")
					.removeClass("entypo-check");

				$(this)
					.removeClass("btn-default")
						.find("i")
						.addClass("entypo-check");

				if($(this).val() == "-1") {
					$(this).addClass("btn-danger");
				} else {
					$(this).addClass("btn-success");
				}

				data_options = {
                    "_token": '{{ csrf_token() }}',
					"value": $(this).val()
                }

				$.ajax({

					url: '/admin/recensioni',
					type: "post",
					async: false,
					data: data_options
					
				});

			});
		});

	</script>

@endsection