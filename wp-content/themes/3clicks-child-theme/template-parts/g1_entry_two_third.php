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
<article itemscope itemtype="<?php g1_render_entry_itemtype(); ?>" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    
	<?php

    if ( has_post_format( 'video' )) {
      // code to display the gallery format post here

    

    } else { ?>

     <?php $imgURL = get_post_thumbnail($post->ID, 'full'); ?>
    <?php if($imgURL): ?>
        <img class="wp-post-image" src="<?php echo $imgURL; ?>" alt="<?php echo esc_attr(get_post_meta( get_post_thumbnail_id($post->ID), '_wp_attachment_image_alt', true )); ?>">
    <?php else: ?>
        <?php // black_square(); ?>
    <?php endif ?>

  <?php  } ?>

	
	
    
    <header class="entry-header">
        <?php g1_render_entry_flags(); ?>
        <div class="g1-hgroup">
            <?php if ( $g1_title ): ?>
                <h1 class="entry-title"><?php echo $g1_title; ?></h1>
            <?php endif; ?>
            <?php if ( $g1_subtitle ): ?>
                <h3 class="entry-subtitle"><?php echo $g1_subtitle; ?></h3>
            <?php endif; ?>
        </div>

        <?php if ( $g1_elems['date'] || $g1_elems['author'] || $g1_elems['comments-link'] ): ?>
        <p class="g1-meta entry-meta">
            <?php
                if ( $g1_elems['date'] )            { g1_render_entry_date(); }
                if(get_the_author_meta('ID') != 1)
                {
                    if ( $g1_elems['author'] )          { g1_render_entry_author(); }
                }
                if ( $g1_elems['comments-link'] )   { g1_render_entry_comments_link(); }
            ?>
        </p>
        <?php endif; ?>
        <?php include(locate_template('template-parts/tcn_single-social-icons.php')); ?>
    </header><!-- .entry-header -->

    <?php /*
    <?php if ( $g1_elems[ 'media-box' ] ) { g1_render_entry_media_box( array(
        'size' => 'g1_two_third',
        'type' => $g1_elems[ 'media-box' ],
        'force_placeholder' => false,
    ) ); } ?>
    */ ?>

    <div class="entry-content" style="padding-top: 20px;">
        <?php the_content(); ?>
        
        <?php g1_wp_link_pages(); ?>
        
    </div><!-- .entry-content -->
    
    <div class="g1-meta entry-terms">
    <?php include(locate_template('template-parts/tcn_single-social-icons.php')); ?>
    </div>
    <div style="clear:both"></div>
    <?php if(! get_the_author_meta('ID') == 1): ?>
    <?php if ( $g1_elems['categories'] || $g1_elems['tags'] ): ?>
    <div class="g1-meta entry-terms">
        <?php
            if ( $g1_elems['categories'] )          { g1_render_entry_categories(); }
            if ( $g1_elems['tags'] )                { g1_render_entry_tags(); }
        ?>
    </div>
    <?php endif ?>
    <?php endif; ?>
    
    <?php $author_id = get_the_author_meta('ID'); ?>
    <?php if($author_id == 1): ?>
        
    <?php else: ?>
        <?php get_template_part( 'template-parts/g1_single_nav' ); ?>
    <?php endif ?>
    
    <?php if($author_id == 1): ?>
        
    <?php else: ?>
        <?php get_template_part( 'template-parts/g1_about_author' ); ?>
    <?php endif ?>
    
    <?php do_action( 'g1_extra_entry_blocks' ); ?>

    <div class="entry-utility">
        <?php edit_post_link( __( 'Edit', 'g1_theme' ), '<span class="edit-link">', '</span>' ); ?>
    </div><!-- .entry-utility -->

    <?php comments_template( '', true ); ?>

</article>
