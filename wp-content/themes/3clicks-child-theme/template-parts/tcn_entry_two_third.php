<?php
/**
 * The Template for displaying work archive|index.
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
    global $post;
    $g1_elems = G1_Elements()->get();
    $g1_title = $g1_elems[ 'title' ] ? the_title( '', '', false ) : '';
    $g1_subtitle = wp_kses_data( get_post_meta( $post->ID, '_g1_subtitle', true ) );
?>

<article itemscope itemtype="" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header>
        <?php g1_render_entry_flags(); ?>
        <div class="tcn-post-feed-thumbnail-holder">
            <?php if(get_post_thumbnail(get_the_ID(), 'full')): ?>
                <a href="<?php the_permalink(); ?>">
                    <img src="<?php echo get_post_thumbnail(get_the_ID(), 'full'); ?>" />
                </a>
            <?php endif ?>
        </div>
        
        <a href="<?php the_permalink(); ?>">
            <div class="g1-hgroup tcn-post-feed-entry-holder">
                <?php if ( $g1_title ): ?>
                    <h3 class="entry-title"><?php echo $g1_title; ?></h3>
                <?php endif; ?>
            </div>
        </a>

        <?php if ( $g1_elems['date'] || $g1_elems['author'] || $g1_elems['comments-link'] ): ?>
        <p class="g1-meta entry-meta">
            <?php
                if ( $g1_elems['date'] )            { g1_render_entry_date(); }
                if($post->post_author != 1)
                {
                    if ( $g1_elems['author'] )          
                    {
                        $author = get_userdata($post->post_author);
                        echo '<span class="entry-author">';
                        _e("by", "tcn");
                        echo ' <a href="'. get_author_posts_url($author->ID) . '">';
                        echo $author->display_name;
                        echo '</a>';
                        echo ' </span>';
                    }
                }
                if ( $g1_elems['comments-link'] )   { g1_render_entry_comments_link(); }
            ?>
        </p>
        <?php endif; ?>
    </header><!-- .entry-header -->

    <?php /*
    <?php if ( $g1_elems[ 'media-box' ] ) { g1_render_entry_media_box( array(
        'size' => 'g1_two_third',
        'type' => $g1_elems[ 'media-box' ],
        'force_placeholder' => false,
    ) ); } ?>
    */ ?>

    <div class="entry-content">
        <?php echo generate_excerpt($post); ?>
        <?php g1_wp_link_pages(); ?>
    </div><!-- .entry-content -->

    
    <?php /*
    <?php if ( $g1_elems['categories'] || $g1_elems['tags'] ): ?>
    <div class="g1-meta entry-terms">
        <?php
            if ( $g1_elems['categories'] )          { g1_render_entry_categories(); }
            if ( $g1_elems['tags'] )                { g1_render_entry_tags(); }
        ?>
    </div>
    <?php endif; ?>
    */ ?>
</article>

<hr />
