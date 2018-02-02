<?php
// Prevent direct script access
if ( ! defined( 'ABSPATH' ) ) {
	die ( 'No direct script access allowed' );
}
?>
<?php

if ( ! class_exists( 'G1_GMaps_Admin' ) ):
	class G1_GMaps_Admin {

		public function __construct() {
			$this->setup_hooks();
		}

		public function setup_hooks() {
			add_action( 'load-post.php', array( $this, 'setup_meta_boxes' ) );
			add_action( 'load-post-new.php', array( $this, 'setup_meta_boxes' ) );
			add_action( 'save_post', array( $this, 'save_meta_boxes' ) );

			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

			add_action( 'wp_ajax_g1gmap_add_marker', array( $this, 'ajax_add_marker' ), 1000 );
			add_action( 'wp_ajax_g1gmap_save_marker', array( $this, 'ajax_save_marker' ), 1000 );
			add_action( 'wp_ajax_g1gmap_remove_marker', array( $this, 'ajax_remove_marker' ), 1000 );

			// hooks for list page
			add_action( 'load-edit.php', array( $this, 'on_admin_page_load' ) );
		}

		public function ajax_add_marker() {
			check_ajax_referer( 'g1-gmaps-update-markers', 'security' );

			$ajax_data = $_POST['ajax_data'];

			$post_data = array(
				'post_status' => 'publish',
				'post_type'   => G1_GMaps()->get_map_marker_post_type(),
			);

			$post_id = wp_insert_post( $post_data );

			if ( $post_id === 0 ) {
				die();
			}

			$meta_data = G1_GMaps()->get_default_map_marker_config();

			$meta_data['lat'] = $ajax_data['lat'];
			$meta_data['long'] = $ajax_data['long'];
			$meta_data['label'] = $ajax_data['label'];

			update_post_meta( $post_id, '_g1_gmap_marker', $meta_data );
			update_post_meta( $post_id, '_g1_gmap_marker_lat', $meta_data['lat'] );
			update_post_meta( $post_id, '_g1_gmap_marker_long', $meta_data['long'] );
			update_post_meta( $post_id, '_g1_gmap_id', $ajax_data['map_id'] );

			echo $this->render_marker_markup( get_post( $post_id ) );
		}

		public function ajax_remove_marker() {
			check_ajax_referer( 'g1-gmaps-update-markers', 'security' );

			$ajax_data = $_POST['ajax_data'];

			$post_id = $ajax_data['id'];

			if ( ! $post_id ) {
				die();
			}

			$post = get_post( $post_id );

			if ( $post->post_type === G1_GMaps()->get_map_marker_post_type() ) {
				// our built-in marker can be removed
				$post = wp_delete_post( $post_id, true );

				if ( ! $post ) {
					die();
				}
			} else {
				// we can't remove post, we can only remove its marker metadata
				delete_post_meta( $post_id, '_g1_gmap_id' );
			}

			echo 'Marker successfully removed.';
		}

		public function ajax_save_marker() {
			check_ajax_referer( 'g1-gmaps-update-markers', 'security' );

			$ajax_data = $_POST['ajax_data'];

			$post_id = $ajax_data['id'];

			unset( $ajax_data['id'] );

			if ( ! $post_id ) {
				die();
			}

			$post = get_post( $post_id );

			// checks if post supports map feature
			if ( ! in_array( $post->post_type, G1_GMaps()->get_supported_post_types() ) ) {
				die();
			}

			update_post_meta( $post_id, '_g1_gmap_marker_lat', $ajax_data['lat'] );
			update_post_meta( $post_id, '_g1_gmap_marker_long', $ajax_data['long'] );
			update_post_meta( $post_id, '_g1_gmap_marker', $ajax_data );

			echo 'Marker successfully updated.';
		}

		public function on_admin_page_load() {
			if ( isset( $_GET['post_type'] ) && $_GET['post_type'] === G1_GMaps()->get_post_type() ) {
				// load hooks only for plugin main admin page
				add_filter( 'manage_edit-' . G1_GMaps()->get_post_type() . '_columns', array( $this, 'add_custom_columns' ) );
				add_action( 'manage_' . G1_GMaps()->get_post_type() . '_posts_custom_column', array( $this, 'manage_custom_columns' ), 10, 2 );
				add_filter( 'post_row_actions', array( $this, 'remove_item_row_actions' ), 10, 2 );
			}
		}

		public function remove_item_row_actions( $actions, $post ) {
			global $current_screen;

			if ( $current_screen->post_type !== G1_GMaps()->get_post_type() ) {
				return $actions;
			}

			unset( $actions['inline hide-if-no-js'] );

			return $actions;
		}

		public function add_custom_columns( $columns ) {
			$new_columns = array();

			foreach ( $columns as $id => $value ) {
				$new_columns[$id] = $value;

				// add new columns after title column
				if ( $id === 'title' ) {
					$new_columns['shortcode'] = __( 'Shortcode', 'g1_gmaps' );
				}
			}

			return $new_columns;
		}

		public function manage_custom_columns( $column, $post_id ) {
			switch ( $column ) {
				case 'shortcode':
					echo '<input onclick="jQuery(this).select()" type="text" value="[g1_gmap map_id=&quot;' . $post_id . '&quot;]" />';
					break;
			}
		}

		public function register_tinymce_button( $buttons ) {
			array_push( $buttons, 'g1_gmaps' );

			return $buttons;
		}

		public function add_tinymce_plugin( $plugin_array ) {
			$dir_url = trailingslashit( G1_GMaps()->get_plugin_dir_url() );

			$plugin_array['g1_gmaps'] = $dir_url . 'js/g1-map-visual-editor-plugin.js';

			return $plugin_array;
		}

		public function enqueue_styles( $hook ) {
			global $post;

			if ( 'post-new.php' === $hook || 'post.php' == $hook ) {
				if ( G1_GMaps()->get_post_type() === $post->post_type ) {

					$dir_url = trailingslashit( G1_GMaps()->get_plugin_dir_url() );

					wp_register_style( 'g1-jquery-rangeinput', $dir_url . 'js/jquery.rangeinput/jquery.rangeinput.css', array(), G1_GMaps()->get_version() );
					wp_register_style( 'g1-gmaps', $dir_url . 'css/g1-gmaps.css', array(), G1_GMaps()->get_version() );
					wp_register_style( 'g1-gmaps-admin', $dir_url . 'css/g1-gmaps-admin.css', array(), G1_GMaps()->get_version() );

					wp_enqueue_style( 'wp-color-picker' );
					wp_enqueue_style( 'g1-jquery-rangeinput' );
					wp_enqueue_style( 'g1-gmaps' );
					wp_enqueue_style( 'g1-gmaps-admin' );
				}
			}
		}

		public function enqueue_scripts( $hook ) {
			global $post;

			if ( 'post-new.php' === $hook || 'post.php' == $hook ) {
				if ( G1_GMaps()->get_post_type() === $post->post_type ) {

					$dir_url = trailingslashit( G1_GMaps()->get_plugin_dir_url() );

					G1_GMaps()->enqueue_scripts();

					wp_register_script( 'g1-jquery-rangeinput', $dir_url . 'js/jquery.rangeinput/jquery.rangeinput.min.js', array( 'jquery' ), G1_GMaps()->get_version(), true );
					wp_register_script( 'g1-gmaps-admin', $dir_url . 'js/g1-gmaps-admin.js', array( 'jquery', 'wp-color-picker' ), G1_GMaps()->get_version(), true );

					wp_enqueue_script( 'g1-jquery-rangeinput' );
					wp_enqueue_script( 'g1-gmaps-admin' );

					wp_localize_script(
						'g1-gmaps-admin',
						'g1_gmap_i18n',
						array(
							'required_fields_missing_msg' => __( 'You need to fill all required fields', 'g1_gmaps' ),
							'remove_marker_confirm_msg'   => __( 'Are you sure you want to remove this marker?', 'g1_gmaps' ),
							'remove_marker_error_msg'     => __( 'Some error occurred while saving. Marker couldn\'t be removed!', 'g1_gmaps' ),
							'add_marker_error_msg'        => __( 'Some error occurred while saving. Marker couldn\'t be added!', 'g1_gmaps' ),
							'save_marker_error_msg'       => __( 'Some error occurred while saving. Marker couldn\'t be updated!', 'g1_gmaps' ),
							'invalid_coordinates_msg'     => __( 'At least one of specified coordinates are invalid!', 'g1_gmaps' ),
							'geocoding_failure_msg'       => __( 'Geocode was not successful for the following reason: ', 'g1_gmaps' ),
							'unsaved_markers_confirm_msg' => __( 'Some markers have been changed. Do you want to proceed and lose these changes?', 'g1_gmaps' ),
						)
					);
				}
			}
		}

		public function setup_meta_boxes() {
			add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		}

		public function add_meta_boxes() {
			$this->add_map_meta_boxes();
			$this->add_map_feature_meta_boxes();
		}

		protected function add_map_meta_boxes() {
			// Size
			add_meta_box(
				'g1-gmaps-map-size-metabox', // Unique ID
				__( 'Size', 'g1_gmaps' ), // Title
				array( $this, 'render_map_size_meta_box' ), // Callback function
				G1_GMaps()->get_post_type(), // Post type
				'side', // Context
				'default' // Priority
			);

			// Center Point
			add_meta_box(
				'g1-gmaps-map-center-point-metabox', // Unique ID
				__( 'Center Point', 'g1_gmaps' ), // Title
				array( $this, 'render_map_center_point_meta_box' ), // Callback function
				G1_GMaps()->get_post_type(), // Post type
				'normal', // Context
				'default' // Priority
			);

			// Preview
			add_meta_box(
				'g1-gmaps-map-preview-metabox', // Unique ID
				__( 'Preview', 'g1_gmaps' ), // Title
				array( $this, 'render_map_preview_meta_box' ), // Callback function
				G1_GMaps()->get_post_type(), // Post type
				'normal', // Context
				'default' // Priority
			);

			// Appearance - UI
			add_meta_box(
				'g1-gmaps-map-appearance-ui-metabox', // Unique ID
				__( 'User Interface', 'g1_gmaps' ), // Title
				array( $this, 'render_map_appearance_ui_meta_box' ), // Callback function
				G1_GMaps()->get_post_type(), // Post type
				'side', // Context
				'default' // Priority
			);

			// Appearance - Colors
			add_meta_box(
				'g1-gmaps-map-appearance-colors-metabox', // Unique ID
				__( 'Custom Colors', 'g1_gmaps' ), // Title
				array( $this, 'render_map_appearance_colors_meta_box' ), // Callback function
				G1_GMaps()->get_post_type(), // Post type
				'side', // Context
				'default' // Priority
			);

			// Markers
			add_meta_box(
				'g1-gmaps-map-markers-metabox', // Unique ID
				__( 'Markers', 'g1_gmaps' ), // Title
				array( $this, 'render_map_markers_meta_box' ), // Callback function
				G1_GMaps()->get_post_type(), // Post type
				'normal', // Context
				'default' // Priority
			);
		}

		protected function add_map_feature_meta_boxes() {
			$post_types = G1_GMaps()->get_supported_post_types();

			foreach ( $post_types as $post_type ) {
				add_meta_box(
					'g1-gmaps-feature-metabox', // Unique ID
					__( 'Map marker', 'g1_gmaps' ), // Title
					array( $this, 'render_map_marker_meta_box' ), // Callback function
					$post_type, // Post type
					'normal', // Context
					'default' // Priority
				);
			}
		}

		public function render_map_size_meta_box( $post ) {
			// Add an nonce field so we can check for it later.
			wp_nonce_field( 'g1_gmaps_map_size_meta_box', 'g1_gmaps_map_size_meta_box_nonce' );

			// post stored data
			$values = get_post_meta( $post->ID, '_g1_gmap', true );

			// defined defaults
			$defaults = $this->get_map_fields_defaults( 'g1_gmaps_map_size_meta_box' );

			// apply filter on defaults
			$defaults = apply_filters( 'g1_gmaps_map_size_defaults', $defaults );

			// merge values from the database with defaults (on first load in db there are no values)
			$values = wp_parse_args( $values, $defaults );
			?>
			<p class="g1gmap-admin-form-row">
				<label><strong><?php _e( 'Width', 'g1_gmaps' ); ?> <span class="required">*</span></strong></label>
				<input class="g1gmap-map-width" type="text" name="map_width" value="<?php echo esc_attr( $values['map_width'] ); ?>" /> px
				<br />
				<label><input class="g1gmap-map-full-width" type="checkbox" name="map_full_width" value="standard" <?php checked( $values['map_full_width'], 'standard' ); ?> /> <?php _e( 'Force full width', 'g1_gmaps' ); ?>
				</label>
			</p>
			<p class="g1gmap-admin-form-row">
				<label><strong><?php _e( 'Height', 'g1_gmaps' ); ?> <span class="required">*</span></strong></label>
				<input class="g1gmap-map-height" type="text" name="map_height" value="<?php echo esc_attr( $values['map_height'] ); ?>" /> px
			</p>
		<?php
		}

		public function render_map_center_point_meta_box( $post ) {
			// Add an nonce field so we can check for it later.
			wp_nonce_field( 'g1_gmaps_map_center_point_meta_box', 'g1_gmaps_map_center_point_meta_box_nonce' );

			// post stored data
			$values = get_post_meta( $post->ID, '_g1_gmap', true );

			// defined defaults
			$defaults = $this->get_map_fields_defaults( 'g1_gmaps_map_center_point_meta_box' );

			// apply filter on defaults
			$defaults = apply_filters( 'g1_gmaps_map_center_point_defaults', $defaults );

			// merge values from the database with defaults (on first load in db there are no values)
			$values = wp_parse_args( $values, $defaults );
			?>
			<table class="form-table">
				<tbody>
				<tr valign="top" class="hide-if-no-js">
					<th scope="row">
						<label><?php _e( 'Location', 'g1_gmaps' ); ?></label>
					</th>
					<td>
						<input type="text" placeholder="<?php _e( 'Type an address&hellip;', 'g1_gmaps' ); ?>" data-g1-geocode-for="<?php echo esc_attr( $post->ID ) ?>" class="g1gmap-geocoding-field" name="map_location" />
						<input class="button" type="button" value="<?php echo _e( 'Find', 'g1_gmaps' ); ?>">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label><?php _e( 'Latitude', 'g1_gmaps' ); ?> <span class="required">*</span></label>
					</th>
					<td>
						<input data-g1-lat-for="<?php echo esc_attr( $post->ID ) ?>" type="text" name="map_lat" value="<?php echo esc_attr( $values['map_lat'] ); ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label><?php _e( 'Longitude', 'g1_gmaps' ); ?> <span class="required">*</span></label>
					</th>
					<td>
						<input data-g1-long-for="<?php echo esc_attr( $post->ID ) ?>" type="text" name="map_long" value="<?php echo esc_attr( $values['map_long'] ); ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label><?php _e( 'Zoom', 'g1_gmaps' ); ?></label>
					</th>
					<td>
						<input class="g1gmap-map-zoom" type="range" min="0" max="19" step="1" name="map_zoom" value="<?php echo esc_attr( $values['map_zoom'] ); ?>" />
					</td>
				</tr>
				</tbody>
			</table>
		<?php
		}

		public function render_map_markers_meta_box( $post ) {
			// Add an nonce field so we can check for it later.
			wp_nonce_field( 'g1_gmaps_map_markers_meta_box', 'g1_gmaps_markers_meta_box_nonce' );

			// post stored data
			$values = get_post_meta( $post->ID, '_g1_gmap', true );

			// defined defaults
			$defaults = $this->get_map_fields_defaults( 'g1_gmaps_map_markers_meta_box' );

			// apply filter on defaults
			$defaults = apply_filters( 'g1_gmaps_map_markers_defaults', $defaults );

			// merge values from the database with defaults (on first load in db there are no values)
			$values = wp_parse_args( $values, $defaults );

			$args = array(
				'meta_key'   => '_g1_gmap_id',
				'meta_value' => $post->ID,
				'post_type'  => G1_GMaps()->get_supported_post_types(),
				'orderby'    => 'ID',
				'order'      => 'ASC',
				'posts_per_page' => - 1,
			);

			$markers = get_posts( $args );

			// new marker
			$ajax_nonce = wp_create_nonce( 'g1-gmaps-update-markers' );
			?>

			<ul class="g1gmap-markers">
				<?php foreach ( $markers as $marker ): ?>
					<?php echo $this->render_marker_markup( $marker ); ?>
				<?php endforeach; ?>

				<li class="g1gmap-marker-new g1gmap-marker-off" id="g1gmap-new-marker">
					<div class="g1gmap-marker-toggle"><?php echo _e( 'Add new marker', 'g1_gmaps' ); ?></div>
					<div class="g1gmap-marker-content">
						<table class="form-table">
							<tbody>
								<tr valign="top">
									<th scope="row">
										<label><?php _e( 'Location', 'g1_gmaps' ); ?></label>
									</th>
									<td>
										<input type="text" placeholder="<?php _e( 'Type an address&hellip;', 'g1_gmaps' ); ?>" data-g1-geocode-for="new-marker" class="g1gmap-geocoding-field" name="map_location" />
										<input class="button" type="button" value="<?php echo _e( 'Find', 'g1_gmaps' ); ?>">
									</td>
								</tr>
								<tr valign="top">
									<th valign="top">
										<label><?php _e( 'Label', 'g1_gmaps' ); ?> <span class="required">*</span></label>
									</th>
									<td>
										<input data-g1-label-for="new-marker" type="text" name="marker_label" value="" /><br />
									</td>
								</tr>
								<tr valign="top">
									<th scope="row">
										<label><?php _e( 'Latitide', 'g1_gmaps' ); ?> <span class="required">*</span></label>
									</th>
									<td>
										<input data-g1-lat-for="new-marker" type="text" name="marker_lat" value="" />
									</td>
								</tr>
								<tr valign="top">
									<th scope="row">
										<label><?php _e( 'Longitude', 'g1_gmaps' ); ?> <span class="required">*</span></label>
									</th>
									<td>
										<input data-g1-long-for="new-marker" class="g1gmap--geocoding-long" type="text" name="marker_long" value="" />
									</td>
								</tr>
							</tbody>
						</table>

						<p class="g1gmap-marker-actions">
							<a href="#" class="button button-small g1gmap-add-marker"><?php _e( 'Add', 'g1_gmaps' ); ?></a>
							<a href="#" data-g1-locate-for="new-marker" class="button button-small g1gmap-locate-marker"><?php _e( 'Locate on map', 'g1_gmaps' ); ?></a>
							<input type="hidden" name="update_nonce" value="<?php echo esc_attr( $ajax_nonce ); ?>" />
							<input type="hidden" name="map_id" value="<?php echo esc_attr( $post->ID ); ?>" />
						</p>
					</div>
				</li>
			</ul>

			<p class="g1gmap-markers-actions">
				<a href="#" class="button g1gmap-save-all-markers"><?php _e( 'Save all markers', 'g1_gmaps' ); ?></a>
			</p>
		<?php
		}

		protected function render_marker_markup( $post ) {
			$id = $post->ID;

			$values = get_post_meta( $id, '_g1_gmap_marker', true );

			$marker_type_label = '';

			if ( $post->post_type !== G1_GMaps()->get_map_marker_post_type() ) {
				$obj               = get_post_type_object( $post->post_type );
				$marker_type_label = ' (' . $obj->labels->singular_name . ')';
			}
			?>
			<li class="g1gmap-marker g1gmap-marker-off">
				<div class="g1gmap-marker-toggle"><?php echo esc_html( $values['label'] ); ?></div>
				<div class="g1gmap-marker-content">
					<table class="form-table">
						<tbody>
							<tr valign="top">
								<th scope="row">
									<label><?php _e( 'Label', 'g1_gmaps' ); ?></label>
								</th>
								<td>
									<input type="text" name="marker_label" value="<?php echo esc_attr( $values['label'] ); ?>" /><?php echo esc_html( $marker_type_label ); ?>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
										<label><?php _e( 'Location', 'g1_gmaps' ); ?></label>
								</th>
								<td>
										<input type="text" placeholder="<?php _e( 'Type an address', 'g1_gmaps' ); ?>" data-g1-geocode-for="<?php echo esc_attr( $id ) ?>" class="g1gmap-geocoding-field" name="map_location" />
										<input class="button" type="button" value="<?php echo _e( 'Find', 'g1_gmaps' ); ?>">

								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label><?php _e( 'Latitude', 'g1_gmaps' ); ?></label>
								</th>
								<td>
									<input type="text" data-g1-lat-for="<?php echo esc_attr( $id ) ?>" name="marker_lat" value="<?php echo esc_attr( $values['lat'] ); ?>" />
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label><?php _e( 'Longitude', 'g1_gmaps' ); ?></label>
								</th>
								<td>
									<input type="text" data-g1-long-for="<?php echo esc_attr( $id ) ?>" name="marker_long" value="<?php echo esc_attr( $values['long'] ); ?>" />
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label><?php _e( 'Visibility', 'g1_gmaps' ); ?></label>
								</th>
								<td>
									<select id="marker_visibility" name="marker_visibility" rows="6">
										<option value="standard" <?php selected( $values['visibility'], 'standard' ); ?>><?php _e( 'Show', 'g1_gmaps' ); ?></option>
										<option value="none" <?php selected( $values['visibility'], 'none' ); ?>><?php _e( 'Hide', 'g1_gmaps' ); ?></option>
									</select>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label> <?php _e( 'Icon', 'g1_gmaps' ); ?></label>
								</th>
								<td>
									<div class="g1gmap-media-upload-field">
										<a href="#" class="button g1gmap-media-upload-button" title="<?php _e( 'Select an image', 'g1_gmaps' ); ?>">
											<span class="wp-media-buttons-icon"></span><?php _e( 'Select an image', 'g1_gmaps' ); ?>
										</a>
										<input class="g1gmap-media-upload-input" name="marker_icon_id" type="hidden" value="<?php echo esc_attr( $values['icon_id'] ); ?>" />
										<input class="g1gmap-media-upload-image-path" name="marker_icon_path" type="hidden" value="<?php echo esc_attr( $values['icon_path'] ); ?>" />
										<a href="#" class="button g1gmap-clear-button"><?php _e( 'Clear', 'g1_gmaps' ); ?></a>
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<?php _e( 'Description', 'g1_gmaps' ); ?>
								</th>
								<td>
									<textarea cols="60" rows="6" name="marker_info"><?php echo esc_textarea( $values['info'] ); ?></textarea>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<?php _e( 'Description visibility', 'g1_gmaps' ); ?>
								</th>
								<td>
									<select id="marker_info_state" name="marker_info_state" rows="6">
										<option value="none" <?php selected( $values['info_state'], 'none' ); ?>><?php _e( 'Leave closed on startup', 'g1_gmaps' ); ?></option>
										<option value="standard" <?php selected( $values['info_state'], 'standard' ); ?>><?php _e( 'Open on startup', 'g1_gmaps' ); ?></option>
									</select> <?php _e( '(reload page to see effect)', 'g1_gmaps' ); ?>
								</td>
							</tr>
						</tbody>
					</table>

					<input type="hidden" name="marker_id" value="<?php echo esc_attr( $id ); ?>" />

					<p class="g1gmap-marker-actions">
						<a href="#" class="button button-small button-primary g1gmap-save-marker"><?php _e( 'Save', 'g1_gmaps' ); ?></a>
						<a href="#" data-g1-locate-for="<?php echo esc_attr( $id ) ?>" class="button button-small g1gmap-locate-marker"><?php _e( 'Locate on map', 'g1_gmaps' ); ?></a>
						<a href="#" class="button button-small g1gmap-remove-marker"><?php _e( 'Remove', 'g1_gmaps' ); ?></a>
					</p>

				</div>
			</li>
		<?php
		}

		public function render_map_preview_meta_box( $post ) {
			?>

			<div id="g1gmap-preview">
				<?php echo do_shortcode( '[g1_gmap map_id="' . esc_attr( $post->ID ) . '"]' ); ?>
				<p class="g1gmap-preview-actions">
					<a href="#" class="button button-small g1gmap-change-map-center"><?php _e( 'Use current map center point', 'g1_gmaps' ); ?></a>
					<a href="#" class="button button-small g1gmap-move-map-to-center"><?php _e( 'Move map to center point', 'g1_gmaps' ); ?></a>
				</p>
			</div>

		<?php
		}

		public function render_map_appearance_ui_meta_box( $post ) {
			// Add an nonce field so we can check for it later.
			wp_nonce_field( 'g1_gmaps_map_appearance_ui_meta_box', 'g1_gmaps_map_appearance_ui_meta_box_nonce' );

			// post stored data
			$values = get_post_meta( $post->ID, '_g1_gmap', true );

			// defined defaults
			$defaults = $this->get_map_fields_defaults( 'g1_gmaps_map_appearance_ui_meta_box' );

			// apply filter on defaults
			$defaults = apply_filters( 'g1_gmaps_map_appearance_ui_defaults', $defaults );

			// merge values from the database with defaults (on first load in db there are no values)
			$values = wp_parse_args( $values, $defaults );

			?>
			<p class="g1gmap-admin-form-row">
				<label><strong><?php _e( 'Type', 'g1gmap' ); ?></strong></label>
				<select id="map_type" name="map_type">
					<option value="roadmap" <?php selected( $values['map_type'], 'roadmap' ); ?>><?php _e( 'Roadmap', 'g1_gmaps' ); ?></option>
					<option value="satellite" <?php selected( $values['map_type'], 'satellite' ); ?>><?php _e( 'Satellite', 'g1_gmaps' ); ?></option>
					<option value="hybrid" <?php selected( $values['map_type'], 'hybrid' ); ?>><?php _e( 'Hybrid', 'g1_gmaps' ); ?></option>
					<option value="terrain" <?php selected( $values['map_type'], 'terrain' ); ?>><?php _e( 'Terrain', 'g1_gmaps' ); ?></option>
				</select>
			</p>

			<p class="g1gmap-admin-form-row">
				<label><strong><?php _e( 'Type control', 'g1_gmaps' ); ?></strong></label>
				<select id="map_type_control" name="map_type_control">' .
					<option value="horizontal" <?php selected( $values['map_type_control'], 'horizontal' ); ?>><?php _e( 'horizontal', 'g1_gmaps' ); ?></option>
					<option value="dropdown" <?php selected( $values['map_type_control'], 'dropdown' ); ?>><?php _e( 'dropdown', 'g1_gmaps' ); ?></option>
					<option value="none" <?php selected( $values['map_type_control'], 'none' ); ?>><?php _e( 'hide', 'g1_gmaps' ); ?></option>
				</select>
			</p>

			<p class="g1gmap-admin-form-row">
				<label><strong><?php _e( 'Pan control', 'g1_gmaps' ); ?></strong></label>
				<select id="map_pan_control" name="map_pan_control">' .
					<option value="standard" <?php selected( $values['map_pan_control'], 'standard' ); ?>><?php _e( 'show', 'g1_gmaps' ); ?></option>
					<option value="none" <?php selected( $values['map_pan_control'], 'none' ); ?>><?php _e( 'hide', 'g1_gmaps' ); ?></option>
				</select>
			</p>

			<p class="g1gmap-admin-form-row">
				<label><strong><?php _e( 'Zoom control', 'g1_gmaps' ); ?></strong></label>
				<select id="map_zoom_control" name="map_zoom_control" rows="6">
					<option value="small" <?php selected( $values['map_zoom_control'], 'small' ); ?>><?php _e( 'show small', 'g1_gmaps' ); ?></option>
					<option value="large" <?php selected( $values['map_zoom_control'], 'large' ); ?>><?php _e( 'show large', 'g1_gmaps' ); ?></option>
					<option value="none" <?php selected( $values['map_zoom_control'], 'none' ); ?>><?php _e( 'hide', 'g1_gmaps' ); ?></option>
				</select>
			</p>

			<p class="g1gmap-admin-form-row">
				<label><strong><?php _e( 'Street view control', 'g1_gmaps' ); ?></strong></label>
				<select id="map_street_view_control" name="map_street_view_control" rows="6">
					<option value="standard" <?php selected( $values['map_street_view_control'], 'standard' ); ?>><?php _e( 'show', 'g1_gmaps' ); ?></option>
					<option value="none" <?php selected( $values['map_street_view_control'], 'none' ); ?>><?php _e( 'hide', 'g1_gmaps' ); ?></option>
				</select>
			</p>

			<p class="g1gmap-admin-form-row">
				<label><strong><?php _e( 'Scale control', 'g1_gmaps' ); ?></strong></label>
				<select id="map_scale_control" name="map_scale_control" rows="6">
					<option value="standard" <?php selected( $values['map_scale_control'], 'standard' ); ?>><?php _e( 'show', 'g1_gmaps' ); ?></option>
					<option value="none" <?php selected( $values['map_scale_control'], 'none' ); ?>><?php _e( 'hide', 'g1_gmaps' ); ?></option>
				</select>
			</p>

			<p class="g1gmap-admin-form-row">
				<label><strong><?php _e( 'Overview control', 'g1_gmaps' ); ?></strong></label>
				<select id="map_overview_control" name="map_overview_control" rows="6">
					<option value="opened" <?php selected( $values['map_overview_control'], 'opened' ); ?>><?php _e( 'show opened', 'g1_gmaps' ); ?></option>
					<option value="collapsed" <?php selected( $values['map_overview_control'], 'collapsed' ); ?>><?php _e( 'show collapsed', 'g1_gmaps' ); ?></option>
					<option value="none" <?php selected( $values['map_overview_control'], 'none' ); ?>><?php _e( 'hide', 'g1_gmaps' ); ?></option>
				</select>
			</p>
			<p class="g1gmap-admin-form-row">
				<label><strong><?php _e( 'Draggable', 'g1_gmaps' ); ?></strong></label>
				<select id="map_draggable" name="map_draggable" rows="6">
					<option value="standard" <?php selected( $values['map_draggable'], 'standard' ); ?>><?php _e( 'yes', 'g1_gmaps' ); ?></option>
					<option value="none" <?php selected( $values['map_draggable'], 'none' ); ?>><?php _e( 'no', 'g1_gmaps' ); ?></option>
				</select>
			</p>
			<p class="g1gmap-admin-form-row">
				<label><strong><?php _e( 'Scroll to zoom', 'g1_gmaps' ); ?></strong></label>
				<select id="map_scroll_wheel_to_zoom" name="map_scroll_wheel_to_zoom" rows="6">
					<option value="standard" <?php selected( $values['map_scroll_wheel_to_zoom'], 'standard' ); ?>><?php _e( 'yes', 'g1_gmaps' ); ?></option>
					<option value="none" <?php selected( $values['map_scroll_wheel_to_zoom'], 'none' ); ?>><?php _e( 'no', 'g1_gmaps' ); ?></option>
				</select>
			</p>
			<p class="g1gmap-admin-form-row">
				<label><strong><?php _e( 'Double click to zoom', 'g1_gmaps' ); ?></strong></label>
				<select id="map_double_click_to_zoom" name="map_double_click_to_zoom" rows="6">
					<option value="standard" <?php selected( $values['map_double_click_to_zoom'], 'standard' ); ?>><?php _e( 'yes', 'g1_gmaps' ); ?></option>
					<option value="none" <?php selected( $values['map_double_click_to_zoom'], 'none' ); ?>><?php _e( 'no', 'g1_gmaps' ); ?></option>
				</select>
			</p>
			<p class="g1gmap-admin-form-row">
				<label><strong><?php _e( 'Parallax effect', 'g1_gmaps' ); ?></strong></label>
				<select id="map_parallax" name="map_parallax" rows="6">
					<option value="standard" <?php selected( $values['map_parallax'], 'standard' ); ?>><?php _e( 'yes', 'g1_gmaps' ); ?></option>
					<option value="none" <?php selected( $values['map_parallax'], 'none' ); ?>><?php _e( 'no', 'g1_gmaps' ); ?></option>
				</select>
				<?php _e( 'not available in preview', 'g1_gmaps' ); ?>
			</p>
		<?php
		}

		public function render_map_appearance_colors_meta_box( $post ) {
			// Add an nonce field so we can check for it later.
			wp_nonce_field( 'g1_gmaps_map_appearance_colors_meta_box', 'g1_gmaps_map_appearance_colors_meta_box_nonce' );

			// post stored data
			$values = get_post_meta( $post->ID, '_g1_gmap', true );

			// defined defaults
			$defaults = $this->get_map_fields_defaults( 'g1_gmaps_map_appearance_colors_meta_box' );

			// apply filter on defaults
			$defaults = apply_filters( 'g1_gmaps_map_appearance_colors_defaults', $defaults );

			// merge values from the database with defaults (on first load in db there are no values)
			$values = wp_parse_args( $values, $defaults );

			// invert lighness
			$map_invert_lightness = isset( $values['map_invert_lightness'] ) ? $values['map_invert_lightness'] : null;

			?>
			<p class="g1gmap-admin-form-row g1gmap-admin-form-row-hue">
				<label><strong><?php _e( 'Enable', 'g1_gmaps' ); ?></strong></label>
				<select name="map_custom_colors" class="g1-switch-colors">
					<option value="none" <?php selected($values['map_custom_colors'], 'none'); ?>><?php echo __('no', 'g1_gmaps'); ?></option>
					<option value="standard" <?php selected($values['map_custom_colors'], 'standard'); ?>><?php echo __('yes', 'g1_gmaps'); ?></option>
				</select>
			</p>
			<p class="g1gmap-admin-form-row g1gmap-admin-form-row-hue">
				<label><strong><?php _e( 'Hue', 'g1_gmaps' ); ?></strong></label>
				<input type="range" min="0" max="360" step="1" class="g1-color-part" name="map_color_hue" value="<?php echo esc_attr( $values['map_color_hue'] ); ?>" />
			</p>

			<p class="g1gmap-admin-form-row">
				<label><strong><?php _e( 'Saturation', 'g1_gmaps' ); ?></strong></label>
				<input type="range" min="-100" max="100" step="1" class="g1-color-part" name="map_color_saturation" value="<?php echo esc_attr( $values['map_color_saturation'] ); ?>" />
			</p>
			<p class="g1gmap-admin-form-row">
				<label><strong><?php _e( 'Lightness', 'g1_gmaps' ); ?></strong></label>
				<input type="range" min="-100" max="100" step="1" class="g1-color-part" name="map_color_lightness" value="<?php echo esc_attr( $values['map_color_lightness'] ); ?>" />
			</p>
			<p class="g1gmap-admin-form-row">
				<label><strong><?php _e( 'Gamma', 'g1_gmaps' ); ?></strong></label>
				<input type="range" min="0.01" max="10" step="0.01" class="g1-color-part" name="map_color_gamma" value="<?php echo esc_attr( $values['map_color_gamma'] ); ?>" />
			</p>
			<p class="g1gmap-admin-form-row">
				<label><input type="checkbox" name="map_invert_lightness" value="standard" <?php checked( $map_invert_lightness, 'standard' ); ?>/><?php _e( 'Invert lightness', 'g1_gmaps' ); ?></label>
			</p>
		<?php
		}

		public function render_map_marker_meta_box( $post ) {
			// Add an nonce field so we can check for it later.
			wp_nonce_field( 'g1_gmaps_marker_meta_box', 'g1_gmaps_marker_meta_box_nonce' );

			// post stored data
			$values = get_post_meta( $post->ID, '_g1_gmap_marker', true );
			$map_id = get_post_meta( $post->ID, '_g1_gmap_id', true );

			// defined defaults
			$defaults = $this->get_marker_fields_defaults( 'g1_gmaps_marker_meta_box' );

			// apply filter on defaults
			$defaults = apply_filters( 'g1_gmaps_marker_defaults', $defaults );

			// merge values from the database with defaults (on first load in db there are no values)
			$values = wp_parse_args( $values, $defaults );

			$map_choices = '<option value="">' . __( 'none', 'g1_gmaps' ) . '</option>';

			$maps = get_posts( array(
				'post_type'   => G1_GMaps()->get_post_type(),
				'posts_per_page' => - 1,
			) );
			?>
			<table class="form-table">
				<tbody>
				<tr valign="top">
					<th scope="row">
						<label><?php _e( 'Assigned to map', 'g1_gmaps' ); ?></label>
					</th>
					<td>
						<select name="g1gmap_marker_map_id">
							<?php foreach ( $maps as $map ): ?>
								<option value="<?php echo esc_attr( $map->ID ); ?>" <?php selected( $map_id, $map->ID ); ?>><?php echo esc_html( $map->post_title ); ?></option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label><?php _e( 'Coordinates', 'g1_gmaps' ); ?></label>
					</th>
					<td>
						<label><?php _e( 'Latitude', 'g1_gmaps' ); ?></label>
						<input type="text" name="g1gmap_marker_lat" value="<?php echo esc_attr( $values['lat'] ); ?>" />
						<label><?php _e( 'Longitude', 'g1_gmaps' ); ?></label>
						<input type="text" name="g1gmap_marker_long" value="<?php echo esc_attr( $values['long'] ); ?>" />
					</td>
				</tr>
				</tbody>
			</table>
		<?php
		}

		public function get_map_fields_defaults( $meta_box_name = null ) {
			$meta_boxes = array(
				'g1_gmaps_map_size_meta_box'         => array(
					'map_width'      => '800',
					'map_height'     => '400',
					'map_full_width' => 'standard'
				),
				'g1_gmaps_map_center_point_meta_box' => array(
					'map_lat'  => '27.37223262859307',
					'map_long' => '-51.154410499999976',
					'map_zoom' => '1',
				),
				'g1_gmaps_map_appearance_ui_meta_box'   => array(
					'map_type'                 => 'roadmap',
					'map_type_control'         => 'horizontal',
					'map_zoom_control'         => 'small',
					'map_scale_control'        => 'standard',
					'map_street_view_control'  => 'standard',
					'map_pan_control'          => 'standard',
					'map_overview_control'     => 'none',
					'map_draggable'            => 'standard',
					'map_scroll_wheel_to_zoom' => 'none',
					'map_double_click_to_zoom' => 'standard',
					'map_parallax' 			   => 'none',
				),
				'g1_gmaps_map_appearance_colors_meta_box'   => array(
					'map_custom_colors'        => 'none',
					'map_color_hue'            => 0,
					'map_color_saturation'     => 0,
					'map_color_lightness'      => 0,
					'map_color_gamma'          => 1,
					'map_invert_lightness'     => '',
				)
			);

			if ( isset( $meta_boxes[$meta_box_name] ) ) {
				return $meta_boxes[$meta_box_name];
			}

			return $meta_boxes;
		}

		public function get_marker_fields_defaults( $meta_box_name = null ) {
			$meta_boxes = array(
				'g1_gmaps_marker_meta_box' => array(
					'map_id' => '',
					'lat'    => '',
					'long'   => '',
				)
			);

			if ( isset( $meta_boxes[$meta_box_name] ) ) {
				return $meta_boxes[$meta_box_name];
			}

			return $meta_boxes;
		}

		public function save_meta_boxes( $post_id ) {
			if ( ! isset( $_POST['post_type'] ) ) {
				return;
			}

			$post_type = $_POST['post_type'];

			// If this is an autosave, our form has not been submitted, so we don't want to do anything.
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return $post_id;
			}

			// Check the user's permissions.
			if ( 'page' == $post_type ) {
				if ( ! current_user_can( 'edit_page', $post_id ) ) {
					return $post_id;
				}
			} else {
				if ( ! current_user_can( 'edit_post', $post_id ) ) {
					return $post_id;
				}
			}

			// save map meta boxes
			if ( $post_type === G1_GMaps()->get_post_type() ) {
				$data_to_save = array();

				// Validate meta boxes nonces
				foreach ( $this->get_map_fields_defaults() as $meta_box => $fields ) {
					$nonce_name = $meta_box . '_nonce';

					// Check if our nonce is set.
					if ( ! isset( $_POST[$nonce_name] ) ) {
						return $post_id;
					}

					$nonce = $_POST[$nonce_name];

					// Verify that the nonce is valid.
					if ( ! wp_verify_nonce( $nonce, $meta_box ) ) {
						return $post_id;
					}

					// OK, its safe for us to save the data now.
					foreach ( $fields as $field_name => $default_value ) {
						if ( isset( $_POST[$field_name] ) ) {
							// Sanitize user input.
							$data_to_save[$field_name] = sanitize_text_field( $_POST[$field_name] );
						} else {
							// When we fetch data from db, we can assume that each field is there.
							// Eg. checkbox field has no posted value when not checked.
							$data_to_save[$field_name] = '';
						}
					}

				}

				if ( isset( $data_to_save['map_lat'] ) && isset( $data_to_save['map_long'] ) ) {
					// to enable map searching by lat/long we save these attributes in separate fields
					update_post_meta( $post_id, '_g1_gmap_lat', $data_to_save['map_lat'] );
					update_post_meta( $post_id, '_g1_gmap_long', $data_to_save['map_long'] );
				}

				// Update the meta field in the database.
				update_post_meta( $post_id, '_g1_gmap', $data_to_save );
			}

			// save meta boxes for post types that define 'g1-map' feature support
			if ( in_array( $post_type, G1_GMaps()->get_supported_post_types() ) ) {
				$data_to_save = array();

				// Validate meta boxes nonces
				foreach ( $this->get_marker_fields_defaults() as $meta_box => $fields ) {
					$nonce_name = $meta_box . '_nonce';

					// Check if our nonce is set.
					if ( ! isset( $_POST[$nonce_name] ) ) {
						return $post_id;
					}

					$nonce = $_POST[$nonce_name];

					// Verify that the nonce is valid.
					if ( ! wp_verify_nonce( $nonce, $meta_box ) ) {
						return $post_id;
					}

					// OK, its safe for us to save the data now.
					foreach ( $fields as $field_name => $default_value ) {
						$post_field_name = 'g1gmap_marker_' . $field_name;

						if ( isset( $_POST[$post_field_name] ) ) {
							// Sanitize user input.
							$data_to_save[$field_name] = sanitize_text_field( $_POST[$post_field_name] );
						} else {
							// When we fetch data from db, we can assume that each field is there.
							// Eg. checkbox field has no posted value when not checked.
							$data_to_save[$field_name] = '';
						}
					}
				}

				if ( isset( $data_to_save['map_id'] ) ) {
					update_post_meta( $post_id, '_g1_gmap_id', $data_to_save['map_id'] );
					unset( $data_to_save['map_id'] );
				}

				if ( isset( $data_to_save['lat'] ) && isset( $data_to_save['long'] ) ) {
					// to enable marker searching by lat/long we save these attributes in separate fields
					update_post_meta( $post_id, '_g1_gmap_marker_lat', $data_to_save['lat'] );
					update_post_meta( $post_id, '_g1_gmap_marker_long', $data_to_save['long'] );
				}

				// Update the meta field in the database.
				update_post_meta( $post_id, '_g1_gmap_marker', $data_to_save );
			}
		}
	}
endif;

if ( ! function_exists( 'G1_GMaps_Admin' ) ):
	function G1_GMaps_Admin() {
		static $instance = null;

		if ( null === $instance ) {
			$instance = new G1_GMaps_Admin();
		}

		return $instance;
	}
endif;

/* Fire in a hole :) */
G1_GMaps_Admin();