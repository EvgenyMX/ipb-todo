(function( $ ) {
	'use strict';


	$(document).on('click', '.sync-todo-list', function () {
		let btn = $(this);
		$.ajax({
			type : 'POST',
			url : ajaxurl,
			data : 'action=sync-todo',
			beforeSend: function () {
				btn.prop('disabled', true)
				btn.after( `<div class="lds-ring"><div></div><div></div><div></div><div></div></div>` )
			},
			success : function(data){
				window.location.reload()
			}
		});



	})



})( jQuery );
