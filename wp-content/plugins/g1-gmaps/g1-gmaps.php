<?php
/*
Plugin Name:    G1 GMaps
Plugin URI:     http://www.bringthepixel.com
Description:    Awesome Google Maps
Author:         bringthepixel
Version:        0.6.1
Author URI:     http://www.bringthepixel.com
License: 		Located in the 'Licensing' folder
License URI: 	Located in the 'Licensing' folder
*/

// Prevent direct script access
if ( ! defined( 'ABSPATH' ) ) {
	die ( 'No direct script access allowed' );
}
?>
<?php

if ( ! class_exists( 'G1_GMaps' ) ):

	class G1_GMaps {
		private $version = '0.6.1';

		public function __construct() {
			$this->setup_hooks();

			if ( is_admin() ) {
				require_once( 'g1-gmaps-admin.php' );
			} else {
				require_once( 'g1-gmaps-front.php' );
			}
		}

		public function setup_hooks() {
			// Standard hooks for plugins
			register_activation_hook( $this->get_plugin_basename(__FILE__), array( $this, 'activate' ) );
			register_deactivation_hook( $this->get_plugin_basename(__FILE__), array( $this, 'deactivate' ) );
			register_uninstall_hook( $this->get_plugin_basename(__FILE__), array( 'G1_GMaps', 'uninstall' ) );

			add_filter( 'plugins_url', array( $this, 'fix_plugin_url_symlink' ), 10, 3 );

			add_action( 'init', array( $this, 'register_map_post_type' ) );
			add_action( 'init', array( $this, 'register_map_marker_post_type' ) );
			add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
		}

		public function get_map_api_version() {
			return apply_filters( 'g1_gmaps_map_api_version', '3' );
		}

		public function load_textdomain() {
			load_plugin_textdomain( 'g1_gmaps', false, dirname( $this->get_plugin_basename( __FILE__ ) ) . '/languages/' );
		}

		public function get_supported_post_types() {
			$feature    = $this->get_map_marker_feature_name();
			$post_types = get_post_types();

			foreach ( $post_types as $key => $value ) {
				if ( ! post_type_supports( $value, $feature ) ) {
					unset( $post_types[$key] );
				}
			}

			return $post_types;
		}

		public function get_default_map_config () {
			return array(
				'width' 			   => '800',
				'height' 			   => '400',
				'full_width' 		   => 'standard',
				'lat'                  => '27.37223262859307',
				'long'                 => '-51.154410499999976',
				'zoom'                 => 1,
				'custom_colors'        => 'none',
				'color_hue'            => 0,
				'color_saturation'     => 0,
				'color_lightness'      => 0,
				'color_gamma'          => 1,
				'invert_lightness'     => '',
				'type'                 => 'roadmap',
				'type_control'         => 'horizontal',
				'pan_control'          => 'standard',
				'scale_control'        => 'standard',
				'zoom_control'         => 'small',
				'street_view_control'  => 'standard',
				'overview_control'     => 'none',
				'scroll_wheel_to_zoom' => 'none',
				'double_click_to_zoom' => 'standard',
				'draggable'            => 'standard',
				'parallax'             => 'none',
			);
		}

		public function get_default_map_marker_config () {
			return array(
				'lat'        => '',
				'long'       => '',
				'label'      => '',
				'icon_id'    => '',
				'icon_path'  => '',
				'info'       => '',
				'info_state' => 'none',
				'visibility' => 'standard',
			);
		}

		public function enqueue_scripts() {
			$dir_url = trailingslashit( G1_GMaps()->get_plugin_dir_url() );

			wp_register_script( 'google-maps', 'https://maps.googleapis.com/maps/api/js?sensor=false', array(), '3', true );
			wp_register_script( 'google-maps-utility-library-infobox', $dir_url . 'js/infobox_packed.js', array( 'google-maps' ), G1_GMaps()->get_version(), true );
			wp_register_script( 'jquery-metadata', $dir_url . 'js/jquery-metadata/jquery.metadata.js', array( 'jquery' ), G1_GMaps()->get_version(), true );
			wp_register_script( 'skrollr', $dir_url . 'js/skrollr/skrollr.min.js', array( 'jquery' ), G1_GMaps()->get_version(), true );
			wp_register_script( 'g1-gmaps', $dir_url . 'js/g1-gmaps.js', array( 'jquery' ), G1_GMaps()->get_version(), true );

			wp_enqueue_script( 'google-maps' );
			wp_enqueue_script( 'google-maps-utility-library-infobox' );
			wp_enqueue_script( 'jquery-metadata' );
			wp_enqueue_script( 'skrollr' );
			wp_enqueue_script( 'g1-gmaps' );
		}

		/**
		 * Gets available UI styles
		 *
		 * @return array
		 */
		public function get_ui_styles() {
			$ui_styles = array(
				'default'      => array( 'g1gmap-ui-default' ),
				'simple-light' => array( 'g1gmap-ui-simple', 'g1gmap-ui-simple-light' ),
				'simple-dark'  => array( 'g1gmap-ui-simple', 'g1gmap-ui-simple-dark' )
			);

			/* You can add your own UI styles */

			return apply_filters( 'g1_gmaps_ui_styles', $ui_styles );
		}


		public function register_map_post_type() {
			$args = array(
				'label'               => __( 'GMap', 'g1_maps' ),
				'labels'              => array(
					'name'               => __( 'GMaps', 'g1_gmaps' ),
					'singular_name'      => __( 'GMap', 'g1_gmaps' ),
					'menu_name'          => _x( 'G1 GMaps', 'Admin menu name', 'g1_gmaps' ),
					'add_new'            => __( 'Add GMap', 'g1_gmaps' ),
					'add_new_item'       => __( 'Add New GMap', 'g1_gmaps' ),
					'edit'               => __( 'Edit', 'g1_gmaps' ),
					'edit_item'          => __( 'Edit GMap', 'g1_gmaps' ),
					'new_item'           => __( 'New GMap', 'g1_gmaps' ),
					'view'               => __( 'View GMap', 'g1_gmaps' ),
					'view_item'          => __( 'View GMap', 'g1_gmaps' ),
					'search_items'       => __( 'Search GMaps', 'g1_gmaps' ),
					'not_found'          => __( 'No GMaps found', 'g1_gmaps' ),
					'not_found_in_trash' => __( 'No GMaps found in trash', 'g1_gmaps' ),
					'parent'             => __( 'Parent GMap', 'g1_gmaps' )
				),
				'public'              => true,
				'exclude_from_search' => true,
				'publicly_queryable'  => false,
				'show_ui'             => true,
				'show_in_nav_menus'   => false,
				'show_in_menu'        => true,
				'show_in_admin_bar'   => false,
				'menu_icon'           => 'dashicons-location-alt',
				'hierarchical'        => false,
				'supports'            => array(
					'title',
					'thumbnail',
				),
				'rewrite'             => false,
				'query_var'           => true,
				'can_export'          => true,
				'has_archive'         => false,
			);

			register_post_type( $this->get_post_type(), apply_filters( 'g1_gmaps_register_post_type_g1_map', $args ) );
		}

		public function register_map_marker_post_type() {
			$args = array(
				'label'               => __( 'GMap marker', 'g1_gmaps' ),
				'public'              => true,
				'exclude_from_search' => true,
				'publicly_queryable'  => false,
				'show_ui'             => true,
				'show_in_nav_menus'   => false,
				'show_in_menu'        => false,
				'show_in_admin_bar'   => false,
				'hierarchical'        => false,
				'supports'            => array(
					'title',
					'thumbnail',
					$this->get_map_marker_feature_name(),
				),
				'rewrite'             => false,
				'query_var'           => true,
				'can_export'          => true,
				'has_archive'         => false,
			);

			register_post_type( $this->get_map_marker_post_type(), apply_filters( 'g1_gmaps_register_post_type_g1_map_marker', $args ) );

		}

		public function get_map_list() {
			$data = array();

			$posts = get_posts( array(
				'posts_per_page' => - 1,
				'post_type'      => $this->get_post_type()
			) );

			if ( is_array( $posts ) ) {
				$data['type'] = 'success';
				$data['list'] = array();

				foreach ( $posts as $post ) {
					$data['list'][$post->ID] = $post->post_title;
				}
			} else {
				$data['type']    = 'error';
				$data['message'] = __( 'Some errors occurred while fetching map list!', 'g1_gmaps' );
			}

			return $data;
		}

		public function get_post_type() {
			return 'g1_gmap';
		}

		public function get_map_marker_post_type() {
			return 'g1_gmap_marker';
		}

		public function get_map_marker_feature_name() {
			return 'g1-gmaps';
		}

		public function fix_plugin_url_symlink( $url, $path, $plugin ) {
			if ( strstr( $plugin, basename( __FILE__ ) ) ) {
				return str_replace( dirname( __FILE__ ), '/' . basename( dirname( $plugin ) ), $url );
			}

			return $url;
		}

		public static function get_plugin_dir_path() {
			return plugin_dir_path( __FILE__ );
		}

		public static function get_plugin_dir_url() {
			return plugin_dir_url( __FILE__ );
		}

		public function get_plugin_basename( $filepath ) {
			$file          = str_replace( '\\', '/', $filepath ); // sanitize for Win32 installs
			$file          = preg_replace( '|/+|', '/', $file ); // remove any duplicate slash
			$plugin_dir    = str_replace( '\\', '/', WP_PLUGIN_DIR ); // sanitize for Win32 installs
			$plugin_dir    = preg_replace( '|/+|', '/', $plugin_dir ); // remove any duplicate slash
			$mu_plugin_dir = str_replace( '\\', '/', WPMU_PLUGIN_DIR ); // sanitize for Win32 installs
			$mu_plugin_dir = preg_replace( '|/+|', '/', $mu_plugin_dir ); // remove any duplicate slash
			$sp_plugin_dir = dirname( $filepath );
			$sp_plugin_dir = dirname( $sp_plugin_dir );
			$sp_plugin_dir = preg_replace( '|/+|', '/', $sp_plugin_dir ); // remove any duplicate slash
			$file          = preg_replace( '#^' . preg_quote( $sp_plugin_dir, '#' ) . '/|^' . preg_quote( $plugin_dir, '#' ) . '/|^' . preg_quote( $mu_plugin_dir, '#' ) . '/#', '', $file ); // get relative path from plugins dir
			$file          = trim( $file, '/' );

			return $file;
		}

		public function get_version() {
			return $this->version;
		}

		public function activate() {
			do_action('g1_gmaps_plugin_activate');
		}

		public function deactivate() {
		}

		public static function uninstall() {
		}
	}
