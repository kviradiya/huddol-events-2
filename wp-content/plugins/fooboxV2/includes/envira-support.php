<?php
if ( !class_exists( 'Foobox_Envira_Support' ) ) {

	class Foobox_Envira_Lite_Support {

		function __construct() {
			add_filter('envira_gallery_lightbox_themes', array($this, 'add_foobox_theme_to_envira'));
			add_filter('envira_gallery_save_settings', array($this, 'save_envira_gallery_lightbox'), 10, 3);
			add_action('envira_gallery_api_enviratope', array($this, 'override_envira_lightbox'));
			add_action('envira_gallery_api_lightbox', array($this, 'restore_envira_lightbox'));
			add_filter('foobox_js_selectors', array($this, 'add_envira_selector'));
			add_action('foobox_pre_tab', array($this, 'add_envira_support_setting'));
		}

		function add_envira_support_setting($tab_id) {
			if ('looknfeel' === $tab_id) {

				$foobox = $GLOBALS['foobox'];

				$link = sprintf( '<a href="http://wordpress.org/plugins/envira-gallery-lite/" target="_blank">%s</a>', __( 'Envira Gallery Lite', 'foobox' ) );

				$foobox->admin_settings_add( array(
					'id'      => 'support_envira_lite',
					'title'   => __( 'Envira Gallery Lite', 'foobox' ),
					'desc'    => '<small>' .
					             sprintf( __( 'To get FooBox working with %s, simply edit your gallery and select "FooBox" as the Gallery LightBox Theme under the Lightbox tab.', 'foobox' ), $link )
								. '</small>',
					'type'    => 'html',
					'section' => 'enabled',
					'tab'     => 'general'
				) );

			}
		}

		function add_foobox_theme_to_envira($themes) {
			$themes[] =	array(
				'value' => 'foobox',
				'name'  => __( 'FooBox', 'foobox' ),
				'file'  => __FILE__
			);

			return $themes;
		}

		function save_envira_gallery_lightbox($settings, $post_id, $post) {
			//allow the lightbox to be saved for an Envira gallery
			$settings['config']['lightbox_theme'] = sanitize_text_field( $_POST['_envira_gallery']['lightbox_theme'] );
			return $settings;
		}

		function override_envira_lightbox($data) {
			if ( isset($data['config']['lightbox_theme']) &&
			     'foobox' === $data['config']['lightbox_theme'] ) {?>
			var fancyBoxBackup_<?php echo $data['id']; ?> = jQuery.fn.fancybox;
			jQuery.fn.fancybox = function () {
				return this;
			};
			<?php
			}
		}

		function restore_envira_lightbox($data) {
			if ( isset($data['config']['lightbox_theme']) &&
			     'foobox' === $data['config']['lightbox_theme'] ) {?>
			jQuery.fn.fancybox = fancyBoxBackup_<?php echo $data['id']; ?>;
			<?php
			}
		}

		function add_envira_selector($selectors) {
			$selectors[] = '.envira-lightbox-theme-foobox';
			return $selectors;
		}
	}

}

//

