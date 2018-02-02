<?php if( ! is_event_private($event->ID)): ?>
    <li class="g1-collection__item isotope-item event">
        <div class="event-inner">
            <article class="post-<?php echo $event->ID ?> post type-post status-publish format-standard has-post-thumbnail category-gallery-4-cols-pc category-grid-3-cols-pc category-grid-2-cols-side-left-gallery-pc category-grid-2-cols-side-right-gallery-pc category-grid-1-col-side-right-pc category-grid-2-cols-side-right-pc category-masonry-3-cols-pc category-grid-2-cols-side-left-pc category-grid-1-col-side-left-pc category-grid-1-col-pc tag-gallery-3-cols-filter-pt tag-grid-2-cols-side-right-filter-pt tag-grid-4-cols-filter-pt g1-brief" id="post-<?php echo $event->ID ?>" itemtype="http://schema.org/BlogPosting" itemscope="">
                <figure class="entry-featured-media">
                    <a class="g1-frame g1-frame--none g1-frame--inherit g1-frame--center " href="<?php echo get_permalink($event->ID); ?>">
                        <div class="event-picture" style="background-image: url('<?php echo get_post_thumbnail($event->ID, 'medium'); ?>');"></div>
                    </a>
                </figure>
                <div class="g1-nonmedia">
                    <div class="g1-inner">
                        <header class="entry-header">
                            <?php
                                $category = get_event_category( $event->ID );
                            ?>
                    
                            <?php if($category): ?>
                                <h4 class="event-category">
                                    <a title="<?php echo $category->cat_name ?>" href="<?php echo get_category_link($category); ?>"><?php echo $category->cat_name ?></a>
                                </h4>
                            <?php endif; ?>
                    
                            <h3 class="event-title">
                                <a title="<?php echo $event->post_title ?>" href="<?php echo get_permalink($event->ID); ?>"><?php echo $event->post_title ?></a>
                            </h3>
                    
                            <?php if($event->post_type == 'tribe_events'): ?>
                                <p class="entry-meta g1-meta event-date">
                                    <time class="entry-date" datetime="<?php echo get_event_date_iso($event); ?>">
                                        <?php echo get_event_date($event); ?>
                                    </time>                                        
                                </p>
                            <?php endif ?>
                        </header>
                        <div class="entry-summary">
                            <p class="event-description">
                                <?php echo generate_excerpt($event); ?>
                            </p>
                        </div>
                    </div>
                    <footer class="entry-footer">
                        <div class="gradient"></div>
                        <div class="button">
                            <a href="<?php echo get_permalink($event->ID); ?>" class="g1-button g1-button--small g1-button--solid g1-button--standard ">
                                <?php if(is_event_over($event->ID)): ?>
                                    <?php _e("More", "tcn"); ?>
                            <?php else: ?>
                                    <?php if(ICL_LANGUAGE_CODE == 'en'): ?>
                                        <?php _e("Register", "tcn"); ?>
                                <?php else: ?>
                                    S'inscrire
                                <?php endif ?>
                            <?php endif ?>
                            </a>                                   
                        </div>
                    </footer>
                    <div class="g1-01"></div>
                </div>
            </article>
        </div>      
    </li>
<?php endif ?>