<?php include('header.php'); ?>

<?php 
  $hosted_by = get_userdata($event->post_author);
  $hosted_by_image = get_cupp_meta( $event->post_author, 'small' );
  $event_image_path = get_post_thumbnail($event->ID, 'full');
?>

<div id="content" style="position: relative;min-height: 350px;padding-top: 20px;padding-bottom: 50px;">

  <h2 style="position: relative;margin: 0px;padding: 0px;margin-bottom: 15px;font-size: 18px;font-weight: 300;color: #29abe2;">
    <?php _e("Nous aimerions beaucoup recevoir vos commentaires.", "tcn"); ?>
  </h2>
  <h4 style="position: relative;margin: 0px;padding: 0px;margin-bottom: 20px;margin-top: 24px;font-size: 14px;font-weight: normal;line-height: 1.75;">
    <?php _e("Vous avez récemment participé à l'événement suivant:", "tcn" ); ?>
  </h4>

  <table class="events-list" style="position: relative;border-collapse: collapse;border-spacing: 0; margin: 0 auto;">
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

        <p class="title" style="position: relative;margin: 0px;padding: 0px;margin-bottom: 5px;font-size: 22px;color: #29abe2;">
          <a href="<?php echo get_the_permalink($event->ID); ?>" style="position: relative;background-color: transparent;text-decoration: none;color: #29abe2;cursor: pointer;outline: none;">
            <?php echo $event->post_title; ?>
          </a>
        </p>
        <p class="date" style="position: relative;margin: 0px;padding: 0px;margin-bottom: 5px;font-size: 14px;font-weight: 300;">
          <?php echo get_event_date_fr($event); ?>
        </p>
        <p class="hosted-by" style="position: relative;margin: 0px;padding: 0px;margin-bottom: 5px;font-size: 14px;font-weight: 300;">
          <?php _e("Présenté par:", "tcn"); ?>
          <?php echo $hosted_by->display_name; ?>
        </p>
      </td>
    </tr>
  </table>

  <div class="survey-bottom" style="position: relative;margin-top: 10px;margin-bottom: 4px;font-size: 14px;line-height: 1.75;">
    <?php
          $survey_link = get_post_meta($event->ID, 'post_surveys_survey', true);
        ?>
      <p style="margin: 0px; margin-bottom: 18px;">
        <?php _e("Nous vous invitons à prendre quelques minutes pour répondre au présent sondage afin que nous puissions obtenir vos réactions sur l'événement.", "tcn"); ?>
        <br />
        <a href="<?php echo $survey_link; ?>" style="position: relative;text-decoration: none;color: #29abe2;font-weight: bold;">
          <?php _e("Cliquez ICI pour commencer le sondage", "tcn" ); ?>
        </a>
      </p>
      <p style="margin: 0px; font-style: italic;">
        <?php _e("*Si vous avez manqué un événement, vous pouvez retourner sur la page de l’événement pour en écouter son enregistrement.", "tcn" ); ?>
      </p>
  </div>
</div>
</div>

<?php include('footer.php'); ?>