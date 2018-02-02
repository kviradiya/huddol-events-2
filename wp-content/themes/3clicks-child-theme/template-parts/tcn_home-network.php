<?php
    $network = new TCNNetwork;
    $events = $network->get_upcoming_network_events(wp_get_current_user());
    include(locate_template('template-parts/tcn_chunk-events.php'));
?>
    
    
    
    