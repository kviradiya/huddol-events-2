<?php
/**
 * Plugin Name: TCN: Event Registration
 * Author: Jon Hill
 * Description: Allows users to register for events
 * Version: Only and forever
 */
 
 

 
class EventRegistration {
	function __construct() 
	{
		add_action( 'add_meta_boxes', array( $this, 'adding_custom_meta_boxes') );
		add_action( 'save_post', array( $this, 'save_meta_box_data' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'load_styles' ));
	}
	
	// used in form validation in tcn_register_event
	public function is_event_full($post_id)
	{
	    if($this->get_max_registrants($post_id) == 0)
	        return false;
	    return $this->get_registration_count($post_id) >= $this->get_max_registrants($post_id);
	}
	
	public function get_registration_count($post_id)
	{
	    global $wpdb;
	    $query = "SELECT *
                      FROM wp_event_registration
                      WHERE 
                            post_id='$post_id' AND valid='1'";
        $results = $wpdb->get_results($query);
        return count($results);
	}

	public function get_max_registrants($post_id)
	{
	    global $wpdb;
	    return get_post_meta( $post_id, 'event_registration_max_registrants', true );
	}
	
	public function get_user_event_count($user_id)
	{
	    global $wpdb;
	    $query = "SELECT user_id
                      FROM wp_event_registration
                      WHERE 
                            user_id='$user_id' AND valid='1'";
        $results = $wpdb->get_results($query);
        return count($results);
	}
	
	public function get_network_partner_user_subs($user_id)
	{
	    $subs = array();
	    $posts = get_posts(array("post_type" => array("tribe_events"), "author" => $user_id, "posts_per_page" => -1));

        global $wpdb;	    
	    foreach($posts as $post)
	    {
    	    $query = "SELECT user_id, post_id
                          FROM wp_event_registration
                          WHERE 
                                post_id='$post->ID' AND valid='1'";
            $results = $wpdb->get_results($query);
            $subs = array_merge($subs, $results);
        }
        return $subs;
	    
	}
	
	public function get_event_role_subs($event_id, $role)
	{
	    $returns = array();
	    $posts = get_posts(array("post_type" => array("tribe_events"), "author" => $user_id, "posts_per_page" => -1));

        global $wpdb;	    
	    foreach($posts as $post)
	    {
    	    $query = "SELECT user_id, post_id
                          FROM wp_event_registration
                          WHERE 
                                post_id='$post->ID' AND valid='1'";
            $results = $wpdb->get_results($query);
            
            foreach($results as $sub)
            {
                if(is_user_caregiver_role($sub->user_id, $role))
                {
                    array_push($returns, $sub);
                }
            }
        }
        return $returns;
	}
	
	public function get_registrants($post_id)
	{
	    global $wpdb;
	    $query = "SELECT *
                      FROM wp_event_registration
                      WHERE 
                            post_id='$post_id' AND valid='1'";
        $results = $wpdb->get_results($query);
        
        $users = array();
        foreach($results as $q)
        {
            $user = get_userdata($q->user_id);
            array_push($users, $user);
        }
        return $users;
	}
	
	public function get_registrations($post_id)
	{
	    global $wpdb;
	    $query = "SELECT *
                      FROM wp_event_registration
                      WHERE 
                            post_id='$post_id' AND valid='1'";
        $results = $wpdb->get_results($query);
        return $results;
	}
	
	public function get_role_registrants($post_id, $role)
	{
	    global $wpdb;
	    $query = "SELECT user_id
                      FROM wp_event_registration
                      WHERE 
                            post_id='$post_id' AND valid='1'";
        $results = $wpdb->get_results($query);
        
        $users = array();
        foreach($results as $q)
        {
            if(is_user_caregiver_role($q->user_id, $role))
            {
                $user = get_userdata($q->user_id);
                array_push($users, $user);
            }
        }
        return $users;
	}
	
