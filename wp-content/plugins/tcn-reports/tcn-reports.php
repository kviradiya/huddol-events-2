<?php
/**
 * Plugin Name: TCN: Reports
 * Author: Jon Hill
 * Description: Stats reports
 * Version: Only and forever
 */
// Hook for adding admin menus
add_action('admin_menu', 'tcn_report_add_pages');

function tcn_report_add_pages() 
{
     // Add a new submenu under Tools:
    add_management_page( __('TCN User Reports'), __('TCN User Reports','menu-test'), 'manage_options', 'tcn_users', 'tcn_render_users_table');
    add_management_page( __('TCN User Stats Reports'), __('TCN User Stats Reports','menu-test'), 'manage_options', 'tcn_user_stats', 'tcn_render_user_stats_table');
    add_management_page( __('TCN Network Partners Reports'), __('TCN Network Partners Reports','menu-test'), 'manage_options', 'tcn_network_partners', 'tcn_render_partner_stats');
    add_management_page( __('TCN Network Partners: Upcoming and Recorded Events'), __('TCN Network Partners: Upcoming and Recorded Events','menu-test'), 'manage_options', 'tcn_np_events', 'tcn_np_events');
    add_management_page( __('TCN Event Inspector'), __('TCN Event Inspector','menu-test'), 'manage_options', 'tcn_event_inspector', 'tcn_event_inspector');    
    add_management_page( __('TCN Add Phone Number Only Registrant'), __('TCN Add Phone Number Only Registrant','menu-test'), 'tcn_add_phone_number_only', 'tcn_add_phone_number_only', 'tcn_add_phone_number_only');
    add_management_page( __('TCN Emails'), __('TCN Emails','menu-test'), 'manage_options', 'tcn_emails', 'tcn_emails');
    // add_management_page( __('TCN Migrate Old Users'), __('TCN Migrate-Old-Users','menu-test'), 'manage_options', 'tcn_migrate_users', 'tcn_migrate_users');
    // add_management_page( __('TCN Migrate Registrations'), __('TCN Migrate Registrations','menu-test'), 'manage_options', 'tcn_migrate_reg', 'tcn_migrate_reg');
    add_management_page( __('TCN Partner Page Links'), __('TCN Partner Page Links','menu-test'), 'manage_options', 'tcn_partner_page_links', 'tcn_partner_page_links');
    // add_management_page( __('TCN Cron Jobs'), __('TCN Cron Jobs','menu-test'), 'manage_options', 'tcn_cron_job_runs', 'tcn_cron_job_runs');
    add_management_page( __('TCN Send Mailchimp Email'), __('TCN Send Mailchimp Email','menu-test'), 'manage_options', 'tcn_mailchimp_email', 'tcn_mailchimp_email');
    // add_management_page( __('TCN Send Welcome Email'), __('TCN Send Welcome Email','menu-test'), 'manage_options', 'tcn_welcome_email', 'tcn_welcome_email');
    add_management_page( __('TCN Send New Password Email'), __('TCN Send New Password Email','menu-test'), 'manage_options', 'tcn_new_password_email', 'tcn_new_password_email');
    add_management_page( __('TCN Channels: All/Upcoming/Most Viewed Inspector'), __('TCN Channels: All/Upcoming/Most Viewed Inspector','menu-test'), 'manage_options', 'tcn_channel_inspector', 'tcn_channel_inspector');
    add_management_page( __('TCN Event Registrations: Add User By Email'), __('TCN Event Registrations: Add User By Email','menu-test'), 'manage_options', 'tcn_register_user', 'tcn_register_user');
    add_management_page( __('TCN Migrate Provinces'), __('TCN Migrate Provinces','menu-test'), 'manage_options', 'tcn_migrate_provinces', 'tcn_migrate_provinces');
    add_management_page( __('TCN Migrate Roles'), __('TCN Migrate Roles','menu-test'), 'manage_options', 'tcn_migrate_roles', 'tcn_migrate_roles');
    // add_management_page( __('TCN Network Inspector'), __('TCN Network Inspector','menu-test'), 'manage_options', 'tcn_network_inspector', 'tcn_network_inspector');
    // add_management_page( __('TCN French->English Event Association'), __('TCN French->English Event Association','menu-test'), 'manage_options', 'tcn_bilingual_events', 'tcn_bilingual_events');
}


function tcn_migrate_roles()
{
    global $wpdb;
    $users = $wpdb->get_results("SELECT * FROM member");

    echo '<pre>';
    
    foreach($users as $user)
    {
        echo $user->email . '<br/>';
        if($user->email != '')
        {
            $wpu = get_user_by_email( $user->email );
            $role = array($user->type);
            if($wpu)
            {
                print_r($role);
                echo '<br/>';
                echo $wpu->ID;
                update_user_meta($wpu->ID, 'tcn_user_meta_caregiver_role', $role);
            }
            else
            {
                echo 'No user with that email';
            }
        }
    }
    echo '</pre>';
        
    print '<br/><br/>';
    
}

$new_provmap = array(
    11 => 10,
    2 => 1,
    9 => 8,
    4 => 3,
    5 => 3,
    7 => 5,
    1 => 0,
    12 => 11,
    3 => 2,
    13 => 12,
    10 => 9
    
);

