<?php
/**
 * Template Name: TCN: Secondary Login
 *
 * For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package G1_Framework
 * @subpackage G1_Theme03
 * @since G1_Theme03 1.0.0
 */
 
?>

<?php get_header(); ?>
    <?php $current_user = wp_get_current_user(); ?>
    <?php $redirect = ''; ?>
    <?php if(isset($_GET['redirect'])) 
    {
        $redirect = $_GET['redirect'];
    } ?>
    
    <?php if(is_user_logged_in()): ?>
        <div class="preheader-login">
            <ul>
                <li><?php _e("You are logged in as", "tcn"); ?> <?php echo $current_user->display_name; ?></li>
                <li><a href="<?php echo wp_logout_url(site_url()); ?>"><?php _e("Logout", "tcn"); ?></a>
            </ul>
        </div>
    <?php else: ?>
        <div class="secondary-login-area">
        <?php if(isset($_GET['callout']) && $_GET['callout']): ?>
            <?php if(ICL_LANGUAGE_CODE == 'en' ): ?>
                <div class="secondary-login-area-callout english">
            <?php else: ?>
                <div class="secondary-login-area-callout french">
            <?php endif ?>
                <div class="left"></div>
                <div class="right"></div>
                <div class="arrow"></div>
            </div>
        <?php endif ?>
        <div class="account-login">
            <div class="sep"></div>
            <?php if(ICL_LANGUAGE_CODE == 'en' ): ?>
            <form id="login_form_secondary" action="/signup_login/" method="POST" style="width: 230px;">
            <?php else: ?>
                <form id="login_form_secondary" action="/fr/signup_login_fr/" method="POST" style="width: 230px;">
            <?php endif ?>
                <input type="hidden" name="action" value="login" />
                <h2><?php _e("Log in", "tcn"); ?> <span class="small">/ <?php _e("current subscriber", "tcn"); ?></span></h2>
                <p><span style="color:#ef3e29">*</span> <?php _e("If you are already a member of our Network and are logging in to our new website for the first time, you will need to", "tcn"); ?> <a href="#" onclick="reset_password_show_secondary(); return false;"><?php _e("reset your password", "tcn"); ?></a> <?php _e("for security purposes.", "tcn"); ?>
                <div id="secondary_login_errors" style="display: none" class="g1-message g1-message--error">
                    <div class="g1-inner" id="secondary_login_errors_inner">
                        &nbsp;
                    </div>
                </div>
            
                <div id="login_rows_secondary">
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
    
        <?php /*
            <div class="forgot-hd">
                <a href="#" onclick="show_forgot_password_form(); return false;"><?php _e("Forgot your username or password?", "tcn"); ?></a>
            </div>
            */ ?>
            <?php if(ICL_LANGUAGE_CODE == 'en' ): ?>
             <form id="forgot_email_password_form_secondary" action="<?php echo site_url(); ?>/signup_login/" method="POST" style="display: none; width:240px;">
            <?php else: ?>
                 <form id="forgot_email_password_form_secondary" action="<?php echo site_url(); ?>/fr/signup_login_fr/" method="POST" style="display: none; width:240px;">
            <?php endif ?>
            
           
                <input type="hidden" name="action" value="forgot_email_password" />
                <div class="row">
                    <input type="text" name="username_email" placeholder="<?php _e("Provide your email", "tcn"); ?>" />
                </div>
                
                <input type="hidden" name="lang" value="<?php echo ICL_LANGUAGE_CODE ?>" />
                <input type="submit" value="Reset" />
                <div id="forgot_email_password_confirmation_secondary" style="visibility: hidden">
                    <?php _e("We have sent you an email.", "tcn"); ?>
                </div>
                <div id="forgot_email_password_errors_secondary" style="display: none" class="g1-message g1-message--error">
                    <div class="g1-inner" id="forgot_email_password_errors_inner_secondary">
                        &nbsp;
                    </div>
                </div>

            </form>
        </div>

        <div class="account-signup">
            <?php if(ICL_LANGUAGE_CODE == 'en' ): ?>
                <form id="signup_form_secondary" action="/signup_login/" method="POST" >
             
            <?php else: ?>
                 <form id="signup_form_secondary" action="/fr/signup_login_fr/" method="POST" >
                 
            <?php endif ?>
                <h2><?php _e("Sign up", "tcn"); ?> <span class="small">/ <?php _e("new subscriber", "tcn"); ?></span></h2>
                
                <div id="signup_errors_secondary" style="display: none" class="g1-message g1-message--error">
                    <div class="g1-inner" id="signup_errors_inner_secondary">
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
                    <input name="city" type="text" placeholder="<?php _e("City", "tcn" ); ?>" />
                    <input name="phone" type="text" placeholder="<?php _e("Phone Number", "tcn" ); ?>" />
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
                    <li><label><input type="checkbox" name="terms_of_use" /><span><?php _e("I have read the", "tcn"); ?> <a href="<?php echo site_url(); ?>/terms-use/"><?php _e("Terms of Use", "tcn"); ?></a> <?php _e("and", "tcn"); ?> <a href="<?php echo site_url(); ?>/privacy-policy/"><?php _e("Privacy Policy", "tcn"); ?></a>.</span></label></li>
                    <?php else: ?>
                     <li><label><input type="checkbox" name="terms_of_use" /><span><?php _e("I have read the", "tcn"); ?> <a href="<?php echo site_url(); ?>/fr/conditions-utilisation/"><?php _e("Terms of Use", "tcn"); ?></a> <?php _e("and", "tcn"); ?> la <a href="<?php echo site_url(); ?>/fr/politique-de-confidentialite/"><?php _e("Privacy Policy", "tcn"); ?></a>.</span></label></li>
                    
                     <?php endif ?>
                </ul>

