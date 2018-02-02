<?php
/**
 * The default template for displaying content
 *
 * For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package G1_Framework
 * @subpackage G1_Theme03
 * @since G1_Theme03 1.0.0
 */

// Prevent direct script access
if ( !defined('ABSPATH') )
    die ( 'No direct script access allowed' );
?>
<?php
    $g1_data = g1_part_get_data();
    $g1_collection = $g1_data['collection'];
    $g1_elems = $g1_data['elems'];
    $g1_options = !empty($g1_data['options']) ? $g1_data['options'] : array();

    // Prepare config helpers
    $g1_elems['byline'] = $g1_elems['date'] || $g1_elems['author'] || $g1_elems['comments-link'];
    $g1_elems['terms'] = $g1_elems['categories'] || $g1_elems['tags'];

    if($post->post_type === 'tribe_events') {
        $itemType = is_event_over($post->ID) ? 'http://schema.org/OnDemandEvent' : 'http://schema.org/BroadcastEvent';
    } else {
        $itemType = g1_capture_entry_itemtype();
    }
?>

<!-- <?php // echo __FILE__; ?> -->
    <article itemscope itemtype="<?php echo $itemType; ?>" id="post-<?php the_ID(); ?>" class="post type-post status-publish format-standard has-post-thumbnail category-gallery-4-cols-pc category-grid-3-cols-pc category-grid-2-cols-side-left-gallery-pc category-grid-2-cols-side-right-gallery-pc category-grid-1-col-side-right-pc category-grid-2-cols-side-right-pc category-masonry-3-cols-pc category-grid-2-cols-side-left-pc category-grid-1-col-side-left-pc category-grid-1-col-pc tag-gallery-3-cols-filter-pt tag-grid-2-cols-side-right-filter-pt tag-grid-4-cols-filter-pt g1-brief" style="background: none;">
        <?php
        /*
            if ( $g1_elems['featured-media'] ) {
                g1_render_entry_featured_media( array(
                    'size'              => $g1_collection->get_image_size(),
                    'lightbox_group'    => $g1_collection->get_lightbox_group(),
                    'force_placeholder' => $g1_collection->get_force_placeholder(),
                ));
            }
            */
        ?>
        
        <figure class="entry-featured-media">
            <a class="g1-frame g1-frame--none g1-frame--inherit g1-frame--center " href="<?php echo get_permalink($post->ID); ?>">
                <div class="event-picture" style="background-image: url('<?php echo get_post_thumbnail($post->ID, 'medium'); ?>');"></div>
            </a>
        </figure>

        <div class="g1-nonmedia">
            <div class="g1-inner">
                <header class="entry-header">
                    <h3 class="event-title">
                        <a title="<?php echo $post->post_title ?>" href="<?php echo get_permalink($post->ID); ?>" itemprop="url"><?php echo $post->post_title ?></a>
                    </h3>
                    <?php if ( $g1_elems['byline'] ): ?>
                    <p class="entry-meta g1-meta event-date">
                        <?php if($post->post_type === 'tribe_events'): ?>
                        
                            <time class="entry-date" datetime="<?php echo get_event_date_iso($post); ?>" itemprop="startDate">
                                <?php echo get_event_date($post); ?>
                            </time>                                  
                        
                        <?php else: ?>
                            <?php if ( $g1_elems['date'] )             { g1_render_entry_date(); } ?>
                        <?php endif ?>
                        <?php if ( $g1_elems['author'] )           { g1_render_entry_author(); } ?>
                        <?php if ( $g1_elems['comments-link'] )    { g1_render_entry_comments_link(); } ?>
                    </p>
                
                    <?php endif; ?>
                </header><!-- .entry-header -->

                <div itemprop="description">
                <?php echo generate_excerpt($post->ID); ?>
                </div>
                
                <?php if ( $g1_elems['terms'] ): ?>
                <div class="g1-meta entry-terms">
                    <?php if(!is_category()): ?>
                        <?php if ( $g1_elems['categories'] )       { g1_render_entry_categories(); } ?>
                        <?php if ( $g1_elems['tags'] )             { g1_render_entry_tags(); } ?>
                    <?php endif ?>
                </div>
                <?php endif; ?>

            </div>
            <footer class="entry-footer">
                <?php if ( $g1_elems['button-1'] ):?>
                    <div>
                        <?php g1_render_entry_button_1(); ?>
                    </div>
                <?php endif; ?>
            </footer>
            <div class="g1-01"></div>
        </div>
    </article><!-- .post-XX -->