function tcn_migrate_provinces()
{
    global $new_provmap;
    global $wpdb;
    $users = $wpdb->get_results("SELECT * FROM member");

    echo '<pre>';
    
    foreach($users as $user)
    {
        if($user->email != '')
        {
            $wpu = get_user_by_email( $user->email );
            update_user_meta($wpu->ID, 'tcn_user_meta_province', $new_provmap[$user->id_province]);
            update_user_meta($wpu->ID, 'tcn_user_meta_phone', $user->phone);
            update_user_meta($wpu->ID, 'tcn_user_meta_city', $user->city);
            
            echo $user->email; echo ': '; echo get_province_name($new_provmap[$user->id_province]);
            echo '<br/>';
        }
    }
    echo '</pre>';
        
    print '<br/><br/>';
}

function tcn_bilingual_events()
{

    $all_events = get_posts(
        array(
            "post_type" => "tribe_events",
            "suppress_filters" => "true",
            "posts_per_page" => -1
        )
    );
    
    echo '<ul>';
    foreach($all_events as $event)
    {
        echo '<li>';
        echo $event->post_title;
        
        global $sitepress;
        $translation = $sitepress->get_element_translations($event->ID);
        print_r($translation);
        
        echo '</li>';
    }
    echo '</ul>';
}

function tcn_np_events()
{
    $event_registration = new EventRegistration;
    $users = get_network_partners();
    foreach($users as $user): ?>
        <a target="_blank" href="<?php echo site_url(); ?>/author/<?php echo $user->user_nicename; ?>/"><?php echo get_partner_name($user->ID); ?></a>
    <?php
    $all = get_network_partner_events($user->ID);
    $up = get_network_partner_upcoming_events($user->ID);
    $rec = get_network_partner_recorded_events($user->ID);
    
    echo '<br/>';
    
    echo 'Upcoming: ';
    echo count($up);
    echo '<br/>';
    
    echo 'Recorded: ';
    echo count($rec);
    echo '<br/>';
    
    echo 'All: ';
    echo count($all);
    echo '<br/>';    
    echo '<hr />';
    endforeach;
}

function tcn_channel_inspector()
{
    /*
    echo '<h1>ALL EVENTS</h1>';
    $all = get_all_events();

    echo '<ul>';
    foreach($all as $e)
    {
        echo '<li>';
        echo $e->post_title;
        echo ' ';
        echo get_event_date($e);
        echo '</li>';
    }
    echo '</ul>';
    */
    
    $categories = get_categories();
    foreach($categories as $cat)
    {
        echo '<h2>' . $cat->cat_name . '</h2>';
        
        echo '<h3>All</h3>';
        $all = get_all_events_in_category($cat->term_id);

        echo '<ul>';
        foreach($all as $e)
        {
            echo '<li>';
            echo $e->post_title;
            echo ' ';
            echo get_event_date($e);
            echo '</li>';
        }
        echo '</ul>';
        
        echo '<h3>Most</h3>';
        $most = get_most_viewed_events_in_category($cat->term_id);
        
        echo '<ul>';
        foreach($most as $e)
        {
            echo '<li>';
            echo $e->post_title;
            echo ' ';
            echo get_event_date($e);
            echo ' ( ';
            echo get_post_meta($e->ID, 'wpb_post_views_count', true);
            echo ' hits)';
            echo '</li>';
        }
        echo '</ul>';
        
        echo '<h3>Upcoming</h3>';
        $upcoming = get_upcoming_events_in_category($cat->term_id);
        echo '<ul>';
        foreach($upcoming as $e)
        {
            echo '<li>';
            echo $e->post_title;
            echo ' ';
            echo get_event_date($e);
            echo '</li>';
        }
        echo '</ul>';
                echo '<hr/>';
    }
}

// mt_tools_page() displays the page content for the Test Tools submenu
function tcn_report_tools_page() {
    echo "<h1>TCN Reports</h1>";
    render_users_table();
    render_user_stats_table();   
}

function tcn_welcome_email()
{
    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $args = array("role" => "subscriber");
        $users = get_users( $args );
        foreach($users as $user)
        {
            send_welcome_email($user->user_email);
        }
    }     
    ?>

    <form method="POST">
        This will send the "welcome" (bilingual) email to all of our users. They are listed below. (Will send to admin only right now)
        <input type="submit" value="You Sure?" />
    </form>
    
<ul>
    <?php
        $args = array("role" => "subscriber");
        $users = get_users( $args );
        foreach($users as $user)
        {
            echo '<li>' . $user->user_email . '</li>';
        }
    ?>
</ul>
    <?php
}

function tcn_mailchimp_email()
{
    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        echo 'sending to admin only as a test';
        send_mailchimp_email('admin+tcn@jonhill.ca');
    }     
    ?>

    <form method="POST">
        This will send the "mailchimp" (bilingual) email to all of our users. They are listed below. (Will send to admin only right now)
        <input type="submit" value="You Sure?" />
    </form>
    
<ul>
    <?php
        $args = array("role" => "subscriber");
        $users = get_users( $args );
        foreach($users as $user)
        {
            echo '<li>' . $user->user_email . '</li>';
        }
    ?>
</ul>
    <?php
}

function tcn_new_password_email()
{
    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $args = array("role" => "subscriber");
        $users = get_users( $args );
        foreach($users as $user)
        {
            send_new_password_email($user->user_email);
        }
    }     
    ?>

    <form method="POST">
        This will send the "new password" (bilingual) email to all of our users. They are listed below. (Will send to admin only right now)
        <input type="submit" value="You Sure?" />
    </form>
    
<ul>
    <?php
        $args = array("role" => "subscriber");
        $users = get_users( $args );
        foreach($users as $user)
        {
            echo '<li>' . $user->user_email . '</li>';
        }
    ?>
</ul>
    <?php
}

