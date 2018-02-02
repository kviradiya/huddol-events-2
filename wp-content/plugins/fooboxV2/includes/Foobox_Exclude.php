<?php
/**
 * Foobox_Exclude class
 * class which allows you to exclude FooBox from specific pages and posts
 */

if ( !class_exists( 'Foobox_Exclude' ) ) {

	class Foobox_Exclude {

		function __construct() {
			add_action( 'init', array($this, 'init') );
		}

		function init() {
			if ( is_admin() ) {
				add_action( 'add_meta_boxes', array($this, 'add_metaboxes') );
				add_action( 'save_post', array($this, 'save_meta') );
			} else {
				add_filter( 'foobox_enqueue_scripts', array($this, 'enqueue_scripts_or_styles'), 99 );
				add_filter( 'foobox_enqueue_styles', array($this, 'enqueue_scripts_or_styles'), 99 );
			}
		}

		function add_metaboxes() {
			$post_types = apply_filters( 'foobox_exclude_post_types', array('post', 'page') );

			foreach ( $post_types as $post_type ) {

				add_meta_box(
					$post_type . '_foobox_exclude'
					, __( 'FooBox Exclude', 'foobox' )
					, array($this, 'render_meta_box')
					, $post_type
					, 'side'
					, 'default'
				);

			}
		}

		function render_meta_box($post) {
			$exclude = get_post_meta( $post->ID, '_foobox_exclude', true );
			?>
			<input type="hidden" name="foobox_exclude_nonce"
				   value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>"/>
			<table class="form-table">
				<tr>
					<td colspan="2">
						<input id="foobox_exclude_check"
							   name="foobox_exclude_check" <?php echo ($exclude == "exclude") ? 'checked="checked"' : ""; ?>
							   type="checkbox" value="exclude">
						<label
							for="foobox_exclude_check"><?php _e( 'Exclude FooBox from this page or post', 'foobox' ); ?></label>
					</td>
				</tr>
			</table>
		<?php
		}

		function save_meta($post_id) {

			// check autosave
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return $post_id;
			}

			// verify nonce
			if ( array_key_exists( 'foobox_exclude_nonce', $_POST ) &&
				wp_verify_nonce( $_POST['foobox_exclude_nonce'], plugin_basename( __FILE__ ) )
			) {
				$exclude = array_key_exists( 'foobox_exclude_check', $_POST ) ? $_POST['foobox_exclude_check'] : '';
				if ( empty($exclude) ) {
					delete_post_meta( $post_id, '_foobox_exclude' );
				} else {
					update_post_meta( $post_id, '_foobox_exclude', $exclude );
				}
			}
		}

		function enqueue_scripts_or_styles() {
			global $post;
			if (isset($post) && is_singular($post)) {
				$exclude = get_post_meta( $post->ID, '_foobox_exclude', true );

				if ('exclude' === $exclude) return false;
			}
			return true;
		}
	}
}