<?php
/**
 * The Header for our theme.
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

?><!DOCTYPE html>
<!--[if IE 7]>
<html class="no-js lt-ie10 lt-ie9 lt-ie8" id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="no-js lt-ie10 lt-ie9" id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 9]>
<html class="no-js lt-ie10" id="ie9" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !IE]>
<html class="no-js" <?php language_attributes(); ?>>
<![endif]--><head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
   <?php if(!is_single()): ?> 
	<meta property="og:image" content="http://thecaregivernetwork.ca/wp-content/uploads/2015/04/lratcn_logo.png" />
<?php endif ?>
	<title><?php wp_title( '', true, 'right' ); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<meta name="google-site-verification" content="b7z_IHh7WOMKDMHYyk1RD85BkExq-qfVhVBOOtsNSec" />
	<meta name="google-site-verification" content="qPfkB-eXISlqyuRSVB4EkS63ngg6b-cP6x64QADXIgI" />
	<meta name="google-site-verification" content="QjtILAhU0SXKLMRIt_SoDVj6Nn5iBvJe-pst2GJ0Bz8" />
   
<meta name="google-site-verification" content="cCaycBEhyQSCa9YGD53WPkAaWKN7z84SkEAIk1L6tgw" />
	<meta name="msvalidate.01" content="1353FB721E221B342108C98D0966102C" />
<script src="https://cdn.optimizely.com/js/5639380899.js"></script>
	<?php wp_head(); ?>
	<?php include_once('analytics.php'); ?>
    <script type="text/javascript" src="/wp-content/themes/3clicks-child-theme/scripts/jquery.cookie.js"></script>
    
  <!-- Add recaptch -->
  <?php if(ICL_LANGUAGE_CODE=='en'): ?>
	<script src="https://www.google.com/recaptcha/api.js?hl=en" async defer></script>
	<?php else: ?>
	<script src="https://www.google.com/recaptcha/api.js?hl=fr" async defer></script>
	<?php endif ?>
            
</head>
<body <?php body_class(); ?> itemscope itemtype="http://schema.org/WebPage">
 <map name="Map"><area shape="rect" coords="165,2,298,46" href="https://www.huddol.com" target="_blank"></map>

 
	<script>(function() {
	var _fbq = window._fbq || (window._fbq = []);
	if (!_fbq.loaded) {
	var fbds = document.createElement('script');
	fbds.async = true;
	fbds.src = '//connect.facebook.net/en_US/fbds.js';
	var s = document.getElementsByTagName('script')[0];
	s.parentNode.insertBefore(fbds, s);
	_fbq.loaded = true;
	}
	_fbq.push(['addPixelId', '1579325682307664']);
	})();
	window._fbq = window._fbq || [];
	window._fbq.push(['track', 'PixelInitialized', {}]);
	</script>
	
<noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?id=1579325682307664&amp;ev=PixelInitialized" /></noscript>
	
	<?php $actual_link = get_permalink(); ?>

            
<?php do_action( 'g1_before_page' ); ?>


<div id="page">
	<?/*php if(!is_user_logged_in()): ?>
		<?php $alert_post_id = 0; ?>
		<?php if(ICL_LANGUAGE_CODE == 'en'): ?>
			<?php $alert_post_id = 6221; ?>
		<?php else: ?>
			<?php $alert_post_id = 6256; ?>    
		<?php endif ?>
	
		<?php 
			$alert_content = '';
			$page_object = get_page( $alert_post_id );
			if($page_object)
			{
				$alert_content = $page_object->post_content;
			}
		?>
	
		<?php if($alert_content !== ""): ?>
			<div class="alert-banner">
				<div class="inner">
					<p>
						<?php echo $alert_content; ?>
					</p>
				</div>
			</div>
		<?php endif ?>
	<?php endif*/ ?>
	
	<div id="g1-top">
	<?php 
		/* Executes a custom hook.
		 * If you want to add some content before the g1-header, hook into 'g1_header_before' action.
		 */	
		do_action( 'g1_header_before' );
	?>

	<!-- BEGIN #g1-header -->
	<div id="g1-header-waypoint">
		<div id="g1-header" class="g1-header" role="banner">
			<div class="g1-layout-inner">
				<?php
					/* Executes a custom hook.
					 * If you want to add some content before the g1-primary-bar, hook into 'g1_header_begin' action.
					 */
					do_action( 'g1_header_begin' );
				?>

				<div id="g1-primary-bar">
					<?php G1_Theme()->render_site_id(); ?>

					<!-- BEGIN #g1-primary-nav -->
					<nav id="g1-primary-nav" class="g1-nav--<?php echo sanitize_html_class( g1_get_theme_option('ta_header', 'primary_nav_style', 'none') ); ?> g1-nav--collapsed">
						<a id="g1-primary-nav-switch" href="#"><?php echo __('Menu', 'g1_theme')?></a>
						<?php
							if ( has_nav_menu( 'primary_nav' ) ) {
								wp_nav_menu( array(
									'theme_location'	=> 'primary_nav',
									'container'			=> '',
									'menu_class'        => '',
									'menu_id'			=> 'g1-primary-nav-menu',
									'depth'				=> 0,
									'walker'            => new G1_Extended_Walker_Nav_Menu(array(
										'with_description' => true,
										'with_icon' => true,
									)),
								));
							} else {
								$helpmode = G1_Helpmode(
									'empty_primary_nvation',
									__( 'Empty Primary Navigation', 'g1_theme' ),
									'<p>' . sprintf( __( 'You should <a href="%s">assign a menu to the Primary Navigation Theme Location</a>', 'g1_theme' ), network_admin_url( 'nav-menus.php' ) ) . '</p>'
								);
								$helpmode->render();
							}
						?>

						<?php if ( apply_filters( 'g1_header_woocommerce_minicart', is_plugin_active('woocommerce/woocommerce.php') ) ): ?>
						<div class="g1-cartbox">
							<a class="g1-cartbox__switch" href="#">
								<div class="g1-cartbox__arrow"></div>
								<strong><?php _ex( '&nbsp;', 'searchbox switch label',  'g1_theme' ); ?></strong>
							</a>

							<div class="g1-cartbox__box">
								<div class="g1-inner woocommerce">
									<?php
										$g1_instance = array(
											'title' => '',
											'number' => 1
										);
										$g1_args = array(
											'title' => '',
											'before_widget' => '',
											'after_widget' => '',
											'before_title' => '<div class="g1-cartbox__title">',
											'after_title' => '</div>',
										);
										$g1_widget = new WC_Widget_Cart();
										$g1_widget->number = $g1_instance['number'];
										$g1_widget->widget( $g1_args, $g1_instance );
									?>
									<p class="g1-cartbox__empty"><?php _e( 'No products in the cart.', 'woocommerce' ); ?></p>
								</div>

							</div>
						</div>
						<?php endif; ?>

						<?php
							$g1_value = g1_get_theme_option( 'ta_header', 'searchform' );
							$g1_layout = g1_get_theme_option( 'ta_header', 'layout', 'semi-standard' );

							$g1_class = array(
								'g1-searchbox',
								'g1-searchbox--' . $g1_value,
								'g1-searchbox--' . $g1_layout
							);
						?>
						<?php if ( 'none' !== $g1_value && !is_404() ): ?>
						<div class="<?php echo  sanitize_html_classes($g1_class); ?>">
							<a class="g1-searchbox__switch" href="#">
								<span class="caption"><?php _e("Search"); ?></span><strong><?php _ex( '&nbsp;', 'searchbox switch label',  'g1_theme' ); ?></strong>
								<div class="g1-searchbox__arrow"></div>
							</a>
							<?php get_search_form(); ?>
						</div>
						<?php endif; ?>

					</nav>
					<!-- END #g1-primary-nav -->
				</div><!-- END #g1-primary-bar -->

				<?php
					/* Executes a custom hook.
					 * If you want to add some content after the g1-primary-bar, hook into 'g1_header_end' action.
					 */
					do_action( 'g1_header_end' );
				?>

			</div> <!-- #g1-layout-inner -->

			<?php get_template_part( 'template-parts/g1_background', 'header' ); ?>
		</div> <!-- END #g1-header -->  
	</div> <!-- END #g1-header-waypoint -->  
	

	<?php 
		/* Executes a custom hook.
		 * If you want to add some content after the g1-header, hook into 'g1_header_after' action.
		 */	
		do_action( 'g1_header_after' );
	?>
	
	<?php 
		/* Executes a custom hook.
		 * If you want to add some content before the g1-content, hook into 'g1_content_before' action.
		 */	
		do_action( 'g1_content_before' );
	?>
	
	<?php get_template_part( 'g1_precontent' ); ?>


		<div class="g1-background">
		</div>
	</div>

