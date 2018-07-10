<?php $event_is_over = is_event_over( $post->ID ); ?>
<?php $user = wp_get_current_user(); ?>
<?php $user_registered = $event_registration->is_user_registered( $post->ID, $user->ID ); ?>
<?php $can_user_register = $event_registration->can_user_register( $post->ID, $user->ID ); ?>
<?php $is_user_logged_in = is_user_logged_in(); ?>
<?php $is_event_full = $event_registration->is_event_full( $post->ID ); ?>
<?php $actual_link = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

$actual_link = add_query_arg( [
	'event_id' => $post->ID,
], $actual_link );

?>

<!--[if IE]>
<style>/* this style block is for IE */
.event-details-sidebar .event-details-register button.register .hd {
    height: 28px;
}
</style>
<![endif]-->

<?php
if ( $event_is_over ): ?>
	<?php $recording = get_post_meta( $post->ID, 'event_meta_recording', TRUE );
	if ( $recording === '' ):?>
        <div class="event-details-register">
			<?php if ( ICL_LANGUAGE_CODE == 'en' ): ?>
            <button class="register no-access"
                    style="background: black; color:#fff;">
				<?php else: ?>
                <button class="register no-access"
                        style="background: black; color:#fff; height: 140px;">
					<?php endif ?>
					<?php if ( ICL_LANGUAGE_CODE == 'fr' ): ?>
                        <span class="sub"
                              style="display: block; margin-top: -25px; height: 40px;"><?php _e( "Event recording being processed", "tcn" ); ?></span>
					<?php else: ?>
                        <span class="sub"
                              style="display: block;"><?php _e( "Event recording being processed", "tcn" ); ?></span>
					<?php endif ?>
                    <span class="hd"
                          style="display: block; font-size: 24px;"><?php _e( "Please Check Back", "tcn" ); ?></span>
                </button>
        </div>
	<?php endif ?>

	<?php if ( is_user_logged_in() ): ?>

        <div class="event-details-share">
            <header>
				<?php _e( "Share this", "tcn" ); ?>
            </header>

            <div class="buttons">
				<?php echo do_shortcode( '[ssba]' ); ?>
            </div>
        </div>

	<?php else: ?>

        <div class="event-details-share">
            <header>
				<?php _e( "Share this", "tcn" ); ?>
            </header>

            <div class="buttons">
				<?php echo do_shortcode( '[ssba]' ); ?>
            </div>
        </div>

	<?php endif ?>