function tcn_cron_job_runs()
{
    global $wpdb;
    $runs = $wpdb->get_results("SELECT * FROM wp_tcn_cron_runs");
    echo '<pre>';
    print_r($runs);
    echo '</pre>';
}

function tcn_partner_page_links()
{
    $users = get_network_partners();
    echo '<ul>';
    foreach($users as $user): ?>
        <li><a href="<?php echo get_author_posts_url($user->ID); ?>"><?php echo get_partner_name($user->ID); ?></li>
    <?php
    echo '</ul>';
    endforeach;
}

function tcn_register_user()
{
    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $er = new EventRegistration;
        $user_data = get_user_by( 'email', trim( $_POST['email'] ) );
        if($user_data)
        {
            $er->register_user($_POST['event_id'], $user_data->ID);
            echo '<p>Successful</p>';
        }
        else
        {
            echo 'No such user';
        }
        ?>
        <p>
            <a href="<?php echo site_url(); ?>/wp-admin/tools.php?page=tcn_event_inspector&event_id=<?php echo $_POST['event_id']; ?>">Inspect Event</a>
        </p>
        <?php
    }
    echo '<h1>Add A User</h1>';

    $events = get_pure_upcoming_events(); // get_posts(array("post_type"=>"tribe_events", "posts_per_page"=>-1, "orderby"=>"title", "order"=>"ASC"));
    

    ?>
    <form method="POST">
        Email:<br/>
        <input type="text" name="email" /><br/>
        
        Event:<br/>
        <select name="event_id">
            <?php foreach($events as $event): ?>
                <option value="<?php echo $event->ID; ?>"><?php echo $event->post_title; ?></option>
            <?php endforeach ?>
        </select><br/><br/>
        <input type="submit" value="Add User" />
    </form>
    <?php
}
    
function tcn_network_inspector()
{
    $users = get_users(array("role"=>"subscriber"));
    array_push($users, wp_get_current_user());
    $network = new TCNNetwork;
    
    ?>
    <h2>Network Inspector</h2>
    <table>
        <tr>
            <th>
                User
            </th>
            <th>
                Channels
            </th>
            <th>
                Upcoming Events
            </th>
        </tr>
        
        <?php foreach($users as $user): ?>
        <?php $cats = $network->get_subscribed_categories($user); ?>
        <?php $events = $network->get_upcoming_network_events($user); ?>
        <tr>
            <td>
                <?= $user->display_name ?>
            </td>
            <td>
                <ul>
                    <?php foreach($cats as $cat): ?>
                        <li><?= $cat->cat_name; ?></li>
                    <?php endforeach ?>
                </ul>
            </td>
            <td>
                <?php foreach($events as $event): ?>
                    <li><?= $event->post_title; ?> <br/> <?= substr(get_post_meta($event->ID, '_EventStartDate', true), 0, 16); ?></li>
                <?php endforeach ?>
            </td>
        </tr>    
        <tr><td colspan="3"><hr/></td></tr>
            
        <?php endforeach ?>
    </table>
<?php
}

function tcn_add_phone_number_only()
{
    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $er = new EventRegistration;
        $er->register_phone_number_user($_POST['event_id'], $_POST['phone_number'], $_POST['phone_number_name']);
        echo '<p>Successful</p>';
        ?>
       <!-- <p>
            <a href="<?php //echo site_url(); ?>/wp-admin/tools.php?page=tcn_event_inspector&event_id=<?php //echo $_POST['event_id']; ?>">Inspect Event</a>
        </p> -->
        <?php
    }
    echo '<h1>Add A User</h1>';
    $events = $events = get_pure_upcoming_events();
    // $events = get_posts(array("post_type"=>"tribe_events", "posts_per_page"=>-1, "orderby"=>"title", "order"=>"ASC"));
    // echo '<pre>';
    // print_r($events);
    // echo '</pre>';

    ?>
    <form method="POST">
        Phone Number:<br/>
        <input type="text" name="phone_number" /><br/>

        Name of Contact:<br/>
        <input type="text" name="phone_number_name" /><br/>
        
        Event:<br/>
        <select name="event_id">
            <?php foreach($events as $event): ?>
                <option value="<?php echo $event->ID; ?>"><?php echo $event->post_title; ?></option>
            <?php endforeach ?>
        </select><br/><br/>
        <input type="submit" value="Add User" />
    </form>

<?php
}

function compare_subscriber_name_asc($a, $b) 
{
    return strcmp($a->display_name, $b->display_name);
}

function compare_subscriber_email_asc($a, $b) 
{
    return strcmp($a->email, $b->email);
}

function compare_subscriber_num_workshops_asc($a, $b) 
{
    $er = new EventRegistration;
    $a_count = $er->get_user_event_count($a->ID);
    $b_count = $er->get_user_event_count($b->ID);

    return strcmp($a_count, $b_count);
}

function compare_subscriber_date_asc($a, $b) 
{
    return strcmp($a->user_registered, $b->user_registered);
}

function compare_subscriber_province_asc($a, $b) 
{
    return strcmp(
            get_province_name(get_user_meta($a->ID, 'tcn_user_meta_province', true)), 
            get_province_name(get_user_meta($b->ID, 'tcn_user_meta_province', true)));
}