	public function get_registrations_partner_month($np, $month)
	{
	    $posts = get_posts(array("post_type" => array("tribe_events"), "author" => $np->ID, "posts_per_page" => -1));

        global $wpdb;	    
        $subs = array();
        
	    foreach($posts as $post)
	    {
    	    $query = "SELECT * 
                          FROM wp_event_registration
                          WHERE 
                                post_id='$post->ID' AND valid='1'";
            $results = $wpdb->get_results($query);
            
            $this_months = array();
            foreach($results as $result)
            {
                $current_month = date("F Y", $month);
                $registration_month = date("F Y", strtotime($result->date_registered));
                                
                if($registration_month == $current_month)
                {
                    array_push($this_months, $result);
                }
            }
            $subs = array_merge($subs, $this_months);
        }
        return $subs;
	}
	
	public function get_registrations_partner_month_role($np, $month, $role)
	{
	    $subs = $this->get_registrations_partner_month($np, $month);
	    $returns = array();
	    foreach($subs as $sub)
	    {
	        if(is_user_caregiver_role($sub->user_id, $role))
	        {
	            array_push($returns, $sub);
	        }
	    }
	    return $returns;
	}
	
	public function clear_registrants($post_id)
	{
        global $wpdb;
	    $query = "DELETE
                      FROM wp_event_registration
                      WHERE 
                            post_id='$post_id'";
        $results = $wpdb->get_results($query);
        return $results;
	}
	
	public function is_event_role_intended($post_id)
	{
	    return !(get_post_meta($post_id, 'event_registration_role_0', 'true') == '' &&
               get_post_meta($post_id, 'event_registration_role_1', 'true') == '' &&
               get_post_meta($post_id, 'event_registration_role_2', 'true') == '' &&
               get_post_meta($post_id, 'event_registration_role_3', 'true') == '');
	}

    
	public function get_role_intention_string($post_id)
	{
	    if(! $this->is_event_role_intended($post_id))
        {
            return "None";
        }
        else
        {
            $ret = "";
            
            if(get_post_meta($post_id, 'event_registration_role_0', 'true'))
            {
                $ret .= get_caregiver_role_name(0, true) . ", ";
            }
            if(get_post_meta($post_id, 'event_registration_role_1', 'true'))
            {
                $ret .= get_caregiver_role_name(1, true) . ", ";
            }
            if(get_post_meta($post_id, 'event_registration_role_2', 'true'))
            {
                $ret .= get_caregiver_role_name(2, true) . ", ";
            }
            if(get_post_meta($post_id, 'event_registration_role_3', 'true'))
            {
                $ret .= get_caregiver_role_name(3, true) . ", ";
            }

            return rtrim($ret, ", ");
        }
	}
	
	public function can_user_role_register($post_id, $user_id)
	{
	    // you can turn role restriction back on here
	    return true;
	    /*
	    if($this->is_event_role_intended($post_id))
	    {
	        $role = get_user_meta($user_id, 'tcn_user_meta_caregiver_role', true);
	        return $this->is_event_available_to_role($post_id, $role);
	    }
	    else
	    {
	        return true;
	    }
	    */
	}
	
	public function is_event_available_to_role($post_id, $role_id)
	{
	    $code = 'event_registration_role_' . $role_id;
        
        $value = get_post_meta($post_id, $code);
        if(count($value))
        {
            return $value[0] == 'yes';
        }

        return false;
	}
	
	// location stuff
	public function is_event_available_outside_of_canada($event_id)
	{
	    return $this->is_event_available_in_province($event_id, 13);
	}
	
	public function can_user_province_register($post_id, $user_id)
	{
	    if(is_user_outside_of_canada($user_id))
	    {
	        return $this->is_event_available_outside_of_canada($post_id);
	    }
        
	    if($this->is_event_province_restricted($post_id))
	    {
	        $province = get_user_meta($user_id, 'tcn_user_meta_province', true);
	        return $this->is_event_available_in_province($post_id, $province);
	    }
	    else
	    {
	        return true;
	    }
	}
    
    public function is_event_province_restricted($post_id)
    {
        // all provinces and terrtories but not "outside of canada"
        for($i = 0; $i < 13; $i++)
        {
            $code = 'event_registration_location_' . $i;
            $value = get_post_meta($post_id, $code, true);
            if($value == true)
            {
                return true;
            }
        }
        return false;
    }
    
    public function is_event_available_in_province($post_id, $location_key)
    {   
        if(! $this->is_event_province_restricted($post_id))
        {
            return true;
        }
        
        $code = 'event_registration_location_' . $location_key;
        
        $value = get_post_meta($post_id, $code);
        if(count($value))
        {
            return $value[0] == 'yes';
        }

        return false;
    }
    
