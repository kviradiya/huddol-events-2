<?php
/**
 * Template Name: TCN [HANDLER]: Event Registration CVS
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
    header("Content-type: text/csv");
    header("Content-Disposition: attachment; filename=file.csv");
    header("Pragma: no-cache");
    header("Expires: 0");
    if($_GET['action'] == 'users_table')
    {    
        $event_registration = new EventRegistration;
                
        $offset = $_GET['offset'];
        $count = $_GET['count'];
        
        $users = get_users( array("role" => "subscriber", "number" => $count, "offset" => $offset));
            
        foreach($users as $user)
        {
            echo $user->display_name;
            echo ',';
        
            echo $user->user_email;
            echo ',';
        
            // echo $event_registration->get_user_event_count($user->ID);
            // echo ',';
        
            $roles = get_user_meta($user->ID, 'tcn_user_meta_caregiver_role', true);
            for($i = 0; $i < 4; $i++)
            {
                if(in_array($i, $roles))
                {
                    echo get_caregiver_role_name($i);
                    echo ' ';
                }
            }
            echo ',';

            echo "\"";
            $marital_status = get_user_profile_field($user->ID, 'tcn_user_meta_marital_status');
            if($marital_status === '0' ){ echo "Single never married"; }
            if($marital_status === '1' ){ echo "Married/Domestic Partner"; }
            if($marital_status === '2' ){ echo "Widowed"; }
            if($marital_status === '3' ){ echo "Divorced"; }
            if($marital_status === '4' ){ echo "Separated"; }
            if($marital_status === '5' ){ echo "Decline to State/Other"; }
            echo ',';
        
            $age = get_user_profile_field($user->ID, 'tcn_user_meta_age');
            if($age === '0' ){ echo "<17"; }
            if($age === '1' ){ echo "18-24"; }
            if($age === '2' ){ echo "25-34"; }
            if($age === '3' ){ echo "35-44"; }
            if($age === '4' ){ echo "45-54"; }
            if($age === '5' ){ echo "55-64"; }
            if($age === '6' ){ echo "65-74"; }
            if($age === '7' ){ echo "75+"; }
            echo ',';
        
            echo $user->user_registered;
            echo ',';
            
            /*
            $cats = $network->get_subscribed_categories($user);
            foreach($cats as $cat)
            {
                echo $cat->cat_name;
                echo ' ';
            }
        
            echo ',';
            */
            
            $province = get_user_meta($user->ID, 'tcn_user_meta_province', true);
            if($province)
                echo get_province_name($province);
            else
                echo 'unknown';
            echo ',';
        
            echo get_user_meta($user->ID, 'tcn_user_meta_city', true);
            echo ',';
            echo get_user_meta($user->ID, 'tcn_user_meta_phone', true);
            echo ',';
            echo get_gender(get_user_meta($user->ID, 'tcn_user_meta_gender', true));
            echo "\r\n";
        }
    }
    else if($_GET['action'] == 'event_registration')
    {
        $event = get_post($_GET['event_id']);
        $event_registration = new EventRegistration;
    
        global $wpdb;
        $query = "SELECT user_id, date_registered
                      FROM wp_event_registration
                      WHERE 
                            post_id='$event->ID'";
        $results = $wpdb->get_results($query);
        foreach($results as $result)
        {
            $user = get_userdata($result->user_id);
            if($user)
            {
                $first_name = get_user_profile_field($result->user_id, 'first_name');
                $last_name = get_user_profile_field($result->user_id, 'last_name');
                $province = 'unknown';
                if(get_user_meta( $result->user_id, 'tcn_user_meta_province', true ) !== '')
                {
                    $province = get_province_name(get_user_meta( $result->user_id, 'tcn_user_meta_province', true ));
                }
                
                $role = '';
                $roles = get_user_meta( $user->ID, 'tcn_user_meta_caregiver_role', true );
                $roles = $roles == '' ? [] : $roles;
                foreach($roles as $r)
                {
                    $role .= get_caregiver_role_name($r) . ' ';
                }
                
                echo sprintf("%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s", 
                    $user->data->user_nicename, 
                    $first_name,
                    $last_name,
                    $user->data->user_email, 
                    $result->date_registered,
                    $province,
                    get_user_meta( $result->user_id, 'tcn_user_meta_phone', true ),
                    $role,
                    get_age(get_user_meta( $user->ID, 'tcn_user_meta_age', true )),
                    str_replace(",", " ", get_marital(get_user_meta( $user->ID, 'tcn_user_meta_marital_status', true ))),
                    get_gender(get_user_meta( $user->ID, 'tcn_user_meta_gender', true ))
                );
                echo "\r\n";
            }
        }
    }
?>