<?php
    $event_registration = new EventRegistration;
?>

<?php 
    $current_user = wp_get_current_user();
    $recents = $event_registration->get_recent_registered_events($current_user);
?>

<div class="dashboard-recent-events">
    <?php if( !empty($recents)): ?>
        <h3><?php _e("You recently attended the following events", "tcn"); ?></h3>
    <?php endif ?>
    
    <ul class="events-list">
        <?php 
            if(empty($recents))
            {?>
               <h3><?php _e("You have no recent events.", "tcn"); ?></h3>
            <?php 
            }
            else
            {
                foreach($recents as $event)
                {
                    include(locate_template('template-parts/tcn_account_dashboard-recent-event.php'));
                }
            }
        ?>
    </ul>
</div>