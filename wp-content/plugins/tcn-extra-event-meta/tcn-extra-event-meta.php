<?php
/**
 * Plugin Name: TCN: Extra Event Meta
 * Author: Jon Hill
 * Description: Extra Event Meta
 * Version: Only and forever
 */
 
$labels = array(
    'event_meta_presenter_name_0' => 'Presenter 1 Name' ,
    'event_meta_presenter_name_1' => 'Presenter 2 Name' ,
    'event_meta_presenter_name_2' => 'Presenter 3 Name' ,
    
    'event_meta_presenter_image_0' => 'Presenter 1 Image',
    'event_meta_presenter_image_1' => 'Presenter 2 Image',
    'event_meta_presenter_image_2' => 'Presenter 3 Image',
    
    'event_meta_presenter_bio_0' => 'Presenter 1 Bio',
    'event_meta_presenter_bio_1' => 'Presenter 2 Bio',
    'event_meta_presenter_bio_2' => 'Presenter 3 Bio',
    
    'event_meta_phone_number' => "Event Phone Number", 
    'event_meta_conference_id' => "Event Conference ID", 
    'event_meta_webinar_link' => "Event Webinar Link", 
    'event_meta_partnership_0' => "Event Partnership", 
    'event_meta_partnership_1' => "Event Partnership",
    'event_meta_recording' => "Event Recording (youtube, soundcloud, etc...)"
);
    
function label_for_key($key)
{
    global $labels;
    
    if(isset($labels[$key]))
    {
        return $labels[$key];
    }
    return $key;
}

class ExtraEventMeta {
    
    private $fields = array(
        'event_meta_phone_number', 'event_meta_conference_id', 
        'event_meta_webinar_link', 
        'event_meta_partnership_0', 'event_meta_partnership_1', 
        'event_meta_recording',
        
        'event_meta_presenter_name_0', 'event_meta_presenter_image_0', 'event_meta_presenter_bio_0',
        'event_meta_presenter_name_1', 'event_meta_presenter_image_1', 'event_meta_presenter_bio_1',
        'event_meta_presenter_name_2', 'event_meta_presenter_image_2', 'event_meta_presenter_bio_2'
        
        );
    
	function __construct() 
	{
		add_action( 'add_meta_boxes', array( $this, 'adding_custom_meta_boxes') );
		add_action( 'save_post', array( $this, 'save_meta_box_data' ) );
	}
    
    function save_meta_box_data()
    {
        global $post;
        if ( ! isset( $_POST['event_meta_box_nonce'] ) ) 
	    {
    		return;
    	}

    	if ( ! wp_verify_nonce( $_POST['event_meta_box_nonce'], 'event_meta_box' ) ) 
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
        
        $recording_now = get_post_meta($post->ID, 'event_meta_recording', true);
        /*
        echo "Now" . $recording_now; 
        echo '<br>';
        echo "Later: " . $_POST['event_meta_recording'];
        echo '<br>';
        */

        if ($recording_now == '')
        {
            if($_POST['event_meta_recording'] != '')
            {
                update_post_meta($post->ID, 'event_meta_recording', $_POST['event_meta_recording']);
                send_recording_ready_emails($post, 'en');
            }
        }
        
		
        foreach($this->fields as $field)
        {
           
            if(strpos($field, '_image_') !== FALSE)
            {
                if($_FILES[$field]['error'] === 0)
                {
                    $attach_id = media_handle_upload( $field, 0 );
                    update_post_meta($post->ID, $field, $attach_id );
                }
            }
            else
            {
                if(isset($_POST[$field]))
                {
                    update_post_meta($post->ID, $field, $_POST[$field]);
                }
            }
        }
        
        if(isset($_POST['event_meta_private']))
        {
            update_post_meta($post->ID, 'event_meta_private', 1);
        }
        else
        {
            update_post_meta($post->ID, 'event_meta_private', 0);
        }
        
        update_post_meta($post->ID, 'event_meta_language', $_POST['event_meta_language'] );
        update_post_meta($post->ID, 'event_meta_type', $_POST['event_meta_type'] );
        update_post_meta($post->ID, 'event_timezone', $_POST['event_timezone'] );
        update_post_meta($post->ID, 'event_meta_primary_channel', $_POST['event_meta_primary_channel'] );
    }
    
