<?php
// Prevent direct script access
if ( !defined('ABSPATH') )
    die ( 'No direct script access allowed' );
?>
<?php
if ( ! class_exists( 'G1_Page_Builder_Admin' ) ):

class G1_Page_Builder_Admin {
    public function __construct() {
        add_action( 'admin_init', array( $this, 'admin_init' ) );
        //add_action( 'admin_menu', array( $this, 'add_admin_page' ) );

        add_action('wp_ajax_g1_pb_load_wp_editor', array( $this, 'load_wp_editor' ));
        add_action('wp_ajax_g1_pb_load_revslider_list', array( $this, 'load_revslider_list' ));
        add_action('wp_ajax_g1_pb_load_mailing_list', array( $this, 'load_mailing_list' ));
        add_action('wp_ajax_g1_pb_load_map_list', array( $this, 'load_map_list' ));
    }

    public function load_wp_editor () {
        $ajax_data = $_POST['ajax_data'];

        $content = stripcslashes($ajax_data['content']);
        $content = str_replace("\n",'<br>', $content);
        $element_id = $ajax_data['element_id'];

        ob_start();
        wp_editor( $content, $element_id , array() );
        $output = ob_get_clean();

        echo $output;
        exit;
    }

    public function load_revslider_list () {
        $response = array();

        if ( !class_exists('GlobalsRevSlider') ) {
            $response['type'] = 'error';
            $response['message'] = __( 'Revolution Slider plugin is not active.', 'g1_page_builder' );
        } else {
            global $wpdb;

            $tableSliders = $wpdb->prefix . GlobalsRevSlider::TABLE_SLIDERS_NAME;
            $res = $wpdb->get_results( "SELECT id, title, alias FROM $tableSliders", ARRAY_A );

            $list = array();

            foreach ($res as $slider) {
                $list[$slider['alias']] = $slider['title'] . ' (ID:' . $slider['id'] . ')';
            }

            $response['type'] = 'success';
            $response['list'] = $list;
        }

        echo json_encode($response);
        exit;
    }

    public function load_mailing_list () {
        $response = array();

        if ( !function_exists('G1_Mailchimp_Shortcode')  ) {
            $response['type'] = 'error';
            $response['message'] = __( 'Mailchimp module is not active.', 'g1_page_builder' );
        } else {
            $list = G1_Mailchimp_Shortcode()->get_mailing_list_choices();

            $response['type'] = 'success';
            $response['list'] = $list;
        }

        echo json_encode($response);
        exit;
    }

    public function load_map_list () {
        $response = array();

        if ( !class_exists('G1_GMaps') ) {
            $response['type'] = 'error';
            $response['message'] = __( 'G1 Map plugin is not active.', 'g1_page_builder' );
        } else {
            $response = G1_GMaps()->get_map_list();

            if ($response['type'] === 'success' && empty($response['list'])) {
                $response['list'][] = __('You have not defined any map', 'g1_page_builder');
            }
        }

        echo json_encode($response);
        exit;
    }

    public function admin_init() {
        register_setting( G1_Page_Builder::get_option_name(), G1_Page_Builder::get_option_name() );
    }

    public function add_admin_page() {
        add_options_page(
            __( 'G1 Page Builder', 'g1_page_builder' ),
            __( 'G1 Page Builder', 'g1_page_builder' ),
            'manage_options',
            'g1_page_builder_options',
            array( $this, 'render_admin_page' )
        );
    }

    public function capture_admin_page() {
        ob_start();
            include( 'tpl_admin.php');
        $out = ob_get_clean();

        return $out;
    }

    public function render_admin_page() {
        echo $this->capture_admin_page();
    }
}
endif;

function G1_Page_Builder_Admin() {
    static $instance = null;

    if ( null === $instance )
        $instance = new G1_Page_Builder_Admin();

    return $instance;
}
// Fire in the hole :)
G1_Page_Builder_Admin();