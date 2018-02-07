<?php
/**
 * The Template Part for displaying the Preheader Theme Area.
 * 
 * The preheader is a collapsible, widget-ready theme area above the header.
 *
 * For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package     G1_Framework
 * @subpackage  G1_Theme01
 * @since       G1ßÏ_Theme01 1.0.0
 */
 require_once( ABSPATH . 'wp-content/plugins/tcn-network/tcn-network.php' );
    
    $network = new TCNNetwork;
    
    $current_user = wp_get_current_user();
    $cats = $network->get_subscribed_categories($current_user);
    
    $province = get_user_profile_field($current_user->ID, 'tcn_user_meta_province');
    $city = get_user_profile_field($current_user->ID, 'tcn_user_meta_city');
    $phone = get_user_profile_field($current_user->ID, 'tcn_user_meta_phone');
    $first_name = get_user_profile_field($current_user->ID, 'first_name');
    $last_name = get_user_profile_field($current_user->ID, 'last_name');
    $user_email = $current_user->user_email;
    $age = get_user_profile_field($current_user->ID, 'tcn_user_meta_age');
    $caregiver_role = get_user_profile_field($current_user->ID, 'tcn_user_meta_caregiver_role');
    $marital_status = get_user_profile_field($current_user->ID, 'tcn_user_meta_marital_status');
    $gender = get_user_profile_field($current_user->ID, 'tcn_user_meta_gender');
    
    $missings_fields = array();

    push_profile_error($missings_fields, $province, __('Residing In', "tcn"));
    push_profile_error($missings_fields, $first_name, __('First Name', "tcn"));
    push_profile_error($missings_fields, $last_name, __('Last Name', "tcn"));
    push_profile_error($missings_fields, $user_email, __('User Email', "tcn"));
    push_profile_error($missings_fields, $age, __('Age', "tcn"));
    push_profile_error($missings_fields, $caregiver_role, __('I Am A', "tcn"));
    push_profile_error($missings_fields, $marital_status, __('Marital Status', "tcn"));
    push_profile_error($missings_fields, $gender, __('Gender', "tcn"));
    push_profile_error($missings_fields, $city, __('City', "tcn"));
    push_profile_error($missings_fields, $phone, __('Phone', "tcn"));
	 
$image_path = get_stylesheet_directory_uri() .'/images/';

function get_language_chooser()
{
    if(ICL_LANGUAGE_CODE === 'en')
    {
    ?>
        <a href="http://lereseauaidant.ca">Français</a>
    <?php    
    }
    else
    {
    ?>
        <a href="http://thecaregivernetwork.ca">English</a>    
    <?php
    }
}

// Prevent direct script access
if ( !defined('ABSPATH') )
    die ( 'No direct script access allowed' );
