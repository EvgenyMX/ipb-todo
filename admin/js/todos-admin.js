(function( $ ) {
	'use strict';


	$(document).on('click', '.sync-todo-list', function () {

		$.ajax({
			type : 'POST',
			url : ajaxurl,
			data : 'action=sync-todo',
			success : function(data){
				console.log(data);
			}
		});



	})

})( jQuery );
