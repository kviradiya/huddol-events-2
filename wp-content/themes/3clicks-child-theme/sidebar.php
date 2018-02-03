<?php
/**
 * The sidebar containing the main widget area.
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
    // TODO JHILL: French
    $is_read = $post->ID == 66 || $post->ID == 4106;
    $is_news = $post->ID == 186 || $post->ID == 4110;
    do_action( 'g1_sidebar_1_before' );
?>
<?php if ( apply_filters( 'g1_sidebar_1', true ) ): ?>

<!-- BEGIN: #secondary -->
<div id="secondary" class="g1-sidebar widget-area" role="complementary">
<?php 
				print_cta();
				?>
	<div class="g1-inner">
	    <?php
	        if($is_read)
	        {
    		    include(locate_template('template-parts/tcn_sidebar-most-viewed.php'));
    		    include(locate_template('template-parts/tcn_sidebar-contributors.php'));
    		    include(locate_template('template-parts/tcn_sidebar-upcoming-events.php'));
	        }
	        else if($is_news)
	        {
    		    include(locate_template('template-parts/tcn_sidebar-most-viewed.php'));
    		    include(locate_template('template-parts/tcn_sidebar-upcoming-events.php'));
	        }
	        else if(is_single())
	        {
                if($post->post_author == 1)
                {
                    $author = get_userdata(1);
                    include(locate_template('template-parts/tcn_sidebar-other-news.php'));
                    include(locate_template('template-parts/tcn_sidebar-upcoming-events.php'));
                }
                else
                {
                    $author = get_userdata(1);
	                include(locate_template('template-parts/tcn_sidebar-author.php'));
    		        include(locate_template('template-parts/tcn_sidebar-related-events.php'));
    		        // include(locate_template('template-parts/tcn_sidebar-related-posts.php'));
    		    }
	        }
	        else
	        {
    	        include(locate_template('template-parts/tcn_sidebar-author.php'));
    		    include(locate_template('template-parts/tcn_sidebar-contributors.php'));
    		    include(locate_template('template-parts/tcn_sidebar-most-viewed.php'));
    		    include(locate_template('template-parts/tcn_sidebar-other-news.php'));
    		    include(locate_template('template-parts/tcn_sidebar-upcoming-events.php'));
    		}
		?>
	</div>
	<div class="g1-background">
        <div></div>
	</div>	
</div>
<!-- END: #secondary -->

<?php endif; ?>
<?php
    // Custom hook
    do_action( 'g1_sidebar_1_after' );
?>