?>
	<!-- BEGIN #g1-preheader -->
	<aside id="g1-preheader" class="g1-preheader">
        <div class="g1-layout-inner">
            <!-- BEGIN #g1-preheader-bar -->
            <div id="g1-preheader-bar" class="g1-meta">
                <div class="language_changer_container">
                    <button id="language_changer" role="button" aria-haspopup="true" aria-expanded="false" type="button" class="language_changer">
                        <span><?php echo(strtoupper(ICL_LANGUAGE_CODE)) ?></span>
                        <span class="chevron"></span>
                    </button>
                    <div class="dropdown">
                        <ul id="language_dropdown" role="menu" class="dropdown-menu close" aria-labelledby="language_changer">
                        <li role="presentation" class="">
                            <a role="menuitem" tabindex="-1" href="#">
                                <span>English</span>
                            </a>
                        </li>
                        <li role="presentation" class="">
                            <a role="menuitem" tabindex="-1" href="#">
                                <span>Français</span>
                            </a>
                        </li>
                        </ul>
                    </div>
                </div>
                <?php
                // Render feeds
                if ( shortcode_exists( 'g1_social_icons') ) {
                    $g1_social_icons_size = g1_get_theme_option( 'ta_preheader', 'g1_social_icons' );
                    if ( is_numeric( $g1_social_icons_size ) ) {
                        $g1_social_icons_size = intval( $g1_social_icons_size );
                        echo do_shortcode('[g1_social_icons template="list-horizontal" size="'. $g1_social_icons_size . '" hide="label, caption"]');
                    }
                }
                ?>
                
                <a href="#" id="g1-preheader__switch" onClick="toggle_slider('account'); return false;">
                    <?php if(is_user_logged_in()): ?>
                        <b><?php _e("My Account", "tcn"); ?></b>
                         <?php if(count($missings_fields) || empty($cats)) { ?>
        				<div class="incomplete-profile-box"><img src="<?php echo $image_path; ?>reminder-bell.png" alt="" /></div>
   						<?php } ?>
                    <?php else: ?>
                        <?php if(ICL_LANGUAGE_CODE == 'en'): ?>
                            <b><?php _e("Log in", "tcn"); ?></b> <?php _e("or", "tcn"); ?> <b><?php _e("Sign up", "tcn"); ?></b>
                        <?php else: ?>
                            <b>S'inscrire</b>
                        <?php endif ?>
                    <?php endif ?>
                    <span class="arrow down"></span>
                    <span class="arrow up"></span>
                </a>
            </div>
            <!-- END #g1-preheader-bar -->

            <div id="preheader-slider-subscribe" class="preheader-slider">
                
                <div class="g1-layout-inner subscribe-inner">
                    <div id="subscribe_form_div">
                    <h2>
                    <span style="font-size: 24px; font-weight: 900;"><?php _e("Subscribe", "tcn"); ?> </span><span style="font-size: 24px;"><?php _e("to our newsletter"); ?></span>. 
                    
                    <?php if(ICL_LANGUAGE_CODE=='en'): ?>
                        <span style="font-size: 14px">Your email is safe with us. View our <a href="/privacy-policy">privacy policy.</a></span>
                <?php else: ?>
                    <span style="font-size: 14px">Votre courriel est en sécurité avec nous. Affichez notre <a href="/politique-de-confidentialite/"> Politique de confidentialité </a></span>
                <?php endif ?>
                    </h2>
                    
                    <?php if(ICL_LANGUAGE_CODE == 'en' ): ?>
                    <form id="newsletter_signup_form" action="<?php echo site_url(); ?>/signup_login/" method="POST" style="width: 500px;">
                    <?php else: ?>
                    <form id="newsletter_signup_form" action="<?php echo site_url(); ?>/fr/signup_login_fr/" method="POST" style="width: 500px;">
                    <?php endif ?>
                    
                        <?php if(ICL_LANGUAGE_CODE == 'en'): ?>
                        <input type="hidden" name="lang" value="en">
                    <?php else : ?>
                        <input type="hidden" name="lang" value="fr">
                    <?php endif ?>
                        <input type="hidden" name="action" value="subscribe">
                        <input id="subscribe_email" type="email" name="email" placeholder="<?php _e("Your email", "tcn"); ?>" /> 
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
                
                    <div id="subscribe_success" style="display: none">                        
                        <div class="subscribe-success-banner">
                            <div class="arrow"></div>
                        <?php if(ICL_LANGUAGE_CODE == 'en'): ?>
                            <p style="font-size: 24px">
                                Thanks for connecting with us. Why not <span class="orange">sign up</span>
                                and be a part of our caregiving community.
                            </p>
                            
                            <img src="<?php echo $image_path ?>header_check.png" alt="Checkmark" />
                            <span class="green">
                                Get <b>early access</b> to our free health and wellness tele-events;
                            </span>
                            <br/>
                        
                            <img src="<?php echo $image_path ?>header_check.png" alt="Checkmark" />
                            <span class="green">
                               Benefit from <b>special offers & promotions;</b>
                            </span>
                            <br/>
                            
                            <img src="<?php echo $image_path ?>header_check.png" alt="Checkmark" />
                            <span class="green">
                               Get the drop on the <b>latest caregiver news</b> in Canada;
                            </span>
                            <br/>
                            
                            <img src="<?php echo $image_path ?>header_check.png" alt="Checkmark" />
                            <span class="green">
                               <b>Track your favorite</b> TCN tele-events. 
                            </span>
                            <br/>
                            
                        <?php else: ?>
                            <p style="font-size: 24px">
                                Merci de vous être connecté avec nous. <span class="orange">Inscrivez-vous</span>
                                pour faire partie de notre communauté.
                            </p>
                            
                            <img src="<?php echo $image_path ?>header_check.png" />
                            <span class="green">
                                <b>Obtenez un pré-accès</b> à nos télés-événements gratuits sur 
                                la santé et le bien-être;
                            </span>
                            <br/>
                        
                            <img src="<?php echo $image_path ?>header_check.png" />
                            <span class="green">
                                Profitez de nos offres et <b>nos promotions spéciales</b>;
                            </span>
                            <br/>
                            
                            <img src="<?php echo $image_path ?>header_check.png" />
                            <span class="green">
                                Prenez <b>les dernières actualités</b> canadiennes au sujet des 
                                proches aidants;
                            </span>
                            <br/>
                            
                            <img src="<?php echo $image_path ?>header_check.png" />
                            <span class="green">
                                Suivez <b>vos télés-événements</b> préférés offerts par LRA.
                            </span>
                            <br/>
                        <?php endif ?>
                        </div>
                    </div>
                    
                    
                    <div id="subscribe_errors"  style="display: none">
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
            </div>
            
            <div id="preheader-slider-account" class="preheader-slider">
                <div class="g1-layout-inner">
                    <?php $current_user = wp_get_current_user(); ?>
                
                    <?php if(is_user_logged_in()): ?>
                        <div class="g1-links tcn-">
                            <ul>
                                <li class="tcn-logged-in"><?php _e("You are logged in as", "tcn"); ?> <?php echo $current_user->user_email; ?></li>
                                <li><a href="<?php echo wp_logout_url(site_url()); ?>"tw><?php _e("Logout", "tcn"); ?></a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <div class="secondary-login-area">
                            <div class="account-login">
                                <div class="sep"></div>
                                <?php if(ICL_LANGUAGE_CODE == 'en' ): ?>
                                <form id="login_form" action="/signup_login/" method="POST" style="width: 250px">
                                <?php else: ?>
                                    <form id="login_form" action="/signup_login/" method="POST" style="width: 250px">
                                <?php endif ?>
                                
                                    <input type="hidden" name="action" value="login" />
                                    <h2><?php _e("Log in", "tcn"); ?> <span class="small">/ <?php _e("current subscriber", "tcn"); ?></span></h2>
                                    <div id="login_errors" style="display: none" class="g1-message g1-message--error">
                                        <div class="g1-inner" id="login_errors_inner">
                                            &nbsp;
                                        </div>
                                    </div>
                                
                                    <div id="login_rows">
                                    <div class="row">
                                        <input type="text" name="username_email" placeholder="<?php _e("Username or Email", "tcn" ); ?>" />
                                    </div>
                                    <div class="row ie9-password-titles" style="font-weight: 500; margin-bottom: 12px;">
                                        <span style="display: inline-block; width: 256px"><?php _e("Password", "tcn"); ?></span>
                                    </div>
                                    <div class="row" style="height: 42px;">
                                        <input type="password" name="password" placeholder="<?php _e("Password", "tcn"); ?>" />
                                    </div>
                                    
                                    <div class="row">
                                    <ul class="options" style="position: relative; top: 4px;">
                                        <li><label><input type="checkbox" name="remember_me" style="margin-right: 10px;" /><span><?php _e("Remember me", "tcn"); ?></span></label></li>
                                    </ul>
                                    </div>
                                    <div class="row">
                                        <input type="hidden" name="lang" value="<?php echo ICL_LANGUAGE_CODE; ?>" />
                                        <input type="submit" value="<?php _e("Log in", "tcn"); ?>" />
                                    </div>
                                    </div>
                                </form>
                    
                                <div class="forgot-hd" id="forgot_password_title" style="width: 250px; margin-top: 8px;">
                                    <a href="#" onClick="show_forgot_password_form(); return false;"><?php _e("Forgot your username or password?", "tcn"); ?></a>
                                </div>
                                <?php if(ICL_LANGUAGE_CODE == 'en' ): ?>
                                <form id="forgot_email_password_form" action="/signup_login/" method="POST" style="display: none; width:240px;">
                                <?php else: ?>
                                    <form id="forgot_email_password_form" action="/fr/signup_login_fr/" method="POST" style="display: none; width:240px;">
                                <?php endif ?>
                                
                                
                                    <input type="hidden" name="action" value="forgot_email_password" />
                                    <div class="row">
                                        <input type="text" name="username_email" placeholder="<?php _e("Your email", "tcn"); ?>" />
                                    </div>
                                    <input type="hidden" name="lang" value="<?php echo ICL_LANGUAGE_CODE; ?>" />
                                    <input type="submit" value="<?php _e("Reset", "tcn"); ?>" />
                                    <div id="forgot_email_password_confirmation" style="visibility: hidden">
                                        <?php _e("We have sent you an email.", "tcn"); ?>
                                    </div>
                                    <div id="forgot_email_password_errors" style="display: none" class="g1-message g1-message--error">
                                        <div class="g1-inner" id="forgot_email_password_errors_inner">
                                            &nbsp;
                                        </div>
                                    </div>

                                </form>
                            </div>
                
                            <div class="account-signup">
                                <?php if(ICL_LANGUAGE_CODE == 'en' ): ?>
                                    <form id="signup_form" action="/signup_login/" method="POST" >
                                <?php else: ?>
                                    <form id="signup_form" action="/fr/signup_login_fr/" method="POST" >
                                <?php endif ?>
                                    <h2><?php _e("Sign up", "tcn"); ?> <span class="small">/ <?php _e("new subscriber", "tcn"); ?></span></h2>
                                
                                    <div id="signup_errors" style="display: none" class="g1-message g1-message--error">
                                        <div class="g1-inner" id="signup_errors_inner">
                                            &nbsp;
                                        </div>
                                    </div>
                                
                                    <div class="row">
                                        <input name="first_name" type="text" placeholder="<?php _e("First Name", "tcn"); ?>" />
                                        <input name="last_name" type="text" placeholder="<?php _e("Last Name", "tcn"); ?>" />
                                    </div>
                                    <div class="row">
                                        <select id="tcn_user_meta_province" name="tcn_user_meta_province">
                                            <option selected="selected" value="-1"><?php _e("Where do you live?", "tcn"); ?></option>
                                            <?php for($i = 0; $i < get_province_count(); $i++): ?>
                                                <option value="<?php echo $i; ?>"><?php echo get_province_name($i); ?></option>
                                            <?php endfor ?>
                                        </select>
                                    
                                    </div>
                                    
                                    <div class="row">
                                        <input name="city" type="text" placeholder="<?php _e("City", "tcn"); ?>" />
                                        <input name="phone" type="text" placeholder="<?php _e("Phone", "tcn"); ?>" />
                                    </div>
                                    
                                    <div class="row">
                                        <input name="email" type="text" placeholder="<?php _e("Email", "tcn" ); ?>" />
                                        <input name="username" type="text" placeholder="<?php _e("Username", "tcn" ); ?>" />
                                    </div>
                                    
                                    <div class="row ie9-password-titles" style="font-weight: 500; margin-bottom: 12px;">
                                        <span style="display: inline-block; width: 256px"><?php _e("Password", "tcn"); ?></span>
                                        <span style="display: inline-block; width: 240px"><?php _e("Confirm Password", "tcn"); ?></span>
                                    </div>
                                    
                                    <div class="row">
                                        <input name="password" type="password" placeholder="<?php _e("Password", "tcn"); ?>" />
                                        <input name="confirm_password" type="password" placeholder="<?php _e("Confirm Password", "tcn"); ?>" />
                                    </div>
                                    
                                    <div class="message">
                                        <?php _e("*Passwords must be at least 8 characters and contain 1 number.", "tcn"); ?><br />
                                        <?php _e("We offer events in English and French. Do you want to be notified of both?", "tcn"); ?>
                                    </div>
                                    <?php if(ICL_LANGUAGE_CODE=='en'): ?>
                                    <ul class="options">
                                        <li><label><input type="checkbox" name="english_newsletters" checked="checked" /><span><?php _e("English", "tcn"); ?></span></label></li>
                                        <li><label><input type="checkbox" name="french_newsletters" /><span><?php _e("Français", "tcn"); ?></span></label></li>
                                    </ul>
                                    <?php else: ?>
                                        <ul class="options">
                                            <li><label><input type="checkbox" name="french_newsletters" checked="checked" /><span><?php _e("Français", "tcn"); ?></span></label></li>
                                            <li><label><input type="checkbox" name="english_newsletters"  /><span><?php _e("English", "tcn"); ?></span></label></li>
                                            
                                        </ul>    
                                        
                                    <?php endif ?>
                                    <ul class="options">
                                        <?php if(ICL_LANGUAGE_CODE=='en'): ?>
                                        <li><label><input type="checkbox" name="terms_of_use" /><span><?php _e("I have read the", "tcn"); ?> <a href="<?php echo site_url(); ?>/terms-use/"><?php _e("Terms of Use", "tcn"); ?></a> <?php _e("and", "tcn"); ?> <a href="<?php echo site_url(); ?>/politique-de-confidentialite/"><?php _e("Privacy Policy", "tcn"); ?></a></span></label></li>
                                    <?php else: ?>
                                        <li><label><input type="checkbox" name="terms_of_use" /><span><?php _e("I have read the", "tcn"); ?> <a href="http://lereseauaidant.ca/conditions-utilisation/"><?php _e("Terms of Use", "tcn"); ?></a> <?php _e("and", "tcn"); ?> la <a href="<?php echo site_url(); ?>/politique-de-confidentialite/"><?php _e("Privacy Policy", "tcn"); ?></a></span></label></li>
                                    <?php endif ?>
                                    </ul>
                                   <div class="g-recaptcha" data-sitekey="6Ld6F0QUAAAAAGc2TeUbh-ujQyOXu7GBNWPkj9Qg"></div> 
				   <input type="hidden" id="signup_form_secondary_is_human" name="signup_form_is_human" value="" />
                                    <br />
                                    <input type="hidden" name="lang" value="<?php echo ICL_LANGUAGE_CODE; ?>" />
                                    <?php if(ICL_LANGUAGE_CODE =='en'):?>
                                        <input type="submit" value="<?php _e("Sign up", "tcn"); ?>" />
                                <?php else :?>
                                        <input type="submit" value="<?php _e("S'inscrire", "tcn"); ?>" />
                                <?php endif ?>
                            
                                    <input type="hidden" name="lang" value="<?php echo ICL_LANGUAGE_CODE; ?>" />
                                    <input type="hidden" name="action" value="signup" />
                                </form>
                        
                                <div id="signup_success" style="display: none">
                                    <h2><?php _e("Success!", "tcn"); ?></h2>
                                    <?php _e("Please login using the form to the left.", "tcn"); ?>
                                </div>
                            </div>
                        </div>
                    <?php endif ?>
                </div>
            </div>
        </div><!-- .g1-inner -->
        
        <div class="g1-background"></div>
        <?php //get_template_part( 'template-parts/g1_background', 'preheader' ); ?>
	</aside>
	<!-- END #g1-preheader -->	
	
	<script type="text/javascript">
	    
        function toggle_slider(slider)
        {
            var $slider = jQuery("#preheader-slider-" + slider);
            var $otherSlider = jQuery(".preheader-slider").not($slider);

            if (slider == "account" && !$slider.is(":visible")) {
                jQuery("#g1-preheader__switch .arrow.up").show();
                jQuery("#g1-preheader__switch .arrow.down").hide();
            }
            else {
                jQuery("#g1-preheader__switch .arrow.up").hide();
                jQuery("#g1-preheader__switch .arrow.down").show();
            }
            if (slider == "subscribe" && !$slider.is(":visible")) {
                jQuery("#g1-preheader-bar a.subscribe-button img.arrow.up").show();
                jQuery("#g1-preheader-bar a.subscribe-button img.arrow.down").hide();
            }
            else {
                jQuery("#g1-preheader-bar a.subscribe-button img.arrow.up").hide();
                jQuery("#g1-preheader-bar a.subscribe-button img.arrow.down").show();
            }

            if ($otherSlider.is(":visible")) {
                jQuery(".preheader-slider").not($slider).slideUp({'easing': 'easeInOutExpo', 'complete': function() {
                    $slider.slideDown({'easing': 'easeInOutExpo'});
                }});
            }
            else if ($slider.is(":visible")) {
                $slider.slideUp({'easing': 'easeInOutExpo'});
            }
            else {
                $slider.slideDown({'easing': 'easeInOutExpo'});
            }
        }
        
        function validate_subscribe(formData, jqForm, options) 
	    {
	        jQuery("#subscribe_errors").hide();
            jQuery("#subscribe_success").hide();
            
            if(jQuery("#subscribe_email").val() == '')
            {
                jQuery("#subscribe_errors").html(<?php __("You must provide a valid email address.", 'tcn'); ?>);
                jQuery("#subscribe_errors").show();
                return false;
            }
            
            return true;
	    }
	    
	    function subscribe_callback(responseText, statusText, xhr, $form)
	    {
	        if(responseText['success'] == false)
	        {
	            jQuery("#subscribe_error").html(responseText["error"]);
                jQuery("#subscribe_errors").show();
            }
            else
            {
                jQuery("#subscribe_form_div").hide(1000);
                jQuery("#subscribe_success").show();
                jQuery("#newsletter_signup_form").clearForm();
            }
	    }
	    
        function validate_login(formData, jqForm, options) 
	    {
	        jQuery("#login_errors").hide();
	    }
	    
	    function login_callback(responseText, statusText, xhr, $form)
	    {
	        if(responseText['success'] == false)
	        {
                jQuery("#login_errors").show();
                jQuery("#login_errors_inner").html(responseText['error']);
            }
            else
            {
                jQuery("#login_form").clearForm();
                location.reload();
            }
	    }
	    
	    function validate_forgot_email_password(formData, jqForm, options) 
	    {
	        jQuery("#forgot_email_password_errors").hide();
	    }
	    
	    function forgot_email_password_callback(responseText, statusText, xhr, $form)
	    {
	        if(responseText['success'] == false)
	        {
                jQuery("#forgot_email_password_errors").show();
                jQuery("#forgot_email_password_errors_inner").html(responseText['error']);
            }
            else
            {
                jQuery("#forgot_email_password_form").clearForm();
                jQuery("#forgot_email_password_errors").show();
                jQuery("#forgot_email_password_errors_inner").html('<?php _e("We have sent you an email.", "tcn"); ?>');
            }
	    }
	    
	    function validate_signup(formData, jqForm, options) 
	    {
	        jQuery("#signup_errors").hide();
	        jQuery("#signup_success").hide();
	    }
	    
	    function signup_callback(responseText, statusText, xhr, $form)
	    {
	        if(responseText['success'] == false)
	        {
                jQuery("#signup_errors").show();
                jQuery("#signup_errors_inner").html(responseText['error']);
            }
            else
            {
                jQuery("#signup_form").clearForm();
                jQuery("#signup_success").show(1000);
                jQuery("#signup_form").hide(1000);
            }
	    }
	    
	    function show_forgot_password_form()
	    {
	        jQuery("#forgot_email_password_form").show();
        }
        
        function reset_password_show()
        {
	        jQuery("#forgot_email_password_form").show();
	        jQuery("#login_rows").hide();
	        jQuery("#forgot_password_title").hide();
        }
	     
	    jQuery(document).ready(function()
	    {
	        var options = { 
                beforeSubmit:  validate_login,  // pre-submit callback 
                success:       login_callback  // post-submit callback 
            };
	        jQuery('#login_form').ajaxForm(options);
            
            options = { 
                beforeSubmit:  validate_forgot_email_password,  // pre-submit callback 
                success:       forgot_email_password_callback  // post-submit callback 
            };
            jQuery('#forgot_email_password_form').ajaxForm(options);

            options = { 
                beforeSubmit:  validate_signup,  // pre-submit callback 
                success:       signup_callback  // post-submit callback 
            };
            jQuery('#signup_form').ajaxForm(options);
            
            options = { 
                beforeSubmit:  validate_subscribe,  // pre-submit callback 
                success:       subscribe_callback  // post-submit callback 
            };
            jQuery('#newsletter_signup_form').ajaxForm(options);
            


            // click outside slider to close it
            jQuery(document).click(function(e) {
                var visible_slider = jQuery(".preheader-slider:visible");
                if (visible_slider.length > 0) {
                    if (e.pageY > visible_slider.offset().top + visible_slider.innerHeight()) {
                        visible_slider.slideUp({'easing': 'easeInOutExpo'});
                        jQuery("#g1-preheader__switch .arrow.up").hide();
                        jQuery("#g1-preheader__switch .arrow.down").show();
                        jQuery("#g1-preheader-bar a.subscribe-button img.arrow.up").hide();
                        jQuery("#g1-preheader-bar a.subscribe-button img.arrow.down").show();
                    }
                }     
            });
	    });

        // language dropdown handler code
        jQuery(document).ready(function() {
            function updateLocation(locale) {
              var origin = window.location.origin;
          
              switch (locale) {
                case 'fr':
                  window.location = origin + '/fr';
                  break;
                default:
                  window.location = origin;
              }
            }
          
            var languageChanger = jQuery('#language_changer');
            var languageDropdown = jQuery('#language_dropdown');
            var languageOptions = languageDropdown.find('a');
          
            languageChanger.on('click', function() {
              languageDropdown.toggleClass('open');
              languageDropdown.toggleClass('close');
            });
          
            languageOptions.each(function(index) {
              jQuery(this).on('click', function(e) {
                e.preventDefault();
                var opt = this.innerText;
                var locale = opt.toLowerCase().slice(0, 2);
                updateLocation(locale);
              });
            });
        });
	</script>