<?php else: ?>
<div class="event-details-register">
	<?php if ( is_user_logged_in() ): ?>
	<?php if ( $event_registration->is_user_registered( $post_id, $user->ID ) ): ?>

	<?php if ( ICL_LANGUAGE_CODE == 'en' ): ?>
    <form action="/register_event/" method="POST">

		<?php else: ?>
        <form action="/fr/register_event_fr/" method="POST">

			<?php endif ?>
            <button type="submit"
                    class="unregister"><?php _e( "Unregister", "tcn" ); ?></button>
            <input type="hidden" name="post_id"
                   value="<?php echo $post->ID ?>"/>
            <input type="hidden" name="redirect"
                   value="<?php echo the_permalink(); ?>"/>
            <input type="hidden" name="action" value="unregister"/>
        </form>
        <div class="signupMsg"><span
                    class="title"><?php _e( "You are registered!", "tcn" ); ?></span><br>
			<?php _e( "A confirmation email has been sent. Please check your spam or junk folders as well. The connection details are also listed below.", "tcn" ); ?>
        </div>
		<?php else: ?>
		<?php if ( $is_event_full ): ?>
            <button class="register no-access"
                    style="background: black; color:#fff;">
                <span class="sub"
                      style="display: block;"><?php _e( "Registration closed. This event is", "tcn" ); ?></span>
                <span class="hd"
                      style="display: block;"><?php _e( "Fully Booked", "tcn" ); ?></span>
            </button>
		<?php else: ?>
		<?php if ( $can_user_register ): ?>
		<?php if ( $event_registration->is_payment_processing( $post_id, $user->ID ) ): ?>
            <p><?php _e( "We are currenly processing your payment for this event. Please try refreshing the page momentarily.", "tcn" ); ?>
            </p>

		<?php else: ?>
		<?php if ( ICL_LANGUAGE_CODE == 'en' ): ?>
        <form action="/register_event/" method="POST">

			<?php else: ?>
            <form action="/fr/register_event_fr/" method="POST">

				<?php endif ?>
                <a type="submit" class="register logged-in">
					<?php if ( ICL_LANGUAGE_CODE == 'en' ): ?>
                        <span class="hd"
                              style="display: block"><?php _e( "Register", "tcn" ); ?></span>
					<?php else : ?>
                        <span class="hd"
                              style="display: block">S'inscrire</span>
					<?php endif ?>
                    <span class="sub"
                          style="display: block"><?php _e( "for this event", "tcn" ); ?></span>
                    <span class="price"
                          style="display: block"><?php echo $event_registration->get_event_price_display( $post->ID ); ?></span>
                </a>
                <input type="hidden" name="post_id"
                       value="<?php echo $post->ID ?>"/>
                <input type="hidden" name="redirect"
                       value="<?php echo the_permalink(); ?>"/>
                <input type="hidden" name="action" value="register"/>
            </form>
			<?php endif ?>
			<?php else: ?>
                <a type="submit" class="register no-access logged-in">
				<?php if ( ICL_LANGUAGE_CODE == 'en' ): ?>
                    <span class="sub"><?php _e( 'Click to ', 'tnc' ) ?></span>
                    <span class="hd"><?php _e( "Register", "tcn" ); ?></span>
				<?php else : ?>
                    <span class="sub"><?php _e( 'Cliquez pour vous ', 'tnc' ) ?></span>
                    <span class="hd">Inscrire</span>
                    <span class="price"
                          style="display: block"><?php echo $event_registration->get_event_price_display( $post->ID ); ?></span>
                    </a>

                    <div id="access-errors" style="display: none">
                        <p>
							<?php _e( "You can't register for this event.", "tcn" ); ?>
                        </p>

						<?php if ( ! $event_registration->can_user_province_register( $post->ID, $user->ID ) ): ?>
                            <p>
								<?php _e( "Access for residents of", "tcn" ); ?><?php echo $event_registration->get_location_restriction_string( $post->ID ); ?><?php _e( "only", "tcn" ); ?>
                            </p>
						<?php endif ?>
                    </div>
				<?php endif ?>
			<?php endif ?>
			<?php endif ?>
			<?php else: ?>
            <a class="register logged-out"
               href="<?php echo site_url(); ?>/login/?redirect=<?php echo $actual_link; ?>">
                <span class="price"
                      style="display: block"><?php echo $event_registration->get_event_price_display( $post->ID ); ?></span>
				<?php if ( ICL_LANGUAGE_CODE == 'en' ): ?>
                    <span class="sub"><?php _e( 'Click to ', 'tnc' ) ?></span>
                    <span class="hd"><?php _e( "Register", "tcn" ); ?></span>
				<?php else : ?>
                    <span class="sub"><?php _e( 'Cliquez pour vous ', 'tnc' ) ?></span>
                    <span class="hd">Inscrire</span>
				<?php endif ?>
            </a>

            <div class="warning bold">
				<?php if ( ICL_LANGUAGE_CODE == 'en' ): ?>

					<?php _e( 'You will be redirected back to this page after logging in or', 'tnc' ) ?>
                    <a href="<?php echo site_url(); ?>/login/?redirect=<?php echo $actual_link; ?>"
                       class="blue bold"><?php _e( 'signing up as a member', 'tnc' ) ?></a>.
				<?php else : ?>
					<?php _e( 'Vous serez redirigé vers cette page suite à votre connexion ou ', 'tnc' ) ?>
                    <a href="<?php echo site_url(); ?>/fr/login-fr/?redirect=<?php echo $actual_link; ?>"
                       class="blue bold"><?php _e( 'inscription en tant que membre', 'tnc' ) ?></a>.
				<?php endif ?>
            </div>

            <!--<div class="italic-message no_more_italic">
                <?php if ( ICL_LANGUAGE_CODE == 'en' ): ?>
					<a href="<?php echo site_url(); ?>/login/?redirect=<?php echo $actual_link; ?>" class="orange_btn_en g1-button g1-button--small g1-button--solid g1-button--standard "><?php _e( "Sign up", "tcn" ); ?></a>
					<span class="spacer size14px"><?php _e( "or", "tcn" ); ?></span>
                    <a href="<?php echo site_url(); ?>/login/?redirect=<?php echo $actual_link; ?>" class="orange_btn_en g1-button g1-button--small g1-button--solid g1-button--standard "><?php _e( "Log in", "tcn" ); ?></a>
					
					<?php //_e("to register.", "tcn"); ?>
					
                <?php else: ?>
					<a href="<?php echo site_url(); ?>/fr/login-fr/?redirect=<?php echo $actual_link; ?>" class="orange_btn_fr g1-button g1-button--small g1-button--solid g1-button--standard ">S'inscrire</a> 
					<span class="spacer size14px"><?php _e( "or", "tcn" ); ?></span>
                    <a href="<?php echo site_url(); ?>/fr/login-fr/?redirect=<?php echo $actual_link; ?>" class="orange_btn_fr g1-button g1-button--small g1-button--solid g1-button--standard "><?php _e( "Log in", "tcn" ); ?></a>
                    
                <?php endif ?>
				<p class="size16px bold"><?php _e( 'Return to this page to register after logging in.' ) ?></p>
				
            </div>-->
			<?php endif ?>
