<?php
    $category = get_event_category( $event->ID );
    $image_path = get_stylesheet_directory_uri() .'/images/';
?>

<div class="tcn-events-thumbnails">
    <h3>
        <a href="<?php get_category_link($category); ?>">
            <?php echo $category->cat_name; ?>
        </a>
    </h3>
    
     <div class="event-picture" style="background-image: url('<?php echo get_post_thumbnail($post->ID, 'medium'); ?>');"></div>
    <h4 class="channel">
        <a href="<?php echo get_the_permalink($post->ID); ?>">
            <?php echo $event->post_title;?>
        </a>
    </h4>
    <p>
        <?php echo get_event_date($event); ?>
    </p>
</div>