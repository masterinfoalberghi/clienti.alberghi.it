	
    

	<link 	href="{{Utility::assets('/vendor/fontello/animation.min.css', true)}}" rel="stylesheet" type="text/css" />	
	<link 	href="{{Utility::assets('/vendor/fontello/fontello.min.css', true)}}" rel="stylesheet" type="text/css" />
	<link   rel="stylesheet" type="text/css" media="screen"  href="{{Utility::assets('/vendor/venobox/venobox.min.css', true)}}" />

	 <script src="{{Utility::assets('/vendor/venobox/venobox.min.js', true)}}"></script>

	  <script type="text/javascript">
	  	$(function() {
	  		
	  			$('.venobox').venobox({
                    framewidth : 'auto',                            // default: ''
                    frameheight: 'auto',                            // default: ''
                    border     : '0',                             // default: '0'
                    bgcolor    : '#fff',                          // default: '#fff'
                    overlayClose : true,
                    // is called when opening is finished
                    cb_post_open  : function(obj, gallIndex, thenext, theprev){
                        $("a.vbox-close").attr("href", "#close")      
                    },
                  });
                  
                $(".venobox-caparre").venobox({
                    framewidth : 'auto',                            // default: ''
                    frameheight: "700px",                            // default: ''
                    overlayClose : true,
                    closeBackground: '#f44336', 
                    closeColor: '#fff'
                });

                $(".venobox-rating").venobox({
                    framewidth : 'auto',                            // default: ''
                    frameheight: "700px",                            // default: ''
                    overlayClose : true,
                    closeBackground: '#f44336',
                    closeColor: '#fff'

                });
	  	});

		</script>

