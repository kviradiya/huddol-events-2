<?php $event_registration = new EventRegistration; ?>
<?php $events_buttons = new Three_Clicks_Child_Theme_Email_Buttons; ?>
<?php
    $event_meta_phone_number = get_post_meta($event->ID, 'event_meta_phone_number', true);
    $event_meta_conference_id = get_post_meta($event->ID, 'event_meta_conference_id', true);
    $event_meta_webinar_link = get_post_meta($event->ID, 'event_meta_webinar_link', true);

    $event_meta_phone_number = override_from_user($event->ID, 'event_meta_phone_number', $event_meta_phone_number);
    $event_meta_conference_id = override_from_user($event->ID, 'event_meta_conference_id', $event_meta_conference_id);
    $event_meta_webinar_link = override_from_user($event->ID, 'event_meta_webinar_link', $event_meta_webinar_link);
    
    $event_image_path = get_post_thumbnail($event->ID, 'full');
    
    $hosted_by = get_userdata($event->post_author);
    $hosted_by_image = get_cupp_meta( $event->post_author, 'small' );

    $image_path = get_stylesheet_directory_uri().'/images/';  
?>

<!DOCTYPE html>
<html style="font-family: sans-serif;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>TCN Newsletter</title>
  <link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css">
</head>
<body style="position: relative;margin: 0;min-width: 596px;font-family: 'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;font-weight: normal;color: #222;line-height: 1.3;background: white;">

  <div class="top-bar" style="position: relative;width: 100%;height: 7px;margin-bottom: 6px;background: #ef3e29;"></div>

  <div class="container" style="position: relative;width: 506px;margin: 0 auto;padding: 0px 45px;">
    <table id="header" style="position: relative; width: 100%; height: 80px; border-collapse: collapse; border-spacing: 0;">
      <tr>
        <td style="position: relative; width: 412px; padding-top: 20px; vertical-align: top; font-family: 'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;">
          <a href="<?php echo site_url(); ?>" class="logo" style="background-color: transparent; text-decoration: none; cursor: pointer; outline: none; display: block;"><img src="<?php echo $image_path; ?>email_logo.png" style="width: 247px; height: 49px;"></img></a>
        </td>
        <td style="position: relative; width: 84px; vertical-align: top; font-family: 'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;">
          <div class="social" style="height: 36px; width: 90px">
            <a href="https://www.facebook.com/lratcn" style="position: relative;background-color: transparent;text-decoration: none;color: #444;cursor: pointer;outline: none;display: block;width: 39px;height: 36px;float: left;margin-left: 4px;">
              <span style="position: relative;display: inline-block;background: url('<?php echo $image_path; ?>email_facebook.png');height: 36px;width: 39px;"></span>
            </a><a href="https://twitter.com/huddol" style="position: relative;background-color: transparent;text-decoration: none;color: #444;cursor: pointer;outline: none;display: block;width: 39px;height: 36px;float: left;margin-left: 4px;">
              <span style="position: relative;display: inline-block;background: url('<?php echo $image_path; ?>email_twitter.png');height: 36px;width: 39px;"></span>
            </a>
          </div>
        </td>
      </tr>
    </table>

    <div id="content" style="position: relative;min-height: 350px;padding-top: 20px;padding-bottom: 50px;">

      <h2 style="position: relative;margin: 0px;padding: 0px;margin-bottom: 15px;font-size: 18px;font-weight: 300;color: #ee3a29;">
        <?php _e("Event Reminder", "tcn"); ?>
      </h2>
      <h4 style="position: relative;margin: 0px;padding: 0px;margin-bottom: 20px;font-size: 14px;font-weight: normal;line-height: 1.5;">
        <?php _e("This is a reminder that you are registered for the following event:", "tcn" ); ?><br style="position: relative;">
        <?php _e("You can", "tcn"); ?> <a href="<?php echo site_url(); ?>/login/" class="important" style="position: relative;background-color: transparent;text-decoration: none;color: #fb4400;cursor: pointer;outline: none;font-size: 16px;font-weight: bold;"><?php _e("Log In to your account", "tcn"); ?></a> <?php _e("to unregister for this event.", "tcn" ); ?>
      </h4>

      <table class="events-list" style="position: relative;border-collapse: collapse;border-spacing: 0;">
        <tr style="position: relative;">
          <td style="position: relative;padding: 0;padding-bottom: 25px;vertical-align: top; font-family: 'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;">
            <a href="<?php echo get_the_permalink($event->ID); ?>" class="picture" style="position: relative;background-color: transparent;text-decoration: none;color: #444;cursor: pointer;outline: none;display: block;width: 172px;height: 104px;">
              <img src="<?php echo $event_image_path; ?>" width="172" height="104" style="position: relative;border: 0;display: block;">
            </a>
          </td>
          <td class="sep" style="position: relative;padding: 0;padding-bottom: 25px;width: 22px;"></td>
          <td style="position: relative;padding: 0;padding-bottom: 25px;vertical-align: top; font-family: 'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;">
              <p class="category" style="position: relative;margin: 0px;padding: 0px;margin-bottom: 2px;font-size: 18px;font-weight: bold;color: #fb4400;">
              <?php if( get_post_meta($event->ID, 'event_meta_type', true) == 0): ?>
                  <?php _e("Webinar"); ?> -
              <?php endif ?>    
              <?php if( get_post_meta($event->ID, 'event_meta_type', true) == 1): ?>
                  <?php _e("Teleconference"); ?> - 
              <?php endif ?>
                   
              <?php if( get_post_meta($event->ID, 'event_meta_language', true) == 0): ?>
                  <?php _e("English"); ?>
              <?php endif ?>    
              <?php if( get_post_meta($event->ID, 'event_meta_language', true) == 1): ?>
                  <?php _e("French"); ?>
              <?php endif ?>
            </p>

            <p class="title" style="position: relative;margin: 0px;padding: 0px;margin-bottom: 5px;font-size: 22px;color: #fb4400;">
              <a href="<?php echo get_the_permalink($event->ID); ?>" style="position: relative;background-color: transparent;text-decoration: none;color: #fb4400;cursor: pointer;outline: none;">
                <?php echo $event->post_title; ?>
              </a>
            </p>
            <p class="date" style="position: relative;margin: 0px;padding: 0px;margin-bottom: 5px;font-size: 14px;font-weight: 300;">
              <?php echo get_event_date($event); ?>
            </p>
            <p class="hosted-by" style="position: relative;margin: 0px;padding: 0px;margin-bottom: 5px;font-size: 14px;font-weight: 300;">
              <?php _e("Hosted by:", "tcn");?> <?php echo get_partner_name($hosted_by->ID); ?>
            </p>
          </td>
        </tr>
      </table>

      <div class="contact" style="position: relative;">
        <?php if( $event_meta_phone_number != '' && $event_meta_conference_id != ''): ?>
          <div class="entry two-lines" style="position: relative;height: 48px;font-size: 15px;color: #646568;line-height: 1.3;">
            <span style="display: block; float: left; background: url('<?php echo $image_path; ?>email_phone.png');height: 34px;width: 34px;margin: auto;"></span>
            <div style="float: left; padding-left: 14px;">
              <span class="red" style="position: relative;color: #fb4400;"><?php echo $event_meta_phone_number ?> (toll free)</span><br style="position: relative;">
              <?php _e("Conference ID", "tcn" ); ?> : <span class="red" style="position: relative;color: #fb4400;"><?php echo $event_meta_conference_id ?></span>
            </div>
          </div>
        <?php endif ?>
          
        <?php if( $event_meta_webinar_link != ''): ?>
          <div class="entry" style="position: relative;height: 48px;font-size: 15px;color: #646568;line-height: 34px;">
            <span style="display: block; float: left; background: url('<?php echo $image_path; ?>email_online.png');height: 34px;width: 34px;margin: auto;"></span>
            <div style="float: left; padding-left: 14px;">
              Click <a href="<?php echo $event_meta_webinar_link; ?>" style="position: relative;background-color: transparent;text-decoration: none;color: #fb4400;cursor: pointer;outline: none;font-weight: bold;"><?php _e("Here") ?></a> <?php _e("to access this webinar", "tcn"); ?>
            </div>
          </div>
        <?php endif ?>
         <div class="entry" style="position: relative;height: 48px;font-size: 15px;color: #646568;line-height: 34px;">
 		  <p><?php echo $events_buttons->my_single_event_links($event->ID); ?></p>
          </div>
           <div class="entry" style="position: relative;height: 48px;font-size: 15px;color: #646568;line-height: 34px;margin-top:10px">
        <div style="position: relative;background-color: transparent;"><a href="https://www.youtube.com/watch?v=ux4Hw_7lPB8" style="text-decoration: none;color: #fb4400;cursor: pointer;outline: none;font-weight: bold; font-size:18px;"><?php _e("Watch this video", "tcn"); ?></a> <?php _e("to learn how to connect to our events.", "tcn"); ?></div>
         <div style="position: relative;background-color: transparent;line-height: 18px;font-size: 14px;margin-top:10px"><?php _e("<strong><span style=\"color:#222\">*All of our learning events are recorded</span></strong> and made available on the event listing page approximately 7 days after the event.", "tcn"); ?><strong></div></div> 
		<br><br>         
      </div>

      <div class="line" style="position: relative;height: 1px;margin: 25px 0px;background: #e6e6e6;"><div class="red" style="position: relative;width: 60px;height: 1px;background: #fb4400;"></div></div>

      <div style="font-size: 14px; line-height: 1.4;">
        <h4 style="font-weight: bold; margin: 0px; margin-bottom: 6px;"><?php _e("Technical Information", "tcn"); ?></h4>
        <h4 style="font-weight: bold; margin: 0px; margin-top: 14px; margin-bottom: 6px;"><?php _e("For a Webinar", "tcn"); ?></h4>
        <p style="margin: 0px; margin-bottom: 6px;">
          - <?php _e("Test your computer:", "tcn"); ?> <a href="http://caringvoicenetwork.adobeconnect.com/common/help/en/support/meeting_test.htm" style="text-decoration: none; color: #fb4400;">http://caringvoicenetwork.adobeconnect.com/common/help/en/support/meeting_test.htm</a>
        </p>
        <p style="margin: 0px; margin-bottom: 6px;">
          - <?php _e("Adobe Connect System Requirements:", "tcn"); ?> <a href="http://www.adobe.com/products/acrobatconnectpro/systemreqs/" style="text-decoration: none; color: #fb4400;">http://www.adobe.com/products/acrobatconnectpro/systemreqs/</a>
        </p>
        <p style="margin: 0px; margin-bottom: 6px;">
          - <a href="http://thecaregivernetwork.ca/wp-content/uploads/2015/03/TCN_AdobeConnect_Troubleshooting-Guide.pdf" style="text-decoration: none; color: #fb4400;"><?php _e("Download our troubleshooting guide", "tcn"); ?></a>
        </p>
        <h4 style="font-weight: bold; margin: 0px; margin-top: 14px; margin-bottom: 6px;"><?php _e("For a Teleconference", "tcn"); ?></h4>
        <p style="margin: 0px; margin-bottom: 6px;">
          - <?php _e("Dial the toll free teleconference number", "tcn"); ?>
        </p>
        <p style="margin: 0px; margin-bottom: 16px;">
          - <?php _e("Enter the conference ID when prompted", "tcn"); ?>
        </p>
        <p style="margin: 0px; margin-bottom: 6px;">
          <?php _e("For additional support with a tele-event, please visit our", "tcn"); ?><a href="<?php echo site_url(); ?>/help/" style="text-decoration: none; color: #fb4400;"> <?php _e("help page", "tcn"); ?></a>.
        </p>

      </div>

    </div>
  </div>

  <div id="footer" style="position: relative;height: 66px;background: #262626;text-align: right;">
    <div class="container" style="position: relative;width: 506px;margin: 0 auto;padding: 0px 45px;">
      <div class="social" style="float: right; height: 36px; padding-top: 14px; padding-left: 14px;">
        <a href="https://www.facebook.com/lratcn" style="position: relative;background-color: transparent;text-decoration: none;color: #444;cursor: pointer;outline: none;display: block;width: 39px;height: 36px;float: left;margin-left: 4px;">
          <span style="position: relative;display: inline-block;background: url('<?php echo $image_path; ?>email_facebook.png');height: 36px;width: 39px;"></span>
        </a><a href="https://twitter.com/huddol" style="position: relative;background-color: transparent;text-decoration: none;color: #444;cursor: pointer;outline: none;display: block;width: 39px;height: 36px;float: left;margin-left: 4px;">
          <span style="position: relative;display: inline-block;background: url('<?php echo $image_path; ?>email_twitter.png');height: 36px;width: 39px;"></span>
        </a>
      </div>
      <a href="<?php echo site_url(); ?>/contact-us/" class="contact" style="float: right; background-color: transparent;text-decoration: none;color: white;cursor: pointer;outline: none;font-size: 13px;line-height: 66px;"><?php _e("Contact Us", "tcn"); ?></a>
    </div>
  </div>
  
</body>
</html>