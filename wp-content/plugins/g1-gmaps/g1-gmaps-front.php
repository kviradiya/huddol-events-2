<?php

// Prevent direct script access
if ( ! defined( 'ABSPATH' ) ) {
	die ( 'No direct script access allowed' );
}
?>
<?php

if ( ! class_exists( 'G1_GMaps_Front' ) ):

	class G1_GMaps_Front {
		public function __construct() {
			$this->setup_hooks();
		}

		public function setup_hooks() {
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		}


		public function enqueue_styles() {
			$core = G1_GMaps();

			$dir_url = trailingslashit( $core->get_plugin_dir_url() );

			// register styles
			wp_register_style( 'g1-gmaps', $dir_url . 'css/g1-gmaps.css', array(), $core->get_version(), 'screen' );

			// enqueue styles
			wp_enqueue_style( 'g1-gmaps' );
		}
	}
endif;

if ( ! function_exists( 'G1_GMaps_Front' ) ):

	function G1_GMaps_Front() {
		static $instance = null;

		if ( null === $instance ) {
			$instance = new G1_GMaps_Front();
		}

		return $instance;
	}

endif;

/* Fire in a hole :) */
G1_GMaps_Front();