<?php  /* Add new banner */

if( is_front_page() ) { ?>    
    <?php
    if(is_user_logged_in())
    {
        $mode = "my_network";
    }
    else
    {
        $mode = "upcoming";
    }
    
    if(isset($_GET["mode"]))
    {
        $mode = $_GET["mode"];
    }
?>

<?php
    $network = new TCNNetwork;
    $network_events = $network->get_upcoming_network_events(wp_get_current_user());
    if(count($network_events) == 0 && $mode === "my_network")
    {
        $mode = "upcoming";
    }
    
    $upcoming_events = array_slice(get_upcoming_events(), 0, 12);
    if(count($upcoming_events) == 0)
    {
        $mode = "most_viewed";
    }
?>
<div id="banner-container">
<div id="banner-space">
<span class="top-title"><?php _e("Benefit from our online learning experiences.", "tcn"); ?></span><br>
<span class="sub-title"><?php _e("We bring together the brightest minds with the biggest hearts to help you on your way.", "tcn"); ?></span>
</div>
</div>
 <div id="g1-content-home" class="g1-content">
 <div class="g1-layout-inner">
    <div class="tcn-filter">
                    <span class="tcn-filter-title">
                         <?php _e("Filter events by:", "tcn"); ?>
                    </span>
                    
                    <?php if(is_user_logged_in() && count($network_events)): ?>
                    <span <?php if($mode === "my_network") echo 'class="tcn-filter-selected"'; ?> >
                        <a href="?mode=my_network">
                            <?php _e("My Caregiver Network", "tcn"); ?>
                        </a>
                    </span>
                    <?php endif ?>
                    
                    <?php if(count($upcoming_events) > 0): ?>
                    <span <?php if($mode === "upcoming") echo 'class="tcn-filter-selected"'; ?> >
                        <a href="?mode=upcoming">
                            <?php _e("Upcoming", "tcn"); ?>
                        </a>
                    </span>
                    <?php endif ?>

                    <span <?php if($mode === "most_viewed") echo 'class="tcn-filter-selected"'; ?> >
                        <a href="?mode=most_viewed">
                            <?php _e("Most Viewed", "tcn"); ?>
                        </a>
                    </span>                    
                </div>
    </div>   
    </div>
    <?php } ?>         

	<!-- BEGIN #g1-content -->
	<div id="g1-content" class="g1-content">
		<div class="g1-layout-inner">
			<?php
				/* Executes a custom hook.
				 * If you want to add some content before the g1-content-area, hook into 'g1_content_begin' action.
				 */
				do_action( 'g1_content_begin' );
			?>
			<div id="g1-content-area">
