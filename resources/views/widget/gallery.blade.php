<div class="container" style="overflow: visible; height:500px; ">
	<div class="row" >
		<div class="gallery">
			<?php $t = 0; ?>
            @foreach($immagini as $img)
				<div class="gallery-item" style="left: <?php echo 502*$t; ?>px; ">
					<img  src="{{Utility::asset("images/pagine/500x500/" . $img)}}" alt="{{$testo[$t]}} - ({{$t+1}})" />
					@if ($testo[$t] != "")
						<div class="slick-text">{{$testo[$t]}}</div>
					@endif
				</div>
				<?php $t++; ?>
			@endforeach
		</div>
	</div>
</div>