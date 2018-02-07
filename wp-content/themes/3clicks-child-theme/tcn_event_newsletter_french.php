<?php
/**
 * Template Name: TCN: Event Newsletter French
 *
 * For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package G1_Framework
 * @subpackage G1_Theme03
 * @since G1_Theme03 1.0.0
 */
 
$event_registration = new EventRegistration;
$event = get_field('event_newsletter_event')
?>

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

<?php
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
          <a href="http://lereseauaidant.ca" class="logo" style="background-color: transparent; text-decoration: none; cursor: pointer; outline: none; display: block;"><span style="position: relative; display: block; background: url('<?php echo $image_path; ?>email_logo_fr.png'); width: 247px; height: 47px;"></span></a>
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

      <h2 style="position: relative;margin: 0px;padding: 0px;margin-bottom: 20px;font-size: 18px;font-weight: 300;">Événement à venir</h2>

      <div class="event-large" style="position: relative;">
        <a href="<?php echo get_the_permalink($event->ID); ?>" class="picture" style="position: relative;background-color: transparent;text-decoration: none;color: #444;cursor: pointer;outline: none;display: block;margin-bottom: 22px;">
          <img src="<?php echo $event_image_path; ?>" width="506" height="286" style="position: relative;border: 0;display: block;">
        </a>
        <div style="margin-bottom: 5px;">
          <span style="font-size: 18px;font-weight: bold;color: #fb4400;">
            <?php if( get_post_meta($event->ID, 'event_meta_type', true) == 0): ?>
              <?php _e("Webinaire"); ?> -
            <?php endif ?>    
            <?php if( get_post_meta($event->ID, 'event_meta_type', true) == 1): ?>
              <?php _e("Téléconference"); ?> - 
            <?php endif ?>

            <?php if( get_post_meta($event->ID, 'event_meta_language', true) == 0): ?>
              <?php _e("Anglais"); ?>
            <?php endif ?>    
            <?php if( get_post_meta($event->ID, 'event_meta_language', true) == 1): ?>
              <?php _e("Français"); ?>
            <?php endif ?>
          </span><br>
          <a href="<?php echo get_the_permalink($event->ID); ?>" style="position: relative;background-color: transparent;text-decoration: none;font-size: 22px;color: #fb4400;cursor: pointer;outline: none;"><?php echo $event->post_title; ?></a><br>
          <span style="font-size: 16px;font-weight: 300;"><?php echo get_event_date_fr($event); ?></span>
          <p style="font-size:14px; margin: 20px 0px;">
              <?php echo get_post_field('post_content', $event->ID); ?>
          </p>
          <p>
                <a href="<?php echo get_the_permalink($event->ID); ?>" style="margin: 0px; padding: 0px; margin-bottom: 15px; font-size: 18px; font-weight: 300; color: #ee3a29;">
                    <img src="<?php echo $image_path ?>register_today_fr.PNG" />
                   </a>
            </p>
        </div>
      </div>

      <div class="line" style="position: relative;height: 1px;margin: 25px 0px;background: #e6e6e6;"><div class="red" style="position: relative;width: 60px;height: 1px;background: #fb4400;"></div></div>

      <table class="event-hosted-by" style="position: relative; width: 100%; height: 104px; border-collapse: collapse; border-spacing: 0;">
        <tr>
          <td style="width: 234px; font-family: 'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;">
            <a href="<?php echo get_author_posts_url($hosted_by->ID); ?>" class="picture" style="display: block; background-color: transparent;text-decoration: none;color: #444;cursor: pointer;outline: none;width: 210px;height: 122px;">
              <img src="<?php echo $hosted_by_image; ?>" width="210" height="122" style="position: relative;border: 0;display: block;">
            </a>
          </td>
          <td style="font-family: 'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;">
            <p class="hd" style="position: relative;margin: 0px;padding: 0px;margin-bottom: 2px;font-size: 16px;"><?php _e("Presenté par", "tcn"); ?></p>
            <p class="name" style="position: relative;margin: 0px;padding: 0px;font-size: 20px;color: #fb4400;"><a href="<?php echo get_author_posts_url($hosted_by->ID); ?>" style="position: relative;background-color: transparent;text-decoration: none;color: #fb4400;cursor: pointer;outline: none;"><?php echo get_partner_french_name($hosted_by->ID); ?></a></p>
          </td>
        </tr>
      </table>
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
      <a href="http://lereseauaidant.ca/contact/" class="contact" style="float: right; background-color: transparent;text-decoration: none;color: white;cursor: pointer;outline: none;font-size: 13px;line-height: 66px;"><?php _e("Contact", "tcn"); ?></a>
    </div>
  </div>
  
</body>
</html>