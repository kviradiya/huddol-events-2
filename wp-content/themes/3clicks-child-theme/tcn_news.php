<?php
/**
 * Template Name: TCN: News
 *
 * For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package G1_Framework
 * @subpackage G1_Theme03
 * @since G1_Theme03 1.0.0
 */
 
?>
<?php
// Prevent direct script access
if ( !defined('ABSPATH') )
    die ( 'No direct script access allowed' );
    
add_filter( 'body_class', array(G1_Theme(), 'secondary_wide_body_class') );
add_filter( 'body_class', array(G1_Theme(), 'secondary_after_body_class') );
?>

<?php get_header(); ?>

    <h2 class="tcn-page-title"><?php _e("News", "tcn"); ?></h2>
    
    <div id="primary">
		<div id="content" role="main" class="tcn-read-list">
		    
        	<?php $author_query = '1'; ?>
            <?php include(locate_template('template-parts/tcn_chunk-read-content.php')); ?>
            
            <?php $page_link = '/blog/'; ?>
            <?php include(locate_template('template-parts/tcn_chunk-nav-offset.php')); ?>
            
            <?php wp_reset_postdata(); // reset the query ?>
                
        </div>
    </div>
    <?php get_sidebar(); ?>

<?php get_footer(); ?>