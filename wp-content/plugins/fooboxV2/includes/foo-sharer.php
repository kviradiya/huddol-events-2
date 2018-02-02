<?php
/**
 * Created by brad.
 * Date: 24/03/2015
 */
if (!class_exists('FooSharer')) {

    class FooSharer {

        function __construct() {
            //add an AJAX endpoint that listens for images shared
            add_action( 'wp_ajax_foo_share', array( $this, 'ajax_handle_foo_share' ) );
            add_action( 'wp_ajax_nopriv_foo_share', array( $this, 'ajax_handle_foo_share' ) );
            add_action( 'wp_head', array( $this, 'add_javascript' ) );
        }

        function add_javascript() {?>
            <script type="text/javascript" >
                jQuery(document).ready(function($) {

                    $('.fbx-facebook').click(function(e) {
                        e.preventDefault();

                        var current = $(this).parents('.fbx-modal').data('fbx_instance').items.current();

                        var data = {
                            'action': 'foo_share',
                            'url': window.location.href,
                            'image': current.image_url,
                            'title': current.title,
                            'description': current.description
                        };

                        $.post( '<?php echo admin_url( "admin-ajax.php" ); ?>', data, function(response) {
                            alert('Got this from the server: ' + response);
                        });
                    });

                });
            </script> <?php
        }

        function ajax_handle_foo_share() {
            //check nonce

            $url = $_POST['url'];
            $image = $_POST['image'];
            $title = $_POST['title'];
            $description = $_POST['description'];

            //check if the url exists in our shared url's
            $foo_shares = get_option('foo_shares');
            if ( false === $foo_shares ) {
                $foo_shares = array();
            }

            $hash_bang = false;

            if ( !array_key_exists( $url, $foo_shares ) ) {
                $hash_bang = wp_generate_password(8, false, false);
                $foo_shares[$url] = array(
                    'id' => $hash_bang,
                    'url' => $url,
                    'image' => $image,
                    'title' => $title,
                    'description' => $description
                );
            } else {
                $hash_bang = $foo_shares[$url]['id'];
            }

            update_option('foo_shares', $foo_shares);

            echo home_url('#!' . $hash_bang);
        }
    }
}