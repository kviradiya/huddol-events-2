<section class="widget widget_search g1-widget--cssclass">
    <header class="tcn-sidebar-title">
        <?php _e("Upcoming Events", "tcn"); ?>
    </header>
    <?php
        $upcoming_events_sidebar = array_slice(get_upcoming_events(), 0, 5);
    ?>
    <ul class="tcn-sidebar-posts">
        <?php foreach($upcoming_events_sidebar as $event): ?>
            <li>
                <a href="<?php echo get_the_permalink($event); ?>">
                    <div class="tcn-image" style="background-image: url('<?php echo get_post_thumbnail($event->ID, 'medium'); ?>');"></div>
                    <div class="post-title"><?php echo $event->post_title ?></div>
                </a>
            </li>
        <?php endforeach ?>
    </ul>
</section>