endif;

if ( ! function_exists( 'G1_GMaps' ) ):

	function G1_GMaps() {
		static $instance = null;

		if ( null === $instance ) {
			$instance = new G1_GMaps();
		}

		return $instance;
	}

endif;

/* Fire in a hole :) */
G1_GMaps();

/**
 * Shortcode
 */
function g1_gmaps_shortcode( $atts ) {
	static $counter;

	extract( shortcode_atts( array(
		'id'     => '',
		'class'  => '',
		'map_id' => '',
	), $atts ) );

	// Sanitize attribute values
	$map_id = absint( $map_id );

	if ( ! $map_id ) {
		return '';
	}

	// Enqueue JavaScripts in the footer
	add_action( 'wp_footer', array( G1_GMaps(), 'enqueue_scripts' ) );

	$meta = get_post_meta( $map_id, '_g1_gmap', true );

	// Compose final HTML id attribute
	$counter++;
	$final_id = strlen( $id ) ? $id : 'g1gmap-'. $counter . '-' . $map_id;

	// Compose final HTML class attribute
	$final_classes = array(
		'g1gmap'
	);
	$final_classes = array_merge( $final_classes, explode( ' ', $class ) );


	$config = array();

	if ( is_array( $meta ) ) {
		// remove "map_" prefix
		foreach ( $meta as $meta_key => $meta_value ) {
			$meta_key = preg_replace( '/^map_/', '', $meta_key );

			$config[$meta_key] = $meta_value;
		}
	}

	$defaults = G1_GMaps()->get_default_map_config();

	$config = wp_parse_args( $config, $defaults );

	$map_width      = $config['width'];
	$map_height     = $config['height'];
	$map_full_width = $config['full_width'] === 'standard';

	if ( $map_full_width ) {
		$map_width = '100%';
	}

	if ( ! $map_width || ! $map_height ) {
		return __( 'You need to set map width (or force full width) and height.', 'g1_gmaps' );
	}

	if ( is_admin() ) {
		$config['parallax'] = 'none';
	}

	$map_height .= 'px';

	if ( ! $map_full_width ) {
		$map_width .= 'px';
	}

	$data_attr = " data-g1gmap-config='" . json_encode( $config ) . "'";

	// markers
	$args = array(
		'meta_key'   => '_g1_gmap_id',
		'meta_value' => $map_id,
		'post_type'  => G1_GMaps()->get_supported_post_types(),
		'orderby'    => 'ID',
		'order'      => 'ASC',
		'posts_per_page' => - 1,
	);
	$markers = get_posts( $args );

	$out = '';
	$out .= '<div style="height: ' . esc_attr( $map_height ) . ';" class="' . implode( ' ', array_map( 'sanitize_html_class', $final_classes ) ) . '">';

		foreach ( $markers as $marker ) {
			$meta = get_post_meta( $marker->ID, '_g1_gmap_marker', true );
			$meta['id'] = $marker->ID;

			$marker_info = '';

			if ( isset( $meta['info'] ) ) {
				$marker_info = $meta['info'];
				unset($meta['info']);
			}

			$marker_data_attr = " data-g1gmap-marker-config='" . json_encode( $meta ) . "'";

			$out .= '<div class="g1gmap-marker-config" '. $marker_data_attr .'>'. $marker_info .'</div>';
		}

		$out .= '<div class="g1gmap-main" style="width: 100%; height: ' . esc_attr( $map_height ) . ';">';
			$out .= '<div style="width: 100%; height: 100%;" id="' . esc_attr( $final_id ) . '" class="g1gmap-inner" ' . $data_attr . '>';
			$out .= '</div>';

			$out .= '<div class="g1gmap-observer"></div>';
		$out .= '</div>';

		$out .= '<div class="g1gmap-side">';
		$out .= '</div>';

	$out .= '</div>';

	return $out;
}

