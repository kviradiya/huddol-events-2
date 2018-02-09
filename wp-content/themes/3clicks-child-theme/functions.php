<?php
// Prevent direct script access
if ( !defined( 'ABSPATH' ) )
	die ( 'No direct script access allowed' );

require_once(ABSPATH . '/wp-admin/includes/user.php');
require_once('emails/class.email_buttons.php');
require_once('lib/assets.php');         // Asset and file upload related functions
require_once('lib/titles.php');         // Page and article titles
require_once('lib/datetime.php');       // Date & Time Related Functions
require_once('cta_btn/cta_btn_options.php');       // CTA button option page

show_admin_bar( false );

date_default_timezone_set( 'America/Montreal' );

add_filter('comment_notification_recipients', 'override_comment_notice_repicient', 10, 2);


// Remove automatic comment feeds on individual posts (generates 404 links on every post when no comments exist)
function remove_comments_rss( $for_comments ) {
    return;
}
add_filter('post_comments_feed_link','remove_comments_rss');

function override_comment_notice_repicient($emails, $comment_id) 
{
    $emails[] = 'admin@lratcn.ca';
    //$emails[] = 'jon@jonhill.ca';
    return $emails;
}

function translate_date_format($format) {
        if (function_exists('icl_translate'))
          $format = icl_translate('Formats', $format, $format);
return $format;
}
add_filter('option_date_format', 'translate_date_format');

add_action('admin_footer', 'wp_footer');

function my_relationship_query( $args, $field, $post )
{
    // add and define tribe events eventDisplay to 'all' since it's predifined only to future.
    $args['eventDisplay'] = 'all';
    $args['posts_per_page'] = -1;

    return $args;
}

// acf/fields/relationship/result - filter for every field
add_filter('acf/fields/relationship/query', 'my_relationship_query', 10, 3);


define('FRENCH_DASHBOARD_POST_ID', 4148);
define('ENGLISH_DASHBOARD_POST_ID', 105);

function get_account_dashboard_url()
{
    if(ICL_LANGUAGE_CODE == 'en')
    {
        return get_the_permalink(ENGLISH_DASHBOARD_POST_ID);
    }
    else
    {
        return get_the_permalink(FRENCH_DASHBOARD_POST_ID);
    }
}

/**
* 3clicks Child Theme Setup
* 
* Always use child theme if you want to make some custom modifications. 
* This way theme updates will be a lot easier.
*/


/**
* 
* remove tribe_events and works from the sitemap
* 
*/
function filter_sitemap_post_types($pts)
{
    return array();
}
add_filter('g1_sitemap_post_types', 'filter_sitemap_post_types');

add_action('post_edit_form_tag', 'add_post_enctype');

function add_post_enctype() {
    echo ' enctype="multipart/form-data"';
}

/**
* 
* keep track of how many times a post is viewed, for most_viewed
* 
*/
function wpb_set_post_views($postID) 
{
    $count_key = 'wpb_post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count=='')
    {
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }
    else
    {
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}

function wpb_track_post_views ($post_id) 
{
    if ( !is_single() ) return;
    if ( empty ( $post_id) ) {
        global $post;
        $post_id = $post->ID;    
    }
    wpb_set_post_views($post_id);
}
add_action( 'wp_head', 'wpb_track_post_views');
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);

/**
* 
* this will add the tribe_events to the posts that are displayed on a category page
*  add 'nav_menu_item' or you won't see menus on category and tags page.
*/
function query_post_type($query) 
{
    if(is_category() || is_tag()) 
    {
        /* $post_type = get_query_var('post_type');
        
    	if($post_type)
    	{
    	    $post_type = $post_type;
    	}
    	else
    	{
    	    $post_type = array('post','tribe_events','nav_menu_item');
    	} */
    	
    	$post_type = array('post','tribe_events','nav_menu_item');
    	
        $query->set('post_type',$post_type);
    	return $query;
    }
}
add_filter('pre_get_posts', 'query_post_type');