function tcn_event_inspector()
{
    $er = new EventRegistration;
    
    if(isset($_GET['event_id']))
    {
        $event = get_post($_GET['event_id']);
        echo '<pre>';
        // print_r($event);
        echo '</pre>';
        
        $registrants = $er->get_registrations($event->ID);
        ?>
        <p>
            <h1><?php echo $event->post_title; ?></h1>
            <p>
                Start: <?php echo get_post_meta($event->ID, '_EventStartDate', true); ?>
            </p>
            <p>
                End: <?php echo get_post_meta($event->ID, '_EventEndDate', true); ?>
            </p>

        </p>
        <p>
            <a target="_blank" href="<?php echo site_url(); ?>/wp-admin/post.php?post=<?php echo $event->ID; ?>&action=edit&lang=en">(edit)</a><br/>
        </p>
        
        <h2>Info</h2>
        <p>
            Is Event Location Restricted: <?php echo $er->is_event_province_restricted($event->ID) ? "yes" : "no"; ?><br/>
            Event Location String: <?php echo $er->get_location_restriction_string($event->ID); ?><br/>
            
            <?php for($i = 0; $i < get_province_count(); $i++ ): ?>
                Available in <?php echo get_province_name($i) ?>: <?php echo $er->is_event_available_in_province($event->ID, $i) ? 'yes' : 'no'; ?><br/>
            <?php endfor ?>
            
        </p>
        <p>
            Available Outside of Canada: <?php echo $er->is_event_available_outside_of_canada($event->ID) ? 'yes' : 'no'; ?><br/>
        </p>
                   
        <p>
            Is Content Role Intended: <?php echo $er->is_event_role_intended($event->ID) ? "yes" : "no"; ?><br/>
            Event Role String: <?php echo $er->get_role_intention_string($event->ID); ?><br/>
        </p>
        
        <p>
            Is Event Full: <?php echo $er->is_event_full($event->ID) ? "yes" : "no"; ?><br/>
            Max Registrants: <?php echo $er->get_max_registrants($event->ID); ?><br/>
        </p>
        
        <?php if($er->has_paypal_button($event->ID)): ?>
            This event has a paypal button.
            Price <?php echo $er->get_event_price($event->ID); ?><br/>
            <?php echo $er->get_paypal_button($event->ID); ?><br/>
        <?php else: ?>
            This event has no paypal button.
        <?php endif ?>
        <hr />
        
        <h2>Registrants</h2>
        <table>
            <tr>
                <th>
                   Name
                </th>
                <th>
                   Call Them To Inform?
                </th>
                <th>
                    Contact Name for Phone Call?
                </th>
            </tr>
            
            <?php foreach($registrants as $r): ?>
                <tr>
                    <td>
                        <?php if($r->user_id == 0): ?>
                            Call This User
                        <?php else: ?>
                            <?php $event_user = get_userdata($r->user_id); echo $event_user->display_name; ?>
                        <?php endif ?>
                    </td>
                    <td>
                            <?php echo $r->phone_number; ?>
                    </td>
                    <td>
                            <?php echo $r->phone_number_name; ?>
                    </td>

                </tr>
            <?php endforeach ?>
        </table>
        
        <h2>What About Every Subscriber? Could They Register?</h2>
        <?php if($er->is_event_full($event->ID)): ?>
            <p>No one can register, the event is full.</p>
        <?php endif ?>
        <?php if(is_event_over($event->ID)): ?>
            <p>No one can register, the event is over.</p>
        <?php endif ?>

        <table>
            <tr>
                <th>
                   Name
                </th>
                <th>
                    Province
                </th>
                <th>
                   Is User Registered?
                </th>
                <th>
                   Can User Register
                </th>
                <th>
                   Can User Province Register
                </th>
            </tr>
            
            <?php $subs = get_users(array("role"=>"subscriber")); ?>
            
            <?php foreach($subs as $sub): ?>
                <tr>
                    <td>
                        <?php echo $sub->display_name; ?>
                    </td>
                    <td>
                        <?php echo get_user_province($sub->ID); ?>
                    </td>
                    <td>
                        <?php echo $er->is_user_registered($event->ID, $sub->ID) ? 'yes' : 'no' ?>
                    </td>
                    <td>
                        <?php echo $er->can_user_register($event->ID, $sub->ID) ? 'yes' : 'no' ?>
                    </td>
                    <td>
                        <?php echo $er->can_user_province_register($event->ID, $sub->ID) ? 'yes' : 'no' ?>
                    </td>
                </tr>
            <?php endforeach ?>
        </table>
    <?php
    }
    else
    {
        $events = get_posts(array("post_type" => "tribe_events", "posts_per_page" => -1));
        tcn_render_events_table($events);
    }
}

