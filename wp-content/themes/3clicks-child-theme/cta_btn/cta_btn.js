// JavaScript Document

jQuery(document).ready(function($) {
	
	var this_trigger;
	
    $('#upload_image_button, #upload_image_button_eng').click(function() {
		
		this_trigger = this.id;
		
		console.log(this_trigger);
		
        tb_show('Upload an image', 'media-upload.php?referer=cta_btn_options&type=image&TB_iframe=true&post_id=0', false);
        return false;
    });

	window.send_to_editor = function(html) {
		
		var this_preview_id = '#upload_logo_preview';
		var this_attachment_id = '#image_attachment_id';
		
		if(this_trigger == 'upload_image_button_eng'){
			this_preview_id = '#upload_logo_eng_preview';
			this_attachment_id = '#image_attachment_id_eng';
		}
		
		var image_url = $('img',html).attr('src');
		$(this_attachment_id).val(image_url);
		tb_remove();
		$(this_preview_id+' img').attr('src',image_url);

		$('#submit_options_form').trigger('click');
	}
	
	

});

