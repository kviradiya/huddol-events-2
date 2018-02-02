<?php
    $category = get_event_category( $event->ID );
    if(count($category))
    {
        $category = $category[0];
    }
    else
    {
        $category = 0;
    }
    
    $image_path = get_stylesheet_directory_uri() .'/images/';
?>

<li>
    <h3 class="event-category">
        <a href="<?php get_category_link($category); ?>">
            <?php echo $category->cat_name; ?>
        </a>
    </h3>
    
    <a href="<?php echo get_the_permalink($event->ID); ?>">
        <div class="tcn-image" style="background-image: url('<?php echo get_post_thumbnail($event->ID, 'medium'); ?>');"></div>
    </a>

    <div class="center-content">
        <h4 class="event-title">
            <a href="<?php echo get_the_permalink($event->ID); ?>">
                <?php echo $event->post_title;?>
            </a>
        </h4>
    
        <div class="event-date">
            <?php echo get_event_date($event); ?>
        </div>
    </div>

    <div class="right-content">
        <?php         
            $favorites = WeDevs_Favorite_Posts::init();
            echo $favorites->link_button($event->ID); 
        ?>
    </div>
</li>