    public function get_pending_paypal($paypal_ipn_id)
    {
        global $wpdb;
        $query = "SELECT * FROM wp_event_registration
                  WHERE paypal_ipn_id='$paypal_ipn_id'";
        $results = $wpdb->get_results($query);
        return $results;
    }
    
    public function is_payment_processing($post_id, $user_id)
    {
        global $wpdb;
        
        $query = "SELECT id
                      FROM wp_event_registration
                      WHERE 
                            post_id='$post_id' AND
                            user_id='$user_id' AND
                            valid='0' AND paypal_ipn_id!=''";
                            
        $accesses = $wpdb->get_results($query);
        return count($accesses) != 0;
    }
    
    public function finalize_paypal($paypal_ipn_id)
    {
        global $wpdb;
        
        $data = array();
        
        $query = "SELECT * FROM wp_event_registration
                  WHERE paypal_ipn_id='$paypal_ipn_id'";
        $results = $wpdb->get_results($query);
        $data['pre_operation_er'] = $results;
        
        if(count($results))
        {
            $query = "UPDATE wp_event_registration
                      SET valid=1
                      WHERE paypal_ipn_id='$paypal_ipn_id'";
            $data['success'] = count($wpdb->get_results($query)) == 0;
        
            $query = "SELECT * FROM wp_event_registration
                      WHERE paypal_ipn_id='$paypal_ipn_id'";
            $results = $wpdb->get_results($query);
            $data['post_operation_er'] = $results;
        }
        else
        {
            $data['success'] = 0;
            $data['error'] = "There was no pending transaction with that paypal_ipn_id. Please contact this user.";
        }
        
        return $data;
    }
    
    public function has_paypal_button($post_id)
    {
        return get_post_meta($post_id, 'event_registration_paypal_button', true) != '';
    }
    
    public function get_paypal_button($post_id, $paypal_ipn_id='')
    {
        $paypal_form = get_post_meta($post_id, 'event_registration_paypal_button', true);
        
        if($paypal_ipn_id != '')
        {
           $paypal_form = substr($paypal_form, 0, strpos($paypal_form, '</form>'));
           $paypal_form .= '<input type="hidden" name="custom" value="' . $paypal_ipn_id . '" /></form>';
        }
        
        return $paypal_form;
    }
	
	public function get_event_price($post_id)
	{
	    return get_post_meta($post_id, 'event_registration_price', true);
	}
	
	public function get_event_price_display($post_id)
	{
	    if($this->get_event_price($post_id) == '' || $this->get_event_price($post_id) === 'Free' || $this->get_event_price($post_id) === 'Gratuit')
	    {
	        return __("", "tcn");
	    }
	    else
	    {
	        return "$" . $this->get_event_price($post_id);
	    }
	}
	    
	
	public function is_user_registered($post_id, $user_id)
    {
        global $wpdb;
        $query = "SELECT id
                      FROM wp_event_registration
                      WHERE 
                            post_id='$post_id' AND
                            user_id='$user_id' AND
                            valid='1'";
                            
        $accesses = $wpdb->get_results($query);
        return count($accesses) != 0;
    }
    
    public function register_user($post_id, $user_id)
    {
        if($this->has_paypal_button($post_id))
        {
            global $wpdb;
            $paypal_ipn_id = generateRandomString();
            $english_id = icl_object_id($post_id, 'tribe_events', false, 'en');
            $french_id = icl_object_id($post_id, 'tribe_events', false, 'fr');
            
            if($english_id != 0)
            {
                $query = "INSERT INTO wp_event_registration (post_id, user_id, valid, paypal_ipn_id)
                          VALUES('$english_id', '$user_id', 0, '$paypal_ipn_id')";      
                $results = $wpdb->get_results($query); 
            }
            
            if($french_id != 0)
            {
                $query = "INSERT INTO wp_event_registration (post_id, user_id, valid, paypal_ipn_id)
                          VALUES('$french_id', '$user_id', 0, '$paypal_ipn_id')";                 
                $results = $wpdb->get_results($query);
            }
            return $paypal_ipn_id;
        }
        else
        {
            global $wpdb;
            
            
            $english_id = icl_object_id($post_id, 'tribe_events', false, 'en');
            $french_id = icl_object_id($post_id, 'tribe_events', false, 'fr');

            
            if($english_id != 0)
            {
                $query = "INSERT INTO wp_event_registration (post_id, user_id, valid)
                          VALUES('$english_id', '$user_id', 1)";   
                $results = $wpdb->get_results($query);      
            }
    
            if($french_id != 0)
            {
                $query = "INSERT INTO wp_event_registration (post_id, user_id, valid)
                            VALUES('$french_id', '$user_id', 1)";                        
            
                $results = $wpdb->get_results($query);
            }
        }
    }
    
