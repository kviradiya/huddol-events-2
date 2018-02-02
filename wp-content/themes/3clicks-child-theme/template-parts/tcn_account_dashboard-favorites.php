<?php
    $favorites = WeDevs_Favorite_Posts::init();
    $current_user = wp_get_current_user();
    $favorite_posts = $favorites->get_favorites('all', $current_user->ID);
    
    $published = array();
    foreach($favorite_posts as $event)
    {
        $event = get_post($event->post_id);
        if($event)
        {
            array_push($published, $event);
        }
    }
?>
    <?php if(! empty($published)): ?>
        <h3><?php _e("My Favorites", "tcn"); ?></h3>
    <div class="g1-collection g1-collection--grid g1-collection--one-fourth g1-collection--filterable g1-effect-none tcn-events-thumbnails tcn-events-thumbnails-four">
        <ul class="isotope events">
            <?php
                foreach($published as $event)
                {
                    include(locate_template('template-parts/tcn_chunk-one-fourth-event.php'));   
                }
            ?>
        </ul>
    <?php else: ?>
        <h3><?php _e("You have no favorites.", "tcn"); ?></h3>
            
    <?php endif ?>
    </div>
