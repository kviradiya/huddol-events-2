<?php include('header.php'); ?>

<div id="content" style="position: relative;min-height: 350px;padding-top: 20px;padding-bottom: 50px;">

  <h2 style="position: relative;margin: 0px;padding: 0px;margin-bottom: 15px;font-size: 18px;font-weight: 300;color: #29abe2;">
    <?php _e("Event Reminder", "tcn"); ?>
  </h2>
  <h4 style="position: relative;margin: 0px;padding: 0px;margin-bottom: 20px;font-size: 14px;font-weight: normal;line-height: 1.5;">
    <?php _e("This is a reminder that you are registered for the following event:", "tcn" ); ?>
    <br style="position: relative;">
    <?php _e("You can", "tcn"); ?>
    <a href="<?php echo site_url(); ?>/login/" class="important" style="position: relative;background-color: transparent;text-decoration: none;color: #29abe2;cursor: pointer;outline: none;font-size: 16px;font-weight: bold;">
      <?php _e("Log In to your account", "tcn"); ?>
    </a>
    <?php _e("to unregister for this event.", "tcn" ); ?>
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
        <p class="category" style="position: relative;margin: 0px;padding: 0px;margin-bottom: 2px;font-size: 18px;font-weight: bold;color: #29abe2;">
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

        <p class="title" style="position: relative;margin: 0px;padding: 0px;margin-bottom: 5px;font-size: 22px;color: #29abe2;">
          <a href="<?php echo get_the_permalink($event->ID); ?>" style="position: relative;background-color: transparent;text-decoration: none;color: #29abe2;cursor: pointer;outline: none;">
            <?php echo $event->post_title; ?>
          </a>
        </p>
        <p class="date" style="position: relative;margin: 0px;padding: 0px;margin-bottom: 5px;font-size: 14px;font-weight: 300;">
          <?php echo get_event_date($event); ?>
        </p>
        <p class="hosted-by" style="position: relative;margin: 0px;padding: 0px;margin-bottom: 5px;font-size: 14px;font-weight: 300;">
          <?php _e("Hosted by:", "tcn");?>
          <?php echo get_partner_name($hosted_by->ID); ?>
        </p>
      </td>
    </tr>
  </table>

  <div class="contact" style="position: relative;">
    <?php if( $event_meta_phone_number != '' && $event_meta_conference_id != ''): ?>
    <div class="entry two-lines" style="position: relative;height: 48px;font-size: 15px;color: #646568;line-height: 1.3;">
      <span style="display: block; float: left; background: url('<?php echo $image_path; ?>email_phone.png');height: 34px;width: 34px;margin: auto;"></span>
      <div style="float: left; padding-left: 14px;">
        <span class="red" style="position: relative;color: #29abe2;">
          <?php echo $event_meta_phone_number ?> (toll free)</span>
        <br style="position: relative;">
        <?php _e("Conference ID", "tcn" ); ?> :
        <span class="red" style="position: relative;color: #29abe2;">
          <?php echo $event_meta_conference_id ?>
        </span>
      </div>
    </div>
    <?php endif ?>

    <?php if( $event_meta_webinar_link != ''): ?>
    <div class="entry" style="position: relative;height: 48px;font-size: 15px;color: #646568;line-height: 34px;">
      <span style="display: block; float: left; background: url('<?php echo $image_path; ?>email_online.png');height: 34px;width: 34px;margin: auto;"></span>
      <div style="float: left; padding-left: 14px;">
        Click
        <a href="<?php echo $event_meta_webinar_link; ?>" style="position: relative;background-color: transparent;text-decoration: none;color: #29abe2;cursor: pointer;outline: none;font-weight: bold;">
          <?php _e("Here") ?>
        </a>
        <?php _e("to access this webinar", "tcn"); ?>
      </div>
    </div>
    <?php endif ?>
    <div class="entry" style="position: relative;height: 48px;font-size: 15px;color: #646568;line-height: 34px;">
      <p>
        <?php echo $events_buttons->my_single_event_links($event->ID); ?>
      </p>
    </div>
    <div class="entry" style="position: relative;height: 48px;font-size: 15px;color: #646568;line-height: 34px;margin-top:10px">
      <div style="position: relative;background-color: transparent;">
        <a href="https://www.youtube.com/watch?v=ux4Hw_7lPB8" style="text-decoration: none;color: #29abe2;cursor: pointer;outline: none;font-weight: bold; font-size:18px;">
          <?php _e("Watch this video", "tcn"); ?>
        </a>
        <?php _e("to learn how to connect to our events.", "tcn"); ?>
      </div>
      <div style="position: relative;background-color: transparent;line-height: 18px;font-size: 14px;margin-top:10px">
        <?php _e("<strong><span style=\"color:#222\">*All of our learning events are recorded</span></strong> and made available on the event listing page approximately 7 days after the event.", "tcn"); ?>
        <strong>
      </div>
    </div>
    <br>
    <br>
  </div>

  <div style="background-color:#e8eaef; width:100%; height:1px;"></div>

  <div style="text-align: left;padding: 20px 40px;">
    <p style="font-weight: bold">
      Technical Information
    </p>

    <p>
      -
      <a href="http://huddol.adobeconnect.com/common/help/en/support/meeting_test.htm" style="color:#29abe2;">Test your computer</a>
      <br> -
      <a href="http://www.adobe.com/products/acrobatconnectpro/systemreqs/" style="color:#29abe2;">Adobe Connect System Requirements</a>
      <br> -
      <a href="https://events.huddol.com/wp-content/uploads/2018/01/adobeconnect-troubleshooting-guide-en.pdf" style="color:#29abe2;">Download our troubleshooting guide</a>
    </p>
  </div>

</div>
</div>

<?php include('footer.php'); ?>