    public function register_phone_number_user($post_id, $number, $name)
    {
        global $wpdb;
        $english_id = icl_object_id($post_id, 'tribe_events', false, 'en');
        $french_id = icl_object_id($post_id, 'tribe_events', false, 'fr');

        
        if($english_id != 0)
        {
            $query = "INSERT INTO wp_event_registration (post_id, valid, phone_number, phone_number_name)
                      VALUES('$english_id', 1, '$number', '$name')";
                      
            $results = $wpdb->get_results($query);      
        }

        if($french_id != 0)
        {
            $query = "INSERT INTO wp_event_registration (post_id, valid, phone_number, phone_number_name)
                      VALUES('$french_id', 1, '$number', '$name')";                    
        
            $results = $wpdb->get_results($query);
        }
    }
    
    public function unregister_user($post_id, $user_id)
    {
        global $wpdb;
        $english_id = icl_object_id($post_id, 'tribe_events', false, 'en');
        $french_id = icl_object_id($post_id, 'tribe_events', false, 'fr');
        
        if($english_id != 0)
        {
            $query = "DELETE FROM wp_event_registration
                      WHERE post_id='$english_id' AND user_id='$user_id'"; 
            $results = $wpdb->get_results($query);
        }
        
        if($french_id != 0)
        {
            $query = "DELETE FROM wp_event_registration
                      WHERE post_id='$french_id' AND user_id='$user_id'"; 
            $results = $wpdb->get_results($query);
        }
    }
    
    public function can_user_register($post_id, $user_id)
    {
        return (! $this->is_user_registered($post_id, $user_id)) &&
               (  $this->can_user_province_register($post_id, $user_id)) &&
               (! $this->is_event_full($post_id)) &&
               (  $this->can_user_role_register($post_id, $user_id));
    }
    
    /* used for users on their profile screens */
	function /* array */ get_recent_registered_events($user)
	{
	    $posts = tribe_get_events();
	    $ret_posts = array();
	    
	    $now = time();
	    foreach($posts as $post)
	    {
	        $start_date = strtotime($post->EventStartDate);
	        if($start_date < $now && $this->is_user_registered($post->ID, $user->ID))
	        {
	            array_push($ret_posts, $post);
	        }
	    }
        return $ret_posts;
	}

	function get_upcoming_registered_events($user)
	{
	    $posts = get_all_events();
	    $ret_posts = array();
	    
	    $now = time();
	    foreach($posts as $post)
	    {
	        $start_date = strtotime(get_post_meta($post->ID, '_EventStartDate', true));
	        if($start_date > $now && $this->is_user_registered($post->ID, $user->ID))
	        {
	            array_push($ret_posts, $post);
	        }
	    }
        return $ret_posts;	    
	}
	
	/* backend */
	private function location_included($location_key)
    {
        global $post;
        $value = get_post_meta( $post->ID, $location_key, true );
        return $value == 'yes' ? 'checked' : '';
    }
    
    private function role_included($role_key)
    {
        global $post;
        $value = get_post_meta( $post->ID, $role_key, true );
        return $value == 'yes' ? 'checked' : '';
    }
    
    private function get_all_event_registrations()
    {
        global $wpdb;
        $posts = $wpdb->get_results("SELECT * FROM wp_event_registration");
        return $posts;
    }
        
