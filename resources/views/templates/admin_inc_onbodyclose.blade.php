	  <script>
	    jQuery(document).ready(function() {
	
	    jQuery('#js-salva-top').click(function(event){
	      
	      jQuery('#js-salva-top-submit').trigger("click");
	     			      
	    });
	    
	    @if( isset($reset) && $reest = "si")
		    jQuery(".reset-content").click(function(){
			    jQuery("#save_form").find('input:text, input:password, input:file, select, textarea').val('');
			    jQuery("#save_form").find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
			});
		@endif

		jQuery(".simpleLogin").click(function(e){
		    e.preventDefault();
		    id = jQuery(this).data("id");
		    document.getElementById(id).submit();
		});
    
	  });

	</script>

    <!-- jQuery -->
    <script src="{{Utility::assets('/vendor/neon/js/gsap/main-gsap.js')}}"></script>
	
    <!-- Bootstrap Core JavaScript -->
    @if ($__env->yieldContent("jquery-ui-js"))
   	 	@yield("jquery-ui-js")
    @else
        <script src="{{Utility::assets('/vendor/neon/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js')}}"></script>
    @endif
    
    <script src="{{Utility::assets('/vendor/neon/js/bootstrap.js')}}"></script>
    <script src="{{Utility::assets('/vendor/neon/js/joinable.js')}}"></script>
    <script src="{{Utility::assets('/vendor/neon/js/resizeable.js')}}"></script>
    <script src="{{Utility::assets('/vendor/neon/js/neon-api.js')}}"></script>
    <script src="{{Utility::assets('/vendor/neon/js/neon-custom.js')}}"></script>
    <script src="{{Utility::assets('/vendor/neon/js/neon-demo.js')}}"></script>
    <script src="{{Utility::assets('/vendor/neon/js/typeahead.min.js')}}"></script>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-TN6N827RXJ"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-TN6N827RXJ');
    </script>