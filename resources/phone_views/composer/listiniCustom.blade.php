<?php

// visualizzo i listini variabili
// @parameters : $listiniVariabili (array di array con i listini ), titolo , locale


?>

@if ($listini->count())
	@foreach ($listini as $listiniCustom)
		@foreach ($listiniCustom->listiniCustomLingua as $listino)
			
			<div class="row listini_custom" id="{{"listino-custom-".$listino->id}}">
				<div class="col-xs-12 titolo_listino">
					<h2>{{$listino->titolo}}</h2>
					<p>{!! $listino->sottotitolo !!}</p>
				</div>
			</div>
			
			<div class="row listini_custom">
			
					
					<?php
					if ($listino->tabella):	
					$html = str_get_html($listino->tabella); 
					
					$tds = $html->find("tr");
					
					if ( count($tds) > 2) {
					
						// Sono in conformazione normale
						
						$t = 0;
						$a = array();
												
						foreach($tds as $rows):
							
							$td = $rows->find("td");					
							
							if (!$td )
								$td = $rows->find("th");
													
							if ($t == 0 ):
								
								$tt = 0;
								foreach($td as $row):
									
									if ($tt > 0)
										$a[$tt] = $row->innertext;
									else 
										$a[$tt] = "";
									$tt++;
									
								endforeach;
								
							else:
								
								?><div class="col-xs-12 listino_items"><?php
								
								$tt = 0;
								foreach($td as $row):
									
									if ($tt > 0):
										?><div class="listino_item"><span><?php echo $a[$tt]; ?></span><span class="price"><?php echo $row->innertext; ?></span><div class="clear"></div></div><?php 
									else:
									
										$pattern = "/([0-9]+)\/([0-9]+)\/([0-9]+) - ([0-9]+)\/([0-9]+)\/([0-9]+)/";
										preg_match($pattern,  $row->innertext, $matches);
										
										if (count($matches)):
										
											$dal = \Carbon\Carbon::create($matches[3],$matches[2],$matches[1]);
											$al =  \Carbon\Carbon::create($matches[6],$matches[5],$matches[4]);
											$custom = Utility::getLocalDate($dal, '%d %B')." / " . Utility::getLocalDate($al, '%d %B');
											
										else: 
										
											$custom =  $row->innertext;
											
										endif;
									
										?><div class="listino_periodo"><?php echo $custom; ?></div><?php 
											
									endif;
									
									$tt++;
									
								endforeach;
								
								?></div><?php
								
							endif;
							
							$t++;
							
						endforeach;
						
					} else if ( count($tds) == 2 ) {
						
						// Sono in conformazione week ( esempio hotel id 969 ) 
						
						$custom_week = [];
						
						?><div class="col-xs-12 listino_items"><?php 
								
								$td = $html->find("td");					
								$th = $html->find("th");
								
								if (!$th):
									
									$th = 	$html->find("tr",0)->find("td");
									$td = 	$html->find("tr",1)->find("td");
									
								endif; 
									
																
								$t=0;
								foreach($td as $row):
									
									$tit = $th[$t]->plaintext;
										
									if ($tit):
									
									?><div class="listino_periodo"><?php echo $tit; ?></div><?php 
									
									?><div class="listino_item">
										<span class="price" style="width:100%; text-align:left;"><?php echo $row->innertext; ?></span>
										<div class="clear"></div>
									</div><?php 
										
									endif;
									$t++;
										
										
										
									
								endforeach;
								
								/*$t = 1;
								
								foreach($td as $row):
									
									$custom_week[$t] = $row->innertext;
									$t += 2;

									
								endforeach;*/
								
							?>
						
						</div><?php 

					}
						endif;				
					
					?>
					
				
			</div>
			
			@if($listino->descrizione)
				<div style="font-size:12px; line-height: 20px !important; padding:5px;">
					{!! strip_tags($listino->descrizione, "<br>") !!}
				</div>
			@endif
			
		@endforeach
	@endforeach
@endif