</div>

<?php if ( $user_registered && $is_user_logged_in ): ?>
    <div class="event-details-how-to-access">
        <header>
			<?php _e( "How to Access This Event", "tcn" ); ?>
        </header>
        <div class="content">
            <p><?php _e( "This", "tcn" ); ?> <?php if ( get_post_meta( $post_id, 'event_meta_type', TRUE ) == 0 ): ?>
					<?php _e( "webinar", "tcn" ); ?>
				<?php endif ?>
				<?php if ( get_post_meta( $post_id, 'event_meta_type', TRUE ) == 1 ): ?>
					<?php _e( "teleconference", "tcn" ); ?>
				<?php endif ?> <?php _e( "takes place on", "tcn" ); ?> <?php echo get_event_date( $post ); ?>
                . <?php _e( "To connect at that time, please use the access information below.", "tcn" ); ?>
            </p>
        </div>
		<?php
		$event_meta_phone_number  = get_post_meta( $post->ID, 'event_meta_phone_number', TRUE );
		$event_meta_conference_id = get_post_meta( $post->ID, 'event_meta_conference_id', TRUE );
		$event_meta_webinar_link  = get_post_meta( $post->ID, 'event_meta_webinar_link', TRUE );

		$event_meta_phone_number  = override_from_user( $post->ID, 'event_meta_phone_number', $event_meta_phone_number );
		$event_meta_conference_id = override_from_user( $post->ID, 'event_meta_conference_id', $event_meta_conference_id );
		$event_meta_webinar_link  = override_from_user( $post->ID, 'event_meta_webinar_link', $event_meta_webinar_link );
		?>

        <div>
            <ul class="tcn-event-numbers">

				<?php if ( $event_meta_phone_number != '' ): ?>
                    <li>
                        <img src="<?php echo $image_path ?>phone_icon.png"/>
                        <div class="content">
                            <span><?php echo $event_meta_phone_number; ?></span> <?php _e( "(toll free)", "tcn" ); ?>
							<?php if ( $event_meta_conference_id != '' ): ?>
                                <br/><?php _e( "Conference ID:", "tcn" ); ?>
                                <span><?php echo $event_meta_conference_id ?></span>
							<?php endif ?>
                        </div>
                    </li>
				<?php endif ?>

                <li>
					<?php if ( $event_meta_webinar_link != '' ): ?>
                        <img src="<?php echo $image_path ?>screen_icon.png"/>
                        <div class="content">
							<?php _e( "Click", "tcn" ); ?> <a
                                    href="<?php echo $event_meta_webinar_link; ?>"><?php _e( "here", "tcn" ); ?></a> <?php _e( "to access this webinar.", "tcn" ); ?>
                        </div>
					<?php endif ?>
                </li>
            </ul>
        </div>

        <div style="clear: both"></div>
        <div class="italic-message">
			<?php _e( "Trouble connecting?", "tcn" ); ?> <a
                    href="<?php echo site_url(); ?>/help/"><?php _e( "Visit our help page", "tcn" ); ?></a>
        </div>
    </div>
<?php endif ?>

    <div class="event-details-share">
        <header>
			<?php _e( "Share this", "tcn" ); ?>
        </header>

        <div class="buttons">
			<?php echo do_shortcode( '[ssba]' ); ?>
        </div>
    </div>
<?php endif ?>
