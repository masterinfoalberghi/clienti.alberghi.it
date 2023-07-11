<?php 
/**
 *
 * visualizzo dati orari dell'hotel:
 * @parameters: $cliente, $titolo, $locale
 *
 *
 *
 */

$campo_checkin = 'checkin_'.$locale;
$campo_checkout = 'checkout_'.$locale;

$campo_altra_lingua = 'altra_lingua_'.$locale;

$campo_caparra = 'caparra_altro_'.$locale;


if ($cliente->reception_24h ||
		$cliente->reception1_da != '0:0' ||
		$cliente->reception1_a != '0:0' ||
		$cliente->reception2_da != '0:0' ||
		$cliente->reception2_a != '0:0' ||

		$cliente->$campo_checkin != '' ||
		$cliente->$campo_checkout != '' ||
		
		$cliente->colazione_da != '' ||
		$cliente->colazione_a != '' ||
		$cliente->pranzo_da != '' ||
		$cliente->pranzo_a != '' ||
		$cliente->cena_da != '' ||
		$cliente->cena_a != '' ||
		
		$cliente->trattamento_ai ||
		$cliente->trattamento_pc ||
		$cliente->trattamento_mp ||
		$cliente->trattamento_mp_spiaggia ||
		$cliente->trattamento_bb ||
		$cliente->trattamento_bb_spiaggia ||
		$cliente->trattamento_sd ||
		$cliente->trattamento_sd_spiaggia ||

		//$cliente->caparra ||
		$cliente->caparra_si ||
		$cliente->caparra_no ||
		$cliente->caparra_altro_check ||
		
		$cliente->n_camere > 0  ||
		$cliente->n_posti_letto > 0 ||

		$cliente->contanti ||
		$cliente->assegno ||
		$cliente->carta_credito ||
		$cliente->bonifico ||
		$cliente->paypal ||
		$cliente->bancomat ||
		
		$cliente->inglese ||
		$cliente->francese ||
		$cliente->tedesco  ||
		$cliente->spagnolo ||
		$cliente->russo  ||
		$cliente->$campo_altra_lingua != '') 
	{
		

		$reception = "";
		$checkin = "";
		$checkout = "";
		$colazione = "";
		$pranzo = "";
		$cena = "";
		$lingue = "";
		$pagamenti = "";
		$trattamenti = "";
		$camere_e_posti = "";


		/*  RECEPTION */
		$reception = "<b>Reception:</b> ";

		if ($cliente->reception_24h) 
			{
			$reception .= "24/24 h";
			}
		else
			{
				$reception .= $cliente->writeReception();
			}

		/*CHECKIN CHECKOUT */
		if($cliente->$campo_checkin != '')
			{
			$checkin = "<b>Check in:</b> ".$cliente->$campo_checkin;					
			}

		/*CHECKIN CHECKOUT */
		if($cliente->$campo_checkout != '')
			{
			$checkout = "<b>Check out:</b> ".$cliente->$campo_checkout;					
			}

		
		/* COLAZIONE */
		if ($cliente->colazione_da != '' && $cliente->colazione_a != '') 
		
			{
			$colazione .="<b>".trans('hotel.colazione').":</b> $cliente->colazione_da  - $cliente->colazione_a";
			}
		elseif ($cliente->colazione_da == '' && $cliente->colazione_a != '') 
			{
			$colazione .="<b>".trans('hotel.colazione').":</b> $cliente->colazione_a";
			}
		elseif ($cliente->colazione_da != '' && $cliente->colazione_a == '') 
			{
			$colazione .="<b>".trans('hotel.colazione').":</b> $cliente->colazione_da";
			}

		/* PRANZO */
		if ($cliente->pranzo_da != '' && $cliente->pranzo_a != '') 
			{
			$pranzo .="<b>".trans('hotel.pranzo').":</b> $cliente->pranzo_da  - $cliente->pranzo_a";
			}
		elseif ($cliente->pranzo_da == '' && $cliente->pranzo_a != '') 
			{
			$pranzo .="<b>".trans('hotel.pranzo').":</b> $cliente->pranzo_a";
			}
		elseif ($cliente->pranzo_da != '' && $cliente->pranzo_a == '') 
			{
			$pranzo .="<b>".trans('hotel.pranzo').":</b> $cliente->pranzo_da";
			}
		
		/* CENA */
		if ($cliente->cena_da != '' && $cliente->cena_a != '') 
			{
			$cena .="<b>".trans('hotel.cena').":</b> $cliente->cena_da  - $cliente->cena_a";
			}
		elseif ($cliente->cena_da == '' && $cliente->cena_a != '') 
			{
			$cena .="<b>".trans('hotel.cena').":</b> $cliente->cena_a";
			}
		elseif ($cliente->cena_da != '' && $cliente->cena_a == '') 
			{
			$cena .="<b>".trans('hotel.cena').":</b> $cliente->cena_da";
			}

			$caparra = ""; 
			$label_caparra = false;
			
		/* CAPARRA */
		if ($cliente->caparra_si == 1) 
			{
			$caparra .= "<b>".trans('hotel.ric_caparra').":</b> " . trans('labels.si');
			$label_caparra = true;
			} 
		else if ($cliente->caparra_no == 1) 
			{
			$caparra .= "<b>".trans('hotel.ric_caparra').":</b> " . trans('labels.no');
			$label_caparra = true;
			}
		
		if ($cliente->caparra_altro_check) 
			{
			if (! $label_caparra ):
			$caparra .= "<b>".trans('hotel.ric_caparra').":</b> " . ucfirst($cliente->$campo_caparra);
			else:
			$caparra .= " -  " . ucfirst($cliente->$campo_caparra);
			endif;
			}


		/* LINGUE PARALTE */
		if ($cliente->inglese ||
				$cliente->francese ||
				$cliente->tedesco  ||
				$cliente->spagnolo ||
				$cliente->russo  ||
				$cliente->$campo_altra_lingua != ''
				) 
			{
				$lingue .= "<b>".trans('hotel.lingue_p').":</b> ";
				if ($cliente->inglese) 
					{
						$lingue .= trans('hotel.inglese') . ', ';
					}
				if ($cliente->francese) 
					{
						$lingue .= trans('hotel.francese') . ', ';
					}
				if ($cliente->tedesco) 
					{
						$lingue .= trans('hotel.tedesco') . ', ';
					}
				if ($cliente->spagnolo) 
					{
						$lingue .= trans('hotel.spagnolo') . ', ';
					}
				if ($cliente->russo) 
					{
						$lingue .= trans('hotel.russo') . ', ';
					}
				if ($cliente->$campo_altra_lingua != '') 
					{
						$lingue .= $cliente->$campo_altra_lingua . ', ';
					}

				$lingue = rtrim($lingue,', ');
			}
		 



		/* PAGAMENTI ACCETTATI */
		if ($cliente->contanti ||
				$cliente->assegno ||
				$cliente->carta_credito ||
				$cliente->bonifico ||
				$cliente->paypal ||
				$cliente->bancomat
				) 
			{
			$pagamenti .= "<b>".trans('hotel.pagamenti').":</b> ";
			if ($cliente->contanti) 
				{
					$pagamenti .= trans('hotel.contanti');
					$note = 'note_contanti_'.$locale;
					if (!empty($cliente->$note))
						{
						$pagamenti .= ' (' . $cliente->$note. ')';
						}		
					$pagamenti .= ', ';
				}
			if ($cliente->assegno) 
				{
					$pagamenti .= trans('hotel.assegno');
					$note = 'note_assegno_'.$locale;
					
					if (!empty($cliente->$note))
						{
						$pagamenti .= ' (' . $cliente->$note. ')';
						}		
					$pagamenti .= ', ';
				}
			if ($cliente->carta_credito) 
				{
					$pagamenti .= trans('hotel.carta_credito');
					$note = 'note_carta_credito_'.$locale;

					if (!empty($cliente->$note))
						{
						$pagamenti .= ' (' . $cliente->$note. ')';
						}
					$pagamenti .= ', ';
				}
			if ($cliente->bonifico) 
				{
					$pagamenti .= trans('hotel.bonifico');
					$note = 'note_bonifico_'.$locale;

					if (!empty($cliente->$note))
						{
						$pagamenti .= ' (' . $cliente->$note. ')';
						}
					$pagamenti .= ', ';
				}
			if ($cliente->paypal) 
				{
					$pagamenti .=  'PayPal';
					$note = 'note_paypal_'.$locale;

					if (!empty($cliente->$note) && $locale == 'it')
						{
						$pagamenti .= ' (' . $cliente->$note. ')';
						}
					$pagamenti .=  ', ';
				}

			if ($cliente->bancomat) 
				{
					$pagamenti .=  'Bancomat';
					$note = 'note_bancomat_'.$locale;

					if (!empty($cliente->$note) && $locale == 'it')
						{
						$pagamenti .= ' (' . $cliente->$note. ')';
						}
					$pagamenti .=  ', ';
				}
			

			$pagamenti = rtrim($pagamenti,', ');
			}



		/* TRATTAMENTI PRINCIPALI */
		if ($cliente->trattamento_ai ||
				$cliente->trattamento_pc ||
				$cliente->trattamento_mp ||
				$cliente->trattamento_mp_spiaggia ||
				$cliente->trattamento_bb ||
				$cliente->trattamento_bb_spiaggia ||
				$cliente->trattamento_sd ||
				$cliente->trattamento_sd_spiaggia
				) 
			{
				
				$trattamenti_note = [];
				foreach (Utility::getTrattamentiNomi() as $key => $value) 
					{

					if ($cliente->$key == 1) 
						{
						$colonna = str_replace('trattamento_','note_', $key).'_'.$locale;
						//dd("cliente->colonna ($colonna) = " .$cliente->$colonna);
						$trattamenti_note[trans('hotel.'.$key)] = $cliente->$colonna;
						} 
						
					}
				
				//dd($trattamenti_note);
				
				$trattamenti_arr = [];
				foreach ($trattamenti_note as $key => $value) 
					{
					if($value != '')
						{
						$trattamenti_arr[] = '<b>'.strtoupper($key).': </b> '. $value;
						}
					else 
						{
						$trattamenti_arr[] = '<b>'.strtoupper($key).'</b> ';
						}
					}
			
			}
		
		?><div class="padding-bottom">
				<h3 class='title'>{{$titolo}}</h3>
				<p class='testo'><?php

					echo $reception	 == "" ? "" : $reception . '<br>';
					echo $checkin	 ==  "" ? "" : $checkin . '&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;';
					echo $checkout	 ==  "" ? "" : $checkout .'<br>';

					echo $colazione	 == "" ? "" : $colazione;
					echo $pranzo	 == "" ? "" : '&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;' . $pranzo;
					echo $cena		 == "" ? "" : '&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;' . $cena .'&nbsp;';

					// se ho almeno una riga metto il <br />

					if ($colazione != "" || $pranzo != "" || $cena != "") echo '<br />';			
					
					//if ($caparra) echo "<br>" . $caparra .'<br>';
					echo "<br>";					

					echo $lingue 		== "" ? "" : $lingue .'<br>';
					echo $pagamenti 	== "" ? "" : $pagamenti;
					
				?></p>
				<p>
					<?php 
					   $tassaSoggiorno = App\TassaSoggiorno::getTassaLabel($cliente->id);
					   if (isset($tassaSoggiorno[0])):
						   echo $tassaSoggiorno[0] . " ";
						   unset($tassaSoggiorno[0]); 
						   echo ucfirst(strtolower(implode(", ", $tassaSoggiorno))) . "<br/>"; 
					   endif;
				    ?>
				</p>
		</div>

		<div class="padding-bottom">
			@if ( count($trattamenti_arr))
				<h3 class="title">{{strtoupper(trans('hotel.trattamenti_p'))}}</h3>
				<p class='testo'>
					{!!implode("<br />", $trattamenti_arr)!!}
				</p>
				@php
					$colonna = 'note_altro_'.$locale;
				@endphp
				@if ($cliente->$colonna != '')
					<p class="box_altri_trattamenti">
						<span class="altri_trattamenti">{{ trans('hotel.trattamenti_includono_anche') }}</span> {{$cliente->$colonna}}
					</p>
				@endif
			@endif
			<div class="clearfix"></div>
		</div><?php
		
	}
 ?>