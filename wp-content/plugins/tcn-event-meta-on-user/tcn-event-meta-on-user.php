<?php
/**
 * Plugin Name: TCN: Event Meta On User
 * Author: Jon Hill
 * Description: Allows users to 
 * Version: Only and forever
 */
 

 
 class EventMetaOnUser {
	function __construct() 
	{
		add_action( 'show_user_profile', array( $this, 'emou_show_custom_fields' ), 10, 1 ); 
		add_action( 'edit_user_profile', array( $this, 'emou_show_custom_fields' ), 10, 1 );
        add_action( 'profile_update', array( $this, 'emou_save_custom_fields' ), 10, 1 );

	}

    public function emou_save_custom_fields($user_id)
    {
        if(isset($_POST['event_meta_phone_number']))
        {
            update_user_meta($user_id, 'event_meta_phone_number', $_POST['event_meta_phone_number']);
        }
        
        if(isset($_POST['event_meta_conference_id']))
        {
            update_user_meta($user_id, 'event_meta_conference_id', $_POST['event_meta_conference_id']);
        }
        
        if(isset($_POST['event_meta_webinar_link']))
        {
            update_user_meta($user_id, 'event_meta_webinar_link', $_POST['event_meta_webinar_link']);
        }
    }
    
	public function emou_show_custom_fields( $user ) 
	{
	    echo '<h3>', __( 'Event Meta on User', 'news-feed' ), '</h3>';

	    $event_meta_phone_number = get_user_meta($user->ID, 'event_meta_phone_number', true);
	    $event_meta_conference_id = get_user_meta($user->ID, 'event_meta_conference_id', true);
	    $event_meta_webinar_link = get_user_meta($user->ID, 'event_meta_webinar_link', true);
            
        echo '<table class="form-table">';
        
        echo '<tr>';
        echo '<td>Event Phone Number Override: </td>'; 
	    echo '<td><input type="input" name="event_meta_phone_number" value="'. $event_meta_phone_number. '" /></td>';
	    echo '</tr>';
	    
	    echo '<tr>';
	    echo '<td>Event Conference ID: </td>'; 
	    echo '<td><input type="input" name="event_meta_conference_id" value="'. $event_meta_conference_id. '" /></td>';
	    echo '</tr>';
	    
	    echo '<tr>';
	    echo '<td>Event Webinar Link: </td>'; 
	    echo '<td><input type="input" name="event_meta_webinar_link" value="'. $event_meta_webinar_link. '" /></td>';
	    echo '</tr>';
	    
	    echo '</table>';
    }
}

new EventMetaOnUser;