    function save_meta_box_data()
    {
        global $post;
        
        if ( ! isset( $_POST['event_registration_meta_box_nonce'] ) ) 
	    {
    		return;
    	}

    	if ( ! wp_verify_nonce( $_POST['event_registration_meta_box_nonce'], 'event_registration_meta_box' ) ) 
    	{
    		return;
    	}

    	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
    	{
    		return;
    	}

    	if ( ! current_user_can( 'edit_post', $post->ID ) ) 
    	{
			return;
		}     
        
        update_post_meta($post->ID, 'event_registration_price', $_POST['event_registration_price']);
        update_post_meta($post->ID, 'event_registration_paypal_button', $_POST['event_registration_paypal_button']);
        update_post_meta($post->ID, 'event_registration_max_registrants', $_POST['event_registration_max_registrants']);
        
        $this->update_location('event_registration_location_0');
        $this->update_location('event_registration_location_1');
        $this->update_location('event_registration_location_2');
        $this->update_location('event_registration_location_3');
        $this->update_location('event_registration_location_4');
        $this->update_location('event_registration_location_5');
        $this->update_location('event_registration_location_6');
        $this->update_location('event_registration_location_7');
        $this->update_location('event_registration_location_8');
        $this->update_location('event_registration_location_9');
        $this->update_location('event_registration_location_10');
        $this->update_location('event_registration_location_11');
        $this->update_location('event_registration_location_12');
        $this->update_location('event_registration_location_13');
        
        $this->update_role('event_registration_role_0');
        $this->update_role('event_registration_role_1');
        $this->update_role('event_registration_role_2');
        $this->update_role('event_registration_role_3');
    }
    
    private function update_location($location_key)
    {
        global $post;
        if(isset($_POST[$location_key]))
        {
            update_post_meta($post->ID, $location_key, 'yes');
        }
        else
        {
            update_post_meta($post->ID, $location_key, '');
        }
    }
    
    private function update_role($role_id)
    {
        global $post;
        if(isset($_POST[$role_id]))
        {
            update_post_meta($post->ID, $role_id, 'yes');
        }
        else
        {
            update_post_meta($post->ID, $role_id, '');
        }
    }
    
    public function get_location_restriction_string($post_id)
    {
        if(! $this->is_event_province_restricted($post_id))
        {
            return "Canada";
        }
        else
        {
            $ret = "";
            for($i = 0; $i < get_province_count(); $i++)
            {
                $key = 'event_registration_location_' . $i;
                if(get_post_meta($post_id, $key, 'true'))
                {
                    $ret .= get_province_name($i) . ', ';
                }                
            }

            return rtrim($ret, ", ");
        }
    }
    
