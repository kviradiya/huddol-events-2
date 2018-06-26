<?php
global $category_id;
$category = get_category( $category_id );
?>

<li class="g1-collection__item filter-305 filter-308 filter-304 isotope-item">
    <article
            class="post-<?php echo $post->ID ?> g1_work type-g1_work status-publish format-image has-post-thumbnail g1-brief"
            id="post-<?php echo $post->ID ?>"
            itemtype="http://schema.org/BlogPosting" itemscope="">
        <a class="g1-frame g1-frame--none g1-frame--inherit g1-frame--center "
           href="<?php echo get_the_permalink( $post->ID ); ?>">
            <div class="channel-picture"
                 style="background-image: url('<?php echo get_post_thumbnail( $post->ID, 'medium' ); ?>');"></div>
        </a>

        <div class="g1-nonmedia">
            <div class="g1-inner">
                <header class="entry-header">
					<?php if ( $category ): ?>
                        <h3 class="channel-category">
                            <a href="<?php echo get_category_link( $category ); ?>">
								<?php echo $category->cat_name ?>
                            </a>
                        </h3>
					<?php endif ?>

                    <h3 class="channel-title">
                        <a href="<?php echo get_the_permalink( $post->ID ); ?>">
							<?php echo $post->post_title ?>
                        </a>
                    </h3>
                    <!--p class="entry-meta g1-meta channel-date">
                        <time class="entry-date" datetime="<?php echo $post->post_date ?>" itemprop="datePublished"><?php echo get_event_date( $post ); ?></time>
                    </p-->
                </header>

                <div class="entry-summary">
                    <p class="channel-description">
						<?php echo generate_excerpt( $post ); ?>
                    </p>
                </div>

                <footer class="entry-footer">

                </footer>
            </div>
            <div class="g1-01"></div>
        </div>
    </article><!-- .post-XX -->
</li>