function tcn_render_users_table()
{
    $args = array();
    $users = get_users( array("role" => "subscriber" ));
    $event_registration = new EventRegistration;
    $network = new TCNNetwork;
    $url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
    
    $caregiver_role = -1;
    if(isset($_GET['caregiver_role']))
    {
        $caregiver_role = $_GET['caregiver_role'];
    }
    
    $page_number = 0;
    if(isset($_GET['page_number']))
    {
        $page_number = $_GET['page_number'];
    }
    
    $sort_col = 'name';
    if(isset($_GET['sort_col']))
    {
        $sort_col = $_GET['sort_col'];
    }
    
    $total_users = count($users);
    if($sort_col == 'name')
    {
        usort($users, "compare_subscriber_name_asc");
    }
    else if($sort_col == 'email')
    {
        usort($users, "compare_subscriber_email_asc");
    }
    else if($sort_col == 'email')
    {
        usort($users, "compare_subscriber_email_asc");
    }
    else if($sort_col == 'num_workshops')
    {
        usort($users, "compare_subscriber_num_workshops_asc");
    }
    else if($sort_col == 'date')
    {
        usort($users, "compare_subscriber_date_asc");
    }
    else if($sort_col == 'province')
    {
        usort($users, "compare_subscriber_province_asc");
    }
    $users = array_slice($users, $page_number * 25, $page_number + 25);
    
    ?>
    
    <h2> Users </h2>
    <a target="_blank" href="<?php site_url() ?>/tcn-handler-event_registration_cvs/?action=users_table&offset=0&count=2000">Download Users 0-2000 as CSV</a> -
    <a target="_blank" href="<?php site_url() ?>/tcn-handler-event_registration_cvs/?action=users_table&offset=2000&count=2000">Download Users 2000-4000 as CSV</a> -
    <a target="_blank" href="<?php site_url() ?>/tcn-handler-event_registration_cvs/?action=users_table&offset=4000&count=2000">Download Users 4000-6000 as CSV</a> -
    <a target="_blank" href="<?php site_url() ?>/tcn-handler-event_registration_cvs/?action=users_table&offset=6000&count=2000">Download Users 6000-8000 as CSV</a> -
    <a target="_blank" href="<?php site_url() ?>/tcn-handler-event_registration_cvs/?action=users_table&offset=8000&count=2000">Download Users 8000+ as CSV</a>
    
    <p>
        <?php if($page_number != 0 ): ?>
            <a href="<?php echo $url; ?>?page=tcn_users&caregiver_role=<?php echo $caregiver_role; ?>&page_number=<?php echo $page_number -1; ?>&sort_col=<?php echo $sort_col; ?>">
                Previous
            </a>
        <?php endif ?>
        
        <?php echo ($page_number * 25); ?> to <?php echo ( $page_number + 1 ) * 25; ?> of <?php echo $total_users; ?>
        
        <a href="<?php echo $url; ?>?page=tcn_users&caregiver_role=<?php echo $caregiver_role; ?>&page_number=<?php echo $page_number + 1; ?>&sort_col=<?php echo $sort_col; ?>">
            Next
        </a>
    </p>

    <table>
        <tr>
            <th>
                <a href="<?php echo $url; ?>?page=tcn_users&caregiver_role=<?php echo $caregiver_role; ?>&page_number=<?php echo $page_number ?>&sort_col=name">
                    Name
                </a>
            </th>
        
            <th>
                <a href="<?php echo $url; ?>?page=tcn_users&caregiver_role=<?php echo $caregiver_role; ?>&page_number=<?php echo $page_number ?>&sort_col=email">
                    Email
                </a>
            </th>
        
            <th>
                <a href="<?php echo $url; ?>?page=tcn_users&caregiver_role=<?php echo $caregiver_role; ?>&page_number=<?php echo $page_number ?>&sort_col=num_workshops">
                    Number of Workshops
                </a>
            </th>
        
            <th>
                    TCN Roles
                
            </th>
            
            <th>
                    Age
                
            </th>        
        
            <th>
                    Channel Subscriptions
            </th>
        
            <th>
                    Province
            </th>
            
            <th>City</th>
            <th>Phone</th>
            <th>Marital Status</th>
            <th>Gender</th>
        </tr>
    
    
        <?php foreach($users as $user): ?>
            <?php if($caregiver_role == -1 || is_user_caregiver_role($user->ID, $caregiver_role)): ?>
                <tr>
                    <td>
                        <?php echo $user->display_name; ?>
                    </td>
                    <td>
                        <?php echo $user->user_email; ?>
                    </td>
                    <td>
                        <?php echo $event_registration->get_user_event_count($user->ID); ?>
                    </td>
                    <td>
                        <?php for($i = 0; $i < get_num_caregiver_roles(); $i++): ?>
                            <ul>
                            <?php if(is_user_caregiver_role($user->ID, $i)): ?>
                                <li><a href="<?php echo $url; ?>?page=tcn_users&caregiver_role=<?php echo $i; ?>&page_number=<?php echo $page_number ?>&sort_col=<?php echo $sort_col; ?>"><?php echo get_caregiver_role_name($i); ?></a> </li>
                            <?php endif ?>
                            </ul>
                        <?php endfor ?>
                    </td>

                    <td>
                        <?php  $age = get_user_profile_field($user->ID, 'tcn_user_meta_age'); ?>
                        <?php if($age === '0' ){ echo "<17"; } ?>
                        <?php if($age === '1' ){ echo "18-24"; } ?>
                        <?php if($age === '2' ){ echo "25-34"; } ?>
                        <?php if($age === '3' ){ echo "35-44"; } ?>
                        <?php if($age === '4' ){ echo "45-54"; } ?>
                        <?php if($age === '5' ){ echo "55-64"; } ?>
                        <?php if($age === '6' ){ echo "65-74"; } ?>
                        <?php if($age === '7' ){ echo "75+"; } ?>
                    </td>
                    <td>
                        <?php $cats = $network->get_subscribed_categories($user); ?>
                        <ul>
                        <?php foreach($cats as $cat): ?>
                            <li><?php echo $cat->cat_name; ?></li>
                        <?php endforeach ?>
                    </ul>
                    </td>
                    <td>
                        <?php 
                            $province = get_user_meta($user->ID, 'tcn_user_meta_province', true);
                        ?>
                        <?php
                            if($province !== '')
                            
                                echo get_province_name($province);
                            else
                                echo 'unknown';
                        ?>
                    </td>
                    <td>
                            <?php echo get_user_meta($user->ID, 'tcn_user_meta_city', true); ?>
                    </td>
                    <td>
                        <?php echo get_user_meta($user->ID, 'tcn_user_meta_phone', true); ?>
                    </td>
                    <td>
                        <?php echo get_marital(get_user_meta($user->ID, 'tcn_user_meta_marital_status', true)); ?>
                    </td>
                    <td>
                        <?php echo get_gender(get_user_meta($user->ID, 'tcn_user_meta_gender', true)); ?>
                    </td>
                </tr>
            <?php endif ?>
        <?php endforeach ?>

    </table>
<?php
}

