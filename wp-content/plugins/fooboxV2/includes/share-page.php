<?php

if ( !class_exists( 'Foobox_Share_Page' ) ) {

	class Foobox_Share_Page {

		function __construct() {
//			add_action( 'init', array($this, 'add_rewrite_tags') );
//			add_action( 'wp_loaded', array($this, 'check_rewrite_rules_loaded') );
//
//			// Activation hook
//			register_activation_hook( FOOBOX_FILE, array($this, 'activate') );
//
//			// Deactivation hook
//			register_deactivation_hook( FOOBOX_FILE, array($this, 'deactivate') );

			add_filter( 'query_vars', array($this, 'query_vars') );
			add_action( 'template_redirect', array($this, 'listen_for_foobox_share_page') );
		}

//		function add_rewrite_tags() {
//			add_rewrite_tag( '%foo-share%', 'true' );
//		}
//
//		function check_rewrite_rules_loaded() {
//			$rules = get_option( 'rewrite_rules' );
//
//			$rule = 'foo-share/([^/]+)/?$';
//
//			if ( !isset($rules[$rule]) ) {
//				$this->activate();
//			}
//		}
//
//		function activate() {
//			// catch any urls like : /foo-share/some-random-key/
//			add_rewrite_rule( 'foo-share/([^/]+)/?(.*)',
//				'index.php?foo-share=true&$2', 'top' );
//
//			flush_rewrite_rules();
//		}
//
//		function deactivate() {
//			flush_rewrite_rules();
//		}

		/**
		 * Registers query vars for API access
		 *
		 * @access public
		 * @since  2.2.2
		 * @author Brad Vincent
		 *
		 * @param array $vars Query vars
		 *
		 * @return array $vars New query vars
		 */
		public function query_vars($vars) {
			$vars[] = 'foo-share';
			$vars[] = 'url';
			$vars[] = 'image';
			$vars[] = 'title';
			$vars[] = 'desc';

			return $vars;
		}

		/**
		 * Hook into the template redirect and output our share page if necessary
		 */
		function listen_for_foobox_share_page() {
			global $wp_query;

			//make sure we are on a share page
			if ( empty($wp_query->query_vars['foo-share']) ) {
				return;
			}

			$url       = urldecode( $wp_query->query_vars['url'] );
			$has_image = array_key_exists( 'image', $wp_query->query_vars );
			$image     = $has_image ? urldecode( $wp_query->query_vars['image'] ) : $has_image;
			$has_title = array_key_exists( 'title', $wp_query->query_vars );
			$title     = $has_title ? urldecode( $wp_query->query_vars['title'] ) : $has_title;
			$has_desc  = array_key_exists( 'desc', $wp_query->query_vars );
			$desc      = $has_desc ? urldecode( $wp_query->query_vars['desc'] ) : $has_desc;

			?>
			<!DOCTYPE html>
			<head>
				<meta charset="UTF-8">
				<title><?php echo $title; ?></title>
				<link rel='canonical' href='<?php echo $url; ?>'>
				<meta property="og:url" content="<?php echo $url; ?>">
				<?php if ($has_image) {?><meta property="og:image" content="<?php echo $image; ?>"><?php } ?>
				<?php if ($has_title) {?><meta property="og:title" content="<?php echo $title; ?>"><?php } ?>
				<?php if ($has_desc) {?><meta property="og:description" content="<?php echo $desc; ?>"><?php } ?>
				<style type="text/css"> #container { display: none; }</style>
			</head>
			<body>
			<div id="container">
				<?php if ($has_title) {?>
				<h1><?php echo $title; ?></h1>
				<?php } ?>
				<?php if ($has_image) {?>
				<img alt="<?php echo $has_title ? $title : ''; ?>" src="<?php echo $image ?>"/>
				<?php } ?>
				<?php if ($has_desc) {?>
				<p><?php echo $desc; ?></p>
				<?php } ?>
			</div>
			<script type="text/javascript">
				document.body.innerHTML += '<div>redirecting to <?php echo htmlentities( $url ); ?></div>';
				window.location = '<?php echo $url; ?>';
			</script>
			</body>
			</html>
			<?php
			$wp_query->is_404=false;
			status_header(200);
			die();
		}
	}
}

