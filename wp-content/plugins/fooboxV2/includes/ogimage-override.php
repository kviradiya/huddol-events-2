<?php
/**
 * OG:Image meta tag override for Facebook sharing
 *
 * @author        Brad Vincent
 * @package       foobox/includes
 * @version       1.0
 */

if ( !class_exists( 'ogimage_override' ) ) {

	class ogimage_override {

		function __construct() {
			add_filter( 'wpseo_opengraph_url', array($this, 'override_opengraph_url') );
			add_filter( 'wpseo_canonical', array($this, 'override_opengraph_url') );
			add_filter( 'wpseo_opengraph_image', array($this, 'override_opengraph_image') );
			add_filter( 'wpseo_pre_analysis_post_content', array($this, 'override_output'), 10, 2 );
		}

		function override_output($content, $post) {
			if ( $this->requires_override() ) {
				return '<img src="' . $_GET['fbx-og'] . '" />';
			}

			return $content;
		}

		function override_opengraph_url($val) {
			if ( $this->requires_override() ) {
				return add_query_arg( 'fbx-og', urlencode($_GET['fbx-og']), $val );
			}

			return $val;
		}

		function override_opengraph_image($val) {
			if ( $this->requires_override() ) {
				return $_GET['fbx-og'];
			}

			return $val;
		}

		function requires_override() {
			return array_key_exists( 'fbx-og', $_GET ) && !empty( $_GET['fbx-og'] );
		}

	}

}