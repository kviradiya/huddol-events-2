<section class="widget widget_search g1-widget--cssclass">
    <header class="tcn-sidebar-title">
        <?php _e("Most Viewed", "tcn"); ?>
    </header>
    <?php
        $most_viewed = get_most_viewed_posts(8);
    ?>
    
    <div class="g1-links">
    <ul style="margin-bottom: 0px;">
        <?php
            foreach($most_viewed as $mv)
            { ?>
                <li>
                    <?php $count = get_post_meta($mv->ID, 'wpb_post_views_count', true); ?>
                    <a href="<?php echo get_the_permalink($mv); ?>">
                        <?php echo $mv->post_title; ?> <!-- (<?php echo $count; ?>) -->
                    </a>
                </li>
            <?php
            }
        ?>
    </ul>
</div>
</section>