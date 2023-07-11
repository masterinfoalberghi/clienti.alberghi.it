
	var Menu = {
			
		init: function () {
            
			$(".menubutton").click(function (e){
			
				e.preventDefault();
				
				if (typeof setCookieLaw == 'function') { 
					setCookieLaw(); 
					setCookieLaw = undefined;
				}
				
				if (!menuISOpen())
					openMenu();
				else 
					closeMenu();
			
			});
			
			$("#cambialocalita").click(function (e) {
				
				e.preventDefault();
				var $me = $(this);
				var txt = $me.data("txt");
				
				if ($me.hasClass("open")) 
				{
					$me.removeClass("open");
					$me.html(txt);
					$(".menu-localita").slideUp();
					
				} 
				else 
				{
					$me.addClass("open");
					$me.html(" X ");
					$(".menu-localita").slideDown();
				}
				
				
				//$me.hide();
				
			});
			
			$("#menu-macrolocalita .badge div").click(function (e) {
				
				e.preventDefault();
				var $me = $(this);
				var id = $me.data("id");
				var txt = $me.data("txt");
				
				if ($me.hasClass("open")) 
				{
					$me.removeClass("open");
					$me.html(txt);
					$("ul#" + id).slideUp();
					
				} 
				else 
				{
					$me.addClass("open");
					$me.html(" X ");
					$("ul#" + id).slideDown();
				}
				
				
				
			});
			
		
			
		},

					
	}
	
	Menu.init();
