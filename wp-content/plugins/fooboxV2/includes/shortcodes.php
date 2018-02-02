<?php
/**
 * FooBox shortcodes
 * Date: 2013/11/14
 */
if (!class_exists('FooBox_AutoOpen_Shortcodes')) {

    class FooBox_AutoOpen_Shortcodes {

		private $_args = false;

		function __construct() {
			add_shortcode( 'foobox-auto-open', array($this, 'hookup') );
		}

		function hookup($atts) {
			$this->_args = shortcode_atts( array(
				'index' => 0,
			), $atts, 'foobox' );

			//add new script to the page footer
			add_action('wp_footer', array($this, 'render_foobox_auto_open'), 100);
		}

		function render_foobox_auto_open() {
			?>
			<script type="text/javascript">
				jQuery(document).bind('foobox-after-init', function() {
					setTimeout(function() {
						FooBox.open(<?php echo $this->_args['index']; ?>);
					}, 200);
				});
			</script>
		<?php
		}
	}
}