function tcn_render_user_stats_table()
{

    $breakdown = get_user_stats_breakdown();
    $args = array("role" => "subscriber");
    $users = get_users( $args );
    
    ?>
    
    <h2> Alltime User Stats </h2>
    <table>
        <tr>
            <th>
                Total Subscribers
            </th>
            <?php for($i = 0; $i < get_num_caregiver_roles(); $i++ ): ?>
                <th>
                    <?php echo get_caregiver_role_name($i); ?>
                </th>
            <?php endfor ?>
        </tr>
        
        <tr>
            <td>
                <?php echo count($users); ?>
            </td>
            <?php for($i = 0; $i < get_num_caregiver_roles(); $i++ ): ?>
                <td>
                    <?php $role = get_caregiver_role_name($i); ?>
                    <?php print_r($breakdown['alltime'][$role]); ?>
                </td>
            <?php endfor ?>
        </tr>
    </table>
    
    
    <h2> User Stats By Month </h2>
    <table>
        
        <tr>
            <th>
                Month
            </th>
            
            <th>
                Total Registrations
            </th>
            
            <?php for($i = 0; $i < get_num_caregiver_roles(); $i++ ): ?>
                <th>
                    <?php echo get_caregiver_role_name($i); ?>
                </th>
            <?php endfor ?>
        </tr>
        
        <?php 
        $months = array();
        $start = $month = strtotime('2015-01-01');
        $end = time();
        while($month < $end)
        {
             $month = strtotime("+1 month", $month);
             array_push($months, $month);
        }
        
        ?>
        <?php foreach($months as $month): ?>
        
        <tr>
            <td>
                <?php $m = date("F Y", $month); echo $m; ?>
            </td>
            
            <td>
                <?php echo$breakdown[$m]['total']; ?>
            </td>
            
            <?php for($i = 0; $i < get_num_caregiver_roles(); $i++ ): ?>
                 <?php $role = get_caregiver_role_name($i); ?>
                <td>
                    <?php echo $breakdown[$m][$role]; ?>
                </td>
            <?php endfor ?>
        </tr>
        <?php endforeach ?>
    </table>
<?php   
}

function get_network_partners() 
{ 
    $users = array();
    $roles = array('network_partner');

    foreach ($roles as $role) :
        $users_query = new WP_User_Query( array( 
            'fields' => 'all_with_meta', 
            'role' => $role, 
            'orderby' => 'display_name'
            ) );
        $results = $users_query->get_results();
        if ($results) $users = array_merge($users, $results);
    endforeach;

    return $users;
}

function tcn_emails()
{
    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $event = get_post(4393); // get_post($_POST['event_id']);
        send_all_emails($_POST['email'], $event, 'fr');      
    }     
    ?>
    <?php echo ICL_LANGUAGE_CODE; ?>
    <form method="POST">
        Email<br/>
        <input type="text" name="email" value="admin+tcn@jonhill.ca" /><br/>
        <?php /*
        Event<br/>
        <select name="event_id">
            <?php foreach(get_all_events() as $event): ?>
                <option value="<?php echo $event->ID ?>"><?php echo $event->post_title; ?></option>
            <?php endforeach ?>
        </select></br>        
        */?>
        <input type="submit" value="Send Emails!" />
    </form>
    <?php
}

$provmap = array(
    11 => 10,
    2 => 1,
    9 => 8,
    4 => 3,
    5 => 3,
    7 => 5,
    1 => 0,
    12 => 11,
    3 => 2,
    13 => 12,
    10 => 9
    
);

function migrate_user($user)
{
    global $provmap;

        echo "--------------------<br/>";
        global $provmap;
        echo $user->email . '<br/>';
        $user_id = wp_create_user( $user->email, $user->password, $user->email );
        update_user_meta($user_id, 'first_name', $user->name);
        update_user_meta($user_id, 'last_name', $user->nameF);
        update_user_meta($user_id, 'phone', $user->phone);
        update_user_meta($user_id, 'phoneO', $user->phoneO);
        update_user_meta($user_id, 'phoneT', $user->phoneT);
        
        if(isset($provmap[$user->id_province]))
        {
            update_user_meta($user_id, 'tcn_user_province', $provmap[$user->id_province]);
        }
        else
        {
            update_user_meta($user_id, 'tcn_user_province', 13);
        }
        
        echo get_province_name(get_user_meta($user_id, 'tcn_user_province', true));
        echo '<br/>';
        
        echo $user->address;
        echo '<br/>';
    
        echo $user->city;
        echo '<br/>';
    
        echo $user->id_province;
        echo '<br/>';
    
        // stash away the id_member from the old database
        echo $user->id_member;
        echo '<br/>';
        
        update_user_meta($user_id, 'id_member', $user->id_member);
}

/*
$tcn_provinces = array(
                    __('Alberta', 'tcn'), 
                    __('British Columbia', 'tcn'), 
                    __('Manitoba', 'tcn'), 
                    __('New Brunswick', 'tcn'), 3
                    __('Newfoundland and Labrador', 'tcn'), 
                    __('Nova Scotia', 'tcn'), 5
                    __('Northwest Territories', 'tcn'), 6
                    __('Nunavut', 'tcn'), 7
                    __('Ontario', 'tcn'), 8
                    __('PEI', 'tcn'), 
                    __('Quebec', 'tcn'), 10
                    __('Saskatchewan', 'tcn'), 11
                    __('Yukon', 'tcn'), 12
                    __('Outside of Canada', 'tcn')); 13
*/



