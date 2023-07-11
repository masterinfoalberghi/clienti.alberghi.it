<link 	href="{{Utility::assets('/vendor/tipped/tipped.min.css', true)}}" rel="stylesheet" type="text/css" />	

<script  src="{{Utility::assets('/desktop/js/validate.min.js', true)}}"></script>
<script  src="{{Utility::assets('/desktop/js/generale.min.js', true)}}"></script>
<script  src="{{Utility::assets('/desktop/js/form.min.js', true)}}"></script>
<script  src="{{Utility::assets('/desktop/js/newsletter.min.js', true)}}"></script>
<script  src="{{Utility::assets('/vendor/tipped/tipped.min.js', true)}}"></script>

@if (Auth::check() && Auth::user()->role == "operatore")
	<script  src="{{Utility::assets('/desktop/js/utility.min.js', true)}}"></script>
@endif