/**
* 
* looks like a normal g1 categories list, but queries for all of the categories an author has used
*/
function tcn_capture_author_categories($author) 
{
    global $wpdb;
    $categories = $wpdb->get_results("
        SELECT DISTINCT(terms.term_id) as ID, terms.name, terms.slug
        FROM $wpdb->posts as posts
        LEFT JOIN $wpdb->term_relationships as relationships ON posts.ID = relationships.object_ID
        LEFT JOIN $wpdb->term_taxonomy as tax ON relationships.term_taxonomy_id = tax.term_taxonomy_id
        LEFT JOIN $wpdb->terms as terms ON tax.term_id = terms.term_id
        WHERE 1=1 AND (
            posts.post_status = 'publish' AND
            posts.post_author = '$author->ID' AND
            tax.taxonomy = 'category' )
        ORDER BY terms.name ASC
    ");
   
    $count = count( $categories );

    $seen = array();
    $out = '<ul>';
    foreach ( $categories as $object ) {
        $category = get_category($object->ID);
        if(! in_array($category->cat_name, $seen))
        {
            $out .= '<li><a href="' . get_category_link($category) . '">' . $category->cat_name . '</a></li>';
            array_push($seen, $category->cat_name);
        }
    }
    $out .= '</ul>';

    if ( !empty( $out ) ) {
        $out =  '<div class="entry-categories"><span>Posted in:</span>' .
                    $out .
                '</div>';
    }

    return $out;

}

function check_password($pwd) 
{
    if (strlen($pwd) < 8) {
        return false;
    }

    if (!preg_match("#[0-9]+#", $pwd)) {
       return false;
    }

    if (!preg_match("#[a-zA-Z]+#", $pwd)) {
        return false;
    }     

    return true;
}

function generateRandomString($length = 10) 
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function randomize_events()
{
    $media = get_posts(array('post_type'=>'attachment', 'posts_per_page' => -1));
    $users = array(3368, 5084);
    $categories = array(85, 86);
    
    for($a=0; $a < 2; $a++)
    {
        $user = get_userdata($users[$a]);
        $category = get_category($categories[$a]);
        
        echo 'Adding to category ' . $category->cat_name . '<br/>';
        echo 'Adding to user ' . $user->display_name . '<br/>';
        
        $posts = get_posts(array("author"=>$user->ID, 'post_type'=>'tribe_events', 'posts_per_page'=>-1));
        foreach($posts as $post)
        {
            wp_delete_post($post->ID, true);
        }
    
        for($i = 0; $i < 100; $i++ )
        {
            $post_id = wp_insert_post(
            array(
            	      'comment_status'  => 'closed',
            	      'ping_status'   => 'closed',
            	      'post_author'   => $user->ID,
            	      'post_name'   => generateRandomString(),
            	      'post_title'    => $category->cat_name . ' (' . generateRandomString() . ')',
            	      'post_status'   => 'publish',
            	      'post_content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam consectetur pulvinar mauris, ac sagittis nunc euismod sed. Nulla dignissim ex nunc, ac vehicula metus tempor vitae. Vestibulum aliquam sit amet arcu a lobortis. Nullam lobortis pretium bibendum. Ut sem magna, eleifend eu dui vel, rhoncus cursus nulla. Aenean vel dolor nec mauris sagittis hendrerit. Nullam tincidunt hendrerit ex, at tristique libero posuere id. Mauris commodo mauris a nisi blandit elementum. Aenean porta erat vel lorem tincidunt, at lacinia felis euismod. Vivamus et neque nibh. Praesent nec enim dignissim, dictum mi quis, dignissim purus. Aenean a bibendum metus, et sollicitudin leo. Phasellus fringilla, sem quis posuere consectetur, nisl felis laoreet orci, nec maximus elit nibh et turpis.',
            	      'post_type'   => 'tribe_events'
            	    )
            	  );
            $event = get_post($post_id);
    
            $start = time() + rand(0, 10000000) - rand(0,10000000);
            $end = $start + 3600;
    
            update_post_meta($event->ID, '_EventStartDate', date(DATE_RFC2822, $start));
            update_post_meta($event->ID, '_EventEndDate', date(DATE_RFC2822, $end));
            
            update_post_meta($event->ID, 'event_meta_phone_number', '1-909-555-8888');
            update_post_meta($event->ID, 'event_meta_conference_id', '#12FAKE');
            update_post_meta($event->ID, 'event_meta_webinar_link', 'http://webinar.example.com');
            
            update_post_meta($event->ID, 'event_meta_presenter_name_0', 'Presenter One');
            update_post_meta($event->ID, 'event_meta_presenter_image_0', '');
            update_post_meta($event->ID, 'event_meta_presenter_bio_0', 'The bio of the presenter.');
            
            update_post_meta($event->ID, 'wpb_post_views_count', rand(100,10000));
            
            
            $thumbnail_id = $media[rand(0, count($media)-1)]->ID;
            update_post_meta($event->ID, '_thumbnail_id', $thumbnail_id);
            
            update_post_meta($event->ID, 'event_meta_type', rand(0,1));
            update_post_meta($event->ID, 'event_meta_language', rand(0,1));
            
            if(rand(0,10) > 5)
            {
                update_post_meta($event->ID, 'post_surveys_survey', 'http://surveymonkey.example.com');
            }
            
            if(rand(0,10) > 8)
            {
                update_post_meta($event->ID, 'event_registration_location_' . rand(0,12), 'yes');
                update_post_meta($event->ID, 'event_registration_location_' . rand(0,12), 'yes');
                update_post_meta($event->ID, 'event_registration_location_' . rand(0,12), 'yes');
                update_post_meta($event->ID, 'event_registration_location_' . rand(0,12), 'yes');
            }
    
            if(rand(0,10) > 4)
            {
                update_post_meta($event->ID, 'event_registration_role_' . rand(0,3), 'yes');
                update_post_meta($event->ID, 'event_registration_role_' . rand(0,3), 'yes');
                update_post_meta($event->ID, 'event_registration_role_' . rand(0,3), 'yes');
            }
    
            if(rand(0,10) > 5)
            {
                update_post_meta($event->ID, 'event_registration_max_registrants', rand(0,50));
            }
    
            wp_set_post_categories( $event->ID, array($category->cat_ID), false);
        }
    }
    
    echo '<pre>';
    echo 'Done';
    echo '</pre>';
}

function add_users($count)
{
    echo 'count of users ' . $count . '<br/><br/>';
    
    for($i = 0; $i < $count; $i++)
    {
        $email = generateRandomString() . '+fake_tcn@jonhill.ca';
        $username = generateRandomString();
        $user_id = wp_create_user( 'tcn_qa_' . $username, '1234qwer', $email );
        update_user_meta($user_id, 'tcn_user_meta_province', rand(0,13));
        update_user_meta($user_id, 'tcn_user_meta_marital_status', rand(0,3));
        update_user_meta($user_id, 'tcn_user_meta_age', rand(0,3));
        update_user_meta($user_id, 'tcn_user_meta_caregiver_role', array(rand(0,1), rand(2,3)));
        echo 'adding ' . $username . '<br/>';
        $categories = array(85,86);
        $num = rand(0, 6);
        for($j = 0; $j < 1; $j++)
        {
            $category_id = $categories[rand(0,1)];
            $category = get_category($category_id);
            if($category)
            {
                    echo "adding to network: " . $category->cat_name . '<br />';
                    update_user_meta($user_id, 'tcn_network_categories', array($category->term_id));
            }
            else
            {
                echo "null category?" . PHP_EOL;
            }
        }

        $rdate = date("Y-m-d H:i:s", time() - rand(0, 40000000));
        wp_update_user( array( 'ID' => $user_id, 'user_registered' => $rdate ));
        
        // echo 'adding: ' . $user_id;
        echo '<br>';
    }
}

function get_network_partner_events($user_id, $source = '')
{
	$args = array(
      'author' => $user_id,
      'parent' => 0,
      'post_type' => 'tribe_events',
      'posts_per_page' => -1
    );
	
	if($source == 'upcoming'){
		$args['meta_key'] = '_EventStartDate';
		$args['orderby'] = 'meta_value';
		$args['order'] = 'ASC';
		$posts = query_posts($args);
	}
	else{
		$posts = get_posts($args);
	}
	
	
    //print_array($args);
	
    $upcoming_events = array();
    
    foreach($posts as $event)
    {
        if(!is_event_private($event->ID))
        {
            $lang = wpml_get_language_information($event->ID);
            if(substr($lang['locale'], 0, 2) == ICL_LANGUAGE_CODE)
            {
                array_push($upcoming_events, $event);
            }
        }
    }
	
	if($source == 'recorded')
	{
		usort($upcoming_events, 'compare_event_dates_desc');
	}
    return $upcoming_events;
}

function get_network_partner_upcoming_events($user_id, $source)
{
    $events = get_network_partner_events($user_id, $source);
    $ret = array();
    foreach($events as $event)
    {
        if(! is_event_over($event->ID))
            array_push($ret, $event);
    }
    return $ret;
}

function get_network_partner_recorded_events($user_id)
{
    $events = get_network_partner_events($user_id);
    
	//print_array($events, 'get_network_partner_recorded_events');
	
	$ret = array();
    foreach($events as $event)
    {
        //print_array(array($event, '_EventStartDate' => get_post_meta($event_id, '_EventStartDate', true)));
		if(is_event_over($event->ID))
            array_push($ret, $event);
    }
    return $ret;
}

function is_network_partner($user)
{
	$user_roles = $user->roles;
	$user_role = array_shift($user_roles);

	return $user_role === 'network_partner';
}

function is_current_user_network_partner()
{
	global $current_user;
    return(is_network_partner($current_user));
}

function get_non_subscribers()
{
        $users = array();
        $roles = array('editor', 'network_partner', 'contributor', 'author', 'admin');

        foreach ($roles as $role) :
            $users_query = get_users(array("role" => $role));
            if ($users_query) $users = array_merge($users, $users_query);
        endforeach;

        return $users;
}

function randomize_subscribers()
{
    global $wpdb;
    $query = "DELETE
                  FROM wp_event_registration
                  ";
    $results = $wpdb->get_results($query);
    
    $users = get_users(array("role"=>"subscriber"));
    foreach($users as $user)
    {
        print 'deleting: ' . $user->ID;
        print wp_delete_user($user->ID, false) . '<br/>';
    }
    $er = new EventRegistration;
    
    global $current_user;
    add_users(50);
    
    $users = get_users(array("role"=>"subscriber"));
    array_push($users, $current_user);
    
    $events = get_posts(array("post_type"=>"tribe_events", "posts_per_page"=>-1));
    foreach($users as $user)
    {
        foreach($events as $event)
        {
            if(rand(0,10) > 7 || $user->ID == 1)
            {
                if($er->can_user_register($event->ID, $user->ID))
                {
                    // print 'adding ' . $event->ID . ', ' . $user->ID . '<br/>';
                    $er->register_user($event->ID, $user->ID);
                }
                
            }
        }
    }
    
    $query = "SELECT *
                  FROM wp_event_registration
                  ";
    $results = $wpdb->get_results($query);
    print 'count: ' . count($results);
}

function get_caregiver_role_count($role)
{
    $count = 0;
    $users = get_users(array("role"=>"subscriber"));
    foreach($users as $user)
    {
        if(is_user_caregiver_role($user->ID, $role))
        {
           $count++;
        }
    }
    return $count;
}

function get_registrations_month($month)
{
    $count = 0;
    $users = get_users(array("role"=>"subscriber"));
    
    foreach($users as $user)
    {
        $user_date = date("Y M", strtotime($user->user_registered));
        
        if(date("Y M", $month) === $user_date)
        {
            $count++;
        }
    }
    return $count;
}

function get_caregiver_role_count_month($month, $role)
{
    $count = 0;
    $users = get_users(array("role"=>"subscriber"));
    
    foreach($users as $user)
    {
        $user_date = date("Y M", strtotime($user->user_registered));
        
        if(date("Y M", $month) === $user_date)
        {
            if(is_user_caregiver_role($user->ID, $role))
            {
               $count++;
            }
        }
    }
    return $count;
    
}

function is_user_caregiver_role($user_id, $role)
{
    $roles = get_user_meta($user_id, 'tcn_user_meta_caregiver_role', true);
    if(is_array($roles))
    {
        return in_array($role, $roles);
    }
    return false;
}

/**
* 
* it's useful to know if an event is in the past or not
*
*/
function is_event_over($event_id)
{
    $now = time();
    $start_date = strtotime(get_post_meta($event_id, '_EventStartDate', true));
	$tz = get_post_meta($event_id, 'event_timezone', true);
	
	$diff = 3 - $tz;
	// add 900 = 15mins to time to allow event to stay open
    if($start_date + ($diff * 3600 + 900) < $now)
    {
        return true;
    }
    
    return false;
}

function is_event_private($event_id)
{
    $meta = get_post_meta($event_id, 'event_meta_private', true);
    return $meta;
}


/**
* 
* profile page helpers here only
*
*/

function get_province_count()
{
    // 10 provinces, 3 territories, outside of Canada
    return 14;
}

$tcn_maritals = array(

__("Single, never married", "tcn"),
    __("Married/Domestic Partner", "tcn"),
    __("Widowed", "tcn"),
    __("Divorced", "tcn"),
    __("Separated", "tcn"),
    __("Decline to State/Other", "tcn")
    );
    
function get_marital($age_id)
{
    global $tcn_maritals;
    if($age_id === '')
    {
        return 'unknown';
    }
    
    if(isset($tcn_maritals[$age_id]))
        return $tcn_maritals[$age_id];
    return 'unknown';
}

$tcn_ages = array(
    '< 17',
    '18-24', '25-34', '35-44', '45-54', '65-74', '75+'    
    );
    
function get_age($age_id)
{
    global $tcn_ages;
    if($age_id === '')
    {
        return 'unknown';
    }
    
    if(isset($tcn_ages[$age_id]))
        return $tcn_ages[$age_id];
    return 'unknown';
}

$tcn_genders = array(
    __("Female", "tcn"),
    __("Male", "tcn"),
    __("Decline to State/Other", "tcn")
);

function get_gender($age_id)
{
    global $tcn_genders;
    if($age_id === '')
    {
        return 'unknown';
    }
    
    if(isset($tcn_genders[$age_id]))
        return $tcn_genders[$age_id];
    return 'unknown';
}

$tcn_provinces = array(
                    __('Alberta', 'tcn'), 
                    __('British Columbia', 'tcn'), 
                    __('Manitoba', 'tcn'), 
                    __('New Brunswick', 'tcn'), 
                    __('Newfoundland and Labrador', 'tcn'), 
                    __('Nova Scotia', 'tcn'), 
                    __('Northwest Territories', 'tcn'), 
                    __('Nunavut', 'tcn'), 
                    __('Ontario', 'tcn'), 
                    __('PEI', 'tcn'), 
                    __('Quebec', 'tcn'), 
                    __('Saskatchewan', 'tcn'), 
                    __('Yukon', 'tcn'), 
                    __('Outside of Canada', 'tcn'));
 
function get_province_name($province_id)
{
    global $tcn_provinces;
    if($province_id === '')
    {
        return $tcn_provinces[13];
    }
    
    if(isset($tcn_provinces[$province_id]))
        return $tcn_provinces[$province_id];
    return 'unknown/invalid';
}

function is_user_outside_of_canada($user_id)
{
    $province = get_user_meta($user_id, 'tcn_user_meta_province', true);
    if($province == '13')
        return true;
    return false;
}

function get_user_province($user_id)
{
    $province = get_user_meta($user_id, 'tcn_user_meta_province', true);
    return get_province_name($province);
}

/**
* 
* keep track of missing fields on the profile page
*
*/

function get_num_caregiver_roles()
{
    return 4;
}

function get_user_stats_breakdown()
{
    $results = array();

    $start = $month = strtotime('2015-01-01');
    $end = time();
    
    $months = array();
    
    $results['alltime'] = array();
    for($i = 0; $i < get_num_caregiver_roles(); $i++ )
     {
         $results['alltime'][get_caregiver_role_name($i)] = 0;
     }
    
    while($month < $end)
    {
         $month = strtotime("+1 month", $month);
         $month_format = date("F Y", $month);
         $results[$month_format] = array();
         
         for($i = 0; $i < get_num_caregiver_roles(); $i++ )
         {
             $results[$month_format][get_caregiver_role_name($i)] = 0;
         }
         $results[$month_format]['total'] = 0;
    }
    
    $args = array("role" => "subscriber");
    $users = get_users( $args );
    
    foreach($users as $u)
    {
        $user_date = date("F Y", strtotime($u->user_registered));
        $results[$user_date]['total']++;
        
        $role = get_user_meta($u->ID, 'tcn_user_meta_caregiver_role', true);

        if(gettype($role) === 'array')
        {
            foreach($role as $r)
            {
                if($r <= 3)
                {
                    $role_name = get_caregiver_role_name($r);
                    $results[$user_date][$role_name]++;
                    $results['alltime'][$role_name]++;
                }
            }
        }
    }

    return $results;
    
}

function get_caregiver_role_name($index, $plural=false)
{
    if(! $plural)
    {
        $names = array(__("Caregiver", 'tcn'), __("Patient", 'tcn'), __("Professional", 'tcn'), __("General Public", 'tcn'));
    }
    else
    {
        $names = array(__("Caregivers", 'tcn'), __("Patients", 'tcn'), __("Professionals", 'tcn'), __("Members of the General Public", 'tcn'));
    }
    return $names[$index];
}

function push_profile_error(&$arr, $key, $missing_field)
{
    if($key == '')
    {
        array_push($arr, $missing_field);
    }
}

function get_user_profile_field($user_id, $key)
{
    $meta = get_user_meta($user_id, $key);
    if(count($meta))
    {
        return $meta[0];
    }
    return '';
}

/**
* 
* the wordpress function for this isn't terrible useful
*
*/
function get_the_post_thumbnail_src($img)
{
    return (preg_match('~\bsrc="([^"]++)"~', $img, $matches)) ? $matches[1] : '';
}

function get_post_thumbnail($post_id, $size='full')
{
    return get_the_post_thumbnail_src(get_the_post_thumbnail($post_id, $size));
}

function get_comma_separated_categories($cats)
{   
    $ret = '';
    $prefix = '';
    foreach ($cats as $cat)
    {
        $ret .= $prefix . $cat;
        $prefix = ', ';
    }
    return $ret;
}

/**
* 
* looks at the excerpt of a post and tries to give it to you. if it's not there
* this function will generate an excerpt from the content of the post
*
*/
function generate_excerpt($post_or_id, $excerpt_more = ' [...]') 
{
    if ( is_object( $post_or_id ) )
    {   
        $postObj = $post_or_id;
    }
    else 
    {   
        $postObj = get_post($post_or_id);
    }

    $raw_excerpt = $text = $postObj->post_excerpt;
    if('' == $text) 
    {
        $text = $postObj->post_content;
        $text = strip_shortcodes( $text );
        $text = apply_filters('the_content', $text);
        $text = str_replace(']]>', ']]&gt;', $text);
        $text = strip_tags($text);
        $excerpt_length = 20; // apply_filters('excerpt_length', 35);

        $words = preg_split("/[\n\r\t ]+/", $text, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY);
        if ( strlen($text) > $excerpt_length ) 
        {
            array_pop($words);
            $text = implode(' ', $words);
            $text = $text . $excerpt_more;
        } 
        else 
        {
            $text = implode(' ', $words);
        }
    }
    
    return apply_filters('wp_trim_excerpt', $text, $raw_excerpt);
}

/**
* 
* return a black placeholder square for posts and events without featured images
*
*/
function black_square()
{
    echo '<img src="' . get_stylesheet_directory_uri() .'/images/black_square_placeholder.png" />';
}

function startsWith($haystack, $needle)
{
     $length = strlen($needle);
     return (substr($haystack, 0, $length) === $needle);
}

/**
* 
* queries only below here!
*
*/

/**
* 
* events that are in the future regardless of any attributes
*
*/


function get_category_tags($category_id)
{
    $tcn_tags = get_terms_meta($category_id, 'tcn_category_tags');
    if(empty($tcn_tags))
    {
        return array();
    }
    
    return $tcn_tags[0];
}

function compare_event_dates($a, $b) 
{
    $er = new EventRegistration;
    $a_count = strtotime(get_post_meta($a->ID, '_EventStartDate', true));
    $b_count = strtotime(get_post_meta($b->ID, '_EventStartDate', true));
    return $a_count > $b_count;
}

function compare_event_dates_desc($a, $b) 
{
    $er = new EventRegistration;
    $a_count = strtotime(get_post_meta($a->ID, '_EventStartDate', true));
    $b_count = strtotime(get_post_meta($b->ID, '_EventStartDate', true));
    return $a_count < $b_count;
}

function get_all_events()
{       
    $tribe_events = get_posts(
        array(
            // 'suppress_filters' => false,
            "post_type" => "tribe_events",
            "posts_per_page" => -1));
    
	
	usort($tribe_events,'compare_event_dates');
    
    $lang_events = array();
    foreach($tribe_events as $event)
    {
        $lang = wpml_get_language_information($event->ID);
        if(substr($lang['locale'], 0, 2) == ICL_LANGUAGE_CODE)
        {
            array_push($lang_events, $event);
        }
    }
	//print_array($lang_events );
    return $lang_events;
}

function get_all_events_all_languages()
{
   $now = date('Y-m-d H:i:s', strtotime('-7 days'));
   $showdateFromNow = new DateTime('+48 hours');
   $args = array('post_type' => 'tribe_events',
   				 'posts_per_page' => -1,
				 'meta_key' => '_EventStartDate',
				 'suppress_filters' => 1,
				 'meta_query' => array(
				 	array(
						'key'      => '_EventStartDate',
						'value'    => array ('now' => $now, 'showdateFromNow' => $showdateFromNow->format('Y-m-d H:i:s')
				 	),
				 'compare'  => 'BETWEEN', 
				 ))
				 
   				);
	
	//print_array($args);
	$query = new WP_Query($args);
	$tribe_events = $query->get_posts();
	//print_array($tribe_events);

    usort($tribe_events,'compare_event_dates');

    return $tribe_events;
}

function get_event_category($event_id)
{
    $category_id = get_post_meta($event_id, 'event_meta_primary_channel', true);
    if(!$category_id)
    {
        $category = get_the_category( $event_id );
        if(count($category))
        {
            $category = $category[0];
        }
        else
        {
            $category = 0;
        }
    }
    else
    {
        $category = get_category($category_id);
    }
    return $category;
}

function get_all_events_in_category($cat_id)
{
    $tribe_events = get_all_events();
    usort($tribe_events,'compare_event_dates_desc');
    $events = array();
    
    foreach($tribe_events as $event)
    {
        $cats = get_the_category($event);
        $cat_ids = array();
        foreach($cats as $cat) 
        {
            array_push($cat_ids, $cat->term_id);
        }
        
        if(!is_event_private($event->ID))
        {
            if(in_array($cat_id, $cat_ids))
            {
                array_push($events, $event);
            }
        }
    }   
    
    return $events;
}

function get_upcoming_events_in_category($cat_id)
{
    $tribe_events = get_all_events();
    $events = array();
    
    foreach($tribe_events as $event)
    {
        
        $cats = get_the_category($event);
        $cat_ids = array();
        foreach($cats as $cat) 
        {
            array_push($cat_ids, $cat->term_id);
        }
        
        if(! is_event_over($event->ID))
        {
            if(!is_event_private($event->ID))
            {
                if(in_array($cat_id, $cat_ids))
                {
                    array_push($events, $event);
                }
            }
        }
    }   
    
    return $events;
}


function get_upcoming_events()
{
    $tribe_events = get_all_events();
    $events = array();
    
    foreach($tribe_events as $event)
    {
        if(!is_event_over($event->ID) && ! is_event_private($event->ID))
        {
            array_push($events, $event);
        }
    }   
    
    return $events;
}


function get_upcoming_events_french()
{
    $tribe_events = get_all_events_all_languages();
    $events = array();
    
    foreach($tribe_events as $event)
    {
        $lang = wpml_get_language_information($event->ID);

        if($lang['locale'] == 'fr_FR' &&!is_event_over($event->ID) && ! is_event_private($event->ID))
        {
            array_push($events, $event);
        }
    }   
    
    return $events;
}

function get_recent_events_french()
{
    $tribe_events = get_all_events_all_languages();
    $events = array();

    foreach($tribe_events as $event)
    {
        $lang = wpml_get_language_information($event->ID);

        if($lang['locale'] == 'fr_FR' &&is_event_over($event->ID) && ! is_event_private($event->ID))
        {
            array_push($events, $event);
        }
    }
    
    return $events;
    
}

function get_latest_stories_french()
{
    $args = array(
	    'posts_per_page'   => -1,
        'offset'           => 0,
        'category'         => '',
        'category_name'    => '',
        'orderby'          => 'post_date',
        'order'            => 'DESC',
        'include'          => '',
        'exclude'          => '',
        'meta_key'         => '',
        'meta_value'       => '',
        'post_type'        => 'post',
        'post_mime_type'   => '',
        'post_parent'      => '',
        'post_status'      => 'publish');
    $posts = get_posts($args);
	$events = array();

    foreach($posts as $event)
    {
        $lang = wpml_get_language_information($event->ID);

        if($lang['locale'] == 'fr_FR')
        {
            array_push($events, $event);
        }
    }
    
    return $events;
}

function get_upcoming_events_english()
{
    $tribe_events = get_all_events_all_languages();
    $events = array();
    
    foreach($tribe_events as $event)
    {
        $lang = wpml_get_language_information($event->ID);
           
        if($lang['locale'] == 'en_US' && !is_event_over($event->ID) && ! is_event_private($event->ID))
        {
            array_push($events, $event);
        }
    }   
    
    return $events;
}

function get_recent_events_english()
{
    $tribe_events = get_all_events_all_languages();
    $events = array();
    
    foreach($tribe_events as $event)
    {
        
        $lang = wpml_get_language_information($event->ID);
        if($lang['locale'] == 'en_US' && is_event_over($event->ID) && ! is_event_private($event->ID))
        {
            array_push($events, $event);
        }
    }
    
    return $events;
}

function get_latest_stories_english()
{
    $args = array(
   	    'posts_per_page'   => -1,
           'offset'           => 0,
           'category'         => '',
           'category_name'    => '',
           'orderby'          => 'post_date',
           'order'            => 'DESC',
           'include'          => '',
           'exclude'          => '',
           'meta_key'         => '',
           'meta_value'       => '',
           'post_type'        => 'post',
           'post_mime_type'   => '',
           'post_parent'      => '',
           'post_status'      => 'publish' );
       $posts = get_posts($args);
   	$events = array();

       foreach($posts as $event)
       {
           $lang = wpml_get_language_information($event->ID);

           if($lang['locale'] == 'en_US')
           {
               array_push($events, $event);
           }
       }

       return $events;
}


function get_pure_upcoming_events()
{
    $tribe_events = get_posts(
        array(
            'suppress_filters' => true,
            "post_type" => "tribe_events",
            "posts_per_page" => -1));
        $events = array();

        foreach($tribe_events as $event)
        {
            if(!is_event_over($event->ID))
            {
                array_push($events, $event);
            }
        }   

        return $events;
    
}

function get_recent_events()
{
    $tribe_events = get_all_events();
    $events = array();
    
    $now = time();
    foreach($tribe_events as $event)
    {
        if(is_event_over($event->ID) && ! is_event_private($event->ID))
        {
            array_push($events, $event);
        }
    }
    
    return $events;
}

function override_from_user($event_id, $meta_key, $default)
{
    $ipfte_user_id = get_post_meta($event_id, 'ipfte_user',true );

    if(get_user_meta($ipfte_user_id, $meta_key, true) != '')
    {
        return get_user_meta($ipfte_user_id, $meta_key, true);
    }
    return $default;
}



/**
* 
* grab the suggested posts off a post, ties into tcn-extra-post-meta
*
*/
function get_suggested_posts($post_id)
{
    $tags = wp_get_post_tags($post_id);  
    // print_r($tags);
    
    $tag_ids = array();  
    foreach($tags as $individual_tag)
    { 
        $tag_ids[] = $individual_tag->term_id;  
    }
    
    $args = array(  
        'tag__in' => $tag_ids,  
        'post__not_in' => array($post_id),  
        'posts_per_page'=> -1, // Number of related posts to display.  
        'suppress_filters' => true
    );  
    
    $posts = get_posts($args);
    $ret = array();
    
    foreach($posts as $post)
    {
        if($post->post_type == 'post')
        {
            array_push($ret, $post);
        }
    }
    
    return array_slice($ret, 0, 5);
}

/**
* 
* grab the suggested events off a post, ties into tcn-extra-post-meta
*
*/
function get_suggested_events($post_id)
{  
    $tags = wp_get_post_tags($post_id);  

    $tag_ids = array();  
    foreach($tags as $individual_tag) $tag_ids[] = $individual_tag->term_id;  
    $args=array(  
        'suppress_filters' => false,
        'post_type' => array('tribe_events'),
    'tag__in' => $tag_ids,  
    'post__not_in' => array($post_id),  
    'posts_per_page'=>-1, // Number of related posts to display.  
    );  

    $posts = get_posts($args);
    
    $public_posts = array();
    foreach($posts as $post)
    {
        if(!is_event_private($post->ID) && $post->post_type == 'tribe_events')
        {
            array_push($public_posts, $post);
        }   
    }
    
    return array_slice($public_posts, 0, 5);
    
}

/**
* 
* all posts where the particular user is the author
*
*/
function get_author_posts($author_id, $exclude_id)
{
   $posts= get_posts(array(
       'suppress_filters' => false,
       "author" => $author_id,
       "posts_per_page" => -1,
       "exclude" => $exclude_id
   ));
   return $posts;
}

/**
* 
* get the posts for channels that are popular. you can do this by
* getting all populated channels and then sorting by views
*
*/
function sort_hits($a, $b) 
{
    return $a < $b;
}

function compare_authors($a, $b) 
{
    return strcmp(get_partner_name($a->ID), get_partner_name($b->ID));
}

function get_category_authors($cat_id)
{
    $args = array( 
        'suppress_filters' => true,
        'post_type' => array('tribe_events'),
        'posts_per_page' => -1, 
        'offset'=> 0, 
        'category' => $cat_id );
    $posts = get_posts($args);
    
    $seen = array();
    $authors = array();
    foreach($posts as $post)
    {
        if(!in_array($post->post_author, $seen))
        {
            array_push($authors, get_userdata($post->post_author));
            array_push($seen, $post->post_author);
        }
    }
    
    usort($authors, 'compare_authors');
    return $authors;
}

function get_popular_channel_posts($posts_per_page=4)
{
    $args = array(
      'orderby' => 'name',
      'parent' => 0,
      'hide_empty' => 1,
      'pad_counts' => 1
    );
    
    $pop_cats = array();
    $categories = get_categories( $args );
    foreach($categories as $cat)
    {
        $count = 0;
        $args = array( 
            'suppress_filters' => false,
            'post_type' => array('tribe_events'),
            'posts_per_page' => -1, 
            'offset'=> 0, 
            'category' => $cat->term_id );
        $posts = get_posts($args);
        foreach($posts as $post)
        {
            $count += get_post_meta($post->ID, 'wpb_post_views_count', true);
        }
        $pop_cats[$cat->term_id] = $count;
    }
    arsort($pop_cats);
    
    $cat_posts = array();
    $count = 0;
    $seen = array();
    foreach($pop_cats as $cat => $value)
    {
        $args = array( 
            'post_type' => array('tribe_events'),
            'posts_per_page' => 5, 
            'orderby' => 'rand',
            'category' => $cat);
        $posts =  get_posts($args);

        $added = false;
        foreach($posts as $post)
        {
            if(!$added)
            {
                if(!is_event_private($post->ID))
                {
                    if(!in_array($post->ID, $seen))
                    {
                        array_push($seen, $post->ID);
                        $cat_posts[$cat] = $post;
                        $added = true;
                        $count++;
                    }
                }
            }
        }
        
        if($count == $posts_per_page)
        {
            break;
        }
    }
    
    // $cat_posts = array_slice($cat_posts, 0, $posts_per_page);
    return $cat_posts;
}


/**
* 
* get the posts that have been viewed the most, including tribe_events
*
*/
function get_most_viewed_posts($posts=12)
{
    return  get_posts( 
        array( 'suppress_filters' => false,
               'post_type' => array('post'),
               'posts_per_page' => $posts, 
               'meta_key' => 'wpb_post_views_count', 
               'orderby' => 'meta_value_num', 
               'order' => 'DESC'  ) );
}

function get_most_viewed_events($posts=12)
{
    $posts =  get_posts( 
        array( 'suppress_filters' => false,
               'post_type' => array('tribe_events'),
               'posts_per_page' => $posts, 
               'meta_key' => 'wpb_post_views_count', 
               'orderby' => 'meta_value_num', 
               'order' => 'DESC'  ) );
    foreach($posts as $post)
    {
        $post->EventStartDate = get_post_meta($post->ID, '_EventStartDate', true);        
    }
    
    return $posts;
}

function get_most_viewed_events_in_category($cat_id, $posts=-1)
{
    // echo 'get_most_viewed_events_in_category';
    $tribe_events = get_posts( 
        array(
               // 'suppress_filters' => false,
               'post_type' => array('tribe_events'),
               'posts_per_page' => $posts,
               'meta_key' => 'wpb_post_views_count', 
               'orderby' => 'meta_value_num', 
               'order' => 'DESC'  
              ));
              
    $events = array();
    foreach($tribe_events as $event)
    {
        $cats = get_the_category($event);
        $cat_ids = array();
        foreach($cats as $cat) array_push($cat_ids, $cat->term_id);
        
        if(! is_event_private($event->ID) &&
           in_array($cat_id, $cat_ids))
        {
            array_push($events, $event);
        }
    }
    return $events;
}

/**
* 
* get the latest stories for the home page
*
*/
function get_latest_stories($count=4)
{
    $args = array(
	    'posts_per_page'   => $count,
        'offset'           => 0,
        'category'         => '',
        'category_name'    => '',
        'orderby'          => 'post_date',
        'order'            => 'DESC',
        'include'          => '',
        'exclude'          => '',
        'meta_key'         => '',
        'meta_value'       => '',
        'post_type'        => 'post',
        'post_mime_type'   => '',
        'post_parent'      => '',
        'post_status'      => 'publish',
        'suppress_filters' => false );
	
	return get_posts($args);
}

/**
*
* emails 
*
*/

function set_html_content_type() {

	return 'text/html';
}

function send_email($email_address, $title, $content)
{   
    
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $success = wp_mail($email_address, $title, $content, $headers);
    // $success = wp_mail('tcn-cc@jonhill.ca', $title, $content, $headers);
    return $success;
}

function send_all_emails($email, $event, $lang)
{
    send_validation_email($email, 'en');
    sleep(1);
    send_validation_email($email, 'fr');
    sleep(1);
    
    send_event_survey_email($email, $event, 'en');
    sleep(1);
    send_event_survey_email($email, $event, 'fr');
    sleep(1);
    
    send_password_reset_email($email, 'fake', 'fake', 'en');
    sleep(1);
    send_password_reset_email($email, 'fake','fake', 'fr');
    sleep(1);
    
    send_event_reminder_email($email, $event, 'en');
    sleep(1);
    send_event_reminder_email($email, $event, 'fr');
    sleep(1);
    
    send_event_1hr_reminder_email($email, $event, 'en');
    sleep(1);
    send_event_1hr_reminder_email($email, $event, 'fr');
    sleep(1);
    
    send_event_confirmation_email($email, $event, 'en');
    sleep(1);
    send_event_confirmation_email($email, $event, 'fr');
    sleep(1);
    send_new_password_email($email, 'en');  
    sleep(1);
    send_new_password_email($email, 'fr');  
    // send_mailchimp_email($email);
    // send_welcome_email($email);
    
    // check_events_and_send_emails();
}

function send_validation_email($email_address, $lang)
{   
    ob_start();
    include(locate_template('huddol_emails/' . $lang . '/' . $lang . '_confirm-membership.php'));
    $content = ob_get_contents();
    ob_end_clean();

    if($lang == 'en')
        $success = send_email($email_address, 'Huddol Events: Signup Confirmation', $content);
    else
        $success = send_email($email_address, 'Huddol Événements : Confirmation de votre inscription', $content);
    return $success;
}

function send_event_reminder_email($email_address, $event, $lang)
{
    global $sitepress;
    $sitepress->switch_lang($lang, true);

    ob_start();
    include(locate_template('huddol_emails/' . $lang . '/' . $lang . '_event-reminder.php'));
    $content = ob_get_contents();
    ob_end_clean();

    if($lang == 'en')
        $success = send_email($email_address, 'Huddol Events: Event Reminder', $content);
    else
        $success = send_email($email_address, 'Huddol Événements : Rappel événement', $content);

    return $success;
}

function send_password_reset_email($email_address, $user_login, $key, $lang)
{
    global $sitepress;
    $sitepress->switch_lang($lang, true);
    
    ob_start();
    include(locate_template('huddol_emails/' . $lang . '/' . $lang . '_password-reset.php'));
    $content = ob_get_contents();
    ob_end_clean();
    
    if($lang == 'en')
        $success = send_email($email_address, 'Huddol Events: Password Reset', $content);
    else
        $success = send_email($email_address, 'Huddol Événements : Réinitialisation du mot de passe', $content);
        
    return $success;
}

function send_event_survey_email($email_address, $event, $lang)
{
    ob_start();
    include(locate_template('huddol_emails/' . $lang . '/' . $lang . '_survey.php'));
    $content = ob_get_contents();
    ob_end_clean();
    
	//return false;
	
    if($lang == 'en')
        $success = send_email($email_address, 'Huddol Events: Event Survey', $content);
    else
        $success = send_email($email_address, 'Huddol Événements : Sondage événement', $content);
        
    return $success;
}

function send_welcome_email($email_address)
{
    $user = get_user_by( 'email', trim( $email_address ) );
    if(get_user_option('send_welcome_email', $user->ID))
    {
        echo 'already sent to: ';
        echo $email_address;
        echo '<br/>';
        return;
    }
    
    ob_start();
    include(locate_template('emails/en/welcome.php'));
    $content = ob_get_contents();
    ob_end_clean();
    
    $success = send_email($email_address, 'Bienvenue au Réseau aidant / Welcome to Huddol Events', $content);
    if($success)
    {
        echo 'Sent email to: ';
        echo $email_address;
        echo '<br/>';
        
        update_user_option( $user->ID, 'send_welcome_email', true);
    }
    else
    {
        echo 'Did not send email to: ';
        echo $email_address;
        echo '<br/>';
    }
    return $success;
}


function send_mailchimp_email($email_address)
{
    ob_start();
    include(locate_template('emails/en/mailchimp_email.php'));
    $content = ob_get_contents();
    ob_end_clean();
    
    echo '<pre>';
    echo $content;
    echo '</pre>';
    
    // $success = send_email($email_address, 'Vos préférences nous importent / Your Preferences Matter to Us', $content);
        
    return $success;
}

function send_new_password_email($email_address, $lang)
{
    
    $user = get_user_by( 'email', trim( $email_address ) );
    update_user_option( $user->ID, '_sent_new_password_email', false);
    if(get_user_option('_sent_new_password_email', $user->ID))
    {
        echo 'already sent to: ';
        echo $email_address;
        echo '<br/>';
        return;
    }
    
    $data = array('action' => 'forgot_email_password');
    $user_login = $email_address;
    global $wpdb, $current_site;

    if ( empty( $user_login) ) 
    {
        $data['error'] = __("You must provide an email or username.", 'tcn');
        $data['success'] = false;
    } 
    else if ( strpos( $user_login, '@' ) ) 
    {
        $user_data = get_user_by( 'email', trim( $user_login ) );
        if ( empty( $user_data ) )
        {
            $data['error'] = __("No user exists with that email or username. Please try again.", 'tcn');
            $data['success'] = false;
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
        print_r($data);
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
            print_r($data);
            return;
        }
        else if ( is_wp_error($allow) )
        {
            $data['error'] = __("You can't reset the password for that user.", 'tcn');
            $data['success'] = false;
            return_with_data($data);
            print_r($data);
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
    
    
        ob_start();
        include(locate_template('huddol_emails/' . $lang . '/' . $lang . '_new-password.php'));
        $content = ob_get_contents();
        ob_end_clean();


        if($lang == 'en')
            $success = send_email($email_address, 'Huddol Events: Important-Resetting Your Password', $content);
        else
            $success = send_email($email_address, 'Huddol Événements : Important-Réinitialiser votre mot de passe', $content);

        if($success)
        {
            echo 'Sent email to: ';
            echo $email_address;
            echo '<br/>';

            update_user_option( $user->ID, '_sent_new_password_email', true);
        }
        else
        {
            echo 'Did not send email to: ';
            echo $email_address;
            echo '<br/>';
        }
        return $success;
    }
}

function send_event_confirmation_email($email_address, $event, $lang)
{
    ob_start();
    include(locate_template('huddol_emails/' . $lang . '/' . $lang . '_confirm-registration.php'));
    $content = ob_get_contents();
    ob_end_clean();

    if($lang == 'en')
        $success = send_email($email_address, 'Huddol Events: Event Confirmation', $content);
    else
        $success = send_email($email_address, 'Huddol Événements : Confirmation de votre inscription', $content);

    return $success;
}

function send_event_reminder_emails($event, $lang)
{   
    $event_registration = new EventRegistration;
    $users = $event_registration->get_registrants($event->ID);
    foreach($users as $user)
    {
        if(is_object($user))
        {
            send_event_reminder_email($user->user_email, $event, $lang);
        }
        else
        {
            send_event_reminder_email($user, $event, $lang);
        }
    }
}

function send_event_1hr_reminder_emails($event, $lang)
{   
    $event_registration = new EventRegistration;
    $users = $event_registration->get_registrants($event->ID);
    foreach($users as $user)
    {
        if(is_object($user))
        {
            send_event_1hr_reminder_email($user->user_email, $event, $lang);
        }
        else
        {
            send_event_1hr_reminder_email($user, $event, $lang);
        }
        
        send_event_1hr_reminder_email('admin@lratcn.ca', $event, $lang);
    }
}

function send_event_1hr_reminder_email($email_address, $event, $lang)
{
    global $sitepress;
    $sitepress->switch_lang($lang, true);
    
    ob_start();
    include(locate_template('huddol_emails/' . $lang . '/' . $lang . '_event-reminder.php'));
    $content = ob_get_contents();
    ob_end_clean();
    
    if($lang == 'en')
        $success = send_email($email_address, 'Huddol Events: Your event is about to start ', $content);
    else
        $success = send_email($email_address, 'Huddol Événements : Votre événement est sur le point de commencer', $content);
        
    return $success;
}

function send_recording_ready_email($email_address, $event, $lang)
{
    global $sitepress;
    $sitepress->switch_lang($lang, true);

    ob_start();
    include(locate_template('huddol_emails/' . $lang . '/' . $lang . '_event-recording.php'));
    $content = ob_get_contents();
    ob_end_clean();

    if($lang == 'en')
        $success = send_email($email_address, 'Huddol Events: Event Recording Ready', $content);
    else
        $success = send_email($email_address, 'Huddol Événements', $content);

    return $success;
}

function send_recording_ready_emails($event, $lang)
{
    $event_registration = new EventRegistration;
    $users = $event_registration->get_registrants($event->ID);
    foreach($users as $user)
    {
        if(is_object($user))
        {
            send_recording_ready_email($user->user_email, $event, $lang);
        }
        else
        {
            send_recording_ready_email($user, $event, $lang);
        }
    }
}

function send_event_survey_emails($event, $lang)
{
    $event_registration = new EventRegistration;
    $users = $event_registration->get_registrants($event->ID);
    foreach($users as $user)
    {
        if(is_object($user))
        {
            send_event_survey_email($user->user_email, $event, $lang);
        }
        else
        {
            send_event_survey_email($user, $event, $lang);
        }
        send_event_survey_email('aronblack@bell.net', $event, $lang);
    }
}

$one_day = 24 * 60 * 60;
$one_hour = 60 * 60;

function check_events_and_send_emails() 
{	     
    global $one_day;
    
    $tribe_events = get_all_events_all_languages();
    
    $sent_message = '';
    $message = '';
    
    $message .= '<pre>';
    $now = time();
    $message .= date(DATE_RFC2822, $now);
    $message .= '<br />';
    
    foreach($tribe_events as $event)
    {        
        // update_post_meta($event->ID, 'sent_event_reminder', false);
        // update_post_meta($event->ID, 'sent_event_survey', false);
        
        // *****************************************************************
        // reminders
        // *****************************************************************
        
        $start_date = get_post_meta($event->ID, '_EventStartDate', true);
        
        $message .= "********************************************************************************\n";
        $message .= $event->post_title;
        $message .= '<br />';
        $message .= $start_date;
        $message .= '<br />';
        
        $message .= 'Checking Event Reminders<br/>';
                
        $start_time = strtotime($start_date);
        $message .= 'start_time: ' . $start_time;
        $message .= '<br/>';
        $message .= 'now: ' . $now;
        $message .= '<br/>';
        $message .= 'difference: ' . ($start_time - $now);
        $message .= '<br/>';
        
        if($start_time - $now < $one_day && $start_time > $now){
			
            $reminded = get_post_meta($event->ID, 'sent_event_reminder', true);
            
            $message .= 'This event is coming up. Do we send reminders?';
            $message .= 'Remind: ';
            $message .= $event->post_title;
            $message .= '<br>';
            $message .= $reminded ? "Reminders already sent" : "Reminders not already sent";
            $message .= '<br>';

          if(!$reminded)
          {
            $lang = wpml_get_language_information($event->ID);
            if(substr($lang['locale'], 0, 2) == 'en')
            {
              send_event_reminder_emails($event, 'en');
              $message .= 'sending reminder in english<br/>';
              $sent_message .= 'sending reminder in english for ' . $event->post_title . '<br/>';
            }
            else
            {
              send_event_reminder_emails($event, 'fr');
              $message .= 'sending reminder in french<br/>';
              $sent_message .= 'sending reminder in french for ' . $event->post_title . '<br/>';

            }

            update_post_meta($event->ID, 'sent_event_reminder', true);
            $message .= '<br>';
            $message .= get_post_meta($event->ID, 'sent_event_reminder', true) ? "marked as sent" : "not marked as sent";
          }
        }
        else
        {
          $message .= 'This event is in the past or is too far in the future.<br/>';
        }
        
        // *****************************************************************
        // SURVEYS
        // *****************************************************************
        
        $message .= 'Checking Event surveys<br/>';
        $interval = get_post_meta($event->ID, 'post_surveys_interval', true);
        
        $message .= $start_time;
        $message .= '<br/>';
        $message .= $now;
        $message .= '<br/>';
        
        if($interval != '')
            $interval = $one_day * $interval;
        else
            $interval = $one_day * 7;
            
        $message .= $start_time + $interval;
        $message .= '<br/>';
            
        if($now > $start_time + $interval)
        {
            $surveyed = get_post_meta($event->ID, 'sent_event_survey', true);
            
            $message .= 'Over: ';
            $message .= $event->post_title;
            $message .= '<br>';
            $message .= $surveyed ? "Surveys already sent" : "Surveys not already sent";
            $message .= '<br>';
            
            if(!$surveyed && get_post_meta($event->ID, 'post_surveys_survey', true) != '')
            {
                $lang = wpml_get_language_information($event->ID);
                if(substr($lang['locale'], 0, 2) == 'en')
                {
                    send_event_survey_emails($event, 'en');
                    $message .= 'sending survey in english<br/>';
                    $sent_message .= 'sending survey in english for ' . $event->post_title . '<br/>';
                    
                }
                else
                {
                    send_event_survey_emails($event, 'fr');
                    $message .= 'sending survey in french<br/>';
                    $sent_message .= 'sending survey in french for ' . $event->post_title . '<br/>';
                }
                
                update_post_meta($event->ID, 'sent_event_survey', true);
                $message .= '<br>';
                $message .= get_post_meta($event->ID, 'sent_event_survey', true) ? "marked as sent" : "not marked as sent";
            }
            else
            {
                $message .= 'Not sending survey. It has already been sent or there is no survey attached.';
            }
        }
        else
        {
             $message .= 'Not sending survey. Not enough time has passed. There might be a survey attached, though.';
        }
        
        $message .= '<br />';
        $message .= '<br />';
    }      

    $message .= '</pre>';  
    
    echo $message;
    
    global $wpdb;
    $results = $wpdb->get_results("INSERT INTO wp_tcn_cron_runs () VALUES ()");
    echo send_email('aronblack@bell.net', 'Huddol Events: Surveys and Reminders Summary', $message);
    echo send_email('aronblack@bell.net', 'Huddol Events: Surveys and Reminders Actually Sent', $sent_message);
    echo '<br/>';
}


/*  #interest function : Moved for better accessibility during development */
function mytheme_enqueue_scripts()
{
	if(!is_admin())
	{
		
		wp_register_script('recaptcha_load', dirname( get_bloginfo('stylesheet_url') ).'/scripts/recaptcha.load.js');
		wp_enqueue_script('recaptcha_load');
		
		/*  Added Ajax request script */
		wp_register_script('interest_update', dirname( get_bloginfo('stylesheet_url') ).'/scripts/ajax.interest-update.js');
		wp_enqueue_script('interest_update');

		/* Added Ajax front-end support */
		wp_localize_script('interest_update', 'MyAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ), //url for php file that process ajax request to WP
          'postCommentNonce' => wp_create_nonce( 'myajax-post-comment-nonce' ), // this is a unique token to prevent form hijacking
            												 ));
	}
}
add_action('wp_enqueue_scripts', 'mytheme_enqueue_scripts');
add_filter('script_loader_tag', 'add_defer_attribute', 10, 2);

function print_array($array, $str = ''){
	echo '<pre class="debug">'.$str.' : '.count($array). 'results<br >';
	print_r($array);
	echo '</pre>'."\n";
}

/* #interest function */
//function to check if the user has subscribed to a channel
function check_is_user_category_subscribed($category_id = 0 ){
	
	global $current_user ;
	$network = new TCNNetwork;
	
	if(!is_array($current_user)){
		$current_user = wp_get_current_user();  // identify the user
	}

    $cats = $network->get_subscribed_categories($current_user); // get the categories
	$cats_subscription = array();
	$is_suscribed = false;
	
	// go through tthe categories and store the IDs in an array
	foreach($cats as $cat)
	{
		$cats_subscription[] = $cat->term_id;
	}
	
	// check if the current category's ID is in the subscription ID array
	if(in_array($category_id, $cats_subscription ) )
	{
		$is_suscribed = true;
	}
	
	return $is_suscribed;
}

/* this #interest function shows a register to channel button to users if not already regisetered */
function print_category_reminder($category_id = 0){

	global $current_user ;
	$network = new TCNNetwork;
	$category = get_category($category_id);
	
	if(!is_array($current_user)){
		$current_user = wp_get_current_user();  // identify the user
	}
	
	$is_suscribed = check_is_user_category_subscribed($category_id);
	$link = get_permalink( get_page_by_path( ICL_LANGUAGE_CODE == 'en' ? 'account-dashboard' : 'mon-compte' ) );

	$str = '<div id="category_reminder">';
	$str.= '<form id="category_reminder_form" class="dashboard-profile" method="POST" action="#">';

	$str.= '<div class="box_holder">';
	$str.= '<input type="text" name="tnc_user_profile" value="'.$current_user->ID.'" />';
	$str.= '<ul class="options">';

	$args = array(
	  'orderby' => 'name',
	  'order' => 'ASC',
	  'hide_empty' => 0,
	  'exclude' => "1,2"
	 );

	$categories = get_categories($args);

	foreach($categories as $category) 
	{ 
	   //print_array(array($current_user, $category->term_id));
	   $check_this = $network->is_user_category_subscribed($current_user, $category->term_id );
	   if(empty($check_this) &&$category->term_id == $category_id ){
		   $check_this = 'checked="checked"';
	   }
	   
		$str.= '<li><label><input '. ($check_this) . ' type="checkbox" name="tcn_network_category[]" value="'. $category->term_id . '" /><span>' . $category->name . '</span></label></li>';
	} 

    $str.= '</ul>';
	$str.= '</div>';
	

	$str.= '<input type="submit" class="g1-button g1-button--small g1-button--solid g1-button--standard ajax_update_profile" value="'.(ICL_LANGUAGE_CODE == 'en' ? 'I want to subscribe' : 'Je veux m\'inscrire'). '">';
	$str.= (ICL_LANGUAGE_CODE == 'en' ? ' to this channel to learn more.' : 'à cette chaîne pour en apprendre plus.' );
	$str.= (ICL_LANGUAGE_CODE == 'en' ? ' Go to ' : 'Cliquez sur ');
	$str.= '<a href="'.$link.'?mode=profile_edit">'.(ICL_LANGUAGE_CODE == 'en' ? 'My Account' : 'Mon Compte' ).'</a> ';
	$str.= (ICL_LANGUAGE_CODE == 'en' ? 'and share with us other topics that interest you. ' : 'et faites-nous connaître les autres sujets qui vous intéressent.');	
	$str.= '</form>';
	$str.= '</div>';
	
	echo ( !check_is_user_category_subscribed($category_id) ? $str : '');
	
}

/* This #interest function handles the Ajax request to register to a channel */
function update_user_categories(){

	$nonce = $_REQUEST['postCommentNonce'];
	
	// check to see if the submitted nonce matches with the
	// generated nonce we created earlier
	if ( ! wp_verify_nonce( $nonce, 'myajax-post-comment-nonce' ) )
	{
		die ( 'Busted!');
	}
	
	$success = false;
	$str = '';
	$link = get_permalink( get_page_by_path( ICL_LANGUAGE_CODE == 'en' ? 'account-dashboard' : 'mon-compte' ) );
	$data = array();
	parse_str($_REQUEST['formData'], $data);

	// if success, show link to profile page 
	if(update_user_meta($data['tnc_user_profile'], 'tcn_network_categories', $data['tcn_network_category']))
	{
		$success = true;
		
		$str.= (ICL_LANGUAGE_CODE == 'en' ? 'Thank you! You are now registered to this channel. ' : 'Merci! Vous êtes inscrits à cette chaîne. ' );
		$str.= (ICL_LANGUAGE_CODE == 'en' ? ' Go to ' : 'Cliquez sur ');
		$str.= '<a href="'.$link.'?mode=profile_edit">'.(ICL_LANGUAGE_CODE == 'en' ? 'My Account' : 'Mon Compte' ).'</a> ';
		$str.= (ICL_LANGUAGE_CODE == 'en' ? 'and share with us other topics that interest you. ' : 'et faites-nous connaître les autres sujets qui vous intéressent.');	
	}
	// else give error message.
	else{
		$str.= ( ICL_LANGUAGE_CODE == 'en' ? 'You are already subscribed to this channel.' : 'Vous êtes déjà abonné à cette chaîne.' );
	}
	echo $str;
	
	exit;
}

//add_action( 'wp_ajax_nopriv_save_categorie_form', 'update_user_categories' );
add_action( 'wp_ajax_update_user_categories', 'update_user_categories' );

function add_theme_caps() {
    // gets the author role
    $role = get_role( 'network_partner' );
 
    // This only works, because it accesses the class instance.
    // would allow the author to edit others' posts for current theme only
    $role->add_cap( 'tcn_add_phone_number_only' );
}
add_action( 'admin_init', 'add_theme_caps');

function add_theme_caps_admin() {
    // gets the author role
    $role = get_role( 'administrator' );
 
    // This only works, because it accesses the class instance.
    // would allow the author to edit others' posts for current theme only
    $role->add_cap( 'tcn_add_phone_number_only' );
}
add_action( 'admin_init', 'add_theme_caps_admin');

function tcn_first_category($post) 
{
    
    $cats = get_the_category($post);
    return $cats[0]->name;
}

function tcn_capture_entry_categories($post) 
{
    $taxonomy_objects = get_object_taxonomies( $post, 'objects' );

    // Remove non-public and hierarchical taxonomies
    foreach ( $taxonomy_objects as $name => $object ) {
        if ( !$object->query_var || !$object->hierarchical ) {
            unset( $taxonomy_objects[$name] );
        }
    }

    $count = count( $taxonomy_objects ) ;

    $out = '';
    foreach ( $taxonomy_objects as $object ) 
    {
        $out .= get_the_cat_list( $post->ID, $object->name, '', ', ', '' );
    }

    return $out;
}


function get_the_cat_list( $id, $taxonomy, $before = '', $sep = '', $after = '' ) {
	$terms = get_the_terms( $id, $taxonomy );

	if ( is_wp_error( $terms ) )
		return $terms;

	if ( empty( $terms ) )
		return false;

	foreach ( $terms as $term ) {
		$link = get_term_link( $term, $taxonomy );
		if ( is_wp_error( $link ) )
			return $link;
		$term_links[] = $term->name;
	}

	$term_links = apply_filters( "term_links-$taxonomy", $term_links );

	return $before . join( $sep, $term_links ) . $after;
}