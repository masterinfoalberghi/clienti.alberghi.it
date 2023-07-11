	$(function () {


	    /* -- */

	    /* Filtro ordinamento */

	    $(".order_select_paging a").click(function (e) {
	        e.preventDefault();
	        var $me = $(this);
	        var $order = $me.data("val");
	        $("#order").val($order);
	        $("#form_filtri_order_paging").submit();

	    });

	});