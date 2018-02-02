<?php

if ( !class_exists( 'FooBox_Script_Generator' ) ) {
	class FooBox_Script_Generator {

		static function get_option($options, $key, $default = false) {
			if ( $options ) {
				return (array_key_exists( $key, $options )) ? $options[$key] : $default;
			}

			return $default;
		}

		static function is_option_checked($options, $key, $default = false) {
			if ( $options ) {
				return array_key_exists( $key, $options );
			}

			return $default;
		}

		static function generate_js_options($fbx_options) {
			$options = array();

			$modal_classes = array();

			$theme  = self::get_option( $fbx_options, 'theme', 'fbx-rounded' );
			$colour = self::get_option( $fbx_options, 'colour', 'light' );
			$icon   = self::get_option( $fbx_options, 'icon', '0' );
			$loader = self::get_option( $fbx_options, 'loader', '0' );

			if ( $theme !== 'fbx-rounded' ) {
				$options['style'] = 'style: "' . $theme . '"';
			}
			if ( $colour == 'white' ) {
				$colour = 'light';
			}

			if ( $colour !== 'light' ) {
				$options['theme'] = 'theme: "fbx-' . $colour . '"';
			}
			if ( $icon !== '0' ) {
				$modal_classes[] = 'fbx-arrows-' . $icon;
			}
			if ( $loader !== '0' ) {
				$modal_classes[] = 'fbx-spinner-' . $loader;
			}

			$debug       = self::is_option_checked( $fbx_options, 'enable_debug' );
			$forceDelay  = self::get_option( $fbx_options, 'force_delay', '0' );
			$fitToScreen = self::is_option_checked( $fbx_options, 'fit_to_screen' );

			$allowFullscreen = self::is_option_checked( $fbx_options, 'allow_fullscreen' );
			$forceFullscreen = self::is_option_checked( $fbx_options, 'force_fullscreen' );
			$fullscreen_api  = self::is_option_checked( $fbx_options, 'fullscreen_api' );

			$forceCaptionsBottom    = self::is_option_checked( $fbx_options, 'force_caption_bottom' );
			$hideScrollbars         = self::is_option_checked( $fbx_options, 'hide_scrollbars', true );
			$hideButtons            = self::is_option_checked( $fbx_options, 'hide_buttons' );
			$close_overlay_click    = self::is_option_checked( $fbx_options, 'close_overlay_click', true );
			$preload_images         = self::is_option_checked( $fbx_options, 'preload_images', true );
			$show_count             = self::is_option_checked( $fbx_options, 'show_count', true );
			$caption_prettify       = self::is_option_checked( $fbx_options, 'caption_prettify', true );
			$show_caption           = self::is_option_checked( $fbx_options, 'show_caption', true );
			$captions_show_on_hover = self::is_option_checked( $fbx_options, 'captions_show_on_hover' );
			$caption_title_source   = self::get_option( $fbx_options, 'caption_title_source', 'default' );
			$caption_desc_source    = self::get_option( $fbx_options, 'caption_desc_source', 'default' );
			$count_message          = self::get_option( $fbx_options, 'count_message', 'image %index of %total' );
			$affiliate_enabled      = self::is_option_checked( $fbx_options, 'affiliate_enabled', true );
			$affiliate_prefix       = self::get_option( $fbx_options, 'affiliate_prefix', fooboxV2::AFFILIATE_PREFIX );
			$affiliate_url          = self::get_option( $fbx_options, 'affiliate_url', fooboxV2::FOOBOX_URL );
			$error_msg              = self::get_option( $fbx_options, 'error_message', fooboxV2::ERROR_MSG );
			$slideshow_enabled      = self::is_option_checked( $fbx_options, 'slideshow_enabled', true );
			$slideshow_autostart    = self::is_option_checked( $fbx_options, 'slideshow_autostart' );
			$slideshow_autostop     = self::is_option_checked( $fbx_options, 'slideshow_autostop' );
			$slideshow_timeout      = self::get_option( $fbx_options, 'slideshow_timeout', '6' );
			$disble_deeplinking     = self::is_option_checked( $fbx_options, 'disble_deeplinking', false );
			$custom_js_options      = self::get_option( $fbx_options, 'custom_js_options', '' );
			$custom_modal_css       = self::get_option( $fbx_options, 'custom_modal_css', '' );
			$disable_swipe			= self::is_option_checked( $fbx_options, 'disable_swipe' );
			$enable_protection 		= self::is_option_checked( $fbx_options, 'enable_protection', false );
			$video_captions			= self::is_option_checked( $fbx_options, 'video_captions', false );
			$html_captions			= self::is_option_checked( $fbx_options, 'html_captions', false );
			$iframe_captions		= self::is_option_checked( $fbx_options, 'iframe_captions', false );
			$caption_anim			= self::get_option( $fbx_options, 'caption_anim', 'slide' );
			$custom_selector		= self::get_option( $fbx_options, 'custom_selector', '' );
			$custom_excludes		= self::get_option( $fbx_options, 'custom_excludes', '' );
			$captions_hidden	    = self::is_option_checked( $fbx_options, 'captions_hidden', false );
			$custom_initcallback_js	= self::get_option( $fbx_options, 'custom_initcallback_js' );
			$iframe_fullscreen	    = self::is_option_checked( $fbx_options, 'iframe_fullscreen', false );
			$iframe_loading			= self::get_option( $fbx_options, 'iframe_loading', 'default' );
			$open_animation         = self::get_option( $fbx_options, 'open_animation', 'none' );
			$image_rel_selector     = self::get_option( $fbx_options, 'image_rel_selector', 'foobox' );
			$button_type            = self::get_option( $fbx_options, 'button_type', 'default' );

			$options['wordpress']         = 'wordpress: { enabled: true }';

			if ($image_rel_selector !== 'foobox') {
				$options['rel'] = "rel: '{$image_rel_selector}'";
			}

			$iframe_options = array();
			if ($iframe_loading !== 'default') {
				$iframe_options['showImmediate'] = 'showImmediate: true';
			}
			if ($iframe_fullscreen) {
				$iframe_options['allowFullscreen'] = 'allowFullscreen: true';
			}
			if (count($iframe_options) > 0) {
				$options['iframe'] = 'iframe: { ' . implode( ', ', $iframe_options ) . ' }';
			}

			if ( $debug ) {
				$options['debug'] = 'debug:true';
			}
			if ( $disble_deeplinking ) {
				$options['deeplinking'] = 'deeplinking : { enabled: false }';
			} else {
				$deeplinking_prefix     = self::get_option( $fbx_options, 'deeplinking_prefix', 'foobox' );
				$options['deeplinking'] = 'deeplinking : { enabled: true, prefix: "' . $deeplinking_prefix . '" }';
			}
			if ( intval( $forceDelay ) > 0 ) {
				$options['loadDelay'] = 'loadDelay:' . $forceDelay;
			}
			if ( $allowFullscreen || $forceFullscreen ) {
				$fullscreen         = 'fullscreen : { ';
				$fullscreen_options = array();
				if ( $allowFullscreen ) {
					$fullscreen_options[] = 'enabled: true';
				}
				if ( $forceFullscreen ) {
					$fullscreen_options[] = 'force: true';
				}
				if ( $fullscreen_api ) {
					$fullscreen_options[] = 'useAPI: true';
				}
				$fullscreen .= implode( ', ', $fullscreen_options );
				$fullscreen .= ' }';
				$options['fullscreen'] = $fullscreen;
			}

			if ( $forceCaptionsBottom ) {
				$modal_classes[] = 'fbx-sticky-caption';
			}

			if ( !empty($custom_modal_css) ) {
				$modal_classes[] = $custom_modal_css;
			}

			if ( $fitToScreen ) {
				$options['fitToScreen'] = 'fitToScreen:true';
			}
			if ( !$hideScrollbars ) {
				$options['hideScrollbars'] = 'hideScrollbars:false';
			}
			if ( $hideButtons ) {
				$options['showButtons'] = 'showButtons:false';
			}
			if ( !$close_overlay_click ) {
				$options['closeOnOverlayClick'] = 'closeOnOverlayClick:false';
			}
			if ( !$show_count ) {
				$options['showCount'] = 'showCount:false';
			}
			if ( $open_animation !== 'none' ) {
				$options['effect'] = "effect: '{$open_animation}'";
			}



			if ( !$show_caption ) {
				$no_right_click = $enable_protection ? 'noRightClick: true, ': '';
				$options['images'] = "images: { $no_right_click showCaptions:false }";
			} else {
				if ($enable_protection) {
					$options['images'] = 'images: { noRightClick: true }';
				}

				$caption_options = array();
				if ( $captions_show_on_hover ) {
					$caption_options[] = 'onlyShowOnHover:true';
				}
				if ( $captions_hidden ) {
					$caption_options[] = 'onlyShowOnClick:true';
				}
				if ( $caption_title_source !== 'default' ) {
					$caption_options[] = "overrideTitle:true";
					$caption_options[] = "titleSource:'{$caption_title_source}'";
				}
				if ( $caption_desc_source !== 'default' ) {
					$caption_options[] = "overrideDesc:true";
					$caption_options[] = "descSource:'{$caption_desc_source}'";
				}
				if ( $caption_anim !== 'slide' ) {
					$caption_options[] = "animation:'{$caption_anim}'";
				}
				if ( $caption_prettify ) {
					$caption_options[] = 'prettify:true';
				}
				if ( sizeof( $caption_options ) > 0 ) {
					$options['captions'] = 'captions: { ' . implode( ', ', $caption_options ) . ' }';
				}
			}
			if ( $count_message != 'item %index of %total' ) {
				$options['countMessage'] = 'countMessage:\'' . addslashes( $count_message ) . '\'';
			}

			$js_excludes[] = '.fbx-link';
			$js_excludes[] = '.nofoobox';
			$js_excludes[] = '.nolightbox';
			$js_excludes[] = 'a[href*="pinterest.com/pin/create/button/"]';

			if ( class_exists( 'JustifiedImageGrid' ) && self::is_option_checked( $fbx_options, 'support_jig', false ) ) {
				$js_excludes[] = '.jig-customLink';
			}

			if ( !empty( $custom_excludes ) ) {
				$js_excludes[] = $custom_excludes;
			}

			$options['excludes'] = 'excludes:\'' . implode(',', $js_excludes) . '\'';

			//Affiliates
			if ( $affiliate_enabled ) {
				$affiliate = 'affiliate : { enabled: true';
				if ( $affiliate_prefix != fooboxV2::AFFILIATE_PREFIX && $affiliate_prefix != '' ) {
					$affiliate .= ', prefix: \'' . addslashes( $affiliate_prefix ) . '\'';
				}
				if ( $affiliate_url != fooboxV2::FOOBOX_URL && $affiliate_url != '' ) {
					$affiliate .= ', url: \'' . $affiliate_url . '\'';
				}
				$affiliate .= ' }';
				$options['affiliate'] = $affiliate;
			} else {
				$options['affiliate'] = 'affiliate : { enabled: false }';
			}

			if ( $error_msg != fooboxV2::ERROR_MSG && $error_msg != '' ) {
				$options['error'] = 'error: "' . addslashes( $error_msg ) . '"';
			}

			//Slideshow
			if ( $slideshow_enabled ) {

				$slideshow = 'slideshow: { enabled:true';

				if ( $slideshow_autostart ) {
					$slideshow .= ', autostart:true';
				}
				if ( $slideshow_autostop ) {
					$slideshow .= ', autostop:true';
				}

				if ( $slideshow_timeout != '6' ) {
					//$slideshow_timeout
					$slideshow_timeout = floatval( $slideshow_timeout ) * 1000;
					$slideshow .= ', timeout:' . $slideshow_timeout;
				}

				$slideshow .= '}';

				$options['slideshow'] = $slideshow;
			} else {
				$options['slideshow'] = 'slideshow: { enabled:false }';
			}

			$social = self::generate_social_options( $fbx_options );

			if ( $social !== false ) {
				$options['social'] = $social;
			}

			if ( $preload_images ) {
				$options['preload'] = 'preload:true';
			}

			if ($button_type !== 'default') {
				$modal_classes[] = $button_type;
			}

			if ( sizeof( $modal_classes ) > 0 ) {
				$options['modalClass'] = 'modalClass: "' . implode( ' ', $modal_classes ) . '"';
			}

			if ($disable_swipe) {
				$options['swipe'] = 'swipe : { enabled: false }';
			}

			if ($video_captions) {
				$options['videoCaptions'] = 'videos: { showCaptions:true }';
			}

			if ($html_captions) {
				$options['htmlCaptions'] = 'html: { showCaptions:true }';
			}

			if ($html_captions) {
				$options['iframeCaptions'] = 'iframe: { showCaptions:true }';
			}

			if (!empty($custom_selector)) {
				$options['selector'] = 'selector: "' . $custom_selector . '"';
			}

			if (!empty($custom_initcallback_js)) {
				$options['selector'] = 'initCallback: ' . $custom_initcallback_js;
			}

			$options = apply_filters( 'foobox-options', $options );

			if ( !empty($custom_js_options) ) {
				$custom_js_options = trim( $custom_js_options );
				if ( substr( $custom_js_options, 0, 1 ) != ',' ) {
					$custom_js_options = ',' . $custom_js_options;
				}
			}

			if ( sizeof( $options ) > 0 ) {
				$seperator = $debug ? ',
		' : ', ';
				return '{' . implode( $seperator, $options ) .
				$custom_js_options .
				'}';
			}

			return false;
		}

		static function generate_javascript_call($selector, $options, $bindings = false) {
			$js = "    $({$selector})";
			if ($bindings !== false) {
				$js .= $bindings;
			}
			$js .= ".foobox({$options});";
			return $js;
		}

		/**
		 * @param $foobox fooboxV2
		 * @param $debug  boolean
		 */
		static function generate_javascript($foobox, $debug = false) {
			$fbx_options = apply_filters('foobox-raw-options', $foobox->get_options());

			$js    = '/* Run FooBox (v' . $foobox->plugin_version() . ') */';

			$js_options = self::generate_js_options( $fbx_options );

			$is_nextgen_active = $foobox->is_nextgenv2_activated();

			if ( !empty($js_options) ) {
				$js .= sprintf( '
(function( FOOBOX, $, undefined ) {
  FOOBOX.o = %s;
  FOOBOX.init = function() {
    $(".fbx-link").removeClass("fbx-link");
', $js_options );
				$js_options = 'FOOBOX.o';
			}

			//get custom JS (pre) from the settings page
			$custom_pre_js = self::get_option( $fbx_options, 'custom_pre_js' );

			if ( !empty($custom_pre_js) ) {
				$js .= '    ' . $custom_pre_js . '
';
			}

			if ( self::is_option_checked( $fbx_options, 'disable_others', false ) ) {
				$js .= '  $("a.thickbox").removeClass("thickbox").unbind("click");
  $("#TB_overlay, #TB_load, #TB_window").remove();
';
			}

			$foobox_selectors = array();
			$foobox_global_selectors = array();

			if ( $foobox->check_admin_settings_page() ) {
				$foobox_selectors[] = '.demo-gallery,.bad-image';
//				$js .= '    //only used for the demo on the settings page. Will not be rendered to frontend
//' . self::generate_javascript_call( '".demo-gallery,.bad-image"', $js_options ) . '
//';
			}

			if ( $is_nextgen_active ) {
				if ( self::is_option_checked( $fbx_options, 'enable_nextgenV2', true ) ) {
					$foobox_selectors[] = '.ngg-galleryoverview, .ngg-widget, [id^=\'ngg-gallery-\']';
//					$js .= self::generate_javascript_call( '".ngg-galleryoverview, .ngg-widget, [id^=\'ngg-gallery-\']"', $js_options ) . '
//';
				}
			} else if ( class_exists( 'nggLoader' ) ) {
				if ( self::is_option_checked( $fbx_options, 'enable_nextgen', true ) ) {
					$foobox_selectors[] = '.ngg-galleryoverview, .ngg-widget';
//					$js .= self::generate_javascript_call( '".ngg-galleryoverview, .ngg-widget"', $js_options ) . '
//';
				}
			}

			if ( class_exists( 'Jetpack' ) ) {
				if ( self::is_option_checked( $fbx_options, 'jetpack_tiled_images', true ) ) {
					$foobox_selectors[] = '.tiled-gallery';
//					$js .= self::generate_javascript_call( '".tiled-gallery"', $js_options ) . '
//';
				}
			}

			if ( class_exists( 'Woocommerce' ) ) {
				if ( self::is_option_checked( $fbx_options, 'override_woocommerce_lightbox', true ) ) {
					$foobox_selectors[] = 'div.product .images';
//					$js .= self::generate_javascript_call( '"div.product .images"', $js_options ) . '
//';
				}
			}

			if ( self::is_option_checked( $fbx_options, 'enable_galleries', true ) ) {
				$foobox_selectors[] = '.gallery';
//				$js .= self::generate_javascript_call( '".gallery"', $js_options ) . '
//';
			}

			$class = self::get_option( $fbx_options, 'enable_class' );

			if ( !empty($class) ) {
				if ( !$foobox->utils()->starts_with( $class, '.' ) &&
					!$foobox->utils()->starts_with( $class, '#' ) ) {
					$class = '.' . $class;
				}
				if ( $foobox->render_for_archive() ) {
					$foobox_global_selectors[] = '.post ' . $class;	//archive selector
				}
				$foobox_selectors[] = $class;
//				$js .= self::generate_javascript_call( '"' . $class . '"', $js_options ) . '
//';
			}

			if ( class_exists( 'JustifiedImageGrid' ) ) {
				if ( self::is_option_checked( $fbx_options, 'support_jig', false ) ) {
					$js_options = '$.extend(true, {}, FOOBOX.o, { alwaysInit: true })';
					$foobox_selectors[] = '.jigFooBoxConnect';
//					$js .= self::generate_javascript_call( '".jigFooBoxConnect"', $js_options ) . '
//';
				}
			}

			$foobox_selectors[] = '.foobox, [target=\"foobox\"]';
//			$js .= self::generate_javascript_call( '".foobox, [target=\"foobox\"]"', $js_options ) . '
//';

			if ( self::is_option_checked( $fbx_options, 'enable_captions', true ) ) {
				$foobox_selectors[] = '.wp-caption';
//				$js .= self::generate_javascript_call( '".wp-caption"', $js_options ) . '
//';
			}

			if ( self::is_option_checked( $fbx_options, 'enable_attachments', true ) ) {
				$foobox_selectors[] = 'a:has(img[class*=wp-image-])';
				if ( $foobox->render_for_archive() ) {
					$foobox_global_selectors[] = '.post a:has(img[class*=wp-image-])';	//archive selector
				}
//				$js .= self::generate_javascript_call( '"a:has(img[class*=wp-image-])"', $js_options ) . '
//';
			}

			$foobox_extra_selector         = apply_filters( 'foobox_extra_selector', '' );

			if ( !empty($foobox_extra_selector) ) {
				$foobox_selectors[] = $foobox_extra_selector;
//				$js .= self::generate_javascript_call( '"' . $foobox_extra_selector . '"', $js_options ) . '
//';
			}

			//add support for foogallery!
			if ( class_exists('FooGallery_Plugin') ) {
				$foobox_selectors[] = '.foogallery-container.foogallery-lightbox-foobox';
				$foobox_selectors[] = '.foogallery-container.foogallery-lightbox-foobox-free';
			}

			$foobox_selectors = apply_filters( 'foobox_js_selectors', $foobox_selectors );

			if ( self::is_option_checked( $fbx_options, 'enable_all' ) ) {
				if ( $foobox->render_for_archive() ) {
					$foobox_global_selectors[] = '.post';
//					$js .= self::generate_javascript_call( '".post"', $js_options ) . '
//';
				}
				$foobox_global_selectors[] = 'body';
//				$js .= self::generate_javascript_call( '"body"', $js_options ) . '
//';
			}

			if ( self::is_option_checked( $fbx_options, 'disable_others', false ) ) {
				$js .= '    $(".fbx-link").unbind(".prettyphoto").unbind(".fb");
';
			}

			$foobox_extra_scripts_pre = apply_filters( 'foobox_extra_scripts_pre', '' );
			if ( !empty($foobox_extra_scripts_pre) ) {
				$js .= '    ' . $foobox_extra_scripts_pre . '
';
			}

			//output the calls to foobox!
			foreach($foobox_selectors as $selector) {
				$js .= self::generate_javascript_call( '"' . $selector . '"', $js_options ) . '
';
			}

			//output the call for all global calls
			foreach($foobox_global_selectors as $selector) {
				$js .= self::generate_javascript_call( '"' . $selector . '"', $js_options ) . '
';
			}

			//get custom JS from the settings page
			$custom_js = self::get_option( $fbx_options, 'custom_js' );

			if ( !empty($custom_js) ) {
				$js .= '    ' . $custom_js . '
';
			}

			//get custom captions JS from the settings page
			$custom_js_captions = self::get_option( $fbx_options, 'custom_js_captions' );

			if ( !empty($custom_js_captions) ) {
				$js .= '
    ' . $custom_js_captions . '
';
			}

			$ready_event = 'jQuery';

			if ( self::is_option_checked( $fbx_options, 'foobox_ready_event', false ) ) {
				$ready_event = 'FooBox.ready';
			}

			$preload = ( self::is_option_checked( $fbx_options, 'disable_font_preload', false ) ) ? '' : '  //preload the foobox font
  jQuery("body").append("<span style=\"font-family:\'foobox\'; color:transparent; position:absolute; top:-1000em;\">f</span>");';

			$js .= '
  };
}( window.FOOBOX = window.FOOBOX || {}, jQuery ));

' . $ready_event . '(function() {
'.$preload.'
  FOOBOX.init();
  jQuery(document).trigger("foobox-after-init");
';
			if ( $is_nextgen_active ) {
				$js .= '  jQuery(document).bind("refreshed", function() {
    FOOBOX.init();
  });
';
			}

			$foobox_extra_scripts_post = apply_filters( 'foobox_extra_scripts_post', '' );
			if ( !empty($foobox_extra_scripts_post) ) {
				$js .= '    ' . $foobox_extra_scripts_post . '
';
			}

			$js .= '
});
';

			$custom_js_extra = self::get_option( $fbx_options, 'custom_js_extra', '' );
			if ( !empty($custom_js_extra) ) {
				$js .= '    ' . $custom_js_extra . '
';
			}

			return $js;
		}

		/**
		 * @param $foobox fooboxV2
		 */
		static function generate_social_options($fbx_options) {
			global $post, $wp;

			if ( !self::is_option_checked( $fbx_options, 'social_enabled', true ) ) {
				return "social: { enabled: false }";
			}
			$debug      = self::is_option_checked( $fbx_options, 'enable_debug' );
			$vertical   = self::get_option( $fbx_options, 'social_vertical', 'above' );
			$horizontal = self::get_option( $fbx_options, 'social_horizontal', 'center' );
			$social_iframe = self::is_option_checked( $fbx_options, 'social_iframe', false );
			$social_html = self::is_option_checked( $fbx_options, 'social_html', false );
			$social_share_what	    = self::get_option( $fbx_options, 'social_share_what', 'default' );
			$social_hidden		    = self::is_option_checked( $fbx_options, 'social_hidden', false );

			if ( $horizontal === 'center' ) {
				$horizontal = '';
			}
			$stacked = '';
			if ( self::is_option_checked( $fbx_options, 'social_icons_stacked', false ) ) {
				$stacked = ' fbx-stacked';
			}

			$onlyShowOnHover = '';
			if ( self::is_option_checked( $fbx_options, 'social_show_on_hover', false ) ) {
				$onlyShowOnHover = 'onlyShowOnHover:true, ';
			}

			$onlyShowOnClick = '';
			if ( $social_hidden ) {
				$onlyShowOnClick = 'onlyShowOnClick:true, ';
			}

			$excludes = array(); //'iframe', 'html');
			if ($social_iframe === false) {
				$excludes[] = "'iframe'";
			}
			if ($social_html === false) {
				$excludes[] = "'html'";
			}
			$exclude = "excludes: [" . implode(',', $excludes) . "], ";

			$share_original = ($social_share_what == 'image') ? "original:true, " : "";

			//$querystring = ($social_share_what == 'page') ? "queryString: 'fbx-share={img}', " : "";

			//$querystring = "queryString: 'fbx-att={att}', ";

			$querystring = '';

			$social = "social: { {$querystring}enabled: true, {$share_original}{$exclude}{$onlyShowOnHover}{$onlyShowOnClick}position: 'fbx-{$vertical}{$horizontal}{$stacked}', links: [ ";

			$social_icons = array();

			$social_title        = addslashes( self::get_option( $fbx_options, 'social_title', 'title' ));
			$social_title_custom = addslashes( self::get_option( $fbx_options, 'social_title_custom', '' ));
			$social_title_json   = '';
			if ( $social_title != 'title' ) {
				$social_title_json = ", titleSource: '{$social_title}', titleCustom: '{$social_title_custom}'";
			}

			if ( self::is_option_checked( $fbx_options, 'social_facebook', true ) ) {
				$app_id = self::get_option( $fbx_options, 'social_facebook_appid', '' );
				if ( self::is_option_checked( $fbx_options, 'social_facebook_feed', false ) && !empty($app_id) ) {

					$redirect_url = urlencode( self::get_option( $fbx_options, 'social_facebook_redirect_url', site_url() ) );
					if ( strlen( $redirect_url ) == 0 ) {
						$redirect_url = urlencode( site_url() );
					}
					$social_icons[] = "{ css: 'fbx-facebook', title: 'Facebook', url: 'https://www.facebook.com/dialog/feed?app_id={$app_id}&link={url}&picture={img}&caption={title}&description={desc}&redirect_uri={$redirect_url}' {$social_title_json} }";
				} else {
					$social_icons[] = "{ css: 'fbx-facebook', title: 'Facebook', url: 'http://www.facebook.com/sharer.php?u={url}' {$social_title_json} }";
				}
			}

			if ( self::is_option_checked( $fbx_options, 'social_googleplus', true ) ) {
//				if ( $share_image_directly ) {
//					$social_icons[] = "{ css: 'fbx-google-plus', title: 'Google+', url: 'https://plus.google.com/share?url={img-ne}' {$social_title_json} }";
//				} else {
					$social_icons[] = "{ css: 'fbx-google-plus', title: 'Google+', url: 'https://plus.google.com/share?url={url-ne}' {$social_title_json} }";
//				}
			}

			if ( self::is_option_checked( $fbx_options, 'social_twitter', true ) ) {
				$via = addslashes( self::get_option( $fbx_options, 'social_twitter_username', '' ));
				if ( $via !== '' ) {
					$via = '&via=' . urlencode( str_replace( '@', '', $via ) );
				}

				$hashtags = addslashes( self::get_option( $fbx_options, 'social_twitter_hashtags', '' ));
				if ( $hashtags !== '' ) {
					$hashtags = '&hashtags=' . urlencode( str_replace( '#', '', $hashtags ) );
				}

//				if ( $share_image_directly ) {
//					$social_icons[] = "{ css: 'fbx-twitter', title: 'Twitter', url: 'https://twitter.com/share?url={img-ne}&text={title}{$via}{$hashtags}' {$social_title_json} }";
//				} else {
					$social_icons[] = "{ css: 'fbx-twitter', title: 'Twitter', url: 'https://twitter.com/share?url={url}&text={title}{$via}{$hashtags}' {$social_title_json} }";
//				}
			}

			if ( self::is_option_checked( $fbx_options, 'social_pinterest', true ) ) {
//				if ( $share_image_directly ) {
//					$social_icons[] = "{ css: 'fbx-pinterest', title: 'Pinterest', url: 'https://pinterest.com/pin/create/bookmarklet/?media={img!}&url={img-ne}&is_video={is_video}&description={title}' {$social_title_json} }";
//				} else {
					$social_icons[] = "{ css: 'fbx-pinterest', title: 'Pinterest', url: 'https://pinterest.com/pin/create/bookmarklet/?media={img!}&url={url}&is_video={is_video}&description={title}' {$social_title_json} }";
//				}
			}

			if ( self::is_option_checked( $fbx_options, 'social_linkedin', true ) ) {
//				if ( $share_image_directly ) {
//					$social_icons[] = "{ css: 'fbx-linkedin', title: 'LinkedIn', url: 'http://www.linkedin.com/shareArticle?url={img-ne}&title={title}' {$social_title_json} }";
//				} else {
					$social_icons[] = "{ css: 'fbx-linkedin', title: 'LinkedIn', url: 'http://www.linkedin.com/shareArticle?url={url}&title={title}' {$social_title_json} }";
//				}
			}

			if ( self::is_option_checked( $fbx_options, 'social_buffer', true ) ) {
//				if ( $share_image_directly ) {
//					$social_icons[] = "{ css: 'fbx-buffer', title: 'Buffer', url: 'http://bufferapp.com/add?text={title}&url={img-ne}' {$social_title_json} }";
//				} else {
					$social_icons[] = "{ css: 'fbx-buffer', title: 'Buffer', url: 'http://bufferapp.com/add?text={title}&url={url}' {$social_title_json} }";
//				}
			}

			if ( self::is_option_checked( $fbx_options, 'social_digg', true ) ) {
//				if ( $share_image_directly ) {
//					$social_icons[] = "{ css: 'fbx-digg', title: 'Digg', url: 'http://digg.com/submit?url={img-ne}&title={title}' {$social_title_json} }";
//				} else {
					$social_icons[] = "{ css: 'fbx-digg', title: 'Digg', url: 'http://digg.com/submit?url={url}&title={title}' {$social_title_json} }";
//				}
			}

			if ( self::is_option_checked( $fbx_options, 'social_tumblr', true ) ) {
//				if ( $share_image_directly ) {
//					$social_icons[] = "{ css: 'fbx-tumblr', title: 'Tumblr', url: 'http://www.tumblr.com/share/link?url={img-ne}&name={title}&description={desc}' {$social_title_json} }";
//				} else {
					$social_icons[] = "{ css: 'fbx-tumblr', title: 'Tumblr', url: 'http://www.tumblr.com/share/photo?source={img}&click_thru={url}&caption={title}%20-%20{desc}' {$social_title_json} }";
//				}
			}

			if ( self::is_option_checked( $fbx_options, 'social_reddit', true ) ) {
//				if ( $share_image_directly ) {
//					$social_icons[] = "{ css: 'fbx-reddit', title: 'Reddit', url: 'http://reddit.com/submit?url={img-ne}&title={title}' {$social_title_json} }";
//				} else {
					$social_icons[] = "{ css: 'fbx-reddit', title: 'Reddit', url: 'http://reddit.com/submit?url={url}&title={title}' {$social_title_json} }";
//				}
			}

			if ( self::is_option_checked( $fbx_options, 'social_stumbleupon', true ) ) {
//				if ( $share_image_directly ) {
//					$social_icons[] = "{ css: 'fbx-stumble-upon', title: 'StumbleUpon', url: 'http://www.stumbleupon.com/submit?url={img-ne}&title={title}' {$social_title_json} }";
//				} else {
					$social_icons[] = "{ css: 'fbx-stumble-upon', title: 'StumbleUpon', url: 'http://www.stumbleupon.com/submit?url={url}&title={title}' {$social_title_json} }";
//				}
			}

			if ( self::is_option_checked( $fbx_options, 'social_email', true ) ) {
//				if ( $share_image_directly ) {
//					$social_icons[] = "{ css: 'fbx-email', title: 'Email', url: 'mailto:friend@example.com?subject={title}&body={desc}%20-%20{img-ne}' {$social_title_json} }";
//				} else {
					$social_icons[] = "{ css: 'fbx-email', title: 'Email', url: 'mailto:friend@example.com?subject={title}&body={desc}%20-%20{url}' {$social_title_json} }";
//				}
			}

			if ( self::is_option_checked( $fbx_options, 'social_download', false ) ) {
				$social_icons[] = "{ css: 'fbx-download', excludes: ['video', 'iframe', 'html'], title: '" . addslashes( __( 'Download Original Image', 'foobox' )) . "', url: '{img-ne}' }";
			}

			//allow for the icons to be overriden
			$social_icons = apply_filters('foobox_social_icons', $social_icons);

			if ( count( $social_icons ) > 0 ) {
				$seperator = $debug ? ',
		' : ', ';
				$social .= implode( $seperator, $social_icons );
				$social .= ' ] }';

				return $social;
			}

			return false;
		}
	}
}