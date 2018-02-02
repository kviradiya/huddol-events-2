<?php
/**
 * Template Name: TCN: Tune In
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
    
// add_filter( 'body_class', array(G1_Theme(), 'secondary_wide_body_class') );
// add_filter( 'body_class', array(G1_Theme(), 'secondary_after_body_class') );
?>

<?php
    get_header(); ?>
    <h2 class="tcn-page-title"><?php echo $post->post_title; ?></h2>
    <div id="primary">
		<div id="content" role="main">
		    <?php get_template_part('template-parts/tcn_chunk', 'tune-in'); ?>
            <?php
                if(isset($_GET['mode']) && $_GET['mode'] === 'upcoming')
                {
                    $events = get_upcoming_events();
                }
                else if(isset($_GET['mode']) && $_GET['mode'] === 'most_viewed')
                {
                    $events = get_most_viewed_events();
                }
                include(locate_template('template-parts/tcn_chunk-events.php'));
            ?>
        </div>
    </div>
<?php get_footer(); ?>