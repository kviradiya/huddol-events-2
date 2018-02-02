// JavaScript Document

var onloadCallback = function() {
    //alert(site_language + "grecaptcha is ready!");
	  jQuery('.google_captcha').each(function(){
		var site_key = '6LfAOg8TAAAAAFHGVHvpPih5LYH38rlHU65mOMEx';
		
		var g_widget = grecaptcha.render( jQuery(this).attr('id'), {
		'sitekey' : site_key,
		'theme' : 'light'
		});	  
	  
	  });
  };

jQuery(document).ready(function(){
	
	jQuery('#signup_form input[type="submit"]').click(function(){
		var captcha_response = jQuery('#signup_form .g-recaptcha-response').val();
		jQuery('#signup_form input[name="signup_form_is_human"]').val(captcha_response);
	});
	
	jQuery('#signup_form_secondary input[type="submit"]').click(function(){
		var captcha_response = jQuery('#signup_form_secondary .g-recaptcha-response').val();
		jQuery('#signup_form_secondary input[name="signup_form_is_human"]').val(captcha_response) ;
	});
	

	
});