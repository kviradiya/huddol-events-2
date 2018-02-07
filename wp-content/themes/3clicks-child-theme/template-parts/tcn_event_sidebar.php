<?php $event_is_over = is_event_over($post->ID); ?>
<?php $user = wp_get_current_user(); ?>
<?php $user_registered = $event_registration->is_user_registered($post->ID, $user->ID); ?>
<?php $can_user_register = $event_registration->can_user_register($post->ID, $user->ID); ?>
<?php $is_user_logged_in = is_user_logged_in(); ?>
<?php $is_event_full = $event_registration->is_event_full($post->ID); ?>

<!--[if IE]>
<style>/* this style block is for IE */
.event-details-sidebar .event-details-register button.register .hd
{
    height: 28px;
}
</style>
<![endif]-->

<?php
if($event_is_over): ?>
    <?php $recording = get_post_meta($post->ID, 'event_meta_recording', true);
    if($recording === ''):?>
        <div class="event-details-register">
            <?php if(ICL_LANGUAGE_CODE == 'en'): ?>
            <button class="register no-access" style="background: black; color:#fff;">
            <?php else: ?>
            <button class="register no-access" style="background: black; color:#fff; height: 140px;">
            <?php endif ?>
            <?php if(ICL_LANGUAGE_CODE == 'fr'): ?>
                <span class="sub" style="display: block; margin-top: -25px; height: 40px;"><?php _e("Event recording being processed", "tcn"); ?></span>
        <?php else: ?>
                <span class="sub" style="display: block;"><?php _e("Event recording being processed", "tcn"); ?></span>
            <?php endif ?>
                <span class="hd" style="display: block; font-size: 24px;"><?php _e("Please Check Back", "tcn"); ?></span>
            </button>
        </div>
    <?php endif ?>
    
    <?php if(is_user_logged_in()): ?>
        
		<?php /* print_cta(); */?>
        
        <div class="event-details-share">
            <header>
                <?php _e("Share this", "tcn"); ?>
            </header>

            <div class="buttons">
                <?php echo do_shortcode('[ssba]'); ?>
            </div>
        </div>
        
        <div class="event-details-rate">
            <header>
                <?php _e("Rate this event", "tcn"); ?>
            </header>
            <div class="rating">
                <?php the_ratings(); ?>
            </div>
            
            <div class="add-to-favorites">
                <?php echo $favorites->link_button($post->ID); ?>
            </div>
        </div>
    <?php else: ?>
    	
		<?php print_cta(); ?>
        
        <div class="event-details-share">
            <header>
                <?php _e("Share this", "tcn"); ?>
            </header>

            <div class="buttons">
                <?php echo do_shortcode('[ssba]'); ?>
            </div>
        </div>
        
        <div class="event-details-rate">
            <header>
                <?php _e("Rate this event", "tcn"); ?>
            </header>
            <div class="rating">
                <?php the_ratings(); ?>
            </div>
            
            <div class="add-to-favorites" style="margin-bottom: 6px;">
                <a href="#" class="favorites-logged-out">
                    <?php if(ICL_LANGUAGE_CODE == 'en'): ?>
                        <img src="<?php echo $image_path ?>favorites_banner.png" alt="Add to my favorites" />
                    <?php else: ?>
                        <img src="<?php echo $image_path ?>favorites_banner_fr.png" alt="Ajouter à mes favoris" />
                    <?php endif ?>
                </a>
            </div>
            
            <div class="italic-message">
                <?php // $actual_link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']; ?>
                <?php $actual_link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>
                <?php if(ICL_LANGUAGE_CODE == 'en'): ?>
                    <a href="<?php echo site_url(); ?>/login/?redirect=<?php echo $actual_link; ?>"><?php _e("Log in", "tcn" ); ?></a> <?php _e("or", "tcn"); ?> <a href="<?php echo site_url(); ?>/login/?redirect=<?php echo $actual_link; ?>"><?php _e("Sign up", "tcn"); ?></a> <?php _e("to add to favorites.", "tcn"); ?>
                <?php else: ?>
                    <a href="<?php echo site_url(); ?>/fr/login-fr/?redirect=<?php echo $actual_link; ?>">Inscrivez-vous</a> pour l'ajouter à vos favoris.                    
                <?php endif ?>
            </div>
        </div>
    <?php endif ?>
<?php else: ?>
    <div class="event-details-register">
        <?php if($is_user_logged_in): ?>
            <?php if($event_registration->is_user_registered($post_id, $user->ID)): ?>
               
               <?php 
				print_cta();
				?>
                <?php if(ICL_LANGUAGE_CODE == 'en' ): ?>
                    <form action="/register_event/" method="POST">

                <?php else: ?>
                    <form action="/fr/register_event_fr/" method="POST">

                <?php endif ?>
                    <button type="submit" class="unregister"><?php _e("Unregister", "tcn"); ?></button>
                    <input type="hidden" name="post_id" value="<?php echo $post->ID ?>" />
                    <input type="hidden" name="redirect" value="<?php echo the_permalink(); ?>" />
                    <input type="hidden" name="action" value="unregister" />
                </form>
                <div class="signupMsg"><span class="title"><?php _e("You are registered!", "tcn"); ?></span><br>
