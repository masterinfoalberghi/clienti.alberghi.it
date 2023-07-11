<?php 
  if( !isset($_GET["l"])) $current_lang = "it";
  else if ( isset($_GET["l"])) $current_lang = $_GET["l"];  
?>

<!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
	@foreach (Langs::getAll() as $lang)
		<li role="presentation" <?=( $lang === $current_lang ? 'class="active"' : null)?>>
			<a class="tab_lang" data-id="{{ $lang }}" href="#{{ $lang }}" aria-controls="profile" role="tab" data-toggle="tab">
				<img src="{{ Langs::getImage($lang) }}" alt="{{ $lang }}"> {{ Langs::getName($lang) }}
			</a>
		</li>
	@endforeach
</ul>

{!! Form::hidden("current_lang", $current_lang ,["id"=>"current_lang"]) !!}

<script type="text/javascript">

	var console=console?console:{log:function(){}}
	jQuery(function($) {
	
		$(".tab_lang").click(function () {
			$("#current_lang").val($(this).data("id"));
		})
	
	});

</script>