<?php
/*
Plugin Name: FooBox HTML & Media Lightbox
Plugin URI: http://fooplugins.com/plugins/foobox/
Description: A responsive lightbox that can display images, video & HTML. Also includes built-in social sharing.
Version: 2.3.2.25
Author: FooPlugins
Author URI: http://fooplugins.com
License: GPL2
Text Domain: foobox
Domain Path: /languages
*/

if ( ! defined( 'FOOBOX_PLUGIN_URL' ) ) {
	define( 'FOOBOX_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'FOOBOX_FILE' ) ) {
	define( 'FOOBOX_FILE', __FILE__ );
}

if (!class_exists('fooboxV2')) {

	// Includes
	require_once "includes/FooBox_Settings.php";
	require_once "includes/FooBox_Script_Generator.php";
	require_once "includes/wp_pluginbase.php";
	require_once "includes/foolic_update_checker.php";
	require_once "includes/foolic_validation.php";
	require_once "includes/managewp_api.php";
	require_once "includes/shortcodes.php";
	require_once "includes/Foobox_Exclude.php";
	require_once "includes/ogimage-override.php";
	require_once "includes/share-page.php";
	require_once "includes/envira-support.php";
	require_once "includes/class-foogallery-foobox-extension.php";

	class fooboxV2 extends wp_pluginbase_v2_6_2 {

		const JS                   = 'foobox.min.js';
		const JS_DEBUG             = 'foobox.debug.js';
		const CSS                  = 'foobox.min.css';
        const CSS_NOIE7            = 'foobox.noie7.min.css';
		const FOOBOX_URL           = 'http://fooplugins.com/plugins/foobox/';
		const BECOME_AFFILIATE_URL = 'http://fooplugins.com/affiliate-program/';
		const AFFILIATE_PREFIX     = 'Powered by ';
		const DOCUMENTATION_URL    = 'http://fooplugins.com/foobox/documentation/';
		const ERROR_MSG            = 'Could not load the item';
		const DEBUG_DEFAULT        = false;
		const ERROR_IMG            = 'error.png';
		const UPDATE_URL           = 'http://fooplugins.com/api/foobox/check';

		const PLUGIN_WORDPRESS_SEO = 'WordPress SEO By Yoast';

		protected $foolic_validator = false;

		function init() {
			$this->plugin_slug    = 'foobox';
			$this->plugin_title   = $this->lightbox_name();
			$this->plugin_version = '2.3.2.25';

			//call base init
			parent::init();

			add_action('plugins_loaded', array($this, 'load_text_domain'));

			if ( is_admin() ) {
				if ($this->check_admin_settings_page()) {
					add_action('admin_head', array($this, 'admin_inline_content'));
				}
				add_filter('foobox-settings_summary', array($this, 'admin_settings_summary'));
				add_filter('foobox-settings_title', array($this, 'admin_settings_title'));
				add_filter('foolic_validation_include_css-foobox', array($this, 'include_foolic_css'));
				add_action('admin_notices', array($this, 'admin_notice'));

				do_action('foobox-admin-init', $this);
			} else {
				add_action('wp_head', array($this, 'add_meta'));
				if ($this->must_disable_other_lightboxes()) {
					add_action('wp_footer', array($this, 'disable_other_lightboxes'), 200);
				}
				new FooBox_AutoOpen_Shortcodes();

				new Foobox_Share_Page();

				add_filter( 'wp_get_attachment_link', array( $this, 'add_gallery_attachment_id_attribute' ), 10, 2 );

				add_action( 'template_redirect', array($this, 'listen_for_facebook_share') );
				//new ogimage_override();

			}

			if ( class_exists( 'Envira_Gallery_Lite' ) ||
				class_exists( 'Envira_Gallery' ) ) {
				new Foobox_Envira_Lite_Support();
			}

			$update_checker = new foolic_update_checker_v1_6(__FILE__, self::UPDATE_URL, $this->plugin_slug, get_site_option($this->plugin_slug . '_licensekey'));
			$this->foolic_validator = new foolic_validation_v1_5(self::UPDATE_URL, $this->plugin_slug);

			new foobox_managewp_api(__FILE__, $this->plugin_slug, $this->plugin_version, $update_checker);

			new Foobox_Exclude();
		}

		function plugin_title() {
			return $this->plugin_title;
		}

		function must_disable_other_lightboxes() {
			return $this->is_option_checked('deregister_others', true) ||
				( class_exists('Woocommerce') && $this->is_option_checked('override_woocommerce_lightbox', true) );
		}

		function include_foolic_css($screen) {
			return $screen->id === 'settings_page_foobox' ||
				$screen->id === 'settings_page_foobox-network';
		}

		function lightbox_name() {
			return $this->apply_filters('foobox-name', 'FooBox');
		}

		function image_url() {
			return $this->plugin_url . 'img/';
		}

		function load_text_domain() {
			load_plugin_textdomain('foobox', false, dirname(plugin_basename(__FILE__)) . '/languages/');
		}

		function add_gallery_attachment_id_attribute( $link, $id ) {
			return str_replace('<a href=', '<a data-attachment-id="'.$id.'" href=', $link);
		}

		function listen_for_facebook_share() {
			if ( empty($_GET['fbx-share']) ) {
				return;
			}

			global $wpdb;
			$attachment_id = false;
			$attachment_url = $_GET['fbx-share'];
			$attachment_page = '';

			//if there is no url, return.
			if ( '' === $attachment_url )
				return;

			//get the upload directory paths
			$upload_dir_paths = wp_upload_dir();

			//make sure the upload path base directory exists in the attachment URL, to verify that we're working with a media library image
			if ( false !== strpos( $attachment_url, $upload_dir_paths['baseurl'] ) ) {

				// If this is the URL of an auto-generated thumbnail, get the URL of the original image
				$attachment_url = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $attachment_url );

				// Remove the upload path base directory from the attachment URL
				$attachment_url = str_replace( $upload_dir_paths['baseurl'] . '/', '', $attachment_url );

				// Finally, run a custom database query to get the attachment ID from the modified attachment URL
				$attachment_id = $wpdb->get_var(
					$wpdb->prepare( "SELECT wposts.ID FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' AND wpostmeta.meta_value = '%s' AND wposts.post_type = 'attachment'", $attachment_url ) );

				$attachment_page = get_attachment_link( $attachment_id );
			}

			wp_redirect( $attachment_page );
		}

		function admin_settings_summary() {

			$html = __('For support, FAQ and demos please visit the <a href="%s" target="_blank">%s homepage</a>.', 'foobox');

			$summary = sprintf($html, self::FOOBOX_URL, $this->plugin_title);

			return apply_filters( 'foobox-settings-summary' , $summary );
		}

		function admin_settings_title() {
			$title = __('%s Settings (v%s)', 'foobox');

			return sprintf($title, $this->plugin_title, $this->plugin_version);
		}

		function is_nextgenv2_activated() {
			if ( defined('NEXTGEN_GALLERY_PLUGIN_VERSION') ) {
				return version_compare(NEXTGEN_GALLERY_PLUGIN_VERSION, '2.0.0') >= 0;
			}
			return false;
		}

		function admin_settings_init() {
			$load_settings = apply_filters( 'foobox-admin-settings-init-condition', true );

			if ( $load_settings ) {
				FooBox_Settings::admin_settings_init($this);
			}

			do_action( 'foobox-admin-settings-init', $this );
		}

		function admin_notice() {
			$screen = get_current_screen();

			$show_notice = apply_filters( 'foobox_show_admin_notice', ($screen->id == 'settings_page_foobox' || $screen->id == 'plugins') );

			if (!$show_notice) return;

			$validation_data = $this->foolic_validator->validate();

			if ($validation_data['valid'] === 'expired' || $validation_data['valid'] === 'invalid' || $validation_data['valid'] === false) {
				echo '<div id="message" class="error foolic-admin-notice-'.$this->plugin_slug.'"><p>';
				echo '<strong>' . __('FooBox Lightbox Notice', 'foobox') . ':</strong><br />';
			} else {
				return;
			}

			if ($validation_data['valid'] === 'expired') {
				_e('Your FooBox license key has expired!', $this->plugin_slug);
			} else if ($validation_data['valid'] === 'invalid') {
				_e('Your FooBox license key is invalid!', $this->plugin_slug);
			} else if ($validation_data['valid'] === false) {
				$link = sprintf('<a href="%s"><strong>%s</strong></a>',  'options-general.php?page=' . $this->plugin_slug, __('validate your copy of FooBox', $this->plugin_slug));
				echo sprintf( __('Please %s to receive automatic updates and priority support.', $this->plugin_slug), $link );
			} else {
				return;
			}

			echo sprintf('<br />%s <a target="_blank" href="%s">%s</a> %s.',
				__( 'If you would like to purchase a license key, please visit the', $this->plugin_slug),
				self::FOOBOX_URL,
				__('FooPlugins.com', $this->plugin_slug),
				__('online store', $this->plugin_slug));
			echo '</p></div>';
		}

		function is_wordpress_seo_plugin_detected() {
			if (class_exists('WPSEO_OpenGraph')) {
				return self::PLUGIN_WORDPRESS_SEO;
			}
			$wpseo_options = get_option('wpseo_social');

			if (isset($wpseo_options['opengraph']) && $wpseo_options['opengraph']) {
				return self::PLUGIN_WORDPRESS_SEO;
			}

			return false;
		}

		function add_meta() {
			if (!$this->is_wordpress_seo_plugin_detected() && $this->is_option_checked('social_opengraph', false)) {
				require_once "includes/opengraph.php";
				$og = new foo_opengraph();
				$og->add_meta();
			}
		}

		function admin_plugin_row_meta($links) {

			$links[] = sprintf('<a target="_blank" href="%s"><b>%s</b></a>', self::DOCUMENTATION_URL, __('Online Documentation', 'foobox'));

			return $links;
		}

		function custom_admin_settings_render($args = array()) {
			$type = '';

			extract($args);

			if ($type == 'debug_output') {
				echo '</td></tr><tr valign="top"><td colspan="2">';
				$this->render_debug_info();
			} else if ($type == 'colours') {
				$this->render_colour_options();
			} else if ($type == 'icons') {
				$this->render_icon_options();
			} else if ($type == 'loader') {
				$this->render_loader_options();
			} else if ($type == 'demo') {
				echo '</td></tr><tr valign="top"><td colspan="2">';
				$this->render_demo();
			} else if ($type == 'license') {
				$data = apply_filters('foolic_get_validation_data-'.$this->plugin_slug, false);
				if ($data === false) return;
				echo $data['html'];
			} else if ($type == 'support') {
				echo '</td></tr><tr valign="top"><td colspan="2">';
				$this->render_support();
			} else if ($type == 'foobot_says') {
				echo '</td></tr><tr valign="top"><td colspan="2">';
				$this->render_foobot_recommendations();
			}
		}

		function generate_javascript($debug = false) {
			return FooBox_Script_Generator::generate_javascript($this, $debug);
		}

		function render_for_archive() {
			if (is_admin()) return true;

			return !is_singular();
		}

		function render_colour_options() {
			$colour     = $this->get_option('colour', 'light');
			if ($colour == 'white') { $colour = 'light'; }
			$custom_colour     = $this->get_option('custom_colour', '#FFFFFF');
			$input_name = $this->plugin_slug . '[colour]';
			$custom_input_name = $this->plugin_slug . '[custom_colour]';
			?>
			<div class="hidden">
				<input name="<?php echo $input_name; ?>" id="rad_colour_default" <?php if ($colour == "light") {
					echo 'checked="checked"';
				} ?> type="radio" value="light" tabindex="1"/>
				<input name="<?php echo $input_name; ?>" id="rad_colour_pink" <?php if ($colour == "pink") {
					echo 'checked="checked"';
				} ?> type="radio" value="pink" tabindex="2"/>
				<input name="<?php echo $input_name; ?>" id="rad_colour_green" <?php if ($colour == "green") {
					echo 'checked="checked"';
				} ?> type="radio" value="green" tabindex="3"/>
				<input name="<?php echo $input_name; ?>" id="rad_colour_blue" <?php if ($colour == "blue") {
					echo 'checked="checked"';
				} ?> type="radio" value="blue" tabindex="4"/>
				<input name="<?php echo $input_name; ?>" id="rad_colour_black" <?php if ($colour == "dark") {
					echo 'checked="checked"';
				} ?> type="radio" value="dark" tabindex="5"/>
			</div>
			<div class="radio_selector">
				<label class="colours_radio" for="rad_colour_default"><a <?php if ($colour == "light") {
						echo 'class="selected"';
					} ?> style="background:#FFF" title="White"></a></label>
				<label class="colours_radio" for="rad_colour_pink"><a <?php if ($colour == "pink") {
						echo 'class="selected"';
					} ?> style="background:#df64b6" title="Pink"></a></label>
				<label class="colours_radio" for="rad_colour_green"><a <?php if ($colour == "green") {
						echo 'class="selected"';
					} ?> style="background:#339933" title="Green"></a></label>
				<label class="colours_radio" for="rad_colour_blue"><a <?php if ($colour == "blue") {
						echo 'class="selected"';
					} ?> style="background:#1b58b7" title="Blue"></a></label>
				<label class="colours_radio" for="rad_colour_black"><a <?php if ($colour == "dark") {
						echo 'class="selected"';
					} ?> style="background:#1b1b1b" title="Black"></a></label>
				<label style="display: none" class="colours_radio" for="rad_colour_custom"><a <?php if ($colour == "custom") {
						echo 'class="selected"';
					} ?> title="Custom">
						<input style="display: none" id="txt_colour_custom1" type="text" name="<?php echo $custom_input_name; ?>" class="foobox-colorpicker" size="10" value="<?php echo $custom_colour; ?>"/>
					</a>
				</label>
			</div>
		<?php
		}

		function render_icon_options() {
			$icon             = $this->get_option('icon', '0');
			$input_name       = $this->plugin_slug . '[icon]';

			if ($icon == 'default' || $icon == 'invert') { $icon = '0'; }
			else if ($icon == 'mini' || $icon == 'mini-invert') { $icon = '1'; }

			?>
			<div class="hidden">
				<input name="<?php echo $input_name; ?>" id="rad_icon_default" <?php if ($icon == "0") { echo 'checked="checked"'; } ?> type="radio" value="0" tabindex="1"/>
				<input name="<?php echo $input_name; ?>" id="rad_icon_1" <?php if ($icon == "1") { echo 'checked="checked"'; } ?> type="radio" value="1" tabindex="2"/>
				<input name="<?php echo $input_name; ?>" id="rad_icon_2" <?php if ($icon == "2") { echo 'checked="checked"'; } ?> type="radio" value="2" tabindex="2"/>
				<input name="<?php echo $input_name; ?>" id="rad_icon_3" <?php if ($icon == "3") { echo 'checked="checked"'; } ?> type="radio" value="3" tabindex="2"/>
				<input name="<?php echo $input_name; ?>" id="rad_icon_4" <?php if ($icon == "4") { echo 'checked="checked"'; } ?> type="radio" value="4" tabindex="2"/>
				<input name="<?php echo $input_name; ?>" id="rad_icon_5" <?php if ($icon == "5") { echo 'checked="checked"'; } ?> type="radio" value="5" tabindex="2"/>
				<input name="<?php echo $input_name; ?>" id="rad_icon_6" <?php if ($icon == "6") { echo 'checked="checked"'; } ?> type="radio" value="6" tabindex="2"/>

				<input name="<?php echo $input_name; ?>" id="rad_icon_7" <?php if ($icon == "7") { echo 'checked="checked"'; } ?> type="radio" value="7" tabindex="2"/>
				<input name="<?php echo $input_name; ?>" id="rad_icon_8" <?php if ($icon == "8") { echo 'checked="checked"'; } ?> type="radio" value="8" tabindex="2"/>
				<input name="<?php echo $input_name; ?>" id="rad_icon_9" <?php if ($icon == "9") { echo 'checked="checked"'; } ?> type="radio" value="9" tabindex="2"/>
				<input name="<?php echo $input_name; ?>" id="rad_icon_10" <?php if ($icon == "10") { echo 'checked="checked"'; } ?> type="radio" value="10" tabindex="2"/>
				<input name="<?php echo $input_name; ?>" id="rad_icon_11" <?php if ($icon == "11") { echo 'checked="checked"'; } ?> type="radio" value="11" tabindex="2"/>
			</div>
			<div class="radio_selector">
				<label class="icons_radio" for="rad_icon_default">
					<a class="fbx-arrows-0<?php if ($icon == "0") { echo ' selected';	} ?>" title="Default"><span class="fbx-next"></span></a>
				</label>
				<label class="icons_radio" for="rad_icon_1">
					<a class="fbx-arrows-1<?php if ($icon == "1") { echo ' selected';	} ?>" title="1"><span class="fbx-next"></span></a>
				</label>
				<label class="icons_radio" for="rad_icon_2">
					<a class="fbx-arrows-2<?php if ($icon == "2") { echo ' selected';	} ?>" title="2"><span class="fbx-next"></span></a>
				</label>
				<label class="icons_radio" for="rad_icon_3">
					<a class="fbx-arrows-3<?php if ($icon == "3") { echo ' selected';	} ?>" title="3"><span class="fbx-next"></span></a>
				</label>
				<label class="icons_radio" for="rad_icon_4">
					<a class="fbx-arrows-4<?php if ($icon == "4") { echo ' selected';	} ?>" title="4"><span class="fbx-next"></span></a>
				</label>
				<label class="icons_radio" for="rad_icon_5">
					<a class="fbx-arrows-5<?php if ($icon == "5") { echo ' selected';	} ?>" title="5"><span class="fbx-next"></span></a>
				</label>
				<label class="icons_radio" for="rad_icon_6">
					<a class="fbx-arrows-6<?php if ($icon == "6") { echo ' selected';	} ?>" title="6"><span class="fbx-next"></span></a>
				</label>
				<label class="icons_radio" for="rad_icon_7">
					<a class="fbx-arrows-7<?php if ($icon == "7") { echo ' selected';	} ?>" title="7"><span class="fbx-next"></span></a>
				</label>
				<label class="icons_radio" for="rad_icon_8">
					<a class="fbx-arrows-8<?php if ($icon == "8") { echo ' selected';	} ?>" title="8""><span class="fbx-next"></span></a>
				</label>
				<label class="icons_radio" for="rad_icon_9">
					<a class="fbx-arrows-9<?php if ($icon == "9") { echo ' selected';	} ?>" title="9"><span class="fbx-next"></span></a>
				</label>
				<label class="icons_radio" for="rad_icon_10">
					<a class="fbx-arrows-10<?php if ($icon == "10") { echo ' selected';	} ?>" title="10"><span class="fbx-next"></span></a>
				</label>
				<label class="icons_radio" for="rad_icon_11">
					<a class="fbx-arrows-11<?php if ($icon == "11") { echo ' selected';	} ?>" title="11"><span class="fbx-next"></span></a>
				</label>
			</div>
		<?php
		}

		function render_loader_options() {
			$loader           = $this->get_option('loader', '0');
			$input_name       = $this->plugin_slug . '[loader]';

			?>
			<div class="hidden">
				<input name="<?php echo $input_name; ?>" id="rad_loader_default" <?php if ($loader == "0") { echo 'checked="checked"'; } ?> type="radio" value="0" tabindex="1"/>
				<input name="<?php echo $input_name; ?>" id="rad_loader_2" <?php if ($loader == "2") { echo 'checked="checked"'; } ?> type="radio" value="2" tabindex="2"/>
				<input name="<?php echo $input_name; ?>" id="rad_loader_3" <?php if ($loader == "3") { echo 'checked="checked"'; } ?> type="radio" value="3" tabindex="2"/>
				<input name="<?php echo $input_name; ?>" id="rad_loader_4" <?php if ($loader == "4") { echo 'checked="checked"'; } ?> type="radio" value="4" tabindex="2"/>
				<input name="<?php echo $input_name; ?>" id="rad_loader_5" <?php if ($loader == "5") { echo 'checked="checked"'; } ?> type="radio" value="5" tabindex="2"/>
				<input name="<?php echo $input_name; ?>" id="rad_loader_6" <?php if ($loader == "6") { echo 'checked="checked"'; } ?> type="radio" value="6" tabindex="2"/>
				<input name="<?php echo $input_name; ?>" id="rad_loader_7" <?php if ($loader == "7") { echo 'checked="checked"'; } ?> type="radio" value="7" tabindex="2"/>
				<input name="<?php echo $input_name; ?>" id="rad_loader_8" <?php if ($loader == "8") { echo 'checked="checked"'; } ?> type="radio" value="8" tabindex="2"/>
				<input name="<?php echo $input_name; ?>" id="rad_loader_9" <?php if ($loader == "9") { echo 'checked="checked"'; } ?> type="radio" value="9" tabindex="2"/>
				<input name="<?php echo $input_name; ?>" id="rad_loader_10" <?php if ($loader == "10") { echo 'checked="checked"'; } ?> type="radio" value="10" tabindex="2"/>
				<input name="<?php echo $input_name; ?>" id="rad_loader_12" <?php if ($loader == "11") { echo 'checked="checked"'; } ?> type="radio" value="11" tabindex="2"/>
			</div>
			<div class="radio_selector">
				<label class="loaders_radio" for="rad_loader_default">
					<a class="fbx-admin-loader fbx-spinner-0<?php if ($loader == "0") { echo ' selected';	} ?>" title="Default"><div><span /></div></a>
				</label>
				<label class="loaders_radio" for="rad_loader_2">
					<a class="fbx-admin-loader fbx-spinner-2<?php if ($loader == "2") { echo ' selected';	} ?>" title="2"><div><span /></div></a>
				</label>
				<label class="loaders_radio" for="rad_loader_3">
					<a class="fbx-admin-loader fbx-spinner-3<?php if ($loader == "3") { echo ' selected';	} ?>" title="3"><div><span /></div></a>
				</label>
				<label class="loaders_radio" for="rad_loader_4">
					<a class="fbx-admin-loader fbx-spinner-4<?php if ($loader == "4") { echo ' selected';	} ?>" title="4"><div><span /></div></a>
				</label>
				<label class="loaders_radio" for="rad_loader_5">
					<a class="fbx-admin-loader fbx-spinner-5<?php if ($loader == "5") { echo ' selected';	} ?>" title="5"><div><span /></div></a>
				</label>
				<label class="loaders_radio" for="rad_loader_6">
					<a class="fbx-admin-loader fbx-spinner-6<?php if ($loader == "6") { echo ' selected';	} ?>" title="6"><div><span /></div></a>
				</label>
				<label class="loaders_radio" for="rad_loader_7">
					<a class="fbx-admin-loader fbx-spinner-7<?php if ($loader == "7") { echo ' selected';	} ?>" title="7"><div><span /></div></a>
				</label>
				<label class="loaders_radio" for="rad_loader_8">
					<a class="fbx-admin-loader fbx-spinner-8<?php if ($loader == "8") { echo ' selected';	} ?>" title="8"><div><span /></div></a>
				</label>
				<label class="loaders_radio" for="rad_loader_9">
					<a class="fbx-admin-loader fbx-spinner-9<?php if ($loader == "9") { echo ' selected';	} ?>" title="9"><div><span /></div></a>
				</label>
				<label class="loaders_radio" for="rad_loader_10">
					<a class="fbx-admin-loader fbx-spinner-10<?php if ($loader == "10") { echo ' selected';	} ?>" title="10"><div><span /></div></a>
				</label>
				<label class="loaders_radio" for="rad_loader_11">
					<a class="fbx-admin-loader fbx-spinner-11<?php if ($loader == "11") { echo ' selected';	} ?>" title="11"><div><span /></div></a>
				</label>
			</div>
		<?php
		}

		function render_debug_info() {

			echo '<strong>Javascript:<br /><pre>';

			echo htmlentities($this->generate_javascript(true));

			echo '</pre><br />Settings:<br /><pre>';

			echo htmlentities( print_r(get_option($this->plugin_slug), true) );

			echo '</pre>';
		}

		function render_demo() {
			require_once "includes/demo.php";
		}

		function render_support() {
			global $wp_version;

			require_once "includes/support.php";
			$support = new foobox_support();

			$validation_data = $this->foolic_validator->validate();

			if ($validation_data['valid'] == '1') {

				$current_user = wp_get_current_user();

				$args = array(
					'license' => $validation_data['license'],
					'email' => $current_user->user_email,
					'version' => $this->plugin_version,
					'wp' => $wp_version,
					'url' => home_url()
				);

				$support->render($args);

			} else {
				$support->render_invalid();
			}
		}

		function render_foobot_recommendations() {
			require_once "includes/recommend.php";
		}

		function frontend_init() {
			$where = 'wp_head';

			if ($this->is_option_checked('scripts_in_footer')) {
				$where = 'wp_print_footer_scripts';
			}

			add_action($where, array($this, 'inline_dynamic_js'));

			add_action('wp_head', array($this, 'inline_dynamic_css'), 100);
		}

		function admin_print_styles() {
			parent::admin_print_styles();
			if ($this->check_admin_settings_page()) {
				$this->frontend_print_styles();
			}
			do_action('foobox_admin_print_styles');
		}

		function admin_print_scripts() {
			parent::admin_print_scripts();
			if ($this->check_admin_settings_page()) {
				if ($this->is_option_checked('enable_debug', self::DEBUG_DEFAULT)) {
					$this->register_and_enqueue_js(self::JS_DEBUG, array('jquery'));
				} else {
					$this->register_and_enqueue_js(self::JS, array('jquery'));
				}
			}
			do_action('foobox_admin_print_scripts');
		}

		function admin_inline_content() {
			$this->inline_dynamic_css();
			$this->inline_dynamic_js();
		}

		function frontend_print_styles() {
			if ( !apply_filters('foobox_enqueue_styles', true) ) return;

            //enqueue foobox CSS
            if ( $this->is_option_checked('dropie7support', false) ) {
                $this->register_and_enqueue_css(self::CSS_NOIE7);
            } else {
                $this->register_and_enqueue_css(self::CSS);
            }
		}

		function frontend_print_scripts() {
			if (!apply_filters('foobox_enqueue_scripts', true)) return;

			//put JS in footer?
			$infooter = $this->is_option_checked('scripts_in_footer');

			if ($this->is_option_checked('enable_debug', self::DEBUG_DEFAULT)) {
				//enqueue debug foobox script
				$this->register_and_enqueue_js(
					$file = self::JS_DEBUG,
					$d = $this->get_js_depends(),
					$v = false,
					$f = $infooter);
			} else {
				//enqueue foobox script
				$this->register_and_enqueue_js(
					$file = self::JS,
					$d = $this->get_js_depends(),
					$v = false,
					$f = $infooter);
			}
		}

		function inline_dynamic_js() {
			if (!apply_filters('foobox_enqueue_scripts', true)) return;

			$foobox_js = $this->generate_javascript();

			echo '<script type="text/javascript">' . $foobox_js . '</script>';
		}

		function inline_dynamic_css() {

			if (!apply_filters('foobox_enqueue_styles', true)) return;

			//get custom CSS from the settings page
			$custom_css = $this->get_option('custom_css', '');

			echo '<style type="text/css">
' . $custom_css;
			echo '
</style>';

		}

		function get_js_depends() {
			return array('jquery');
		}

		function disable_other_lightboxes() {
			?>
			<script type="text/javascript">
				jQuery.fn.prettyPhoto = function () {
					return this;
				};
				jQuery.fn.fancybox = function () {
					return this;
				};
				jQuery.fn.fancyZoom = function () {
					return this;
				};
				jQuery.fn.colorbox = function () {
					return this;
				};
			</script>
		<?php
		}
	}

	//run the plugin!
	$GLOBALS['foobox'] = new fooboxV2();
}