<?php _e("A confirmation email has been sent. Please check your spam or junk folders as well. The connection details are also listed below.", "tcn"); ?></div>
            <?php else: ?>
                <?php if($is_event_full): ?>
                    <button class="register no-access" style="background: black; color:#fff;">
                        <span class="sub" style="display: block;"><?php _e("Registration closed. This event is", "tcn"); ?></span>
                        <span class="hd" style="display: block;"><?php _e("Fully Booked", "tcn"); ?></span>
                    </button>
                <?php else: ?>
                    <?php if($can_user_register): ?>
                        <?php if($event_registration->is_payment_processing($post_id, $user->ID)): ?>
                            <p><?php _e("We are currenly processing your payment for this event. Please try refreshing the page momentarily.", "tcn"); ?>
                            </p>
                            
                        <?php else: ?>
                            <?php if(ICL_LANGUAGE_CODE == 'en' ): ?>
                                <form action="/register_event/" method="POST">

                            <?php else: ?>
                                <form action="/fr/register_event_fr/" method="POST">

                            <?php endif ?>
                                <button type="submit" class="register logged-in">
                                    <?php if(ICL_LANGUAGE_CODE == 'en'): ?>
                                        <span class="hd" style="display: block"><?php _e("Register", "tcn"); ?></span>
                                    <?php else : ?>
                                        <span class="hd" style="display: block">S'inscrire</span>
                                    <?php endif ?>
                                    <span class="sub" style="display: block"><?php _e("for this event", "tcn"); ?></span>
                                    <span class="price" style="display: block"><?php echo $event_registration->get_event_price_display($post->ID); ?></span>
                                </button>
                                <input type="hidden" name="post_id" value="<?php echo $post->ID ?>" />
                                <input type="hidden" name="redirect" value="<?php echo the_permalink(); ?>" />
                                <input type="hidden" name="action" value="register" />
                            </form>
                        <?php endif ?>
                    <?php else: ?>
                        <button type="submit" class="register no-access logged-in">
                            <?php if(ICL_LANGUAGE_CODE == 'en'): ?>
                                
                                <span class="hd" style="display: block"><?php _e("Register", "tcn"); ?></span>
                            <?php else : ?>
                                <span class="hd" style="display: block">S'inscrire</span>
                            <?php endif ?>
                            <span class="sub" style="display: block"><?php _e("for this event", "tcn"); ?></span>
                            <span class="price" style="display: block"><?php echo $event_registration->get_event_price_display($post->ID); ?></span>
                        </button>

                        <div id="access-errors" style="display: none">
                            <p>
                                <?php _e("You can't register for this event.", "tcn"); ?>
                            </p>

                            <?php if(! $event_registration->can_user_province_register($post->ID, $user->ID)): ?>
                                <p>
                                    <?php _e("Access for residents of", "tcn"); ?> <?php echo $event_registration->get_location_restriction_string($post->ID); ?> <?php _e("only", "tcn"); ?>
                                </p>
                            <?php endif ?>
                        </div>
                    <?php endif ?>
                <?php endif ?>
            <?php endif ?>
        <?php else: ?>
            <button class="register logged-out">
                <span class="price" style="display: block"><?php echo $event_registration->get_event_price_display($post->ID); ?></span>
				<?php if(ICL_LANGUAGE_CODE == 'en'): ?>
					<span class="sub"><?php _e('Want to ', 'tnc')?></span>
                    <span class="hd"><?php _e("Register", "tcn"); ?></span>
                <?php else : ?>
                   <span class="sub"><?php _e('Want to ', 'tnc')?></span>
                   <span class="hd">inscrire</span>
                <?php endif ?>
                <span class="sub" style="display: block"><?php _e("for this event?", "tcn"); ?></span>
				
            </button>
			
			<div class="warning bold">
				<?php if(ICL_LANGUAGE_CODE == 'en'): ?>
				
				<?php _e('To register, you must be a', 'tnc')?> <a href="<?php echo site_url(); ?>/login/?redirect=<?php echo $actual_link; ?>" class="orange bold"><?php _e('subscriber to TCN', 'tnc') ?></a>.
                <?php else : ?>
                <?php _e('To register, you must be a', 'tnc')?> <a href="<?php echo site_url(); ?>/fr/login-fr/?redirect=<?php echo $actual_link; ?>" class="orange bold"><?php _e('subscriber to TCN', 'tnc') ?></a>.
                 <?php endif ?>
			</div>

            <div class="italic-message no_more_italic">
			
                <?php // $actual_link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']; ?>
                <?php $actual_link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>
				
				
				<h5 class="steps"><?php _e('Step 1', 'tnc')?>:</h5>
				
                <?php if(ICL_LANGUAGE_CODE == 'en'): ?>
					<a href="<?php echo site_url(); ?>/login/?redirect=<?php echo $actual_link; ?>" class="orange_btn_en g1-button g1-button--small g1-button--solid g1-button--standard "><?php _e("Sign up", "tcn"); ?></a> 
					<span class="spacer size14px"><?php _e("or", "tcn"); ?></span>
                    <a href="<?php echo site_url(); ?>/login/?redirect=<?php echo $actual_link; ?>" class="orange_btn_en g1-button g1-button--small g1-button--solid g1-button--standard "><?php _e("Log in", "tcn" ); ?></a> 
					
					<?php //_e("to register.", "tcn"); ?>
					
                <?php else: ?>
					<a href="<?php echo site_url(); ?>/fr/login-fr/?redirect=<?php echo $actual_link; ?>" class="orange_btn_fr g1-button g1-button--small g1-button--solid g1-button--standard ">S'inscrire</a> 
					<span class="spacer size14px"><?php _e("or", "tcn"); ?></span>
                    <a href="<?php echo site_url(); ?>/fr/login-fr/?redirect=<?php echo $actual_link; ?>" class="orange_btn_fr g1-button g1-button--small g1-button--solid g1-button--standard "><?php _e("Log in", "tcn" ); ?></a> 
                    
                <?php endif ?>
				
				<h5 class="steps"><?php _e('Step 2', 'tnc')?>:</h5>
				<p class="size16px bold"><?php _e('Return to this page to register after logging in.')?></p>
				
            </div>
        <?php endif ?>
    </div>
    
    <?php if($user_registered && $is_user_logged_in): ?>
        <div class="event-details-how-to-access">
            <header>
                <?php _e("How to Access This Event", "tcn"); ?>
            </header>
            <div class="content">
                <p><?php _e("This", "tcn" ); ?> <?php if( get_post_meta($post_id, 'event_meta_type', true) == 0): ?>
                    <?php _e("webinar", "tcn"); ?>
                <?php endif ?>    
                <?php if( get_post_meta($post_id, 'event_meta_type', true) == 1): ?>
                    <?php _e("teleconference", "tcn"); ?>
                <?php endif ?> <?php _e("takes place on", "tcn" ); ?> <?php echo get_event_date($post); ?>. <?php _e("To connect at that time, please use the access information below.", "tcn" ); ?>
                </p>
            </div>
            <?php 
                $event_meta_phone_number = get_post_meta($post->ID, 'event_meta_phone_number', true);
                $event_meta_conference_id = get_post_meta($post->ID, 'event_meta_conference_id', true);
                $event_meta_webinar_link = get_post_meta($post->ID, 'event_meta_webinar_link', true);

                $event_meta_phone_number = override_from_user($post->ID, 'event_meta_phone_number', $event_meta_phone_number);
                $event_meta_conference_id = override_from_user($post->ID, 'event_meta_conference_id', $event_meta_conference_id);
                $event_meta_webinar_link = override_from_user($post->ID, 'event_meta_webinar_link', $event_meta_webinar_link);
            ?>

            <div>
                <ul class="tcn-event-numbers">
                
                        <?php if( $event_meta_phone_number != '' ): ?>	      
                            <li>      
        	                <img src="<?php echo $image_path ?>phone_icon.png" />
        	                <div class="content">
        	                    <span><?php echo $event_meta_phone_number; ?></span> <?php _e("(toll free)", "tcn"); ?>
            	                <?php if($event_meta_conference_id != ''): ?>
            	                    <br /><?php _e("Conference ID:", "tcn"); ?> <span><?php echo $event_meta_conference_id ?></span>
            	                <?php endif ?>
            	            </div>
            	            </li>
                        <?php endif ?>
                
                    <li>
                        <?php if($event_meta_webinar_link != ''): ?>
        	                <img src="<?php echo $image_path ?>screen_icon.png" />
        	                <div class="content">
                                <?php _e("Click", "tcn"); ?> <a href="<?php echo $event_meta_webinar_link; ?>"><?php _e("here", "tcn"); ?></a> <?php _e("to access this webinar.", "tcn"); ?>
                            </div>
                        <?php endif ?>
                    </li>
                </ul>
            </div>
            
            <div style="clear: both"></div>
            <div class="italic-message">
                <?php _e("Trouble connecting?", "tcn"); ?> <a href="<?php echo site_url(); ?>/help/"><?php _e("Visit our help page", "tcn"); ?></a>
            </div>
        </div>
    <?php endif ?>
    
    <div class="event-details-share">
        <header>
            <?php _e("Share this", "tcn"); ?>
        </header>

        <div class="buttons">
            <?php echo do_shortcode('[ssba]'); ?>
        </div>
    </div>
<?php endif ?>
