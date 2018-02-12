<?php 
/*
 * titles.php - Custom functions related to page and post titles. 
 *
 * Note: Moved this outside functions.php to help alleviate development conflicts editing large functions.php file
 *
 */


/**
 * Modify wp_title to print a neat <title> tag based on what is being viewed.
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string The filtered title.
 */
function tcn_wp_title( $title, $sep ) {
   
    if( is_singular( 'tribe_events' ) ) {
        if(empty($sep)) {
            $sep = '-';
        }

        // Append event start date
        $start = get_post_meta( get_the_ID(), '_EventStartDate', true);
        if( !empty($start) ) {
            $title .= sprintf( ' %s %s', trim($sep), date('F j, Y', strtotime($start))); 
        }

        // Prepend event type
        $type = get_post_meta( get_the_ID(), 'event_meta_type', true) ? __('Teleconference', 'tcn') : __('Webinar', 'tcn');
        $title = sprintf('%s: %s', $type, $title);
       
    }

    // Remove auto-appended site title if title is > 55 characters
    if( is_single() && strlen( $title ) > 55) {
        $title = preg_replace( '/(?: \W{1}){0,1} (?:Huddol Events|Huddol Événements)$/i', '', $title );
    }

    return $title;
}
add_filter( 'wp_title', 'tcn_wp_title', 20, 2 );
