
	$(function() {
		
		/**
		 * Link modifica
		 */
	
		$("[data-page-edit]").each(function () {
			
			var id = $(this).data("page-edit");
			var link = '&nbsp;<small><a href="/admin/pages/'+id+'/edit" class="btn btn-small btn-rosso" target="_blank">modifica</a></small>';
			$(this).find(".page-edit").append(link);
				
		});
		
		$("[data-hotel-edit]").each(function () {
			
			var id = $(this).data("hotel-edit");
			var link = '&nbsp;<small><a href="/admin/seleziona-hotel?ui_editing_hotel='+id+'" class="btn btn-small btn-rosso" target="_blank">modifica</a></small>';
			$(this).find(".hotel-edit").append(link);
				
		});
		
			
	});
