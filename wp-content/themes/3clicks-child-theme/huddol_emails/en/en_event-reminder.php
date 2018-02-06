<?php include('header.php'); ?>
			
<?php
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
			
			<a href="#" style="display: inline-block; margin:10px 0 35px; background-color:#4e85cc; padding:20px 30px; border-radius:30px; 
							   color: #fff; font-size:18px; font-weight: bold; border: 0px; outline: 0; text-decoration: none;">
				Add to Calendar
			</a>
			
			<img src="<?php echo $imgURL; ?>" style="width: 100%;">
			
			<div style="text-align: left;padding: 10px 40px;"> 
				<h2><?php echo $event->post_title ?></h2>
				
				<p>
					Hosted by: <a href="<?php echo $hosted_by_url ?>" style="color:#25aae1;"><?php echo $author->display_name ?></a><br>
					Topics: <?php echo tcn_capture_entry_categories($event); ?><br>
					Date: <?php echo get_event_date($post); ?>
				</p>
				
				<p style="font-weight: bold">
					To join the event please follow the steps listed below:
				</p>
				
				<p>
					1. Click the “Join Presentation” button below<br>
					2. Log in to the meeting room by selecting the option “Enter as a Guest”<br>
					3. Enter your name then select “Enter Room”<br>
					4. Dial the following toll-free number: 1-877-394-5901, access code: 7481585
				</p>
			</div>
				
			<a href="<?php echo get_permalink($event->ID); ?>" style="display: inline-block; margin:10px 0 35px; background-color:#3bc38f; padding:20px 30px; border-radius:30px; 
							   color: #fff; font-size:18px; font-weight: bold; border: 0px; outline: 0; text-decoration: none;">
				Join Presentation
			</a>
			
			<div style="background-color:#e8eaef; width:100%; height:1px;"></div>
			
			<div style="text-align: left;padding: 20px 40px;"> 
				<p style="font-weight: bold">
					Technical Information
				</p>
				
				<p>
					- <a href="http://huddol.adobeconnect.com/common/help/en/support/meeting_test.htm" style="color:#25aae1;">Test your computer</a><br>
					- <a href="http://www.adobe.com/products/acrobatconnectpro/systemreqs/" style="color:#25aae1;">Adobe Connect System Requirements</a><br>
					- <a href="#" style="color:#25aae1;">Download our troubleshooting guide</a>
				</p>
			</div>

<?php include('footer.php'); ?>
