<?php
/**
 * Template Name: TCN [HANDLER]: signup_login
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
function return_with_data($data)
{
    header('Content-Type: application/json');
    $string = json_encode($data);
    echo trim($string);
}

function reset_password_handler()
{
    $data = array('action' => 'reset_password');
    $data['success'] = true;
    return_with_data($data);    
}

function valid_number($phone)
{
    return ( ! preg_match("/^([1]-)?[0-9]{3}-[0-9]{3}-[0-9]{4}$/i", $phone) && ! preg_match("/^([1]-)?[0-9]{3}.[0-9]{3}.[0-9]{4}$/i", $phone) &&  ! preg_match("/^([1]-)?\([0-9]{3}\) [0-9]{3}-[0-9]{4}$/i", $phone) && ! preg_match("/^[0-9]{10}$/i", $phone)) ? FALSE : TRUE ;
}

function subscribe()
{
    $data = array('action' => 'subscribe');
    $data['success'] = true;
    
    global $sitepress;
    $sitepress->switch_lang($_POST['lang']);    
    
    if( isset($_POST['english']))
    {
        $mc = new G1_Mailchimp_Module;
        $mc_data = array("subscriber_email"=>$_POST['email'], "mailing_list"=>"88bf92d64b");
        $data['english_error'] = $mc->add_to_mailing_list($mc_data);
    }
    
    if( isset($_POST['french']))
    {
        $mc = new G1_Mailchimp_Module;
        $mc_data = array("subscriber_email"=>$_POST['email'], "mailing_list"=>"d86ada7a53");
        $data['french_error'] = $mc->add_to_mailing_list($mc_data);
    }
    
    return_with_data($data);
    
}

function login()
{
    $data = array('action' => 'login');

    global $sitepress;
    $sitepress->switch_lang($_POST['lang']);
    
    $creds = array();
    $user_login = $_POST['username_email'];
    $data['success'] = false;
    if ( strpos( $user_login, '@' ) ) 
    {
        $user_data = get_user_by( 'email', trim( $user_login ) );
        $data['user_data'] = $user_data;
        if( ! empty( $user_data ) )
        {
            $creds['user_login'] = $user_data->user_login;
        }
        else
        {
            $creds['user_login'] = '';
        }
    }
    else
    {
	    $creds['user_login'] = $_POST['username_email'];
	}
	
	if($creds['user_login'] === '')
	{
	    $data['error'] = __("No user by that name found.", 'tcn');
        $data['success'] = false;
	}
	else
	{
	
    	$creds['user_password'] = $_POST['password'];
    	$creds['remember'] = isset($_POST['remember_me']);
	
    	$user = wp_signon( $creds, true );
    	if ( is_wp_error($user) )
    	{
            $data['error'] = __("Your username or password is incorrect. Please try again.", 'tcn');
            $data['success'] = false;
    	}
    	else
    	{
            $data['success'] = true;	    
    	}
	}
	
    return_with_data($data);
}
?>

<?php
function forgot_email_password()
{
    global $sitepress;
    $sitepress->switch_lang($_POST['lang']);
    
    $data = array('action' => 'forgot_email_password');
    $user_login = $_POST['username_email'];
    global $wpdb, $current_site;

    if ( empty( $user_login) ) 
    {
        $data['error'] = __("You must provide an email or username.", 'tcn');
        $data['success'] = false;
        return_with_data($data);
        return;
    } 
    else if ( strpos( $user_login, '@' ) ) 
    {
        $user_data = get_user_by( 'email', trim( $user_login ) );
        if ( empty( $user_data ) )
        {
            $data['error'] = __("No user exists with that email or username. Please try again.", 'tcn');
            $data['success'] = false;
            return_with_data($data);
            return;
        }
    } 
    else 
    {
        $login = trim($user_login);
        $user_data = get_user_by('login', $login);
    }

    if ( !$user_data ) 
    {
        $data['success'] = false;
        $data['error'] = __("No user exists with that email or username. Please try again.", 'tcn');
        return_with_data($data);
        return;
    }
    else
    {
        // redefining user_login ensures we return the right case in the email
        $user_login = $user_data->user_login;
        $user_email = $user_data->user_email;

        do_action('retreive_password', $user_login);  // Misspelled and deprecated
        do_action('retrieve_password', $user_login);

        $allow = apply_filters('allow_password_reset', true, $user_data->ID);

        if ( ! $allow )
        {
            $data['error'] = __("You can't reset the password for that user.", 'tcn');
            $data['success'] = false;
            return_with_data($data);
            return;
        }
        else if ( is_wp_error($allow) )
        {
            $data['error'] = __("You can't reset the password for that user.", 'tcn');
            $data['success'] = false;
            return_with_data($data);
            return;
        }

        $key = wp_generate_password( 20, false );
            do_action( 'retrieve_password_key', $user_login, $key );

            if ( empty( $wp_hasher ) ) {
                require_once ABSPATH . 'wp-includes/class-phpass.php';
                $wp_hasher = new PasswordHash( 8, true );
            }
            $hashed = $wp_hasher->HashPassword( $key );
            $wpdb->update( $wpdb->users, array( 'user_activation_key' => $hashed ), array( 'user_login' => $user_login ) );
        
        
        $data['success'] = send_password_reset_email($user_email, $user_login, $key, $_POST['lang']);
    }
    
    return_with_data($data);
}

function signup()
{
    $data = array('action' => 'signup');
    $data['success'] = false;
    
    global $sitepress;
    $sitepress->switch_lang($_POST['lang']);
    
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $province = $_POST['tcn_user_meta_province'];
    $captcha = $_POST['signup_form_is_human'];
    $city = $_POST['city'];
    $phone = $_POST['phone'];

    if(empty($first_name))
    {
        $data['success'] = false;
        $data['error'] = __("You must provide a name.", 'tcn');
        return_with_data($data);
        return;
    }
    
    if(empty($last_name))
    {
        $data['success'] = false;
        $data['error'] = __("You must provide a family name.", 'tcn');
        return_with_data($data);
        return;
    }

    if(empty($email))
    {
        $data['success'] = false;
        $data['error'] = __("You must provide an email address.", 'tcn');
        return_with_data($data);
        return;
    }
    
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) 
    {
        $data['success'] = false;
        $data['error'] = __("You must provide a valid email address.", 'tcn');
        return_with_data($data);
        return;
    }

    
    if(empty($username))
    {
        $data['success'] = false;
        $data['error'] = __("You must provide a username.", 'tcn');
        return_with_data($data);
        return;
    }
    
    if(empty($password))
    {
        $data['success'] = false;
        $data['error'] = __("You must provide a password.", 'tcn');
        return_with_data($data);
        return;
    }
    
    if(empty($confirm_password))
    {
        $data['success'] = false;
        $data['error'] = __("You must enter your password twice.", 'tcn');
        return_with_data($data);
        return;
    }
    
    if($password !== $confirm_password)
    {
        $data['success'] = false;
        $data['error'] = __("The passwords entered must match.", 'tcn');
        return_with_data($data);
        return;        
    }
    
    if(!check_password($password))
    {
        $data['success'] = false;
        $data['error'] = __("You must provide a valid password.", 'tcn');
        return_with_data($data);
        return;
    }


    if($province == -1)
    {
        $data['success'] = false;
        $data['error'] = __("You must indicate what province you live in.", 'tcn');
        return_with_data($data);
        return;
    }
    
    if($city == '')
    {
        $data['success'] = false;
        $data['error'] = __("You must indicate what city you live in.", 'tcn');
        return_with_data($data);
        return;
    }
    
    if($phone == '' || ! valid_number($phone))
    {
        $data['success'] = false;
        $data['error'] = __("You must provide your phone number.", 'tcn');
        return_with_data($data);
        return;
    }
	
	  if(empty($captcha) || $captcha == NULL || $captcha === false){
        $data['success'] = false;
        $data['error'] = __("You must prove you are human.", 'tcn');
        return_with_data($data);
        return;
    }
    
    if(! isset($_POST['terms_of_use']))
    {
        $data['success'] = false;
        $data['error'] = __("You must accept the terms of use.", 'tcn');
        return_with_data($data);
        return;
    }
    
    if( isset($_POST['french_newsletters']))
    {
        $mc = new G1_Mailchimp_Module;
        $mc_data = array("subscriber_email"=>$_POST['email'], "mailing_list"=>"d86ada7a53");
        $data['french_newsletter_results'] = $mc->add_to_mailing_list($mc_data);
    }
    
    if( isset($_POST['english_newsletters']))
    {
        $mc = new G1_Mailchimp_Module;
        $mc_data = array("subscriber_email"=>$_POST['email'], "mailing_list"=>"88bf92d64b");
        $data['english_newsletter_results'] = $mc->add_to_mailing_list($mc_data);
    }

    
    $user_data = get_user_by( 'email', trim( $email ) );
    if( ! empty( $user_data ) )
    {
        $data['success'] = false;
        $data['error'] = __("A user with that email address already exists.", 'tcn');
        return_with_data($data);
        return;
    }
    
    
    $user_data = get_user_by( 'login', trim( $username ) );
    if( ! empty( $user_data ) )
    {
        $data['success'] = false;
        $data['error'] = __("A user with that username address already exists.", 'tcn');
        return_with_data($data);
        return;
    }    
    
    $user_id = wp_create_user( $username, $password, $email );
    $user_id = wp_update_user( array( 'ID' => $user_id, 'first_name' => $first_name ) );
    $user_id = wp_update_user( array( 'ID' => $user_id, 'last_name' => $last_name ) );
    update_user_meta($user_id, 'tcn_user_meta_province', $province);
    update_user_meta($user_id, 'tcn_user_meta_city', $city);
    update_user_meta($user_id, 'tcn_user_meta_phone', $phone);
                    
    $data['success'] = true;    
    send_validation_email($email, $_POST['lang']);

	$admin_email = get_option('admin_email', '');
 $message = strip_tags($username. ' - ' . $email . ' Has Registered To Your Website');
    $mail_result = wp_mail( $admin_email, 'New User Has Registered', $message );

	$data['admin_email'] = $admin_email;
	$data['mail_result'] = $mail_result;
    
    return_with_data($data);
    return;
}
?>

<?php
    if(!isset($_POST['action']))
    {
        $data = array('success' => false);
        header('Content-Type: application/json');
        echo json_encode($data);
    }
    else if($_POST['action'] === 'login')
    {
       login();
    }
    else if($_POST['action'] === 'forgot_email_password')
    {
        forgot_email_password();
    }
    else if($_POST['action'] === 'signup')
    {
        signup();
    }
    else if($_POST['action'] === 'subscribe')
    {
        subscribe();
    }
    else if($_POST['action'] === 'reset_password')
    {
        reset_password();
    }

?>
