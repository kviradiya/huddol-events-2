<?php
/*
Plugin Name:    G1 Page Builder
Plugin URI:     http://www.bringthepixel.com
Description:    Page Builder
Author:         bringthepixel
Version:        1.1.2
Author URI:     http://www.bringthepixel.com
License: 		Located in the 'Licensing' folder
License URI: 	Located in the 'Licensing' folder
*/

// Prevent direct script access
if ( !defined('ABSPATH') )
    die ( 'No direct script access allowed' );
?>
<?php

if ( ! class_exists( 'G1_Page_Builder' ) ):

    class G1_Page_Builder {
        private $version = '1.1.2';
        private static $option_name = 'g1_page_builder';

        public function __construct() {
            $this->setup_hooks();
        }

        public function setup_hooks () {
            // Standard hooks for plugins
            register_activation_hook( plugin_basename( __FILE__ ), array( $this, 'activate' ) );
            register_deactivation_hook( plugin_basename( __FILE__ ), array( $this, 'deactivate' ) );
            register_uninstall_hook( plugin_basename( __FILE__ ), array( 'G1_Page_Builder', 'uninstall' ) );

            add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
            add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
            add_action( 'wp_print_scripts', array( $this, 'print_builder_js_config' ));
            add_action( 'save_post', array( $this, 'save_post' ), 10, 2 );
        }

        public function save_post ( $id, $post_object ) {
            // store editor state
            if( isset($_POST['g1_page_builder_state']) ) {
                update_post_meta( (int)$_POST['post_ID'], 'g1_page_builder_state', $_POST['g1_page_builder_state'] );
            }
        }

        public function print_builder_js_config () {
            global $post_ID;
            $state = get_post_meta( $post_ID, 'g1_page_builder_state', true );

            if ( empty( $state ) ) {
                $state  = 'inactive';
            }

            $ext_components = array();

            if ( is_plugin_active('bbpress/bbpress.php') ) {
                $ext_components[] = 'bbpress';
            }

            echo "\n<script type='text/javascript'>\n/* <![CDATA[ */\n";
            echo "var g1_page_builder_state = '$state';\n";
            echo "var g1_page_builder_ext_components = ". json_encode( $ext_components ) .";\n";
            echo "/* ]]> */ \n";
            echo "</script>\n \n ";
        }

        public function enqueue_styles () {
            $dir_url = trailingslashit( self::get_plugin_dir_url() );

            wp_register_style( 'jquery.magnific-popup', $dir_url . 'js/jquery.magnific-popup/magnific-popup.css', array(), $this->get_version(), 'screen' );
            wp_register_style( 'g1-page-builder', $dir_url . 'css/main.css', array(), $this->get_version() );

            // enqueue styles
            wp_enqueue_style( 'jquery.magnific-popup' );
            wp_enqueue_style( 'g1-page-builder' );
        }

        public function enqueue_scripts () {
            $dir_url = trailingslashit( self::get_plugin_dir_url() );

            wp_register_script( 'jquery.magnific-popup', $dir_url . 'js/jquery.magnific-popup/jquery.magnific-popup.js', array('jquery'), $this->get_version(), true );

            wp_register_script( 'g1_page_builder_core', $dir_url . 'js/g1-builder-core.js', array('jquery','jquery-ui-core', 'jquery-ui-droppable', 'jquery-ui-sortable'), $this->get_version(), true );
            wp_register_script( 'g1_page_builder_config', $dir_url . 'js/g1-builder-config.js', array('g1_page_builder_core'), $this->get_version(), true );

            wp_register_script( 'g1_page_builder_elements', $dir_url . 'js/elements/g1-elements.js', array('g1_page_builder_config'), $this->get_version(), true );
            wp_register_script( 'g1_page_builder_helpers', $dir_url . 'js/g1-builder-helpers.js', array('g1_page_builder_config'), $this->get_version(), true );
            wp_register_script( 'g1_page_builder_classes', $dir_url . 'js/g1-builder-classes.js', array('g1_page_builder_helpers'), $this->get_version(), true );
            wp_register_script( 'g1_page_builder', $dir_url . 'js/g1-builder.js', array('g1_page_builder_classes'), $this->get_version(), true );
            wp_register_script( 'g1_page_builder_main', $dir_url . 'js/g1-main.js', array('g1_page_builder'), $this->get_version(), true );

            wp_register_script( 'g1_page_builder_fields_classes', $dir_url . 'js/elements/g1-fields-classes.js', array('g1_page_builder_core'), $this->get_version(), true );
            wp_register_script( 'g1_page_builder_elements_classes', $dir_url . 'js/elements/g1-elements-classes.js', array('g1_page_builder_fields_classes'), $this->get_version(), true );

            // enqueue scripts
            wp_enqueue_script( 'jquery.magnific-popup' );
            wp_enqueue_script( 'g1_page_builder_core' );
            wp_enqueue_script( 'g1_page_builder_config' );

            wp_enqueue_script( 'g1_page_builder_elements' );
            wp_enqueue_script( 'g1_page_builder_helpers' );
            wp_enqueue_script( 'g1_page_builder_classes' );
            wp_enqueue_script( 'g1_page_builder' );
            wp_enqueue_script( 'g1_page_builder_main' );

            wp_enqueue_script( 'g1_page_builder_fields_classes' );
            wp_enqueue_script( 'g1_page_builder_elements_classes' );
        }

        public static function get_plugin_dir_path() {
            return plugin_dir_path( __FILE__ );
        }

        public static function get_plugin_dir_url() {
            return plugin_dir_url( __FILE__ );
        }

        public function get_version() {
            return $this->version;
        }

        public static function get_option_name() {
            return self::$option_name;
        }

        public function activate() {
        }

        public function deactivate() {
        }

        public static function uninstall() {
            delete_option( self::$option_name );
        }
    }
endif;

if ( !function_exists( 'G1_Page_Builder' ) ):

    function G1_Page_Builder() {
        static $instance = null;

        if ( null === $instance ) {
            $instance = new G1_Page_Builder();
        }

        return $instance;
    }

endif;


// plugin should be loaded only in admin area
if ( is_admin() ) {
    // loads admin settings page
    require_once( G1_Page_Builder::get_plugin_dir_path() . 'admin.php' );

    global $pagenow;

    if ( in_array( $pagenow, array('post.php', 'post-new.php') ) ) {
        // creates builder instance
        G1_Page_Builder();
    }
}