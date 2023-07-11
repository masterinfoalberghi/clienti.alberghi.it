<?php 
if($cliente->chiuso_temp):
 
	echo '<span class="chiusoTemp">' . trans('labels.chiusura_temporanea') . '</span>';

else: ?>

<div  class="infodati_img"><img src="{{Utility::asset('/mobile/img/orario.svg')}}"></div>
<div class="infodati">
<?php 
/**
 *
 * visualizzo dati apertuta dell'hotel:
 * @parameters: $cliente, $titolo, $locale, $pathDeviceType (mi dice se sono nella vista del mobile oppure no)
 *
 *
 *
 */

		if ($cliente->annuale) 
			{
			echo trans('listing.annuale');
			} 
		else 
			{
			if ($cliente->aperto_dal->toDateString() !=  '-0001-11-30') 
				{
				echo trans('labels.apertura').": <strong>". Utility::myFormatLocalized($cliente->aperto_dal, '%d %B', $locale) . '</strong><br />';
				}

			if ($cliente->aperto_al->toDateString() !=  '-0001-11-30') 
				{
				echo trans('labels.chiusura').": <strong>". Utility::myFormatLocalized($cliente->aperto_al, '%d %B', $locale) . "</strong><br />";
				}
			}
			
		if ($cliente->aperto_eventi_e_fiere || $cliente->aperto_pasqua || $cliente->aperto_capodanno || $cliente->aperto_25_aprile || $cliente->aperto_1_maggio || $cliente->aperto_altro_check) 
			{
			$msg = "<br />";
			$field = 'aperto_altro_'.$locale;
			if ($cliente->aperto_eventi_e_fiere) 
				{
				$msg .= trans('hotel.aperto_eventi_e_fiere') . ', ';
				}
			if ($cliente->aperto_pasqua) 
				{
				$msg .= trans('hotel.aperto_pasqua') . ', ';
				}
			if ($cliente->aperto_capodanno) 
				{
				$msg .= trans('hotel.aperto_capodanno') . ', ';
				}
			if ($cliente->aperto_25_aprile) 
				{
				$msg .= trans('hotel.aperto_25_aprile') . ', ';
				}
			if ($cliente->aperto_1_maggio) 
				{
				$msg .= trans('hotel.aperto_1_maggio') . ', ';
				}
			if ($cliente->aperto_altro_check) 
				{
				$msg .= $cliente->$field . ', ';
				}
			
			$msg = rtrim($msg,', ');
			
			if (!empty($msg)) 
				{
				echo  "<br /><small>" . trans('labels.dispo').":</small>".$msg;
				}
			
			}
		elseif ($cliente->annuale) 
			{
			echo "<strong>".trans('listing.annuale')."</strong>";
			}
	?>
</div>
<?php endif; ?>