<?php
/**
 * Template Name: TCN [HANDLER]: register_event
 *
 * For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package G1_Framework
 * @subpackage G1_Theme03
 * @since G1_Theme03 1.0.0
 */

// Prevent direct script access
if ( !defined('ABSPATH') )
    die ( 'No direct script access allowed' );
?>

<?php
function print_error_screen($error, $redirect)
{
    $_SESSION["tcn_register_event_error"] = $error;
    header('Location: ' . $_POST['redirect']);
}
?>

<?php
    global $wpdb;
    
    $_SESSION["tcn_register_event_error"] = '';
    
    $current_user = wp_get_current_user();
    $post_id = $_POST['post_id'];
    $user_id = $current_user->ID;
    $register = $_POST['action'] === 'register' ? true : false;
    $redirect = $_POST['redirect'];
    
    $event_registration =  new EventRegistration;
    
    if(!$current_user->data)
    {
        print_error_screen("You aren't logged in.", $redirect);
    }   

    if($_POST['action'] === 'register')
    {
        if($event_registration->is_user_registered($post_id, $user_id))
        {
            print_error_screen("You are already registered for this event.", $redirect);
        }
        else if(! $event_registration->can_user_province_register($post_id, $user_id))
        {
            print_error_screen("You aren't in the right province or territory to register for this event.", $redirect);
        }
        else if( $event_registration->is_event_full($post_id))
        {
            print_error_screen("This event is full. Try checking back later.", $redirect);
        }
        else if( ! $event_registration->can_user_role_register($post_id, $user_id))
        {
            print_error_screen("You can't register for this event because it is not offered to your caregiver role.", $redirect);
        }
        else if( $event_registration->has_paypal_button($post_id))
        {
            $paypal_ipn_id = $event_registration->register_user($post_id, $user_id);
            header('Location: ' . $_POST['redirect'] . '/?mode=paypal_redirect&paypal_ipn_id=' . $paypal_ipn_id);
        }
        else
        {
            $event_registration->register_user($post_id, $user_id);
            send_event_confirmation_email($current_user->user_email, get_post($post_id), ICL_LANGUAGE_CODE);
            header('Location: ' . $_POST['redirect']);
        }
    }
    else if($_POST['action'] === 'unregister')
    {
        $event_registration->unregister_user($post_id, $user_id);
        header('Location: ' . $_POST['redirect']);
    }
    else if($_POST['action'] === 'clear_registrants')
    {
        $event_registration->clear_registrants($post_id);
        header('Location: ' . $_POST['redirect']);
    }
    else
    {
        print_r($_POST);
    }
?>