    function render_meta_box()
    {
        global $post;
        wp_nonce_field( 'event_registration_meta_box', 'event_registration_meta_box_nonce' );
            	
    	$value = get_post_meta( $post->ID, 'event_registration_price', true );
        echo '<label for="event_registration_price">';
    	_e( 'Price', 'tcn' );
    	echo '</label><br/> ';
    	echo '<input type="text" id="event_registration_price" name="event_registration_price" value="' . esc_attr( $value ) . '" /><br/>';
    	
    	$value = get_post_meta( $post->ID, 'event_registration_paypal_button', true );
        echo '<label for="event_registration_paypal_button">';
    	_e( 'Paypal Button', 'tcn' );
    	echo '</label><br/> ';
    	echo '<textarea cols="40" height="400" id="event_registration_paypal_button" name="event_registration_paypal_button" value="' . $value . '"></textarea><br/>';
    	
    	?>
    	<ul>
    	<li> https://www.paypal.com/us/webapps/mpp/standard-integration
        <li>  click Create Button
        <li>  Item name = title of this event
        <li>  Item ID = id of this event (<?php echo $post->ID; ?>)
        <li>  Price = same as you entered on this event
        <li>  Currency = CAD
        <li>  Select button text = Pay Now
        <li>  Set the language as appropriate
        <li>  Step 3
            <li>  Add advanced variables

                notify_url=https://events.huddol.com/ipn/
                    OR
                notify_url=https://events.huddol.com/ipn/
        </ul>
        <?php
    	
    	$value = get_post_meta( $post->ID, 'event_registration_max_registrants', true );
        echo '<label for="event_registration_max_registrants">';
    	_e( 'Max Registrants', 'tcn' );
    	echo '</label><br/> ';
    	echo '<input type="text" id="event_registration_max_registrants" name="event_registration_max_registrants" value="' . esc_attr( $value ) . '" /><br/>';
    	
    	echo '<p><label for="event_registration_location">';
    	_e( 'Location', 'tcn' );
    	echo '</label><br/> ';
    	
    	for($i = 0; $i < get_province_count(); $i++)
    	{
    	    echo '<input ' . $this->location_included('event_registration_location_' . $i) . ' type="checkbox" name="event_registration_location_' . $i . '" />' . get_province_name($i) . '</br>';
    	}
        echo '</p>';
        
        echo '<p>';
        if($this->is_event_province_restricted($post->ID))
        {
            echo 'This post is only available to residents of the above locations.';
        }
        else
        {
            echo 'This post is <b>not</b> location restricted and is available Canada-wide.';
        }
    	echo '</p>';
    	
    	echo '<p><label>';
    	_e( 'Role', 'tcn' );
    	echo '</label><br/> ';
    	
   	
    	echo '<input ' . $this->role_included('event_registration_role_0') . ' type="checkbox" name="event_registration_role_0" />' . get_caregiver_role_name(0) . ' </br>';
    	echo '<input ' . $this->role_included('event_registration_role_1') . ' type="checkbox" name="event_registration_role_1" />' . get_caregiver_role_name(1) . ' </br>';
    	echo '<input ' . $this->role_included('event_registration_role_2') . ' type="checkbox" name="event_registration_role_2" />' . get_caregiver_role_name(2) . ' </br>';
    	echo '<input ' . $this->role_included('event_registration_role_3') . ' type="checkbox" name="event_registration_role_3" />' . get_caregiver_role_name(3) . ' </br>';
        echo '</p>';
        
        echo '<p>';
        if($this->is_event_role_intended($post->ID))
        {
            echo 'This post is intended for users of the above roles.';
        }
        else
        {
            echo 'This post is <b>not</b> intended for any specific role.';
        }
        echo '</p>';
        
        echo '<p>';
        echo $this->get_role_intention_string($post->ID);
    	echo '</p>';

    }
    
    function render_meta_box_users()
    {
        global $wpdb;
        global $post;
        $query = "SELECT *
                      FROM wp_event_registration
                      WHERE 
                            post_id='$post->ID'";
        $results = $wpdb->get_results($query);
        $this->render_event_registrations_table($results);
        
        echo '<br><br>';
        echo '<a target="_blank" href="'. site_url() . '/tcn-handler-event_registration_cvs/?action=event_registration&event_id=' . $post->ID . '">Download Users as CVS</a>';
    }
        
    function adding_custom_meta_boxes( $post_type, $post=null ) 
    {    
        add_meta_box( 
            'event-registration',
            __( 'Event Registration' ),
             array( $this, 'render_meta_box'),
            'tribe_events',
            'normal',
            'default'
        );
        
        add_meta_box( 
            'event-registration-users',
            __( 'Event Registration Users' ),
             array( $this, 'render_meta_box_users'),
            'tribe_events',
            'normal',
            'default'
        );
    }

	function load_styles() 
	{
		wp_enqueue_style('style', plugins_url( 'assets/style.css', __FILE__ ), null, null, 'screen');
	}

	function add_page() 
	{
		// $page = add_menu_page( 'Event Registration', 'Event Registration', 'manage_options', 'event-registration', array( $this, 'add_admin_page' ) );
	}
	
	static function compare_user_asc($a, $b) 
    {
        return strcmp(get_userdata($a->user_id )->user_email, get_userdata($b->user_id )->user_email);
    }
 
    static function compare_user_desc($a, $b) 
    {
        return strcmp(get_userdata($b->user_id )->user_email, get_userdata($a->user_id )->user_email);
    }

    static function compare_post_asc($a, $b) 
    {
        return strcmp(get_post($a->post_id)->post_title, get_post($b->post_id)->post_title);
    }

    static function compare_post_desc($a, $b) 
    {    
        return strcmp(get_post($b->post_id)->post_title, get_post($a->post_id)->post_title);
    }
    
	private function render_event_registrations_table($event_registrations)
	{
	    echo '<table class="event-registration">';
        echo '<tr>';
        $headers = array('User', 'First Name', 'Last Name', 'Registered', 'City', 'Province', 'Phone Number', 'Role', 'Age', 'Marital Status', 'Gender' );
        
        $uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);
        $uri = 'http://' . $_SERVER['HTTP_HOST'] . $uri_parts[0] . '?page=event-registration';
        