<!--		<div class="g-recaptcha" data-sitekey="6Ld6F0QUAAAAAGc2TeUbh-ujQyOXu7GBNWPkj9Qg"></div> -->
<!--		<input type="hidden" id="signup_form_secondary_is_human" name="signup_form_is_human" value="" />-->

                <input type="submit" value="<?php _e("Sign up", "tcn"); ?>" />

                <input type="hidden" name="lang" value="<?php echo ICL_LANGUAGE_CODE ?>" />
                <input type="hidden" name="action" value="signup" />
            </form>

            <div id="signup_success_secondary" style="display: none">
                <h2><?php _e("Success!", "tcn"); ?></h2>
                <?php _e("Please login using the form to the left.", "tcn"); ?>
                <?php // _e("We have sent you an email to validate your account. If you did not receive an email to your inbox, check your spam or junk folders and add us to your safe senders list.", "tcn"); ?>
            </div>
        </div>
        </div>
    <?php endif ?>

    <script type="text/javascript">
        <?php if($redirect != ''): ?>
            var redirect = true;
            var redirect_url = '<?php echo $redirect; ?>';
        <?php else: ?>
            var redirect = false;
        <?php endif ?>

        function secondary_validate_forgot_email_password(formData, jqForm, options)
	    {
	        jQuery("#forgot_email_password_errors_secondary").hide();
	    }

	    function secondary_forgot_email_password_callback(responseText, statusText, xhr, $form)
	    {
	        if(responseText['success'] == false)
	        {
                jQuery("#forgot_email_password_errors_secondary").show();
                jQuery("#forgot_email_password_errors_secondary").html(responseText['error']);
            }
            else
            {
                jQuery("#forgot_email_password_form_secondary").clearForm();
                jQuery("#forgot_email_password_errors_secondary").html('<?php _e("We have sent you an email.", "tcn"); ?>');
                jQuery("#forgot_email_password_errors_secondary").show();
            }
	    }

        function secondary_validate_login(formData, jqForm, options)
	    {
	        jQuery("#secondary_login_errors").hide();
	    }

	    function secondary_login_callback(responseText, statusText, xhr, $form)
	    {
	        //alert(statusText);
			if(responseText['success'] == false)
	        {
                jQuery("#secondary_login_errors").show();
                jQuery("#secondary_login_errors_inner").html(responseText['error']);
            }
            else
            {
                jQuery("#login_form_secondary").clearForm();
                if(!redirect)
                {
                    location.reload();
                }
                else
                {
                    location.replace(redirect_url + '?loggedin=true');
                }
            }
	    }

	    function secondary_validate_signup(formData, jqForm, options)
	    {
	        jQuery("#signup_errors_secondary").hide();
	        jQuery("#signup_success_secondary").hide();
	    }

	    function reset_password_show_secondary()
        {
	        jQuery("#forgot_email_password_form_secondary").show();
	        jQuery("#login_rows_secondary").hide();
	        jQuery("#forgot_password_title_secondary").hide();
        }

	    function secondary_signup_callback(responseText, statusText, xhr, $form)
	    {
	        if(responseText['success'] == false)
	        {
                jQuery("#signup_errors_secondary").show();
                jQuery("#signup_errors_inner_secondary").html(responseText['error']);
            }
            else
            {
                jQuery("#signup_form_secondary").clearForm();
                jQuery("#signup_success_secondary").show(1000);
                jQuery("#signup_form_secondary").hide(1000);
            }
	    }

        jQuery(document).ready(function()
	    {
	        jQuery(".secondary-login-area").show();
	        var options = {
                beforeSubmit:  secondary_validate_login,  // pre-submit callback
                success:       secondary_login_callback  // post-submit callback
            };
	        jQuery('#login_form_secondary').ajaxForm(options);

            options = {
                beforeSubmit:  secondary_validate_forgot_email_password,  // pre-submit callback
                success:       secondary_forgot_email_password_callback  // post-submit callback
            };
            jQuery('#forgot_email_password_form_secondary').ajaxForm(options);

            options = {
                beforeSubmit:  secondary_validate_signup,  // pre-submit callback
                success:       secondary_signup_callback  // post-submit callback
            };
            jQuery('#signup_form_secondary').ajaxForm(options);
        });
    </script>

<?php get_footer(); ?>