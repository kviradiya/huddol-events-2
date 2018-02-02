<?php
/**
 * Template Name: TCN [HANDLER]: edit_profile
 *
 * For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package G1_Framework
 * @subpackage G1_Theme03
 * @since G1_Theme03 1.0.0
 */
 ?>
 
<?php
    $user = wp_get_current_user();
?>

<?php
    
    if(isset($_POST['first_name']))
    {
       update_user_meta($user->ID, 'first_name', ($_POST['first_name']));
    }
    
    if(isset($_POST['last_name']))
    {
       update_user_meta($user->ID, 'last_name', ($_POST['last_name']));
    }
    
    if(isset($_POST['user_email']))
    {
        wp_update_user( array( 'ID' => $user->ID, 'user_email' => $_POST['user_email'] ) );
    }
    
    if(isset($_POST['tcn_user_meta_province']))
    {
       update_user_meta($user->ID, 'tcn_user_meta_province', ($_POST['tcn_user_meta_province']));
    }
    
    if(isset($_POST['tcn_user_meta_age']))
    {
       update_user_meta($user->ID, 'tcn_user_meta_age', ($_POST['tcn_user_meta_age']));
    }
    
    if(isset($_POST['city']))
    {
       update_user_meta($user->ID, 'tcn_user_meta_city', ($_POST['city']));
    }
    
    if(isset($_POST['phone']))
    {
       update_user_meta($user->ID, 'tcn_user_meta_phone', ($_POST['phone']));
    }

    $roles = array();
    for($i = 0; $i < get_num_caregiver_roles(); $i++ )
    {
        if(isset($_POST['tcn_user_meta_caregiver_role_' . $i]))
        {
            array_push($roles, $i);
        }
    }
    
    delete_user_meta($user->ID, 'tcn_user_meta_caregiver_role');
    update_user_meta($user->ID, 'tcn_user_meta_caregiver_role', $roles);
    
    if(isset($_POST['tcn_user_meta_gender']))
    {
       update_user_meta($user->ID, 'tcn_user_meta_gender', ($_POST['tcn_user_meta_gender']));
    }
    
    if(isset($_POST['tcn_user_meta_marital_status']))
    {
       update_user_meta($user->ID, 'tcn_user_meta_marital_status', ($_POST['tcn_user_meta_marital_status']));
    }

    
    $network = new TCNNetwork;
    $network->save_categories($user->ID);
    
    // JHILL you can turn this back on
    // $network->save_tags($user->ID);
    // $network->save_partners($user->ID);
?>

<?php
header('Location: ' . get_account_dashboard_url() . '/?mode=profile');
?>
