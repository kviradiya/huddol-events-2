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
// Our tricky way to get variables that were passed to this template part
$g1_data = g1_part_get_data();
$g1_collection = $g1_data['collection'];
$g1_elems = G1_Elements()->get();
$g1_options = array(
    'summary-type' => !empty($g1_data['summary-type']) ? $g1_data['summary-type'] : 'excerpt'
);


// Build config array based on the name of a collection
$g1_collection = G1_Collection_Manager()->get_collection( $g1_collection );

$title = '';
$subtitle = '';

if ( is_home() ) {
    $title = __( 'Blog', 'g1_theme' );

    $page_id = (int) get_option( 'page_for_posts' );
    if( $page_id ) {
        // WPML fallback
        if ( G1_WPML_LOADED )
            $page_id = icl_object_id( $page_id, 'page', true );

        if (  $page_id ) {
            $title = get_the_title( $page_id );
            $subtitle = wp_kses_data( get_post_meta( $page_id, '_g1_subtitle', true ) );
        }
    }
} elseif ( is_post_type_archive() ) {
    $title = post_type_archive_title( '', false );
    $page_id = g1_get_theme_option( 'post_type_'. get_query_var( 'post_type' ), 'page_for_posts' );

    if ( $page_id ) {
        // WPML fallback
        if ( G1_WPML_LOADED )
            $page_id = icl_object_id( $page_id, 'page', true );

        if ( $page_id ) {
            $subtitle = wp_kses_data( get_post_meta( $page_id, '_g1_subtitle', true ) );
        }
    }
} elseif ( is_category() ) {
    $title = '<span>' . single_term_title( '', false ) . '</span>';
    $subtitle = strip_tags( term_description() );
} elseif( is_tag() ) {
    $title = sprintf( __( 'Tag Archives: %s', 'g1_theme' ), '<span>' . single_term_title( '', false ) . '</span>' );
    $subtitle = strip_tags( term_description() );
} elseif( is_tax() ) {
    $title = single_term_title( '', false );
    $subtitle = strip_tags( term_description() );
} elseif ( is_year() ) {
    $title = get_the_date( 'Y' );
    $subtitle = __( 'Yearly Archives', 'g1_theme' );
} elseif ( is_month() ) {
    $title = get_the_date( 'F Y' );
    $subtitle = __( 'Monthly Archives', 'g1_theme' );
} elseif ( is_day() ) {
    $title = get_the_date();
    $subtitle = __( 'Daily Archives', 'g1_theme' );
} elseif ( is_author() ) {
    if	(	get_query_var('author_name' ) ) {
        $curauth = get_user_by( 'login', get_query_var('author_name') );
    } else {
        $curauth = get_userdata( get_query_var('author') );
    }
    if ( $curauth  ) {
        $title = $curauth->display_name;
    }
    $subtitle = __( 'Author Archives', 'g1_theme' );
}
?>