        foreach($headers as $header)
        {
                echo '<th>';
                echo $header;
                echo '</th>';
        }
        echo '</tr>';
    	    
        foreach($event_registrations as $er)
        {
            if($er->user_id == 0)
            {
                ?>
                <td>Call This User</td>
                <td>
                        <?php echo $er->phone_number; ?>
                </td>
                <td>
                        <?php echo $er->phone_number_name; ?>
                </td>
                <td></td>
            <?php
            }
            else
            {
                $user = get_userdata($er->user_id );
                $er_post = get_post($er->post_id);
            
                if($user && $er_post)
                {
                    echo '<tr>';
                
                    echo '<td>';
                    echo $user->user_email;
                    echo '</td>';
                
                    echo '<td>';
                    echo get_user_meta( $user->ID, 'first_name', true );
                    echo '</td>';
                    echo '<td>';
                    echo get_user_meta( $user->ID, 'last_name', true );
                    echo '</td>';
                
                    echo '<td>' . $er->date_registered . '</td>';
                    
                    echo '<td>';
                    echo get_user_meta( $user->ID, 'tcn_user_meta_city', true );
                    echo '</td>';
                    
                    echo '<td>';
                    if(get_user_meta( $user->ID, 'tcn_user_meta_province', true ))
                    {
                        echo get_province_name(get_user_meta( $user->ID, 'tcn_user_meta_province', true ));
                    }
                    else
                    {
                        echo 'unknown';
                    }
                    echo '</td>';
                    
                    echo '<td>';
                    echo get_user_meta( $user->ID, 'tcn_user_meta_phone', true );
                    echo '</td>';

                    echo '<td>';
                    $roles = get_user_meta( $user->ID, 'tcn_user_meta_caregiver_role', true );
                    $roles = $roles == '' ? [] : $roles;
                    foreach($roles as $role)
                    {
                        echo get_caregiver_role_name($role);
                        echo ' ';
                    }
                    echo '</td>';

                    echo '<td>';
                    echo get_age(get_user_meta( $user->ID, 'tcn_user_meta_age', true ));
                    echo '</td>';

                    echo '<td>';
                    echo get_marital(get_user_meta( $user->ID, 'tcn_user_meta_marital_status', true ));
                    echo '</td>';
                    
                    echo '<td>';
                    echo get_gender(get_user_meta( $user->ID, 'tcn_user_meta_gender', true ));
                    echo '</td>';
                    
                    echo '</tr>';
                }
            }
        }
    
        echo '</table>';
	}

	function add_admin_page() 
	{
		if ( ! is_super_admin() )  
		{
		    wp_die( __( 'You do not have sufficient permissions to access this page.', 'event-registration' ) );
		}

        echo '<p>';
        echo ' Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris purus nibh, mattis posuere leo non, ultricies posuere nisi. Phasellus eget sem vehicula, ornare justo sit amet, blandit nisi. In in porttitor purus. Ut sit amet gravida orci. Nam porttitor nunc a arcu facilisis varius. Quisque tempor posuere nunc, ut facilisis sem. Maecenas in consectetur sem. Donec nunc est, finibus sed sem et, sodales tempus mi. Curabitur posuere metus mi, id tempor orci convallis eget. Nullam sed hendrerit augue, quis pretium sapien. Nulla nec felis felis. Etiam at ornare enim, sed varius leo.';
        echo '</p>';
        
        echo '<p>';
        echo ' Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris purus nibh, mattis posuere leo non, ultricies posuere nisi. Phasellus eget sem vehicula, ornare justo sit amet, blandit nisi. In in porttitor purus. Ut sit amet gravida orci. Nam porttitor nunc a arcu facilisis varius. Quisque tempor posuere nunc, ut facilisis sem. Maecenas in consectetur sem. Donec nunc est, finibus sed sem et, sodales tempus mi. Curabitur posuere metus mi, id tempor orci convallis eget. Nullam sed hendrerit augue, quis pretium sapien. Nulla nec felis felis. Etiam at ornare enim, sed varius leo.';
        echo '</p>';
	}
}

new EventRegistration;
