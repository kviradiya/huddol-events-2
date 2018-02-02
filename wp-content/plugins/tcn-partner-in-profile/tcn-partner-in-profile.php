<?php
/**
 * Plugin Name: TCN: Partner In Profile
 * Author: Jon Hill
 * Description: AAllows us to set a user as the partner in profile.
 * Version: Only and forever
 */
 
function get_partner_name($user_id)
{
    $name = '';
    if(ICL_LANGUAGE_CODE == 'en')
        $name = get_partner_english_name($user_id);
    else
        $name = get_partner_french_name($user_id);
        
    if($name === '')
    {
       $user = get_userdata($user_id);
       return $user->display_name;
    }
    
    return $name;
}

function get_partner_english_name($user_id)
{
    return get_user_meta($user_id, 'tcn_partner_english_name', true);
}

function get_partner_french_name($user_id)
{
    return get_user_meta($user_id, 'tcn_partner_french_name', true);
}
 
class PartnerInProfile 
{
	function __construct() 
	{
		add_action( 'show_user_profile', array( $this, 'pip_show_custom_fields' ), 10, 1 ); 
		add_action( 'edit_user_profile', array( $this, 'pip_show_custom_fields' ), 10, 1 );
        add_action( 'profile_update', array( $this, 'pip_save_custom_fields' ), 10, 1 );

	}

    public function pip_save_custom_fields($user_id)
    {
        if(is_super_admin() || is_current_user_network_partner())
        {
            if(isset($_POST['pip_profiled']))
            {
                update_user_meta($user_id, '_partner_in_profile', true);
            }
            else
            {
                update_user_meta($user_id, '_partner_in_profile', false);
            }
            
            if(isset($_POST['pip_profiled_fr']))
            {
                update_user_meta($user_id, '_partner_in_profile_fr', true);
            }
            else
            {
                update_user_meta($user_id, '_partner_in_profile_fr', false);
            }
            
            update_user_meta($user_id, 'tcn_partner_english_name', $_POST['tcn_partner_english_name']);
            
            update_user_meta($user_id, 'tcn_partner_french_name', $_POST['tcn_partner_french_name']);
            
            update_user_meta($user_id, 'tcn_partner_english_website', $_POST['tcn_partner_english_website']);
            
            update_user_meta($user_id, 'tcn_partner_french_website', $_POST['tcn_partner_french_website']);
            
            update_user_meta($user_id, 'tcn_partner_english_description', $_POST['tcn_partner_english_description']);
            
            update_user_meta($user_id, 'tcn_partner_french_description', $_POST['tcn_partner_french_description']);
        }
    }
    
	// $profileuser is a WP_User object
	public /*.void.*/ function pip_show_custom_fields( $user ) 
	{
	    if(is_super_admin())
        {
            
    	    echo '<h3>', __( 'Extra Partner or Author Information'), '</h3>';
            {
    	    $checked = '';
    	    $meta = get_user_meta($user->ID, '_partner_in_profile');
    
    	    if(count($meta))
    	    {
    	        if(isset($meta[0]))
    	        {
    	            if($meta[0] == 1)
    	            {
    	                $checked = 'checked';   
    	            }
    	        }
    	    }
    	    echo '<input type="checkbox" name="pip_profiled" '. $checked . '/> Partner in Profile English?';
	        }
	        echo '<br/></br/>';
	        {
    	    $checked = '';
    	    $meta = get_user_meta($user->ID, '_partner_in_profile_fr');
    
    	    if(count($meta))
    	    {
    	        if(isset($meta[0]))
    	        {
    	            if($meta[0] == 1)
    	            {
    	                $checked = 'checked';   
    	            }
    	        }
    	    }
    	    echo '<input type="checkbox" name="pip_profiled_fr" '. $checked . '/> Partner in Profile French?';
	        }
    	}
    	
    	if(is_super_admin() || is_current_user_network_partner())
    	{
    	    ?>
    	    <table class="form-table">
    	        <tr>
    	            <td>
    	                English Name
    	            </td>
    	            <td>
    	                <input type="text" name="tcn_partner_english_name" 
    	                       value="<?php echo get_user_meta($user->ID, 'tcn_partner_english_name', true); ?>">
    	            </td>
    	        </tr>
    	        
    	        <tr>
    	            <td>
    	                French Name
    	            </td>
    	            <td>
    	                <input type="text" name="tcn_partner_french_name"
    	                       value="<?php echo get_user_meta($user->ID, 'tcn_partner_french_name', true); ?>">
    	            </td>
    	        </tr>
    	        
    	        <tr>
    	            <td>
    	                English Website
    	            </td>
    	            <td>
    	                <input type="text" name="tcn_partner_english_website" 
    	                       value="<?php echo get_user_meta($user->ID, 'tcn_partner_english_website', true); ?>">
    	            </td>
    	        </tr>
    	        
    	        <tr>
    	            <td>
    	                French Website
    	            </td>
    	            <td>
    	                <input type="text" name="tcn_partner_french_website"
    	                       value="<?php echo get_user_meta($user->ID, 'tcn_partner_french_website', true); ?>">
    	            </td>
    	        </tr>
    	        
    	        <tr>
    	            <td>
    	                English Description
    	            </td>
    	            <td>
    	                <textarea name="tcn_partner_english_description"><?php echo get_user_meta($user->ID, 'tcn_partner_english_description', true); ?></textarea>
    	            </td>
    	        </tr>
    	        
    	        <tr>
    	            <td>
    	                French Description
    	            </td>
    	            <td>
    	                <textarea name="tcn_partner_french_description"><?php echo get_user_meta($user->ID, 'tcn_partner_french_description', true); ?></textarea>
    	            </td>
    	        </tr>
    	    </table>
    	    
    	    <?php
        }
    }
}

new PartnerInProfile;
