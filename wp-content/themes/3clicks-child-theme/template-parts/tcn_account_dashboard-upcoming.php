<?php
    $event_registration = new EventRegistration;
    $current_user = wp_get_current_user();
    $recents = $event_registration->get_upcoming_registered_events($current_user);
    $image_path = get_stylesheet_directory_uri() .'/images/';
?>

<div class="dashboard-upcoming-events">
    <div class="trouble-connecting">
        <img src="<?php echo $image_path ?>exclamation_icon.png" />
        <span style="display: inline-block; min-width:240px;"><?php _e("Trouble connecting to an event?", "tcn"); ?> 
            <?php if(ICL_LANGUAGE_CODE == 'en'): ?>
                <a href="<?php echo site_url(); ?>/help/">
            <?php else: ?>
                <a href="<?php echo site_url(); ?>/fr/aide/">
            <?php endif ?>
            <br/>
                <?php _e("Visit our help page", "tcn"); ?>
            </a>
    </div>
    <?php if(count($recents)): ?>
        <h3><?php _e("You are currently registered for the following events", "tcn"); ?></h3>
    
        <ul class="events-list">
            <?php 
                foreach($recents as $event)
                {
                    include(locate_template('template-parts/tcn_account_dashboard-upcoming-event.php'));
                }
            ?>
        </ul>
    <?php else: ?>
        <h3><?php _e("You are not registered for any upcoming events.", "tcn"); ?></h3>
    <?php endif ?>
</div>