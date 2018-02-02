<?php
/**
 * The Template Part for displaying the footer.
 *
 * For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package G1_Framework
 * @subpackage G1_Theme03
 * @since G1_Theme03 1.0.0
 */
 
$image_path = get_stylesheet_directory_uri() .'/images/';

// Prevent direct script access
if ( !defined('ABSPATH') )
    die ( 'No direct script access allowed' );
?>
            </div>
            <!-- END #g1-content-area -->
            <?php
                /* Executes a custom hook.
                 * If you want to add some content after the g1-content-area,
                 * hook into 'g1_content_end' action.
                 */
                do_action( 'g1_content_end' );
            ?>
        </div>

        <?php get_template_part( 'template-parts/g1_background', 'content' ); ?>
	</div>
	<!-- END #g1-content -->	

	<?php 
		/* Executes a custom hook.
		 * If you want to add some content after the g1-content,
		 * hook into 'g1_content_after' action.
		 */	
		do_action( 'g1_content_after' );
	?>

    <?php
        // For the SEO purposes the preheader is placed here
        get_template_part( 'g1_preheader' );
    ?>


	<?php get_template_part( 'g1_prefooter' ); ?>
	
	<?php 
		/* Executes a custom hook.
		 * If you want to add some content before the g1-footer,
		 * hook into 'g1_footer_before' action.
		 */	
		do_action( 'g1_footer_before' );
	?>
	
	<!-- BEGIN #g1-footer -->
	<footer id="g1-footer" class="g1-footer" role="contentinfo">
            <?php
                /* Executes a custom hook.
                 * If you want to add some content before the g1-footer-area,
                 * hook into 'g1_footer_begin' action.
                 */
                do_action( 'g1_footer_begin' );
            ?>

            <!-- BEGIN #g1-footer-area -->
            <div id="g1-footer-area" class="g1-layout-inner">
                <div class="footer-social-icons">
                    <?php
                    // Render feeds
                    if ( shortcode_exists( 'g1_social_icons') ) {
                        $g1_social_icons_size = g1_get_theme_option( 'ta_preheader', 'g1_social_icons' );
                        if ( is_numeric( $g1_social_icons_size ) ) {
                            $g1_social_icons_size = intval( $g1_social_icons_size );
                            echo do_shortcode('[g1_social_icons template="list-horizontal" size="'. $g1_social_icons_size . '" hide="label, caption"]');
                        }
                    }
                    ?>
                </div>
                <nav id="g1-footer-nav">
                    <?php
                        if ( has_nav_menu( 'footer_nav' ) ) {
                            $footer_nav = array(
                                'theme_location'	=> 'footer_nav',
                                'container'			=> '',
                                'menu_id'			=> 'g1-footer-nav-menu',
                                'menu_class'		=> '',
                                'depth'				=> 1
                            );
                            wp_nav_menu($footer_nav);
                        } else {
                            $helpmode = G1_Helpmode(
                                'empty_footer_navigation',
                                __( 'Empty Footer Navigation', 'g1_theme' ),
                                '<p>' . sprintf( __( 'You should <a href="%s">assign a menu to the Footer Navigation Theme Location</a>', 'g1_theme' ), network_admin_url( 'nav-menus.php' ) ) . '</p>'
                            );
                            $helpmode->render();
                        }
                     ?>
                </nav>
                <p id="g1-footer-text"><?php echo g1_get_theme_option( 'general', 'footer_text', '' ); ?></p>
            </div>
            <!-- END #g1-footer-area -->

            <?php
                /* Executes a custom hook.
                 * If you want to add some content after the g1-footer-area,
                 * hook into 'g1_footer_end' action.
                 */
                do_action( 'g1_footer_end' );
            ?>

        <?php get_template_part( 'template-parts/g1_background', 'footer' ); ?>
	</footer>
	<!-- END #g1-footer -->

    <?php if ( 'none' !== g1_get_theme_option( 'general', 'scroll_to_top', 'standard' ) ): ?>
        <a href="#page" id="g1-back-to-top"><?php _e( 'Back to Top', 'g1_theme' ); ?></a>
    <?php endif; ?>
	
	<?php 
		/* Executes a custom hook.
		 * If you want to add some content after the g1-footer,
		 * hook into 'g1_footer_after' action.
		 */	
		do_action( 'g1_footer_after' );
	?>
</div>
<!-- END #page -->
<?php wp_footer(); ?>

<!-- Google Code for Sign Up Conversion Page
In your html page, add the snippet and call
goog_report_conversion when someone clicks on the
chosen link or button. -->
<script type="text/javascript">
  /* <![CDATA[ */
  goog_snippet_vars = function() {
    var w = window;
    w.google_conversion_id = 821887963;
    w.google_conversion_label = "upeRCKbk9HoQ24f0hwM";
    w.google_remarketing_only = false;
  }
  // DO NOT CHANGE THE CODE BELOW.
  goog_report_conversion = function(url) {
    goog_snippet_vars();
    window.google_conversion_format = "3";
    var opt = new Object();
    opt.onload_callback = function() {
    if (typeof(url) != 'undefined') {
      window.location = url;
    }
  }
  var conv_handler = window['google_trackConversion'];
  if (typeof(conv_handler) == 'function') {
    conv_handler(opt);
  }
}
/* ]]> */
</script>
<script type="text/javascript"
  src="//www.googleadservices.com/pagead/conversion_async.js">
</script>
  
</body>
</html>