    function render_meta_box()
    {
        global $post;
        echo '<table class="form-table">';
        wp_nonce_field( 'event_meta_box', 'event_meta_box_nonce' );
        
        echo '<tr>';
        echo '<td>Primary Channel <br/><font style="font-size: 10px">Please note: this date is for display purposes only. You <b>must</b> choose categories for this event as well.</td>';
        echo '<td>';
        $categories = get_categories(array("hide_empty"=>0));
        $sel_cat = get_post_meta($post->ID, 'event_meta_primary_channel', true);
        echo '<select name="event_meta_primary_channel">';
        echo '<option value="0">--</option>';
        foreach($categories as $cat)
        {
            if($cat->term_id == $sel_cat)
                echo '<option selected="selected" value="' . $cat->term_id . '">'. $cat->cat_name . '</option>';
            else
                echo '<option value="' . $cat->term_id . '">'. $cat->cat_name . '</option>';
        }
        echo '</select>';
        
        foreach($this->fields as $field)
        {
            if( strpos($field, 'event_meta_partnership') === false)
            {
                echo '<tr>';
                echo '<td>' . label_for_key($field) . '</td>';
                $post_meta = get_post_meta($post->ID, $field, true);
                if(strpos($field, '_image_') !== FALSE)
                {
                    if($post_meta != '')
                    {
                        $media = get_post($post_meta);
                    }
                    else
                    {
                        $media = 0;
                    }
                    
                    //echo '<pre>';
                    //print_r($media);
                    // echo '</pre>';
                    
                    echo '<td>';
                    if($media && $media->guid !== '')
                    {
                        echo '<img style="height: 40px;" src="' . $media->guid . '" />';
                    }
                    echo '<input name="'. $field . '" type="file" value="' . $post_meta . '" /></td>';    
                }
                else
                {
                    echo '<td> <input name="'. $field . '" type="text" value="' . $post_meta . '" /></td>';
                }
                echo '</tr>';
            }
            
        }
        
        echo '<tr>';
        echo '<td>Private (accessible by link only, not in search)</td>';
        $private = get_post_meta($post->ID, 'event_meta_private');
        if(count($private))
        {
            $private = $private[0] ? ' checked="checked"' : '';
        }
        else
        {
            $private = '';
        }
        
        echo '<td><input type="checkbox" '. $private . ' name="event_meta_private" /></td>';
        echo '</tr>';
        
        

            $users = get_non_subscribers();        
        for($i = 0; $i < 2; $i++)
        {
            echo '<tr>';
            echo '<td>With the Support Of</td>';
            echo '<td><select name="event_meta_partnership_'. $i .'">';

            $partnership = get_post_meta($post->ID, 'event_meta_partnership_' . $i);
            if(count($partnership))
            {
                $partnership = $partnership[0];
            }
            else
            {
                $partnership = -1;
            }
        
            if($partnership == -1)
            {
                echo '<option selected value="-1">--</option>';
            }
            else
            {
                echo '<option value="-1">--</option>';            
            }
        
            foreach($users as $u)
            {
                if($u->ID == $partnership)
                {
                    echo '<option selected value="'. $u->ID .'"> ' . $u->display_name . '</option>';
                }
                else
                {
                    echo '<option value="'. $u->ID .'"> ' . $u->display_name . '</option>';
                }
            }
            echo '</select></td>';
            echo '</tr>';
        }
        
        $language = get_post_meta($post->ID, 'event_meta_language', true);
        $type = get_post_meta($post->ID, 'event_meta_type', true);
        
        echo '<tr>';
        echo '<td>Language</td>';
        echo '<td><input type="radio" value="0" name="event_meta_language" ' . ($language == 0 ? 'checked="checked"' : '') . '>English</input>';
        echo ' <input type="radio" value="1" name="event_meta_language" ' . ($language == 1 ? 'checked="checked"' : '')  . '>French</input></td>';
        echo '</tr>';

        echo '<tr>';
        echo '<td>Type</td>';
        echo '<td><input type="radio" value="0" name="event_meta_type" ' . ($type == 0 ? 'checked="checked"' : '') . '>Webinar</input>';
        echo ' <input type="radio" value="1" name="event_meta_type" ' . ($type == 1 ? 'checked="checked"' : '') . '>Teleconference</input></td>';
        echo '</tr>';
        
        $tz = get_post_meta($post->ID, 'event_timezone', true);
                
        echo '<tr>';
        echo '<td>Timezone</td>';
        echo '<td><select name="event_timezone">';
        $timezones = get_timezones();
        
        for($i = 0; $i < count($timezones); $i++)
        {
            if($i == $tz)
            {
                echo '<option selected value="'. $i .'"> ' . get_timezones()[$i] . '</option>';
            }
            else
            {
                echo '<option value="'. $i .'"> ' . get_timezones()[$i]  . '</option>';
            }
        }
        echo '</select>';
        echo '</td></tr>';
        
        echo '</table>';
    }
    
    function adding_custom_meta_boxes( $post_type, $post=null ) 
    {
        add_meta_box( 
            'extra-event-meta',
            __( 'Extra Event Meta Data Especially for TCN' ),
             array( $this, 'render_meta_box'),
            'tribe_events',
            'normal',
            'default'
        );
    }
}

new ExtraEventMeta;
