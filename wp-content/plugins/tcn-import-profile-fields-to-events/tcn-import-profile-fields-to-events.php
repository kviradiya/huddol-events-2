<?php
/**
 * Plugin Name: TCN: Import Profile Fields to Meta
 * Author: Jon Hill
 * Description: Import Profile Fields to Meta
 * Version: Only and forever
 */
 

class IPFTE {
	function __construct() 
	{
		add_action( 'add_meta_boxes', array( $this, 'adding_custom_meta_boxes') );
		add_action( 'save_post', array( $this, 'save_meta_box_data' ) );
	}
    
    function save_meta_box_data()
    {   
        global $post;
        if ( ! isset( $_POST['ipfte_meta_box_nonce'] ) ) 
	    {
    		return;
    	}

    	if ( ! wp_verify_nonce( $_POST['ipfte_meta_box_nonce'], 'ipfte_meta_box' ) ) 
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
		
        if(isset($_POST['ipfte_user']))
        {
            update_post_meta($post->ID, 'ipfte_user', $_POST['ipfte_user']);
        }     
        else
        {
  
        }   
    }
    
    function render_meta_box()
    {
        global $post;
        echo '<table class="form-table">';
        wp_nonce_field( 'ipfte_meta_box', 'ipfte_meta_box_nonce' );
        
        echo '<p>You can import some fields from your profile to this even automatically. Those fields are the phone number, conference id and webinar link. Select your username to import these fields and override the values you entered above.</p>';
        echo '<tr>';
        echo '<td>From This User:</td>';
        
        
        
        $selected_user = get_post_meta($post->ID, 'ipfte_user');
        if(count($selected_user))
        {
            $selected_user = $selected_user[0];
        }
        else
        {
            $selected_user = -1;
        }
        
        echo '<td><select name="ipfte_user">';
        echo '<option value="-1"> -- none do not override -- </option>';
        if(is_super_admin())
        {
            $users = get_users(array("role"=>"network_partner"));
            foreach($users as $u)
            {
                if($u->ID == $selected_user)
                {
                    echo '<option selected value="'. $u->ID .'"> ' . $u->display_name . '</option>';
                }
                else
                {
                    echo '<option value="'. $u->ID .'"> ' . $u->display_name . '</option>';
                }
            }
        }
        else
        {
            if(wp_get_current_user()->ID == $selected_user)
            {
                echo '<option selected value="'. wp_get_current_user()->ID .'"> ' . wp_get_current_user()->display_name . '</option>';
            }
            else
            {
                echo '<option value="'. wp_get_current_user()->ID .'"> ' . wp_get_current_user()->display_name . '</option>';
            }
        }
        
        echo '</select></td>';
        echo '</tr>';
        
        echo '</table>';
    }
    
    function adding_custom_meta_boxes( $post_type, $post=null ) 
    {
        add_meta_box( 
            'ifpte-meta',
            __( 'Import Profile Fields To This Event From This User' ),
             array( $this, 'render_meta_box'),
            'tribe_events',
            'normal',
            'default'
        );
    }
}

new IPFTE;
