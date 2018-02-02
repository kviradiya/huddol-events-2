<?php
    $category = get_event_category( $event->ID );
    
    $image_path = get_stylesheet_directory_uri() .'/images/';
?>

<li>
    <h4 class="event-category">
        <a href="<?php get_category_link($category); ?>">
            <?php echo $category->cat_name; ?>
        </a>
    </h4>
    
    <a href="<?php echo get_the_permalink($event->ID); ?>">
        <div class="tcn-image" style="background-image: url('<?php echo get_post_thumbnail($event->ID, 'medium'); ?>');"></div>
    </a>
        
    <h4 class="event-channel">
        <?php if( get_post_meta($event->ID, 'event_meta_type', true) == 0): ?>
              <?php _e("Webinar", "tcn"); ?> -
          <?php endif ?>    
          <?php if( get_post_meta($event->ID, 'event_meta_type', true) == 1): ?>
              <?php _e("Teleconference", "tcn"); ?> - 
          <?php endif ?>
               
          <?php if( get_post_meta($event->ID, 'event_meta_language', true) == 0): ?>
              <?php _e("English", "tcn"); ?>
          <?php endif ?>    
          <?php if( get_post_meta($event->ID, 'event_meta_language', true) == 1): ?>
              <?php _e("French", "tcn"); ?>
          <?php endif ?>
    </h4>
    
    <h3 class="event-title" style="width: 260px;">
        <a href="<?php echo get_the_permalink($event->ID); ?>">
            <?php echo $event->post_title;?>
        </a>
    </h3>
    
    <div class="event-date">
        <?php echo get_event_date($event); ?>
    </div>
    
    <?php 
        $event_meta_phone_number = get_post_meta($event->ID, 'event_meta_phone_number', true);
        $event_meta_conference_id = get_post_meta($event->ID, 'event_meta_conference_id', true);
        $event_meta_webinar_link = get_post_meta($event->ID, 'event_meta_webinar_link', true);
        
        $event_meta_phone_number = override_from_user($event->ID, 'event_meta_phone_number', $event_meta_phone_number);
        $event_meta_conference_id = override_from_user($event->ID, 'event_meta_conference_id', $event_meta_conference_id);
        $event_meta_webinar_link = override_from_user($event->ID, 'event_meta_webinar_link', $event_meta_webinar_link);
    ?>
    
    <div>
    
    
    
    <ul class="tcn-event-numbers center-content" style="top:32px">
        <li style="height: 150px">
        <div>    

            <h3><?php _e("How to Access This Event", "tcn"); ?></h3>

                <p><?php _e("This", "tcn" ); ?> <?php if( get_post_meta($event->ID, 'event_meta_type', true) == 0): ?>
                    <?php _e("webinar", "tcn"); ?>
                <?php endif ?>    
                <?php if( get_post_meta($event->ID, 'event_meta_type', true) == 1): ?>
                    <?php _e("teleconference", "tcn"); ?>
                <?php endif ?> <?php _e("takes place on", "tcn" ); ?> <?php echo get_event_date($event); ?>. <?php _e("To connect at that time, please use the access information below.", "tcn" ); ?>
                </p>
        </div>
        </li>
        <li>
            <?php if( $event_meta_phone_number != '' ): ?>	            
                <img src="<?php echo $image_path ?>phone_icon.png" />
                <div class="content">
                    <span><?php echo $event_meta_phone_number; ?></span> <?php _e("(toll free)", "tcn"); ?>
	                <?php if($event_meta_conference_id != ''): ?>
	                    <br /><?php _e("Conference ID:", "tcn"); ?> <span><?php echo $event_meta_conference_id ?></span>
	                <?php endif ?>
	            </div>
            <?php endif ?>
        </li>
        <li>
            <?php if($event_meta_webinar_link != ''): ?>
                <img src="<?php echo $image_path ?>screen_icon.png" />
                <div class="content">
                    <?php _e("Click", "tcn"); ?> <a href="<?php echo $event_meta_webinar_link; ?>"><?php _e("here", "tcn"); ?></a> <?php _e("to access this webinar.", "tcn" ); ?>
                </div>
            <?php endif ?>
        </li>
    </ul>

    <div class="right-content">
        <?php if(ICL_LANGUAGE_CODE == 'en' ): ?>
            <form action="/register_event/" method="POST">
        <?php else: ?>
             <form action="/register_event_fr/" method="POST">
        <?php endif ?>
        
            <input type="submit" value="<?php _e("Unregister", "tcn"); ?>" class="gray" />
            <input type="hidden" name="post_id" value="<?php echo $event->ID ?>" />
            <input type="hidden" name="redirect" value="<?php echo the_permalink(); ?>/?mode=upcoming" />
            <input type="hidden" name="action" value="unregister" />
        </form>
    </div>
    
    <div class="tcn-sep"></div>
</li>