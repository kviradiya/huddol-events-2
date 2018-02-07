<?php $event_registration = new EventRegistration; ?>

<?php
    $event_meta_phone_number = get_post_meta($event->ID, 'event_meta_phone_number', true);
    $event_meta_conference_id = get_post_meta($event->ID, 'event_meta_conference_id', true);
    $event_meta_webinar_link = get_post_meta($event->ID, 'event_meta_webinar_link', true);

    $event_meta_phone_number = override_from_user($event->ID, 'event_meta_phone_number', $event_meta_phone_number);
    $event_meta_conference_id = override_from_user($event->ID, 'event_meta_conference_id', $event_meta_conference_id);
    $event_meta_webinar_link = override_from_user($event->ID, 'event_meta_webinar_link', $event_meta_webinar_link);
    
    $event_image_path = get_post_thumbnail($event->ID, 'full');
    
    $hosted_by = get_userdata($event->post_author);

    $image_path = get_stylesheet_directory_uri().'/images/';
    
    setlocale(LC_ALL, "fr_FR");
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
          <a href="http://lereseauaidant.ca" class="logo" style="background-color: transparent; text-decoration: none; cursor: pointer; outline: none; display: block;"><img src="<?php echo $image_path; ?>email_logo_fr.png" style="width: 247px; height: 49px;"></img></a>
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
        <?php _e("Nous aimerions beaucoup recevoir vos commentaires.", "tcn"); ?>
      </h2>
      <h4 style="position: relative;margin: 0px;padding: 0px;margin-bottom: 20px;margin-top: 24px;font-size: 14px;font-weight: normal;line-height: 1.75;">
        <?php _e("Vous avez récemment participé à l'événement suivant:", "tcn" ); ?>
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
                  <?php _e("Webinaire", "tcn"); ?> -
              <?php endif ?>    
              <?php if( get_post_meta($event->ID, 'event_meta_type', true) == 1): ?>
                  <?php _e("Téléconference", "tcn"); ?> - 
              <?php endif ?>
                   
              <?php if( get_post_meta($event->ID, 'event_meta_language', true) == 0): ?>
                  <?php _e("Anglais", "tcn"); ?>
              <?php endif ?>    
              <?php if( get_post_meta($event->ID, 'event_meta_language', true) == 1): ?>
                  <?php _e("Français", "tcn"); ?>
              <?php endif ?>
            </p>

            <p class="title" style="position: relative;margin: 0px;padding: 0px;margin-bottom: 5px;font-size: 22px;color: #fb4400;"><a href="<?php echo get_the_permalink($event->ID); ?>" style="position: relative;background-color: transparent;text-decoration: none;color: #fb4400;cursor: pointer;outline: none;"><?php echo $event->post_title; ?></a></p>
            <p class="date" style="position: relative;margin: 0px;padding: 0px;margin-bottom: 5px;font-size: 14px;font-weight: 300;"><?php echo get_event_date_fr($event); ?></p>
            <p class="hosted-by" style="position: relative;margin: 0px;padding: 0px;margin-bottom: 5px;font-size: 14px;font-weight: 300;"><?php _e("Présenté par:", "tcn"); ?> <?php echo $hosted_by->display_name; ?></p>
          </td>
        </tr>
      </table>

      <div class="survey-bottom" style="position: relative;margin-top: 10px;margin-bottom: 4px;font-size: 14px;line-height: 1.75;">
        <?php
          $survey_link = get_post_meta($event->ID, 'post_surveys_survey', true);
        ?>
        <p style="margin: 0px; margin-bottom: 18px;">
          <?php _e("Nous vous invitons à prendre quelques minutes pour répondre au présent sondage afin que nous puissions obtenir vos réactions sur l'événement.", "tcn"); ?>
          <a href="<?php echo $survey_link; ?>" style="position: relative;text-decoration: none;color: #fb4400;font-weight: bold;">
            <?php _e("Cliquez ICI pour commencer le sondage", "tcn" ); ?>
          </a>
        </p>
        <p style="margin: 0px; font-style: italic;">
          <?php _e("*Si vous avez manqué un événement, vous pouvez retourner sur la page de l’événement pour en écouter son enregistrement.", "tcn" ); ?>
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
      <a href="http://lereseauaidant.ca/contact/" class="contact" style="float: right; background-color: transparent;text-decoration: none;color: white;cursor: pointer;outline: none;font-size: 13px;line-height: 66px;"><?php _e("Contact", "tcn"); ?></a>
    </div>
  </div>
  
</body>
</html>