add_shortcode( 'g1_gmap', 'g1_gmaps_shortcode' );
add_shortcode( 'g1_gmaps', 'g1_gmaps_shortcode' );

/**
 * Widget
 */
class G1_Gmaps_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'g1_gmaps_widget', // Base ID
			__( 'G1 Gmaps', 'g1_gmaps' ), // Name
			array( 'description' => __( 'G1 GMaps Widget', 'g1_gmaps' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $args['before_widget'];

		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		echo do_shortcode( '[g1_gmap map_id="' . $instance['map_id'] . '"]' );

		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		if ( isset( $instance['title'] ) ) {
			$title = $instance['title'];
		} else {
			$title = __( 'New title', 'g1_gmaps' );
		}
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php
		if ( isset( $instance['map_id'] ) ) {
			$map_id = $instance['map_id'];
		} else {
			$map_id = null;
		}

		$maps = G1_GMaps()->get_map_list();
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'map_id' ); ?>"><?php _e( 'Map:' ); ?></label>
			<select name="<?php echo $this->get_field_name( 'map_id' ); ?>" id="<?php echo $this->get_field_id( 'map_id' ); ?>">
				<?php foreach ( $maps['list'] as $id => $title ): ?>
					<option value="<?php echo esc_attr( $id ) ?>" <?php selected( $map_id, $id ); ?>><?php echo esc_html( $title ); ?></option>
				<?php endforeach; ?>
			</select>
		</p>
	<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance           = array();
		$instance['title']  = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['map_id'] = ( ! empty( $new_instance['map_id'] ) ) ? strip_tags( $new_instance['map_id'] ) : '';

		return $instance;
	}

}

// register widget
function register_g1_gmaps_widget() {
	register_widget( 'G1_Gmaps_Widget' );
}

add_action( 'widgets_init', 'register_g1_gmaps_widget' );