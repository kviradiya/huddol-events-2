<?php
/**
 * Template Name: TCN: Mailchimp
 *
 * For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package G1_Framework
 * @subpackage G1_Theme03
 * @since G1_Theme03 1.0.0
 */

// Prevent direct script access
if ( !defined('ABSPATH') )
    die ( 'No direct script access allowed' );
?>

<?php $image_path = get_stylesheet_directory_uri() .'/images/'; ?>

<?php get_header(); ?>
        <div id="primary">
            <div id="content" role="main">
                <div class="secondary-login-area">
                <div class="account-login" style="width: 100%">
                <div id="subscribe_form_div_2">
                    <h2>
                    <span style="font-size: 24px; font-weight: 900;"><?php _e("Subscribe", "tcn"); ?> </span><span style="font-size: 24px;"><?php _e("to our newsletter"); ?></span>. 
                    
                    <?php if(ICL_LANGUAGE_CODE=='en'): ?>
                        <span style="font-size: 14px">Your email is safe with us. View our <a href="/privacy-policy">privacy policy.</a></span>
                <?php else: ?>
                    <span style="font-size: 14px">Votre courriel est en sécurité avec nous. Affichez notre <a href="/politique-de-confidentialite/"> Politique de confidentialité </a></span>
                <?php endif ?>
                    </h2>
                    
                    <?php if(ICL_LANGUAGE_CODE == 'en' ): ?>
                    <form id="newsletter_signup_form_2" action="<?php echo site_url(); ?>/signup_login/" method="POST" style="width: 500px;">
                    <?php else: ?>
                    <form id="newsletter_signup_form_2" action="<?php echo site_url(); ?>/fr/signup_login_fr/" method="POST" style="width: 500px;">
                    <?php endif ?>
                    
                        <input type="hidden" name="action" value="subscribe">
                        
                        <?php if(ICL_LANGUAGE_CODE == 'en' ): ?>
                            <input type="hidden" name="lang" value="en">
                             <?php else: ?>
                                <input type="hidden" name="lang" value="fr">
                                <?php endif ?>
                        <input type="text" name="email" placeholder="<?php _e("Your email", "tcn"); ?>" /> 
                        <p>
                            <?php _e("We offer events in English and French.<br/>Do you want to be notified of both?", "tcn"); ?>
                        </p>

                        <?php if(ICL_LANGUAGE_CODE == 'en'): ?>
                        <ul class="options" style="position: relative; top: -10px;">
                            <li><label><input type="checkbox" name="english" checked="checked" /><span><?php _e("English"); ?></span></label></li>
                            <li><label><input type="checkbox" name="french" /><span><?php _e("Français"); ?></span></label></li>
                        </ul>
                        <?php else: ?>
                            <ul class="options" style="position: relative; top: -10px;">
                                <li><label><input type="checkbox" name="french" checked="checked"/><span><?php _e("Français"); ?></span></label></li>
                                <li><label><input type="checkbox" name="english" /><span><?php _e("English"); ?></span></label></li>
                            </ul>      
                        <?php endif ?>

                        <input type="submit" value="<?php _e("Subscribe", "tcn" ); ?>" />
                    </form>
                    </div>
                    
                    <div id="subscribe_success_2" style="display: none">
                        <p style="font-size: 24px">
                            <?php _e("Thank you.", "tcn"); ?>
                        </p>
                        <p style="font-size: 14px"> 
                            <?php _e("Your subscription is confirmed and you should be hearing from us soon.", "tcn"); ?>
                        </p>
                    </div>
                    
                    
                    <div id="subscribe_errors_2"  style="display: none">
                        <p style="font-size: 24px">
                            <?php _e("We're sorry.", "tcn"); ?>
                    </p>
                        <p style="font-size: 14px"> 
                            <span id="subscribe_error">
                                <?php _e("There was an error subscribing you to the newsletter. Please try again.", "tcn"); ?>
                            </span>
                        </p>
                    </div>
                </div>
                </div></div>    
            </div><!-- #content -->
        </div><!-- #primary -->
        <script type="text/javascript">
        function validate_subscribe_2(formData, jqForm, options) 
	    {
	        jQuery("#subscribe_errors_2").hide();
            jQuery("#subscribe_success_2").hide();
	    }
	    
	    function subscribe_callback_2(responseText, statusText, xhr, $form)
	    {
	        if(responseText['success'] == false)
	        {
	            jQuery("#subscribe_error_2").html(responseText["error"]);
                jQuery("#subscribe_errors_2").show();
            }
            else
            {
                jQuery("#subscribe_success_2").show();
                jQuery("#subscribe_form_div_2").hide();
                jQuery("#newsletter_signup_form_2").clearForm();
            }
	    }
	    
	    jQuery(document).ready(function()
	    {
    	    options = { 
                beforeSubmit:  validate_subscribe_2,  // pre-submit callback 
                success:       subscribe_callback_2  // post-submit callback 
            };
            jQuery('#newsletter_signup_form_2').ajaxForm(options);
        });
        
        </script>

        
<?php get_footer(); ?>