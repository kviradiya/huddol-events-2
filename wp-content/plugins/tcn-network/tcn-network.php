<?php
/**
 * Plugin Name: TCN: Network
 * Author: Jon Hill
 * Description: Allow users to subscribe to categories, tags, or other users. Provide access to a news-feed API to serve up posts.
 * Version: Only and forever
 */
 

 
class TCNNetwork 
{
	/******** utilities *********/
	private function get_category_ids($user)
	{
	    $cats = $this->get_subscribed_categories($user);
	    $ids = array();
	    
	    foreach($cats as $cat)
	    {
	        array_push($ids, $cat->cat_ID);
	    }
	    return $ids;
	}
	
	public function get_network_category_events($user)
	{
	    $cat_ids = $this->get_category_ids($user);
	    if(! count($cat_ids))
	        return array();
	        
	    $args = array(
    	    'posts_per_page'   => -1,
            'offset'           => 0,
            'post_type'        => array('tribe_events'),
            'post_status'      => 'publish',
            'category__in'      => $cat_ids,
            'suppress_filters' => true );
	
	    return get_posts($args);
	}
	
	public function get_upcoming_network_events($user, $num=12)
	{
	    $cat_events = $this->get_network_category_events($user);
	    
        $event_objs = array();
        $event_registration = new EventRegistration;
        
	    foreach($cat_events as $cat_event)
	    {
            if(is_event_private($cat_event->ID))
            {
                continue;
            }
            
            if(is_event_over($cat_event->ID))
            {
                continue;
            }
            
            $lang = wpml_get_language_information($cat_event->ID);
            if(substr($lang['locale'], 0, 2) != ICL_LANGUAGE_CODE)
            {
                continue;
            }
            
            if(!$event_registration->can_user_province_register($cat_event->ID, $user->ID))
            {
                continue;
            }
            else
            {
                // echo "can't";
            }
            
            array_push($event_objs, $cat_event);            
	    }
	    	    
	    usort($event_objs, 'compare_event_dates');
	    return array_slice($event_objs, 0, $num);
	}
	
	public function get_subscribed_categories($user)
	{
        if($user->ID != 0)
        {
    	    $meta = get_user_meta($user->ID, 'tcn_network_categories');
    	    if(count($meta))
    	    {
    	        $ret_categories = array();
    	    
    	        foreach($meta[0] as $cat_id)
    	        {
    	            $category = get_category($cat_id);
    	            array_push($ret_categories, $category);
    	        }
    	        return $ret_categories;
    	    }
    	}
	    return array();
	}
	
	public function is_user_category_subscribed($user, $category_id)
	{
	    $meta = get_user_meta($user->ID, 'tcn_network_categories');
	    if(count($meta))
	    {
	        $arr = $meta[0];
	        if(in_array($category_id, $arr))
	        {
	            return 'checked';
	        }
	    }
	    return '';
	}
	
	public function save_categories($user_id)
	{
	    $checked_cats = array();
	    
	    $args = array(
          'orderby' => 'name',
          'order' => 'ASC',
          'hide_empty' => 0
        );
	    $categories = get_categories($args);
	    foreach($categories as $category)
	    {
	        $post_name = 'tcn_network_category_' . $category->term_id;
	        if(isset($_POST[$post_name]))
	        {
	            array_push($checked_cats, $category->term_id);
	        }
	    }
	    
	    update_user_meta($user_id, 'tcn_network_categories', $checked_cats);
	}
}

new TCNNetwork;
