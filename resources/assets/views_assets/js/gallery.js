	
	/**
	 * Se ho conteuti li inizializzo
	 */
	
	$(function() {
		
        $('.gallery').slick({
	      
	      arrows:false,
		  lazyLoad: 'ondemand',
		  slidesToShow: 1,
		  slidesToScroll: 1,
		  centerMode: true,
		  centerPadding: '0px',
		  infinite: false,
		  dots: true,
		  variableWidth: true,
		  
		}).on("beforeChange", function(t, e, o, s) {

			0 == s ? $(".gallery .slick-track").addClass("iniziale") : $(".gallery .slick-track").removeClass("iniziale")
			
        });
        
        $(".gallery .slick-track").addClass("iniziale");
		
		
			
	});
        
