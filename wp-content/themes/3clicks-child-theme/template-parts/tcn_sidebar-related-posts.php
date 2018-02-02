<?php $related_events = get_suggested_posts($post->ID); ?>

<?php if(count($related_events)): ?>
    <section class="widget widget_search g1-widget--cssclass">
        <header class="tcn-sidebar-title">
            <?php _e("Related Posts", "tcn"); ?>
        </header>
    
        <ul class="tcn-sidebar-posts">
        
        
            <?php foreach($related_events as $sugg_post): ?>
                <li>
                    <a href="<?php echo get_the_permalink($sugg_post); ?>">
                        <div class="tcn-image" style="background-image: url('<?php echo get_post_thumbnail($sugg_post->ID, 'medium'); ?>');"></div>
                        <div class="post-title"><?php echo $sugg_post->post_title ?></div>
                    </a>
                </li>
            <?php endforeach ?>
        </ul>
    </section>
<?php endif ?>