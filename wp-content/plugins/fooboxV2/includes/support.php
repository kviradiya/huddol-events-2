<?php
/**
 * Support Tab on Foobox Settings Page
 *
 * @author      Brad Vincent
 * @package     foobox/includes
 * @version     1.0
 */

if (!class_exists('foobox_support')) {

	class foobox_support {

		public function render($args) {
			?>
			<h3><?php _e('FooBox PRO Support', 'foobox'); ?></h3>
			<p>
				<?php
				$link = sprintf('<strong><a target="_blank" href="http://docs.fooplugins.com/support/forum/premium-plugin-support/foobox-pro/">%s</a></strong>', __('FooBox PRO Support Forums', 'foobox'));
				echo sprintf( __('The quickest way to get priority support is to post a topic in our %s.', 'foobox'), $link );
				?>
			</p>
			<p>
				<?php _e('Please search the support forums to find common issues and conflicts.', 'foobox'); ?>
			</p>
		<?php
		}

		public function render_invalid() {
			?>
			<h3><?php _e('Validation Required!', 'foobox'); ?></h3>
			<p>
				<?php
				_e('Support options only show after validating your license key. Please validate your license key under the "General" tab above, and make sure to click "Save Changes" after a successful validation.', 'foobox');
				?>
			</p>
		<?php
		}
	}
}