function migrate_users()
{
    ini_set('memory_limit', 1073741824);
    
    $users = get_users(array("role"=>"subscriber"));
    foreach($users as $user)
    {
        // print 'deleting: ' . $user->ID;
        wp_delete_user($user->ID, false);
    }
    print "DONE DELETING<br/>";
    
    // CREATE
    /*
    global $wpdb;
    $users = $wpdb->get_results("SELECT * FROM member");
    // $users = array_slice($users, 0, 100);
    print count($users);
    
    echo '<pre>';
    $count = 0;
    
    $unmigrated = array();
    foreach($users as $user)
    {
        if($user->password != '' && $user->email != '')
        {
            migrate_user($user);
            $count++;
        }
        else
        {
            array_push($unmigrated, $user);
        }
    }
    echo '</pre>';
    
    echo count($unmigrated);
    
    print '<br/><br/>';
    */
}

$evmap = array(
    636 => 4381, /* 26 */
    638 => 4374, /* 9 */
    639 => 4388, /* 132 */
    627 => 4378, /* 29 */
    627 => 4806, /* 29 */
    635 => 4387, /* 26 */
    687 => 0, /* */
    648 => 0, /* 14 */
    667 => 0, 
    637 => 4372,
    665 => 0,
    668 => 0,
    669 => 0,
    676 => 4373,
    664 => 0,
    670 => 4366,
    663 => 0,
    640 => 0,
    644 => 4386,
    692 => 4391,
    677 => 4376,
    645 => 4387,
    646 => 4387,
    
    // 679 => 4380,
    673 => 0,
    
    // 674 => 4382,
    
    690 => 0,
    692 => 0,
    
    643 => 4384,
    689 => 4389,
    691 => 4392,
    685 => 4371,
    675 => 4383,
    684 => 0,
    671 => 0,
    666 => 0,
    623 => 0,
    683 => 0,
    626 => 0,
    641 => 0,
    624 => 0, 
    688 => 0
);

function migrate_reg()
{
    
    global $evmap;
    
    // okay so after you build your thing up above, comment it all out
    // you just neeed the evmap
    // scroll through all of the users, pick out their special ID,
    // look it up in the f-key table, and start mapping them to events

    global $wpdb;
    $query = "DELETE
                  FROM wp_event_registration";
    $results = $wpdb->get_results($query);
    
    $er = new EventRegistration;
    $users = get_users(array("role"=>"subscriber"));
    // $users = array_slice($users, 0,100);
    
    $made = array();
    
    foreach($users as $user)
    {
        $id_member = get_user_meta($user->ID, 'id_member', true);
        
        echo "Member ID: ";
        echo $id_member;
        echo '<br/>';
        
        global $wpdb;
        $regs = $wpdb->get_results("SELECT * FROM member_workshop_assn WHERE member_id='$id_member'");

        if(count($regs))
        {
            echo "--------";
            echo $user->display_name;
            echo '<br/>';
            
            echo $id_member;
            echo '<br/>';
            
            echo count($regs);
            echo '<br/>';
            echo '<br/>';
            
            foreach($regs as $reg)
            {
                echo $reg->workshop_id;
                echo '<br/>';
                if($evmap[$reg->workshop_id] != 0)
                {
                    echo 'reg';
                    echo '<br/>';
                    $er->register_user($evmap[$reg->workshop_id], $user->ID);
                    
                    if(isset($made[$reg->workshop_id]))
                    {
                        $made[$reg->workshop_id]++;
                    }
                    else
                    {
                        $made[$reg->workshop_id] = 0;
                    }
                }
            }
        }
    }
    
    echo '<pre>';
    print_r($made);
    echo '</pre>';
}

function tcn_migrate_reg()
{
    
    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        migrate_reg();
    }     
    ?>
    This will delete all registrations and re-add them.<br/>
    <form method="POST">
        <input type="submit" value="You Sure?" />
    </form>
    <?php
}

function tcn_migrate_users()
{
    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        migrate_users();
    }     
    ?>
    This will delete all subscribers and migrate the old users from the old website.<br/>
    <form method="POST">
        <input type="submit" value="You Sure?" />
    </form>
    <?php
}

function tcn_randomize_subs()
{
    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        randomize_events();
        randomize_subscribers();        
    }     
    ?>
    This will delete all subscribers and events for certain channels. It will then add them back.</br>
    <form method="POST">
        <input type="submit" value="You Sure?" />
    </form>
    <?php
}

