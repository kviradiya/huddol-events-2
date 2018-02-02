// JavaScript Document
/* File created April 7th 2016 : Mac */

jQuery(document).ready( function($) {

	$('#category_reminder_form').on('submit', function(e){
		e.preventDefault();
		
		$('#category_reminder').html('<img src="http://mailgun.github.io/validator-demo/loading.gif" alt="Loading...">');
		
		// We'll pass this variable to the PHP function example_ajax_request
		var fruit = new Array('Banane', 'kiwi', 'orange');
		var u_id = $('input[name=tnc_user_profile]').val();
		var categories = new Array();
		$("#category_reminder_form input[type=checkbox]:checked").each(function(){
			categories.push($(this).val());
		});
		// serialize form data to send through Ajax
		var myData = jQuery(this).serialize();  
		
		
		// This does the ajax request
		$.ajax({
			type: 'POST',
			url: MyAjax.ajaxurl,
			data: {
				// PHP function to call
				'action':'update_user_categories',
				// serialized data to send
				'formData':myData,
				// send the nonce along with the request
				'postCommentNonce' : MyAjax.postCommentNonce		
			},
			success:function(data) {
				// This outputs the result of the ajax request even if there was an error server-side
				$('#category_reminder').html(data);
			},
			error: function(errorThrown){
				$('#category_reminder').html("Error: " + errorThrown);
			},
		});  
	});
});