<li class="g1-collection__item isotope-item event">
    <div class="event-inner">
        <article class="post-<?php echo $event->ID ?> g1_work type-g1_work status-publish format-image has-post-thumbnail g1-brief" id="post-<?php echo $event->ID ?>" itemtype="http://schema.org/BlogPosting" itemscope="">
            <a title="<?php echo $event->post_title ?>" href="<?php echo get_the_permalink($event->ID); ?>">
    	        <div class="event-picture" style="background-image: url('<?php echo get_post_thumbnail($event->ID, 'medium'); ?>');"></div>
            </a>
		
            <div class="g1-nonmedia">
                <div class="g1-inner">
                    <header class="entry-header">
                        <?php if($event->post_type == 'tribe_events'): ?>
                        <?php
                            $category = get_event_category( $event->ID );
                        ?>
                    
                        <?php if($category): ?>
                            <h4 class="event-category">
                                <a title="<?php echo $category->cat_name ?>" href="<?php echo get_category_link($category); ?>"><?php echo $category->cat_name ?></a>
                            </h4>
                        <?php endif; ?>
                    <?php endif ?>
                    
                        <h3 class="event-title">
                            <a title="<?php echo $event->post_title ?>" href="<?php echo get_the_permalink($event->ID); ?>">
                                <?php echo $event->post_title ?>
                            </a>
                        </h3>
                    
                        <p class="entry-meta g1-meta event-date">
                            <time class="entry-date" itemprop="datePublished">
                                <?php echo get_event_date($event); ?>
                            </time>
                        </p>
                    </header>

                    <div class="entry-summary">
                        <p class="event-description">
                            <?php echo generate_excerpt($event); ?>
                        </p>
                    </div>
                </div>
                <div class="g1-01"></div>
            </div>
        </article>
    </div>
</li>