<div id="primary">
    <div id="content" role="main">
        <?php if(is_author()): ?>
            <?php $imgURL = get_cupp_meta( $curauth->ID, 'thumbnail' ); ?>

            <section itemtype="http://schema.org/UserComments" itemscope="" id="comments">
                <div class="g1-replies g1-replies--comments">
                    <ol class="commentlist">
        	            <li id="li-comment-2" class="comment byuser comment-author-admin bypostauthor even thread-even depth-1">
                            <article id="comment-2" itemtype="http://schema.org/Comment" itemprop="comment" itemscope="">
                                <header itemtype="http://schema.org/Person" itemscope="" itemprop="author">
                                    <div itemtype="http://schema.org/Person" itemscope="" itemprop="author">
                                        <img style="height: 60px; width: 60px" class="avatar avatar-60 photo" src="<?php echo $imgURL; ?>" alt="" itemprop="image">                    
                                        <h3>
                                            
                                            <a href="<?php echo get_author_posts_url($curauth->ID); ?>">
                                                <?php echo $curauth->display_name ?>
                                            </a>
                                        </h3>              
                                    </div>
                                </header>
    
                                <div class="comment-body" itemprop="text">
                                    <p>
                                        <?php echo $curauth->description; ?>
                                    </p>
                                </div>
                            </article><!-- END: #comment-##  -->
                        </li><!-- #comment-## -->
                    </ol>
                </div>
            </section>    
            
        <?php endif ?>
        
        <?php if( is_tag() && ICL_LANGUAGE_CODE == 'fr'): ?>
            <?php if ( ( $g1_elems['title'] && strlen( $title ) ) || strlen( $subtitle ) ): ?>
            <header class="archive-header">
            
                    <?php if(is_author()): ?>
                        <h2 class="tcn-page-title"><?php _e("Blog", "tcn"); ?> / <span class="h2-smaller"><?php echo $curauth->display_name; ?></span></h2>
                    <?php elseif(is_tag()): ?>
                        <?php $tag       = get_queried_object(); ?>
                        <h2 class="tcn-page-title"><?php _e("Tag", "tcn"); ?> / <span class="h2-smaller"><?php echo $tag->name; ?></span></h2>
                    <?php elseif(is_category()): ?>
                        <?php $category       = get_queried_object(); ?>
                        <h2 class="tcn-page-title"><?php echo $category->cat_name; ?></h2>
                    <?php else: ?>
                        <?php if ( $g1_elems['title'] && strlen( $title ) ): ?>
                            <h2 class="tcn-page-title"><?php echo $title; ?></h2>
                        <?php endif; ?>
                    <?php endif ?>
                </h2>
            </header><!-- .archive-header -->
            <?php endif; ?>
            
            <div style="display:none">
                <?php
                    $query = new WP_Query(array("post_type"=>"tribe_events", "posts_per_page"=> -1, "tag" => $title));
                ?>
            </div>
            
            <?php

                // Our tricky way to pass variables to a template part
                g1_part_set_data( array(
                    'query' => $query,
                    'collection' => $g1_collection,
                    'options' => $g1_options,
                    'elems' => $g1_elems['collection'],
                ));
                
                get_template_part( $g1_collection->get_file() );
            ?>
        <?php else: ?>        
            <?php if ( have_posts() ) : ?>
                <?php if ( ( $g1_elems['title'] && strlen( $title ) ) || strlen( $subtitle ) ): ?>
                <header class="archive-header">
                
                        <?php if(is_author()): ?>
                            <h2 class="tcn-page-title"><?php _e("Blog", "tcn"); ?> / <span class="h2-smaller"><?php echo $curauth->display_name; ?></span></h2>
                        <?php elseif(is_tag()): ?>
                            <?php $tag       = get_queried_object(); ?>
                            <h2 class="tcn-page-title"><?php _e("Tag", "tcn"); ?> / <span class="h2-smaller"><?php echo $tag->name; ?></span></h2>
                        <?php elseif(is_category()): ?>
                            <?php $category       = get_queried_object(); ?>
                            <h1 class="tcn-page-title"><?php echo $category->cat_name; ?></h1>
                            <?php if( !empty($category->description) ): ?>
                                <p class="tcn-term-description"><?php echo $category->description; ?></p>
                            <?php endif; ?>
                            
                        <?php else: ?>
                            <?php if ( $g1_elems['title'] && strlen( $title ) ): ?>
                                <h2 class="tcn-page-title"><?php echo $title; ?></h2>
                            <?php endif; ?>
                        <?php endif ?>
                    </h2>
                </header><!-- .archive-header -->
                <?php endif; ?>
				
              <?php
				// modified April 7th 2016 #Interest note
				if(is_user_logged_in())
				{
					print_category_reminder($category->term_id);
				}
				?>
                
                <?php
                    global $wp_query;

                    // Our tricky way to pass variables to a template part
                    g1_part_set_data( array(
                        'query' => $wp_query,
                        'collection' => $g1_collection,
                        'options' => $g1_options,
                        'elems' => $g1_elems['collection'],
                    ));
                
                    get_template_part( $g1_collection->get_file() );
                ?>
            <?php else: ?>
                <?php get_template_part( 'template-parts/g1_no_results', 'works' ); ?>
            <?php endif;?>
        <?php endif ?>

    </div><!-- #content -->
</div><!-- #primary -->