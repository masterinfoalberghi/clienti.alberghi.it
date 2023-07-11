	$(function() {

		$(".smartlink a").click(function (e) {
			
			e.preventDefualt();
			
		});
		
		$(".smartlink").click(function () {
			
			var href = $(this).find("a").attr("href");
			window.location.href = href;	
			
		});
			
	});