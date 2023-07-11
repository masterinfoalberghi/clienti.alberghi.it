@extends('templates.admin')

@section('title')
	Moderazione bonus vacanze 2020
@endsection

@section('content')

<p>Con il "Decreto Rilancio" del 17 maggio 2020 è stata approvata la Tax credit vacanze, conosciuta come Buonus Vacanza estate 2020: un contributo alle famiglie con ISEE inferiore a 40.000 euro per trascorrere le vacanze in Italia.
Il bonus ha un valore massimo di 500 euro e si può utilizzare dal 1 luglio al 31 dicembre 2020. <a target="_blank" href="https://www.bonusvacanze.net">Leggi di più</a></p>
<h3>La tua struttura accetta il bonus vacanze ? </h3>

<button value="1" style="margin:20px 15px 0 0;" class="btn-option btn @if($hotel->bonus_vacanze_2020 == "1") btn-success @else btn-default @endif">
	<i @if($hotel->bonus_vacanze_2020 == "1")class="entypo-check"@endif></i><b>Si</b>, accetto il bonus
</button>

<button value="-1" style="margin:20px 0px 0 0;"  class="btn-option btn @if($hotel->bonus_vacanze_2020 == "-1") btn-danger @else btn-default @endif">
	<i @if($hotel->bonus_vacanze_2020 == "-1")class="entypo-check"@endif></i><b>No</b>, non accetto il bonus
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

					url: '/admin/politiche-bonus',
					type: "post",
					async: false,
					data: data_options
					
				});

			});
		});

	</script>

@endsection
