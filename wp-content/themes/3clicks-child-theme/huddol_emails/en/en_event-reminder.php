<?php include('header.php'); ?>
			
<?php
		$events_buttons = new Three_Clicks_Child_Theme_Email_Buttons;

		$event_meta_phone_number = get_post_meta($event->ID, 'event_meta_phone_number', true);
    $event_meta_conference_id = get_post_meta($event->ID, 'event_meta_conference_id', true);
		$event_meta_webinar_link = get_post_meta($event->ID, 'event_meta_webinar_link', true);

    $event_meta_webinar_link = $event_meta_webinar_link != '' ? $event_meta_webinar_link : get_permalink($event->ID);
		
    $hosted_by_url = '';

    $imgURL = get_post_thumbnail($event->ID, 'full'); 

    $author = get_user_by("id", $event->post_author);

    $hosted_by_url = get_user_meta($author->ID, 'tcn_partner_english_website', true);

    if(substr($hosted_by_url, 0, 7) == 'http://' || substr($hosted_by_url, 0, 8) == 'https://')
    {
        // URL is okay, do nothing
    }
    else
    {
        $hosted_by_url = 'http://' . $hosted_by_url;
    }
    
?>

			<h1 style="font-size:24px;">Event Reminder</h1>				
			
      <p><?php echo $events_buttons->my_single_event_links($event->ID); ?></p>
			
			<img src="<?php echo $imgURL; ?>" style="width: 100%;">
			
			<div style="text-align: left;padding: 10px 40px;"> 
				<h2 style="font-size: 25px;"><?php echo $event->post_title ?></h2>
				
				<p>
					Hosted by: <?php echo $author->display_name ?><br>
					Topics: <?php echo tcn_capture_entry_categories($event); ?><br>
					Date: <?php echo get_event_date($event); ?>
				</p>
				
				<p style="font-weight: bold">
					To join the event please follow the steps listed below:
				</p>
				
				<p>
					1. Click the “Join Presentation” button below<br>
					2. Log in to the meeting room by selecting the option “Enter as a Guest”<br>
					3. Enter your name then select “Enter Room”<br>
					<?php if( $event_meta_phone_number != '' && $event_meta_conference_id != ''): ?>
					4. Dial the following toll-free number: <?php echo $event_meta_phone_number ?>, access code: <?php echo $event_meta_conference_id ?>
					<?php endif ?>
				</p>
			</div>
				
			<a href="<?php echo $event_meta_webinar_link; ?>" style="display: inline-block; margin:10px 0 35px; background-color:#3bc38f; padding:20px 30px; border-radius:30px; 
							   color: #fff; font-size:18px; font-weight: bold; border: 0px; outline: 0; text-decoration: none;">
				Join Presentation
			</a>
			
			<div style="background-color:#e8eaef; width:100%; height:1px;"></div>
			
			<div style="text-align: left;padding: 20px 40px;"> 
				<p style="font-weight: bold">
					Technical Information
				</p>
				
				<p>
					- <a href="https://na1cps.adobeconnect.com/common/help/en/support/meeting_test.htm" style="color:#25aae1;">Test your computer</a><br>
					- <a href="http://www.adobe.com/products/acrobatconnectpro/systemreqs/" style="color:#25aae1;">Adobe Connect System Requirements</a><br>
					- <a href="https://events.huddol.com/wp-content/uploads/2018/01/adobeconnect-troubleshooting-guide-en.pdf" style="color:#25aae1;">Download our troubleshooting guide</a>
				</p>
			</div>

<?php include('footer.php'); ?>
