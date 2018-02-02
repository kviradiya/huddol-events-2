<?php

if ( ! class_exists( 'FooBox_Settings' ) ) {

	class FooBox_Settings {

		static function is_multisite() {
			return defined( 'FOOBOX_MULTISITE' ) && FOOBOX_MULTISITE === true;
		}

		/**
		 * @param $foobox fooboxV2
		 */
		static function admin_settings_init( $foobox ) {

			$name = $foobox->plugin_title();

			$foobox->admin_settings_add_tab( 'general', __( 'General', 'foobox' ) );

			$is_multisite = self::is_multisite();

			if ( ! $is_multisite ) {

				$foobox->admin_settings_add_section_to_tab( 'general', 'license', __( 'License', 'foobox' ) );

				$foobox->admin_settings_add( array(
					'id'           => 'license',
					'title'        => __( 'FooBox License Key', 'foobox' ),
					'desc'         => sprintf( __( 'The license key is used to access automatic updates and support for %s.<br /><strong>Please Note:</strong> After validating, click "save changes" to activate the support tab.', 'foobox' ), $name ),
					'type'         => 'license',
					'section'      => 'license',
					'tab'          => 'general',
					'setting_name' => 'foobox_key',
					'update_url'   => fooboxV2::UPDATE_URL
				) );
			}

			$foobox->admin_settings_add_section_to_tab( 'general', 'enabled', __( 'Attach FooBox', 'foobox' ) );

			$foobox->admin_settings_add( array(
				'id'      => 'enable_galleries',
				'title'   => __( 'WordPress Galleries', 'foobox' ),
				'desc'    => sprintf( __( 'Enables %s for all WordPress image galleries', 'foobox' ), $name ),
				'default' => 'on',
				'type'    => 'checkbox',
				'section' => 'enabled',
				'tab'     => 'general'
			) );

			$foobox->admin_settings_add( array(
				'id'      => 'enable_captions',
				'title'   => __( 'WordPress Images With Captions', 'foobox' ),
				'desc'    => sprintf( __( 'Enable %s for all WordPress images with captions', 'foobox' ), $name ),
				'default' => 'on',
				'type'    => 'checkbox',
				'section' => 'enabled',
				'tab'     => 'general'
			) );

			$foobox->admin_settings_add( array(
				'id'      => 'enable_attachments',
				'title'   => __( 'Attachment Images', 'foobox' ),
				'desc'    => sprintf( __( 'Enable %s for all media images included in posts or pages', 'foobox' ), $name ),
				'default' => 'on',
				'type'    => 'checkbox',
				'section' => 'enabled',
				'tab'     => 'general'
			) );

			if ( $foobox->is_nextgenv2_activated() ) {
				//NextGen V2 support

				$html = '';

				if ( class_exists( 'C_Lightbox_Installer' ) ) {

					$nextgenv2_installer = new C_Lightbox_Installer();

					$lightbox_option = $nextgenv2_installer->mapper->find_by_name( 'foobox' );
					if ( ! $lightbox_option ) {
						$nextgenv2_installer->install_lightbox( 'foobox', 'FooBox', 'class="foobox"', array(), array(), array() );
					}

				}
				else if ( class_exists( 'C_Lightbox_Library_Manager') ) {

					$nlm_instance = C_Lightbox_Library_Manager::get_instance();

					// Add Fancybox
					$foobox_ngg = new stdClass();
					$foobox_ngg->title = __('FooBox', 'foobox');
					$foobox_ngg->code = 'class="foobox" rel="%GALLERY_NAME%"';
					$foobox_ngg->styles = array();
					$foobox_ngg->scripts = array();
					$nlm_instance->register('foobox', $foobox_ngg);

				}

				$nextgen_options_link = sprintf( '<a href="admin.php?page=ngg_other_options" target="_blank">%s</a>', __( 'NextGen V2 Other Options', 'foobox' ) );
				$html                 = '<br />' .
				                        sprintf( __( 'Please choose "FooBox" under the "Lightbox Effects" tab on the %s page.', 'foobox' ), $nextgen_options_link );

				$foobox->admin_settings_add( array(
					'id'      => 'enable_nextgenV2',
					'title'   => __( 'NextGen V2 Galleries', 'foobox' ),
					'desc'    => sprintf( __( 'Enable %s for all NextGen V2 image galleries.', 'foobox' ), $name ) . $html,
					'default' => 'on',
					'type'    => 'checkbox',
					'section' => 'enabled',
					'tab'     => 'general'
				) );

			} else if ( class_exists( 'nggLoader' ) ) {
				$nextgen_options_link = sprintf( '<a href="admin.php?page=nggallery-options#effects" target="_blank">%s</a>', __( 'NextGen Options', 'foobox' ) );

				$foobox->admin_settings_add( array(
					'id'      => 'enable_nextgen',
					'title'   => __( 'NextGen Galleries', 'foobox' ),
					'desc'    => sprintf( __( 'Enable %s for all NextGen image galleries.', 'foobox' ), $name ) . '<br />' .
					             sprintf( __( 'Please set "Javascript Thumbnail Effect" to "none" under the Effects tab on the %s page.', 'foobox' ), $nextgen_options_link ),
					'default' => 'on',
					'type'    => 'checkbox',
					'section' => 'enabled',
					'tab'     => 'general'
				) );
			}

			if ( class_exists( 'Jetpack' ) ) {
				$foobox->admin_settings_add( array(
					'id'      => 'jetpack_tiled_images',
					'title'   => __( 'Jetpack Tiled Galleries', 'foobox' ),
					'desc'    => sprintf( __( 'Enable %s for all Jetpack tiled image galleries.', 'foobox' ), $name ),
					'default' => 'on',
					'type'    => 'checkbox',
					'section' => 'enabled',
					'tab'     => 'general'
				) );
			}

			if ( class_exists( 'Woocommerce' ) ) {
				$foobox->admin_settings_add( array(
					'id'      => 'override_woocommerce_lightbox',
					'title'   => __( 'WooCommerce Products', 'foobox' ),
					'desc'    => sprintf( __( 'Override the default WooCommerce product image lightbox with %s.', 'foobox' ), $name ),
					'default' => 'on',
					'type'    => 'checkbox',
					'section' => 'enabled',
					'tab'     => 'general'
				) );
			}

			$foobox->admin_settings_add( array(
				'id'      => 'enable_class',
				'title'   => __( 'Specific CSS classes', 'foobox' ),
				'desc'    => sprintf( __( 'Enable %s on specific container elements that have a specific CSS class name.<br />Use this to target only very specific elements in your site.<br />Example : <code>.container, .gallery</code>', 'foobox' ), $name ),
				'type'    => 'text',
				'section' => 'enabled',
				'tab'     => 'general'
			) );

			$foobox->admin_settings_add( array(
				'id'      => 'enable_all',
				'title'   => __( 'All images', 'foobox' ),
				'desc'    => sprintf( __( 'Enable %s for all image links in your WordPress site. This will exclude any items that are already included above', 'foobox' ), $name ),
				'default' => 'off',
				'type'    => 'checkbox',
				'section' => 'enabled',
				'tab'     => 'general'
			) );

			$jig_link = sprintf( '<a href="http://codecanyon.net/item/justified-image-grid-premium-wordpress-gallery/2594251?ref=themergency" target="_blank">%s</a>', __( 'Justified Image Grid', 'foobox' ) );

			$foobox->admin_settings_add( array(
				'id'      => 'support_jig',
				'title'   => __( 'Justified Image Grid', 'foobox' ),
				'desc'    => sprintf( __( 'Add full support for %s galleries. No other custom settings needed!', 'foobox' ), $jig_link ),
				'type'    => 'checkbox',
				'section' => 'enabled',
				'tab'     => 'general'
			) );

			$foobox->admin_settings_add_tab( 'looknfeel', __( 'Look &amp; Feel', 'foobox' ) );
			$foobox->admin_settings_add_section_to_tab( 'looknfeel', 'styling', __( 'Styling', 'foobox' ) );

			$style_choices                = array();
			$style_choices['fbx-rounded'] = __( 'Rounded', 'foobox' );
			$style_choices['fbx-metro']   = __( 'Metro', 'foobox' );

			$foobox->admin_settings_add( array(
				'id'        => 'theme',
				'title'     => __( 'Theme', 'foobox' ),
				'default'   => 'fbx-rounded',
				'type'      => 'radio',
				'section'   => 'styling',
				'choices'   => $style_choices,
				'tab'       => 'looknfeel',
				'separator' => '&nbsp;&nbsp;&nbsp;'
			) );

			$foobox->admin_settings_add( array(
				'id'      => 'colour',
				'title'   => __( 'Colour Scheme', 'foobox' ),
				'type'    => 'colours',
				'section' => 'Styling',
				'tab'     => 'looknfeel'
			) );

			$foobox->admin_settings_add( array(
				'id'      => 'icon',
				'title'   => __( 'Icon Set', 'foobox' ),
				'type'    => 'icons',
				'section' => 'styling',
				'tab'     => 'looknfeel'
			) );

			$foobox->admin_settings_add( array(
				'id'      => 'loader',
				'title'   => __( 'Loader Icon', 'foobox' ),
				'type'    => 'loader',
				'section' => 'styling',
				'tab'     => 'looknfeel'
			) );

			$button_choices                  = array();
			$button_choices['default']       = __( 'default (buttons are on the sides of the modal)', 'foobox' );
			$button_choices['fbx-sticky-buttons']  = __( 'Sticky (buttons stick to the sides of the screen)', 'foobox' );
			$button_choices['fbx-sticky-buttons fbx-full-buttons']  = __( 'Full Height Sticky (buttons are the full height of the screen and also sticky)', 'foobox' );
			$button_choices['fbx-inset-buttons']  = __( 'Inside Hover (buttons are inside the modal and are only shown when hovering on the image)', 'foobox' );
			$foobox->admin_settings_add( array(
				'id'      => 'button_type',
				'title'   => __( 'Navigation Buttons', 'foobox' ),
				'desc'    => __( 'Choose the way the navigation buttons are displayed', 'foobox' ),
				'default' => 'default',
				'type'    => 'radio',
				'section' => 'styling',
				'choices' => $button_choices,
				'tab'     => 'looknfeel'
			) );

			$animation_choices                  = array();
			$animation_choices['none']          = __( 'None', 'foobox' );
			$animation_choices['fbx-effect-1']  = __( 'Zoom in', 'foobox' );
			$animation_choices['fbx-effect-2']  = __( 'Slide from right', 'foobox' );
			$animation_choices['fbx-effect-3']  = __( 'Slide from bottom', 'foobox' );
			$animation_choices['fbx-effect-4']  = __( 'Newspaper (spin while zooming in)', 'foobox' );
			$animation_choices['fbx-effect-5']  = __( 'Fall', 'foobox' );
			$animation_choices['fbx-effect-6']  = __( 'Slide Fall', 'foobox' );
			$animation_choices['fbx-effect-7']  = __( 'Flip (horizontal)', 'foobox' );
			$animation_choices['fbx-effect-8']  = __( 'Flip (vertical)', 'foobox' );
			$animation_choices['fbx-effect-9']  = __( 'Fold Down', 'foobox' );
			$animation_choices['fbx-effect-10'] = __( 'Super Scaled', 'foobox' );
			$animation_choices['fbx-effect-11'] = __( 'Swing in from bottom', 'foobox' );
			$animation_choices['fbx-effect-12'] = __( 'Swing in from left', 'foobox' );
			$foobox->admin_settings_add( array(
				'id'      => 'open_animation',
				'title'   => __( 'Opening/Closing Animation', 'foobox' ),
				'desc'    => __( 'Adds a cool opening and closing animation in newer browsers <strong>EXPERIMENTAL!</strong>', 'foobox' ),
				'default' => 'none',
				'type'    => 'radio',
				'section' => 'styling',
				'choices' => $animation_choices,
				'tab'     => 'looknfeel'
			) );

			$foobox->admin_settings_add_tab( 'captions', __( 'Captions', 'foobox' ) );

			$foobox->admin_settings_add_section_to_tab( 'captions', 'captions', sprintf( __( '%s Captions', 'foobox' ), $name ) );

			$foobox->admin_settings_add( array(
				'id'      => 'show_caption',
				'title'   => __( 'Show Image Captions', 'foobox' ),
				'desc'    => __( 'Whether or not to show captions for images', 'foobox' ),
				'default' => 'on',
				'type'    => 'checkbox',
				'section' => 'captions',
				'tab'     => 'captions'
			) );

			$foobox->admin_settings_add( array(
				'id'      => 'video_captions',
				'title'   => __( 'Show Video Captions', 'foobox' ),
				'desc'    => __( 'Shows captions when viewing videos in FooBox.<br />Set a video caption title by adding a <code>data-caption-title</code> attribute to your link.<br />Set a caption description by adding a <code>data-caption-desc</code> attribute to your link.', 'foobox' ),
				'type'    => 'checkbox',
				'section' => 'captions',
				'tab'     => 'captions'
			) );

			$foobox->admin_settings_add( array(
				'id'      => 'html_captions',
				'title'   => __( 'Show HTML Captions', 'foobox' ),
				'desc'    => sprintf( __( 'Shows captions when viewing HTML content in FooBox. Use the same data attributes used for video captions.', 'foobox' ), $name ),
				'type'    => 'checkbox',
				'section' => 'captions',
				'tab'     => 'captions'
			) );

			$foobox->admin_settings_add( array(
				'id'      => 'iframe_captions',
				'title'   => __( 'Show iFrame Captions', 'foobox' ),
				'desc'    => sprintf( __( 'Shows captions when viewing iFrames in FooBox. Use the same data attributes used for video captions.', 'foobox' ), $name ),
				'type'    => 'checkbox',
				'section' => 'captions',
				'tab'     => 'captions'
			) );

			$foobox->admin_settings_add( array(
				'id'      => 'force_caption_bottom',
				'title'   => sprintf( __( 'Force Caption To Bottom', 'foobox' ), $name ),
				'desc'    => sprintf( __( 'Force the caption to be fixed along the bottom of the screen', 'foobox' ), $name ),
				'type'    => 'checkbox',
				'section' => 'captions',
				'tab'     => 'captions'
			) );

			$foobox->admin_settings_add( array(
				'id'      => 'captions_show_on_hover',
				'title'   => __( 'Show Captions On Hover', 'foobox' ),
				'desc'    => __( 'Only show the caption when hovering over the item.', 'foobox' ),
				'type'    => 'checkbox',
				'section' => 'captions',
				'tab'     => 'captions'
			) );

			$foobox->admin_settings_add( array(
				'id'      => 'captions_hidden',
				'title'   => __( 'Hide Captions Initially', 'foobox' ),
				'desc'    => __( 'Hide the caption when the item is first shown.', 'foobox' ),
				'type'    => 'checkbox',
				'section' => 'captions',
				'tab'     => 'captions'
			) );

			$caption_anim_choices          = array();
			$caption_anim_choices['slide'] = __( 'Slide (Default)', 'foobox' );
			$caption_anim_choices['fade']  = __( 'Fade', 'foobox' );
			$caption_anim_choices['show']  = __( 'None', 'foobox' );

			$foobox->admin_settings_add( array(
				'id'      => 'caption_anim',
				'title'   => __( 'Caption Animation', 'foobox' ),
				'desc'    => sprintf( __( 'Change the way captions are shown or hidden', 'foobox' ), $name ),
				'default' => 'slide',
				'type'    => 'radio',
				'section' => 'captions',
				'choices' => $caption_anim_choices,
				'tab'     => 'captions'
			) );

			$caption_title_choices               = array();
			$caption_title_choices['default']    = __( 'Default (Anchor Title or Image Title or Image Alt)', 'foobox' );
			$caption_title_choices['image_find'] = __( 'Image Title or Image Alt', 'foobox' );
			$caption_title_choices['image']      = __( 'Image Title Only', 'foobox' );
			$caption_title_choices['image_alt']  = __( 'Image Alt Only (NextGen title field)', 'foobox' );
			$caption_title_choices['anchor']     = __( 'Anchor Title Only', 'foobox' );
			$caption_title_choices['none']       = __( 'None', 'foobox' );

			$foobox->admin_settings_add( array(
				'id'      => 'caption_title_source',
				'title'   => __( 'Override Image Caption Title', 'foobox' ),
				'desc'    => sprintf( __( 'Overrides where the default image caption titles are pulled from', 'foobox' ), $name ),
				'default' => 'default',
				'type'    => 'radio',
				'section' => 'captions',
				'choices' => $caption_title_choices,
				'tab'     => 'captions'
			) );

			$caption_desc_choices              = array();
			$caption_desc_choices['default']   = __( 'Default (Image Title or Image Alt)', 'foobox' );
			$caption_desc_choices['image']     = __( 'Image Title Only', 'foobox' );
			$caption_desc_choices['image_alt'] = __( 'Image Alt Only', 'foobox' );
			$caption_desc_choices['anchor']    = __( 'Anchor Title Only (NextGen description field)', 'foobox' );
			$caption_desc_choices['none']      = __( 'None', 'foobox' );

			$foobox->admin_settings_add( array(
				'id'      => 'caption_desc_source',
				'title'   => __( 'Override Image Caption Description', 'foobox' ),
				'desc'    => sprintf( __( 'Overrides where the default image caption descriptions are pulled from', 'foobox' ), $name ),
				'default' => 'default',
				'type'    => 'radio',
				'section' => 'captions',
				'choices' => $caption_desc_choices,
				'tab'     => 'captions'
			) );

			$foobox->admin_settings_add( array(
				'id'      => 'caption_prettify',
				'title'   => __( 'Prettify Ugly Captions', 'foobox' ),
				'desc'    => __( 'Attempts to make captions look less "generated" and more readable.<br/ >Example : <code>image-showing-something-021</code> would result in <code>Image Showing Something</code>', 'foobox' ),
				'type'    => 'checkbox',
				'section' => 'captions',
				'tab'     => 'captions'
			) );

			$foobox->admin_settings_add_tab( 'settings', __( 'Functions', 'foobox' ) );

			$foobox->admin_settings_add_section_to_tab( 'settings', 'general', __( 'General', 'foobox' ) );

			$foobox->admin_settings_add( array(
				'id'      => 'fit_to_screen',
				'title'   => __( 'Fit To Screen', 'foobox' ),
				'desc'    => sprintf( __( 'Force smaller images/video/html to fit the screen dimensions', 'foobox' ), $name ),
				'default' => 'off',
				'type'    => 'checkbox',
				'section' => 'general',
				'tab'     => 'settings'
			) );

			$foobox->admin_settings_add( array(
				'id'      => 'hide_scrollbars',
				'title'   => __( 'Hide Page Scrollbars', 'foobox' ),
				'desc'    => sprintf( __( 'Hide the page\'s scrollbars when %s is visible', 'foobox' ), $name ),
				'default' => 'on',
				'type'    => 'checkbox',
				'section' => 'general',
				'tab'     => 'settings'
			) );

			$foobox->admin_settings_add( array(
				'id'      => 'hide_buttons',
				'title'   => __( 'Hide Navigation Buttons', 'foobox' ),
				'desc'    => __( 'Hide the prev / next buttons when there is more than one item in the gallery', 'foobox' ),
				'type'    => 'checkbox',
				'section' => 'general',
				'tab'     => 'settings'
			) );

			$foobox->admin_settings_add( array(
				'id'      => 'close_overlay_click',
				'title'   => __( 'Close On Overlay Click', 'foobox' ),
				'desc'    => sprintf( __( 'Should the %s close when the modal overlay is clicked.', 'foobox' ), $name ),
				'default' => 'on',
				'type'    => 'checkbox',
				'section' => 'general',
				'tab'     => 'settings'
			) );

			$foobox->admin_settings_add( array(
				'id'      => 'error_message',
				'title'   => __( 'Error Message', 'foobox' ),
				'desc'    => __( 'The error message to display when an item cannot be loaded', 'foobox' ),
				'default' => __( fooboxV2::ERROR_MSG, 'foobox' ),
				'type'    => 'text',
				'section' => 'general',
				'tab'     => 'settings'
			) );

			$foobox->admin_settings_add_section_to_tab( 'settings', 'deeplinking', __( 'Deeplinking', 'foobox' ) );

			$foobox->admin_settings_add( array(
				'id'      => 'disble_deeplinking',
				'title'   => __( 'Disable Deeplinking', 'foobox' ),
				'desc'    => __( 'By default, each FooBox item will have it\'s own unique URL when opened. When this unique URL is visited, FooBox will automatically open on that item.', 'foobox' ),
				'type'    => 'checkbox',
				'section' => 'deeplinking',
				'tab'     => 'settings'
			) );

			$foobox->admin_settings_add( array(
				'id'      => 'deeplinking_prefix',
				'title'   => __( 'Deeplinking Prefix', 'foobox' ),
				'desc'    => __( 'When building the deep link, this prefix is used in the URL.', 'foobox' ),
				'default' => 'foobox',
				'type'    => 'text',
				'section' => 'deeplinking',
				'tab'     => 'settings',
				'class'   => 'short_input'
			) );

			$foobox->admin_settings_add_section_to_tab( 'settings', 'fullscreen', __( 'Fullscreen', 'foobox' ) );

			$foobox->admin_settings_add( array(
				'id'      => 'allow_fullscreen',
				'title'   => __( 'Show Fullscreen Button', 'foobox' ),
				'desc'    => sprintf( __( 'Shows a fullscreen button which allows the visitor to toggle between fullscreen and normal mode', 'foobox' ), $name ),
				'default' => 'off',
				'type'    => 'checkbox',
				'section' => 'fullscreen',
				'tab'     => 'settings'
			) );

			$foobox->admin_settings_add( array(
				'id'      => 'force_fullscreen',
				'title'   => __( 'Force Fullscreen', 'foobox' ),
				'desc'    => sprintf( __( 'Forces the %s into fullscreen mode by default', 'foobox' ), $name ),
				'default' => 'off',
				'type'    => 'checkbox',
				'section' => 'fullscreen',
				'tab'     => 'settings'
			) );

			$foobox->admin_settings_add( array(
				'id'      => 'fullscreen_api',
				'title'   => __( 'Use Browser API', 'foobox' ),
				'desc'    => sprintf( __( 'Uses the native browser fullscreen API (if available)', 'foobox' ), $name ),
				'default' => 'off',
				'type'    => 'checkbox',
				'section' => 'fullscreen',
				'tab'     => 'settings'
			) );

			$foobox->admin_settings_add_section_to_tab( 'settings', 'images', __( 'Images', 'foobox' ) );

			$foobox->admin_settings_add( array(
				'id'      => 'preload_images',
				'title'   => __( 'Preload Images', 'foobox' ),
				'desc'    => sprintf( __( 'Preloads the next and previous images when an image is displayed. Images will appear to load much faster.', 'foobox' ), $name ),
				'type'    => 'checkbox',
				'default' => 'on',
				'section' => 'images',
				'tab'     => 'settings'
			) );

			$foobox->admin_settings_add( array(
				'id'      => 'enable_protection',
				'title'   => __( 'Enable Image Protection', 'foobox' ),
				'desc'    => sprintf( __( 'This disables the user from right-clicking on the images shown in the %s. Although this is not 100%% proven to work, it can help in some cases.', 'foobox' ), $name ),
				'type'    => 'checkbox',
				'section' => 'images',
				'tab'     => 'settings'
			) );

			$foobox->admin_settings_add( array(
				'id'      => 'image_rel_selector',
				'title'   => __( 'REL Attribute', 'foobox' ),
				'desc'    => __( 'Images can be grouped by their REL attribute if needed.', 'foobox' ),
				'default' => 'foobox',
				'type'    => 'text',
				'section' => 'images',
				'tab'     => 'settings'
			) );

			$foobox->admin_settings_add_section_to_tab( 'settings', 'counter', __( 'Counter', 'foobox' ) );

			$foobox->admin_settings_add( array(
				'id'      => 'show_count',
				'title'   => __( 'Show Counter', 'foobox' ),
				'desc'    => sprintf( __( 'Shows a counter under the %s when viewing a gallery of items', 'foobox' ), $name ),
				'default' => 'on',
				'type'    => 'checkbox',
				'section' => 'counter',
				'tab'     => 'settings'
			) );

			$foobox->admin_settings_add( array(
				'id'      => 'count_message',
				'title'   => __( 'Count Message', 'foobox' ),
				'desc'    => __( 'the message to use as the item counter. The fields <code>%index</code> and <code>%total</code> can be used to substitute the correct values. <br/ >Example : <code>item %index / %total</code> would result in <code>item 1 / 7</code>', 'foobox' ),
				'default' => 'item %index of %total',
				'type'    => 'text',
				'section' => 'counter',
				'tab'     => 'settings'
			) );

			$foobox->admin_settings_add_section_to_tab( 'settings', 'slideshow', __( 'SlideShow', 'foobox' ) );

			$foobox->admin_settings_add( array(
				'id'      => 'slideshow_enabled',
				'title'   => __( 'Enable Slideshow', 'foobox' ),
				'desc'    => __( 'Enable slideshow functionality when there is more than one item in the gallery', 'foobox' ),
				'default' => 'on',
				'type'    => 'checkbox',
				'section' => 'slideshow',
				'tab'     => 'settings'
			) );

			$foobox->admin_settings_add( array(
				'id'      => 'slideshow_autostart',
				'title'   => __( 'Auto-start Slideshow', 'foobox' ),
				'desc'    => __( 'Start the slideshow automatically when it is enabled', 'foobox' ),
				'type'    => 'checkbox',
				'section' => 'slideshow',
				'tab'     => 'settings'
			) );

			$foobox->admin_settings_add( array(
				'id'      => 'slideshow_autostop',
				'title'   => __( 'Auto-stop Slideshow', 'foobox' ),
				'desc'    => __( 'Stop the slideshow automatically when it gets to the last item', 'foobox' ),
				'type'    => 'checkbox',
				'section' => 'slideshow',
				'tab'     => 'settings'
			) );

			$foobox->admin_settings_add( array(
				'id'      => 'slideshow_timeout',
				'title'   => __( 'Slide Timeout', 'foobox' ),
				'desc'    => __( 'The time in seconds that each slide is shown in the slideshow', 'foobox' ),
				'default' => '6',
				'type'    => 'text',
				'section' => 'slideshow',
				'tab'     => 'settings',
				'class'   => 'short_input'
			) );

			$foobox->admin_settings_add_section_to_tab( 'settings', 'iframe', __( 'iFrame', 'foobox' ) );

			$iframe_loading_choices                = array();
			$iframe_loading_choices['default']     = __( 'Only show after iFrame content has finished loading', 'foobox' );
			$iframe_loading_choices['immediately'] = __( 'Show Immediately', 'foobox' );

			$foobox->admin_settings_add( array(
				'id'      => 'iframe_loading',
				'title'   => __( 'When To Show iFrame Content', 'foobox' ),
				'desc'    => sprintf( __( 'Choose how soon iFrame content is shown', 'foobox' ), $name ),
				'default' => 'default',
				'type'    => 'radio',
				'section' => 'iframe',
				'choices' => $iframe_loading_choices,
				'tab'     => 'settings'
			) );

			$foobox->admin_settings_add( array(
				'id'      => 'iframe_fullscreen',
				'title'   => __( 'iFrame Allow Fullscreen', 'foobox' ),
				'desc'    => __( 'Append the allowfullscreen attribute onto the iFrame', 'foobox' ),
				'type'    => 'checkbox',
				'section' => 'iframe',
				'tab'     => 'settings'
			) );

			$foobox->admin_settings_add_tab( 'social', __( 'Social Icons', 'foobox' ) );

			$options = $foobox->get_options();
			$facebook_enabled = array_key_exists( 'social_facebook', $options );
			$facebook_feed_enabled = array_key_exists( 'social_facebook_feed', $options );
			$has_facebook_appid = array_key_exists( 'social_facebook_appid', $options ) && !empty( $options['social_facebook_appid'] );

			$facebook_app_link     = sprintf( '<a href="https://developers.facebook.com/apps" target="_blank">%s</a>', __( 'Create a Facebook App ID now', 'foobox' ) );
			$facebook_app_tut_link = sprintf( '<a href="http://docs.fooplugins.com/foobox/facebook-app-id-setup/" target="_blank">%s</a>', __( 'Read our step-by-step tutorial on how to create your App ID', 'foobox' ) );
			$facebook_app_message = sprintf( __( 'A Facebook App ID is required in order for the feed dialog to work. %s.<br />%s.', 'foobox' ), $facebook_app_link, $facebook_app_tut_link );

			if ($facebook_enabled && !$facebook_feed_enabled) {
				$warning = '<div class="red-message"><br />';
				$warning .= '<strong>' . __('Facebook Sharer Updated!','foobox') . '</strong><br /><br />';
				$warning .= __( 'Facebook have recently changed the way content is shared using their simple sharer method (which is enabled in FooBox at the moment).', 'foobox' );
				$warning .= '<br />';
				$warning .= __( 'Your visitors will no longer be able to share individual images. We recommend you enable the Feed Dialog Method of sharing below, which will allow for individual image sharing as before.', 'foobox' );
				$warning .= '<br /><br /></div>';

				$foobox->admin_settings_add( array(
					'id'      => 'social_warning',
					'title'   => __( '', 'foobox' ),
					'desc'    => $warning,
					'type'    => 'heading',
					'tab'     => 'social'
				) );
			} else if ($facebook_feed_enabled && !$has_facebook_appid) {
				$warning = '<div class="red-message"><br />';
				$warning .= '<strong>' . __('Facebook App ID Required!','foobox') . '</strong><br /><br />';
				$warning .= $facebook_app_message;
				$warning .= '<br /><br /></div>';

				$foobox->admin_settings_add( array(
					'id'      => 'social_facebook_app_id',
					'title'   => __( '', 'foobox' ),
					'desc'    => $warning,
					'type'    => 'heading',
					'tab'     => 'social'
				) );
			} else {
				$foobox->admin_settings_add( array(
					'id'      => 'social_warning_placeholder',
					'title'   => __( '', 'foobox' ),
					'type'    => 'heading',
					'tab'     => 'social'
				) );
			}

			$foobox->admin_settings_add_section_to_tab( 'social', 'main', __( 'Social Icon Settings', 'foobox' ) );

			$foobox->admin_settings_add( array(
				'id'      => 'social_enabled',
				'title'   => __( 'Social Icons Enabled', 'foobox' ),
				'default' => 'on',
				'type'    => 'checkbox',
				'section' => 'main',
				'tab'     => 'social'
			) );

			$social_share_what            = array();
			$social_share_what['default'] = __( 'Share deeplink (if deeplinking is enabled). Fallback to share page', 'foobox' );
			$social_share_what['image']   = __( 'Share image/video file directly. (This has it\'s downsides, as you loose a page view when the shared link is clicked)', 'foobox' );
			$social_share_what['page']    = __( 'Share image attachment page (if one exists). Fallback to share page. [experimental]', 'foobox' );

			$foobox->admin_settings_add( array(
				'id'      => 'social_share_what',
				'title'   => __( 'What link is shared', 'foobox' ),
				'desc'    => __( 'Choose what link is shared when the social icons are clicked.', 'foobox' ),
				'type'    => 'radio',
				'section' => 'main',
				'choices' => $social_share_what,
				'default' => 'default',
				'tab'     => 'social'
			) );

//			$foobox->admin_settings_add( array(
//				'id'      => 'social_share_image',
//				'title'   => __( 'Share Item Directly', 'foobox' ),
//				'desc'    => __( 'Share the actual image/video file directly, rather than the deeplink URL.', 'foobox' ),
//				'type'    => 'checkbox',
//				'section' => 'main',
//				'tab'     => 'social'
//			) );

			$foobox->admin_settings_add( array(
				'id'      => 'social_show_on_hover',
				'title'   => __( 'Show Icons On Hover', 'foobox' ),
				'desc'    => __( 'Only show the social icons when hovering over the item.', 'foobox' ),
				'type'    => 'checkbox',
				'section' => 'main',
				'tab'     => 'social'
			) );

			$foobox->admin_settings_add( array(
				'id'      => 'social_hidden',
				'title'   => __( 'Hide Icons Initially', 'foobox' ),
				'desc'    => __( 'Hide the social icons when the item is first shown.', 'foobox' ),
				'type'    => 'checkbox',
				'section' => 'main',
				'tab'     => 'social'
			) );

			$social_title_choices            = array();
			$social_title_choices['title']   = __( 'Page\'s title + caption title', 'foobox' );
			$social_title_choices['caption'] = __( 'Caption title + caption description', 'foobox' );
			$social_title_choices['h1']      = __( 'Page\'s header (h1 text)', 'foobox' );
			$social_title_choices['custom']  = __( 'Custom title', 'foobox' );

			$foobox->admin_settings_add( array(
				'id'      => 'social_title',
				'title'   => __( 'Title When Sharing', 'foobox' ),
				'desc'    => __( 'Determines how the title is generated when sharing on the social networks', 'foobox' ),
				'default' => 'title',
				'type'    => 'radio',
				'section' => 'main',
				'choices' => $social_title_choices,
				'tab'     => 'social'
			) );

			$foobox->admin_settings_add( array(
				'id'      => 'social_title_custom',
				'title'   => __( 'Custom Title', 'foobox' ),
				'desc'    => __( 'The custom title text used when "Custom title" is selected above', 'foobox' ),
				'type'    => 'text',
				'section' => 'main',
				'tab'     => 'social',
				'class'   => 'short_input'
			) );

			$foobox->admin_settings_add( array(
				'id'      => 'social_iframe',
				'title'   => __( 'Show Icons With iFrames', 'foobox' ),
				'desc'    => __( 'Show the social icons when viewing iFrame content.', 'foobox' ),
				'type'    => 'checkbox',
				'section' => 'main',
				'tab'     => 'social'
			) );

			$foobox->admin_settings_add( array(
				'id'      => 'social_html',
				'title'   => __( 'Show Icons With HTML', 'foobox' ),
				'desc'    => __( 'Show the social icons when viewing inline HTML content.', 'foobox' ),
				'type'    => 'checkbox',
				'section' => 'main',
				'tab'     => 'social'
			) );

			$foobox->admin_settings_add_section_to_tab( 'social', 'position', __( 'Social Icon Position', 'foobox' ) );

			$social_position_choices           = array();
			$social_position_choices['above']  = __( 'Above', 'foobox' );
			$social_position_choices['top']    = __( 'Top', 'foobox' );
			$social_position_choices['bottom'] = __( 'Bottom', 'foobox' );
			$social_position_choices['below']  = __( 'Below', 'foobox' );

			$foobox->admin_settings_add( array(
				'id'      => 'social_vertical',
				'title'   => __( 'Vertical Position', 'foobox' ),
				'default' => 'above',
				'type'    => 'radio',
				'section' => 'position',
				'choices' => $social_position_choices,
				'tab'     => 'social'
			) );

			$social_orientation_choices           = array();
			$social_orientation_choices['left']   = __( 'Left', 'foobox' );
			$social_orientation_choices['center'] = __( 'Center', 'foobox' );
			$social_orientation_choices['right']  = __( 'Right', 'foobox' );

			$foobox->admin_settings_add( array(
				'id'      => 'social_horizontal',
				'title'   => __( 'Horizontal Position', 'foobox' ),
				'default' => 'center',
				'type'    => 'radio',
				'section' => 'position',
				'choices' => $social_orientation_choices,
				'tab'     => 'social'
			) );

			$foobox->admin_settings_add( array(
				'id'      => 'social_icons_stacked',
				'title'   => __( 'Stack Icons', 'foobox' ),
				'desc'    => __( 'Stack the social icons on top of each other. This only works with Left and Right positions', 'foobox' ),
				'type'    => 'checkbox',
				'section' => 'position',
				'tab'     => 'social'
			) );

			$foobox->admin_settings_add_section_to_tab( 'social', 'settings', __( 'Networks', 'foobox' ) );

			$foobox->admin_settings_add( array(
				'id'      => 'social_facebook',
				'title'   => __( 'Facebook Enabled', 'foobox' ),
				'desc'    => sprintf( __( 'We recommend adding OpenGraph meta data to your site\'s %s section. See the OpenGraph settings below.', 'foobox' ), '<code>head</code>' ),
				'default' => 'on',
				'type'    => 'checkbox',
				'section' => 'settings',
				'tab'     => 'social'
			) );

			$foobox->admin_settings_add( array(
				'id'      => 'social_googleplus',
				'title'   => __( 'Google+ Enabled', 'foobox' ),
				'desc'    => __( 'PLEASE NOTE : Google+ sharing has some limitations. The page will be shared, rather than individual images.', 'foobox' ),
				'default' => 'on',
				'type'    => 'checkbox',
				'section' => 'settings',
				'tab'     => 'social'
			) );

			$foobox->admin_settings_add( array(
				'id'      => 'social_pinterest',
				'title'   => __( 'Pinterest Enabled', 'foobox' ),
				'default' => 'on',
				'type'    => 'checkbox',
				'section' => 'settings',
				'tab'     => 'social'
			) );

			$foobox->admin_settings_add( array(
				'id'      => 'social_linkedin',
				'title'   => __( 'LinkedIn Enabled', 'foobox' ),
				'default' => 'on',
				'type'    => 'checkbox',
				'section' => 'settings',
				'tab'     => 'social'
			) );

			$foobox->admin_settings_add( array(
				'id'      => 'social_twitter',
				'title'   => __( 'Twitter Enabled', 'foobox' ),
				'default' => 'on',
				'type'    => 'checkbox',
				'section' => 'settings',
				'tab'     => 'social'
			) );

			$foobox->admin_settings_add( array(
				'id'      => 'social_buffer',
				'title'   => __( 'Buffer Enabled', 'foobox' ),
				'default' => 'on',
				'type'    => 'checkbox',
				'section' => 'settings',
				'tab'     => 'social'
			) );

			$foobox->admin_settings_add( array(
				'id'      => 'social_digg',
				'title'   => __( 'Digg Enabled', 'foobox' ),
				'default' => 'on',
				'type'    => 'checkbox',
				'section' => 'settings',
				'tab'     => 'social'
			) );

			$foobox->admin_settings_add( array(
				'id'      => 'social_reddit',
				'title'   => __( 'Reddit Enabled', 'foobox' ),
				'default' => 'on',
				'type'    => 'checkbox',
				'section' => 'settings',
				'tab'     => 'social'
			) );

			$foobox->admin_settings_add( array(
				'id'      => 'social_tumblr',
				'title'   => __( 'Tumblr Enabled', 'foobox' ),
				'default' => 'on',
				'type'    => 'checkbox',
				'section' => 'settings',
				'tab'     => 'social'
			) );

			$foobox->admin_settings_add( array(
				'id'      => 'social_stumbleupon',
				'title'   => __( 'StumbleUpon Enabled', 'foobox' ),
				'default' => 'on',
				'type'    => 'checkbox',
				'section' => 'settings',
				'tab'     => 'social'
			) );

			$foobox->admin_settings_add( array(
				'id'      => 'social_email',
				'title'   => __( 'Email Enabled', 'foobox' ),
				'desc'    => __( 'Adds an email link so that images can easily be shared by email', 'foobox' ),
				'type'    => 'checkbox',
				'section' => 'settings',
				'tab'     => 'social'
			) );

			$foobox->admin_settings_add( array(
				'id'      => 'social_download',
				'title'   => __( 'Download Original Image', 'foobox' ),
				'desc'    => __( 'Adds a link to download the original image. The image is simply opened in a new browser window or tab', 'foobox' ),
				'type'    => 'checkbox',
				'section' => 'settings',
				'tab'     => 'social'
			) );

			$foobox->admin_settings_add_section_to_tab( 'social', 'opengraph', __( 'OpenGraph Settings', 'foobox' ) );

			$plugin = fooboxV2::PLUGIN_WORDPRESS_SEO;

			if ( $foobox->is_wordpress_seo_plugin_detected() ) {

				$foobox->admin_settings_add( array(
					'id'      => 'social_opengraph_desc',
					'title'   => __( 'OpenGraph Tags', 'foobox' ),
					'desc'    => '<div class="foo-message">' . sprintf( __( 'The plugin <strong>%s</strong> has been detected, which includes support for OpenGraph meta tags already.<br />The OpenGraph functionality from that plugin will be used.', 'foobox' ), $plugin ) . '</div>',
					'type'    => 'html',
					'section' => 'opengraph',
					'tab'     => 'social'
				) );

			} else {

				$url = admin_url( 'plugin-install.php?tab=search&s=WordPress+SEO+by+yoast&plugin-search-input=Search+Plugins' );

//				$foobox->admin_settings_add( array(
//					'id'      => 'social_promote_opengraph',
//					'title'   => __( 'OpenGraph Tags', 'foobox' ),
//					'desc'    => '<div class="foo-message">' . sprintf( __( 'The plugin <strong>%s</strong> has NOT been detected, which includes support for Facebook OpenGraph meta tags.<br />We recommend that you install %s and enable the OpenGraph functionality. <a target="_blank" href="%s">Install it now!</a>', 'foobox' ), $plugin, $plugin, $url ) . '</div>',
//					'type'    => 'html',
//					'section' => 'opengraph',
//					'tab'     => 'social'
//				) );

				$foobox->admin_settings_add( array(
					'id'      => 'social_opengraph',
					'title'   => __( 'Add OpenGraph', 'foobox' ),
					'desc'    => sprintf( __( 'Automatically add OpenGraph meta data to your site.<br />We recommend that you install %s and enable the OpenGraph functionality. <a target="_blank" href="%s">Install it now!</a>', 'foobox' ), $plugin, $url ),
					'type'    => 'checkbox',
					'section' => 'opengraph',
					'tab'     => 'social'
				) );
			}

			$foobox->admin_settings_add_section_to_tab( 'social', 'facebook', __( 'Facebook Settings', 'foobox' ) );

			$foobox->admin_settings_add( array(
				'id'      => 'social_facebook_feed',
				'title'   => __( 'Enable New Feed Dialog', 'foobox' ),
				'desc'    => __( 'Enable Facebook\'s new feed dialog way of sharing, rather than their old (and deprecated!) sharer.<br />Please note that this method requires a Facebook App Id in order to function!', 'foobox' ),
				'type'    => 'checkbox',
				'section' => 'facebook',
				'tab'     => 'social'
			) );

			$foobox->admin_settings_add( array(
				'id'      => 'social_facebook_appid',
				'title'   => __( 'Facebook App ID', 'foobox' ),
				'desc'    => $facebook_app_message,
				'default' => '',
				'type'    => 'text',
				'section' => 'facebook',
				'tab'     => 'social',
				'class'   => 'short_input'
			) );

			$foobox->admin_settings_add( array(
				'id'      => 'social_facebook_redirect_url',
				'title'   => __( 'Facebook Feed Redirect URL', 'foobox' ),
				'desc'    => __( 'When using the Facebook feed dialog, you might want to override the redirect URL. This is the URL facebook will navigate to when someone clicks cancel on the share page.<br />By default this is your site URL.', 'foobox' ),
				'default' => site_url(),
				'type'    => 'text',
				'section' => 'facebook',
				'tab'     => 'social'
			) );

			$foobox->admin_settings_add_section_to_tab( 'social', 'twitter', __( 'Twitter Settings', 'foobox' ) );

			$foobox->admin_settings_add( array(
				'id'      => 'social_twitter_username',
				'title'   => __( 'Twitter Username', 'foobox' ),
				'desc'    => __( 'The Twitter username that will be appended to the end of all tweets', 'foobox' ),
				'default' => '',
				'type'    => 'text',
				'section' => 'twitter',
				'tab'     => 'social',
				'class'   => 'short_input',
				'prefix'  => '@'
			) );

			$foobox->admin_settings_add( array(
				'id'      => 'social_twitter_hashtags',
				'title'   => __( 'Twitter Hashtags', 'foobox' ),
				'desc'    => __( 'Comma seperated list of hashtags to be included in all tweets', 'foobox' ),
				'default' => '',
				'type'    => 'text',
				'section' => 'twitter',
				'tab'     => 'social',
				'class'   => 'short_input',
				'prefix'  => '@'
			) );

			if ( apply_filters( 'foobox_settings_show_advanced', true ) ) {

				$foobox->admin_settings_add_tab( 'advanced', __( 'Advanced', 'foobox' ) );

				if ( ! $is_multisite ) {

					$foobox->admin_settings_add_section_to_tab( 'advanced', 'affiliate', __( 'Affiliate Link', 'foobox' ) );

					$foobox->admin_settings_add( array(
						'id'      => 'affiliate_enabled',
						'title'   => __( 'Show Affiliate Link', 'foobox' ),
						'desc'    => sprintf( __( 'Show a %s affiliate link in the modal window', 'foobox' ), $name ),
						'default' => 'on',
						'type'    => 'checkbox',
						'section' => 'affiliate',
						'tab'     => 'advanced'
					) );

					$become_affiliate_link = sprintf( '<a target="_blank" href="%s">%s</a>', fooboxV2::BECOME_AFFILIATE_URL, __( 'Become an affiliate', 'foobox' ) );

					$foobox->admin_settings_add( array(
						'id'      => 'affiliate_url',
						'title'   => __( 'Affiliate URL', 'foobox' ),
						'desc'    => sprintf( __( 'Use your own affiliate URL. %s and make extra cash from %s!', 'foobox' ), $become_affiliate_link, $name ),
						'default' => fooboxV2::FOOBOX_URL,
						'type'    => 'text',
						'section' => 'affiliate',
						'tab'     => 'advanced'
					) );

					$foobox->admin_settings_add( array(
						'id'      => 'affiliate_prefix',
						'title'   => __( 'Affiliate Prefix', 'foobox' ),
						'desc'    => __( 'The text shown before the affiliate link', 'foobox' ),
						'default' => __( fooboxV2::AFFILIATE_PREFIX, 'foobox' ),
						'type'    => 'text',
						'section' => 'affiliate',
						'tab'     => 'advanced',
						'class'   => 'short_input'
					) );
				}

				$foobox->admin_settings_add_section_to_tab( 'advanced', 'other', __( 'Other', 'foobox' ) );

				$foobox->admin_settings_add( array(
					'id'      => 'disable_swipe',
					'title'   => __( 'Disable Swipe', 'foobox' ),
					'desc'    => __( 'Disable the swipe touch functionality on mobile devices', 'foobox' ),
					'type'    => 'checkbox',
					'section' => 'other',
					'tab'     => 'advanced'
				) );

				$foobox->admin_settings_add( array(
					'id'      => 'disable_others',
					'title'   => __( 'Disable Other Lightboxes', 'foobox' ),
					'desc'    => __( 'Try to disable other lightbox scripts built into themes and other plugins (PrettyPhoto and ThickBox)', 'foobox' ),
					'type'    => 'checkbox',
					'section' => 'other',
					'tab'     => 'advanced'
				) );

				$foobox->admin_settings_add( array(
					'id'      => 'deregister_others',
					'title'   => __( 'Deregister Other Lightbox Files', 'foobox' ),
					'desc'    => __( 'Try to deregister other lightbox scripts and stylesheets', 'foobox' ),
					'type'    => 'checkbox',
					'section' => 'other',
					'tab'     => 'advanced'
				) );

				$foobox->admin_settings_add( array(
					'id'      => 'scripts_in_footer',
					'title'   => __( 'Scripts In Footer', 'foobox' ),
					'desc'    => __( 'Load the javascript files in the site footer (for better performance). This requires the theme to have the wp_footer() hook in the appropriate place', 'foobox' ),
					'type'    => 'checkbox',
					'section' => 'other',
					'tab'     => 'advanced'
				) );

				$foobox->admin_settings_add( array(
					'id'      => 'foobox_ready_event',
					'title'   => __( 'Use Custom Ready Event', 'foobox' ),
					'desc'    => __( 'If you are having issues with FooBox loading, you can try using a custom FooBox ready event', 'foobox' ),
					'type'    => 'checkbox',
					'section' => 'other',
					'tab'     => 'advanced'
				) );

				$foobox->admin_settings_add( array(
					'id'      => 'enable_debug',
					'title'   => __( 'Enable Debug Mode', 'foobox' ),
					'desc'    => sprintf( __( 'If this is enabled, %s will write to the console log so you can debug any problems. We also show an extra debug information tab on this settings page', 'foobox' ), $name ),
					'type'    => 'checkbox',
					'section' => 'other',
					'tab'     => 'advanced'
				) );

				$foobox->admin_settings_add( array(
					'id'      => 'force_delay',
					'title'   => __( 'Force Delay', 'foobox' ),
					'desc'    => __( 'Force a delay between loading items. You can use this to test what the loading indicator looks like', 'foobox' ),
					'default' => '0',
					'type'    => 'text',
					'section' => 'other',
					'tab'     => 'advanced',
					'class'   => 'short_input'
				) );

				$foobox->admin_settings_add( array(
					'id'      => 'disable_font_preload',
					'title'   => __( 'Disable Font Preload', 'foobox' ),
					'desc'    => sprintf( __( 'By default, we preload the foobox font on the page by adding a small hidden span to the body.', 'foobox' ), $name ),
					'type'    => 'checkbox',
					'section' => 'other',
					'tab'     => 'advanced'
				) );

                $foobox->admin_settings_add( array(
                    'id'      => 'dropie7support',
                    'title'   => __( 'Drop IE7 Support', 'foobox' ),
                    'desc'    => sprintf( __( 'Drop support for IE7, which removes some hacks to get things working in IE7. This also allows the foobox CSS to pass CSS validation.', 'foobox' ), $name ),
                    'type'    => 'checkbox',
                    'section' => 'other',
                    'tab'     => 'advanced'
                ) );

			}

			if ( apply_filters( 'foobox_settings_show_js', true ) ) {
				$foobox->admin_settings_add_tab( 'custom', __( 'JS &amp; CSS', 'foobox' ) );

				$foobox->admin_settings_add( array(
					'id'      => 'custom_css',
					'title'   => __( 'Custom CSS', 'foobox' ),
					'desc'    => sprintf( __( 'Alter the icon set, colour scheme, or the look and feel of %s using custom CSS styles. (Only to be used by developers!)', 'foobox' ), $name ),
					'default' => '',
					'type'    => 'textarea',
					'tab'     => 'custom',
					'class'   => 'medium_textarea'
				) );

				$foobox->admin_settings_add( array(
					'id'      => 'custom_modal_css',
					'title'   => __( 'Custom Modal Class', 'foobox' ),
					'desc'    => __( 'Add a custom CSS class to the FooBox modal element', 'foobox' ),
					'default' => '',
					'type'    => 'text',
					'tab'     => 'custom',
					'class'   => 'short_input'
				) );

				$foobox->admin_settings_add_section_to_tab( 'custom', 'custom_js', __( 'Javascript', 'foobox' ) );

				$foobox->admin_settings_add( array(
					'id'      => 'custom_pre_js',
					'title'   => __( 'Custom Javascript (Pre)', 'foobox' ),
					'desc'    => sprintf( __( 'Call any custom JS before %s is initialized. (Only to be used by developers!)', 'foobox' ), $name ),
					'default' => '',
					'type'    => 'textarea',
					'tab'     => 'custom',
					'section' => 'custom_js',
					'class'   => 'medium_textarea'
				) );

				$example_js_code = "$('.fbx-instance').on('foobox.beforeShow', function(e) {
	var \$element = $(e.fb.item.element),		//the anchor tag
		\$fooboxInstance = e.fb.instance,		//the foobox instance
		\$modal = e.fb.instance.modal.element;	//the modal object

	//your custom code goes here...

});";

				$example_js_link = ' <a href="#custom_js" class="foobox_insert_code" data-code="' . $example_js_code . '" >' . __( 'Insert example code', 'foobox' ) . '</a>';

				$foobox->admin_settings_add( array(
					'id'      => 'custom_js',
					'title'   => __( 'Custom Javascript (Post)', 'foobox' ),
					'desc'    => sprintf( __( 'Alter the way %s works by hooking into the built-in events, using custom javascript code. (Only to be used by developers!)', 'foobox' ), $name ) . $example_js_link,
					'default' => '',
					'type'    => 'textarea',
					'tab'     => 'custom',
					'section' => 'custom_js',
					'class'   => 'medium_textarea'
				) );

				$foobox->admin_settings_add( array(
					'id'      => 'custom_js_options',
					'title'   => __( 'Custom JS Options', 'foobox' ),
					'desc'    => sprintf( __( 'Alter the options passed into %s for complete customization. (Only to be used by developers!)', 'foobox' ), $name ),
					'default' => '',
					'type'    => 'textarea',
					'tab'     => 'custom',
					'section' => 'custom_js',
					'class'   => 'medium_textarea'
				) );

				$foobox->admin_settings_add( array(
					'id'      => 'custom_js_extra',
					'title'   => __( 'Custom Extra JS', 'foobox' ),
					'desc'    => sprintf( __( 'Add some custom javascript after the %s init code. (Only to be used by developers!)', 'foobox' ), $name ),
					'default' => '',
					'type'    => 'textarea',
					'tab'     => 'custom',
					'section' => 'custom_js',
					'class'   => 'medium_textarea'
				) );

				$foobox->admin_settings_add( array(
					'id'      => 'custom_selector',
					'title'   => __( 'Custom FooBox Selector', 'foobox' ),
					'desc'    => sprintf( __( 'The selector is used to find elements within a container to open in FooBox. (Only to be used by developers!)', 'foobox' ), $name ),
					'type'    => 'text',
					'tab'     => 'custom',
					'section' => 'custom_js',
					'class'   => 'short_input'
				) );

				$foobox->admin_settings_add( array(
					'id'      => 'custom_excludes',
					'title'   => __( 'Custom Excludes', 'foobox' ),
					'desc'    => sprintf( __( 'The exclude selector is used to exclude certain elements, so that they do not open in FooBox. (Default is <code>.fbx-link, .nofoobox</code>)', 'foobox' ), $name ),
					'type'    => 'text',
					'tab'     => 'custom',
					'section' => 'custom_js',
					'class'   => 'short_input'
				) );

				$example_caption_code = "$('.fbx-instance').on('foobox.alterCaption', function(e) {
	var \$element = $(e.fb.item.element),
		title = e.fb.item.title,
		description = e.fb.item.description;

	//your custom code goes here...
	// e.fb.item.caption = '&lt;div class=&quot;fbx-caption-title&quot;&gt;' + title + '&lt;/div&gt;' +
	//		'&lt;div class=&quot;fbx-caption-desc&quot;&gt;' + description + '&lt;/div&gt;';

});";

				$example_caption_link = ' <a href="#custom_js_captions" class="foobox_insert_code" data-code="' . $example_caption_code . '" >' . __( 'Insert example caption code', 'foobox' ) . '</a>';

				$foobox->admin_settings_add( array(
					'id'      => 'custom_js_captions',
					'title'   => __( 'Custom Captions Code', 'foobox' ),
					'desc'    => sprintf( __( 'Intercept and change FooBox captions via the foobox.alterCaption event. (Only to be used by developers!)', 'foobox' ), $name ) . $example_caption_link,
					'type'    => 'textarea',
					'tab'     => 'custom',
					'section' => 'custom_js',
					'class'   => 'medium_textarea'
				) );

				$example_initcallback_code = "	function() {
		//your custom code goes here...
		//this.instance is the FooBox instance
		//this.items.array are all the items bound to FooBox
	}