function tcn_render_partner_stats()
{
    $url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
    $event_registration = new EventRegistration;
    
    if(isset($_GET['partner_id']))
    {
        $np = get_userdata($_GET['partner_id']);
        
        ?>
        <h1><?php echo $np->display_name; ?></h1>
        <pre><?php // print_r($np); ?></pre>
        
        <h2>Events</h2>
        <table>
            <tr>
                <th>
                    Event
                </th>
                
                <th>
                    Event Date
                </th>
                
                <th>
                    Registration Total
                </th>
                
                <?php for($i = 0; $i < get_num_caregiver_roles(); $i++ ): ?>
                    <th>
                        <?php echo get_caregiver_role_name($i); ?> Registration
                    </th>
                <?php endfor ?>
            </tr>
            
            <?php foreach(get_network_partner_events($np->ID) as $event): ?>
                <tr>
                    <td>
                        <?php echo $event->post_title; ?><br/>
                        <a target="_blank" href="<?php echo site_url(); ?>/wp-admin/post.php?post=<?php echo $event->ID; ?>&action=edit&lang=en">(edit)</a><br/>
                        <a target="_blank" href="<?php echo get_the_permalink($event->ID); ?>">(view)</a>
                    </td>
                    <td>
                        <?php echo $event->EventStartDate ?>
                    </td>
                    <td>
                        <?php echo $event_registration->get_registration_count($event->ID); ?>
                    </td>
                    
                    <?php for($i = 0; $i < get_num_caregiver_roles(); $i++ ): ?>
                        <td>
                            <?php echo count($event_registration->get_role_registrants($event->ID, $i)); ?>
                        </td>
                    <?php endfor ?>
                    
                </tr>
            <?php endforeach ?>
                
        </table>
        
        <h2>Registrations By Month</h2>
        <table>
            <tr>
                <th>
                    Month
                </th>
                
                <th>
                    Registration Total
                </th>
                
                <?php for($i = 0; $i < get_num_caregiver_roles(); $i++ ): ?>
                    <th>
                        <?php echo get_caregiver_role_name($i); ?> Registration
                    </th>
                <?php endfor ?>
            </tr>
            
            <?php
            $start = $month = strtotime('2014-01-01');
            $end = time();
            
            $months = array();
            
            while($month < $end)
            {
                 $month = strtotime("+1 month", $month);
                 array_push($months, $month);
            }
            
            ?>
            
            <?php foreach($months as $month): ?>
                <tr>    
                    <td>
                        <?php echo date("F Y", $month ); ?>
                    </td>
                    
                    <td>
                        <?php echo count($event_registration->get_registrations_partner_month($np, $month)); ?>
                    </td>
                
                    <?php for($i = 0; $i < get_num_caregiver_roles(); $i++ ): ?>
                        <td>
                            <?php echo count($event_registration->get_registrations_partner_month_role($np, $month, $i)); ?>
                        </td>
                    <?php endfor ?>
                </tr>
            <?php endforeach ?>
            
        </table>
        
        
    <?php
    }
    else
    {
        $users = get_network_partners();
        ?>
    
        <h2> Network Partner Stats </h2>
    
        <table>
            <tr>
                <th>
                    Name
                </th>
        
                <th>
                    Email
                </th>
                            
                <th>
                    Event Count
                </th>
            
                <th>
                    Total User Subscription Counts
                </th>
            
            </tr>
    
    
            <?php foreach($users as $user): ?>
                <tr>
                    <td>
                        <a href="<?php echo $url;?>?page=tcn_network_partners&partner_id=<?php echo $user->ID; ?>">
                            <?php echo get_user_meta($user->ID, 'tcn_partner_english_name', true); ?>
                            <?php if(get_user_meta($user->ID, 'tcn_partner_french_name', true) !== ''): ?>
                            (<?php echo get_user_meta($user->ID, 'tcn_partner_french_name', true); ?>)
                        <?php endif ?>
                        </a>
                    </td>
                    <td>
                        <?php echo $user->user_email; ?>
                    </td>
                    <td>
                        <?php echo count(get_network_partner_events($user->ID)); ?>
                    </td>
                    <td>
                        <?php echo count($event_registration->get_network_partner_user_subs($user->ID)); ?>
                    </td>
                </tr>
            <?php endforeach ?>
        </table>
    <?php } ?>
<?php
}

function tcn_render_events_table($events)
{
    $event_registration = new EventRegistration;
    $url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
    ?>
    <table>
        <tr>
            <th>
                Event Name
            </th>
    
            <th>
                Network Partner
            </th>
                        
            <th>
                Event Begin Date
            </th>
            
            <th>
                Event End Date
            </th>
            
            <th>
                Registration Count
            </th>
            
            <?php for($i = 0; $i < get_num_caregiver_roles(); $i++ ): ?>
                <th>
                    <?php echo get_caregiver_role_name($i); ?>
                </th>
            <?php endfor ?>
            
        </tr>
        
        <?php foreach($events as $event): ?>
            <?php $np = get_userdata($event->post_author); ?>

            <tr>
                <td>
                    <?php echo $event->post_title; ?> <br/>
                    <a target="_blank" href="<?php echo site_url(); ?>/wp-admin/post.php?post=<?php echo $event->ID; ?>&action=edit&lang=en">(edit)</a><br/>
                    <a target="_blank" href="<?php echo get_the_permalink($event->ID); ?>">(view)</a><br/>
                    <a href="<?php echo $url; ?>?page=tcn_event_inspector&event_id=<?php echo $event->ID; ?>">(inspect)</a>
                </td>

                <td>
                    <a href="<?php echo $url; ?>?page=tcn_network_partners&partner_id=<?php echo $np->ID; ?>">
                        <?php echo $np->display_name ?>
                    </a>
                </td>
                
                <td>
                    <?php // print_r($event); ?>
                    <?php echo $event->EventStartDate; ?>
                </td>
                
                <td>
                    <?php echo $event->EventEndDate; ?>
                </td>
                
                <td>
                    <?php echo $event_registration->get_registration_count($event->ID); ?>
                </td>
                
                <?php for($i = 0; $i < get_num_caregiver_roles(); $i++ ): ?>
                    <td>
                        <?php echo count($event_registration->get_role_registrants($event->ID, $i)); ?>
                    </td>
                <?php endfor ?>
                
            </tr>
        <?php endforeach ?>
    </table>
<?php
}
?>
