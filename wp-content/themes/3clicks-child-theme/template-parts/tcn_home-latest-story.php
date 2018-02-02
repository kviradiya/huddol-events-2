<li class="g1-collection__item filter-305 filter-308 filter-304 isotope-item">
    <article class="post-<?php echo $post->ID ?> g1_work type-g1_work status-publish format-image has-post-thumbnail g1-brief" id="post-<?php echo $post->ID ?>" itemtype="http://schema.org/BlogPosting" itemscope="">
        
		<div class="g1-nonmedia">
            <div class="g1-inner">
                <div class="story-top">
                    <a class="" href="<?php echo get_the_permalink($post->ID); ?>">
                        <div class="story-picture" style="background-image: url('<?php echo get_post_thumbnail($post->ID, 'medium'); ?>');"></div>
        			</a>
                    <header class="entry-header">
                        <h3 class="story-category">
                            <?php if($post->post_author == 1): ?>
                                <?php _e("News", "tcn"); ?>
                            <?php else: ?>
                                <?php _e("Blog", "tcn"); ?>
                            <?php endif ?>
                        </h3>
                        
                        <h3 class="story-title">
                            <a title="<?php echo $post->post_title ?>" href="<?php echo get_the_permalink($post->ID); ?>">
                                <?php echo $post->post_title ?>
                            </a>
                        </h3>           
                                             
                        <?php if($post->post_author != 1): ?>
                        <span class="entry-meta">
                            <?php $post_author = get_userdata($post->post_author); ?>
                            <a href="<?php echo get_author_posts_url($post->post_author); ?>"><?php echo $post_author->display_name; ?></a>
                        </span>
                        <?php endif ?>
                        
                        <p class="entry-meta g1-meta story-date">
                            <time class="entry-date" datetime="<?php echo $post->post_date ?>" itemprop="datePublished"><?php echo translate_months(get_the_date('', $post->ID), ICL_LANGUAGE_CODE); ?></time>
                        </p>
                    </header>
                </div>

                <div class="entry-summary">
                    <p class="story-description">
                        <?php echo generate_excerpt($post); ?>
                    </p>
                </div>
                
                <footer class="entry-footer">
                
                </footer>
            </div>
            <div class="g1-01"></div>
        </div>
    </article><!-- .post-XX -->        
</li>