";

				$example_js_link = ' <a href="#custom_initcallback_js" class="foobox_insert_code" data-code="' . $example_initcallback_code . '" >' . __( 'Insert example code', 'foobox' ) . '</a>';

				$foobox->admin_settings_add( array(
					'id'      => 'custom_initcallback_js',
					'title'   => __( 'Custom Init Callback Javascript', 'foobox' ),
					'desc'    => sprintf( __( 'Code that runs after FooBox has loaded. (Only to be used by developers!)', 'foobox' ), $name ) . $example_js_link,
					'default' => '',
					'type'    => 'textarea',
					'tab'     => 'custom',
					'section' => 'custom_js',
					'class'   => 'medium_textarea'
				) );

			}

			$foobox->admin_settings_add_tab( 'demo', __( 'Demo', 'foobox' ) );

			$foobox->admin_settings_add( array(
				'id'    => 'demo_js',
				'title' => '',
				'desc'  => sprintf( __( 'PLEASE NOTE : If you have made changes to the settings, please save settings first in order to see your changes', 'foobox' ), $name ),
				'type'  => 'demo',
				'tab'   => 'demo'
			) );

			if ( $foobox->is_option_checked( 'enable_debug', fooboxV2::DEBUG_DEFAULT ) ) {
				$foobox->admin_settings_add_tab( 'debug', __( 'Debug Info', 'foobox' ) );

				$foobox->admin_settings_add( array(
					'id'      => 'debug_output',
					'title'   => __( 'Debug Information', 'foobox' ),
					'default' => 'off',
					'type'    => 'debug_output',
					'tab'     => 'debug'
				) );
			}

			$foobox->admin_settings_add_tab( 'foobot_says', __('FooBot Says...', 'foobox') );

			$foobox->admin_settings_add( array(
				'id'    => 'foobot_says',
				'title' => __('', 'foobox-free'),
				'type'  => 'foobot_says',
				'tab'   => 'foobot_says'
			) );

			if ( ! $is_multisite ) {

				$foobox->admin_settings_add_tab( 'support', __( 'Support', 'foobox' ) );

				$foobox->admin_settings_add( array(
					'id'    => 'support',
					'title' => '',
					'type'  => 'support',
					'tab'   => 'support'
				) );

			}
		}
	}
}