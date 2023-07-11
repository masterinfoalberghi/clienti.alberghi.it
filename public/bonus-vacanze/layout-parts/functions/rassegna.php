<?php 

function rassegna_stampa($rassegna) {
 
	$link_fonte = $rassegna['link_fonte'];
	$immagine = $rassegna['url_immagine'];

	echo "
			<div class='visible news-content'>
				<div class='news_postdate'>
						<span>{$rassegna['data']}</span>
				</div>";
				if ($immagine) {
					echo "
					<a data-fancybox='gallery' href='$immagine'>
							<h3>«{$rassegna['titolo']}»</h3>
					</a>";
				} else {
					echo "<h3>«{$rassegna['titolo']}»</h3> ";
				}
	echo "
				<p>
				{$rassegna['riassunto']}
				</p>
				<div class='news_authorinfo'>
				<span><i class='icon-newspaper'></i>";
					if ($link_fonte){
						echo "
						<a rel='nofollow noreferrer noopener' target='_blank' href='$link_fonte'>
							{$rassegna['fonte']} (leggi)
						</a> ";
					} else {
						echo "{$rassegna['fonte']} (leggi)";
					}
	echo "
				</span>
				</div>
			</div>
";}