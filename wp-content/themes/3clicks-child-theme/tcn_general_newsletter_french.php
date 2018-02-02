<?php
/**
 * Template Name: TCN: General Newsletter French
 *
 * For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package G1_Framework
 * @subpackage G1_Theme03
 * @since G1_Theme03 1.0.0
 */

/*
    $upcoming = get_upcoming_events_french();
    $recent = get_recent_events_french();
    $stories = get_latest_stories_french();
*/
    $upcoming = get_field('general_newsletter_events');
    $recent = get_field('general_newsletter_recorded');
    $stories = get_field('general_newsletter_stories');

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
            </a><a href="https://twitter.com/lratcn" style="position: relative;background-color: transparent;text-decoration: none;color: #444;cursor: pointer;outline: none;display: block;width: 39px;height: 36px;float: left;margin-left: 4px;">
              <span style="position: relative;display: inline-block;background: url('<?php echo $image_path; ?>email_twitter.png');height: 36px;width: 39px;"></span>
            </a>
          </div>
        </td>
      </tr>
    </table>

    <div id="content" style="position: relative;min-height: 350px;padding-top: 20px;padding-bottom: 50px;">

      <h2 style="position: relative;margin: 0px;padding: 0px;margin-bottom: 15px;font-size: 18px;font-weight: 300;"><?php _e("Événements à venir", "tcn"); ?></h2>
      <table class="events-grid" style="position: relative;border-collapse: collapse;border-spacing: 0;margin-bottom: -10px;">
        <?php
          if (count($upcoming) > 0) {
            $row = 0;
            $count_in_row = 0;
            $per_row = 2;
            for ($n = 0; $n < min(count($upcoming), 6); $n ++) {
              if ($count_in_row == 0) {
                if ($row > 0) {
                  echo '</tr>';
                }
                echo '<tr style="position: relative;">';
              }
              if ($count_in_row > 0) {
                echo '<td class="sep" style="position: relative;padding: 0;width: 30px;"></td>';
              }
              $event = $upcoming[$n];
              $category = get_event_category( $event->ID );
        ?>
              <td style="position: relative;padding: 0;vertical-align: top; font-family: 'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;">
                <a href="<?php echo get_the_permalink($event->ID); ?>" class="picture" style="position: relative;background-color: transparent;text-decoration: none;color: #444;cursor: pointer;outline: none;display: block;margin-bottom: 6px;">
                  <img src="<?php echo get_post_thumbnail($event->ID); ?>" width="238" height="134" style="position: relative;border: 0;display: block;">
                </a>
                <div style="margin-bottom: 30px;">
                  <?php if($category): ?>
                    <a href="<?php echo get_category_link($category); ?>" style="position: relative;background-color: transparent;text-decoration: none;font-size: 18px;font-weight: bold;color: #fb4400;cursor: pointer;outline: none;"><?php echo $category->cat_name; ?></a><br>
                  <?php endif ?> 
                  <a href="<?php echo get_the_permalink($event->ID); ?>" style="position: relative;background-color: transparent;text-decoration: none;font-size: 16px;color: #222;cursor: pointer;outline: none;"><?php echo $event->post_title; ?></a>
                </div>
              </td>
        <?php
              $count_in_row ++;
              if ($count_in_row == $per_row) {
                $row += 1;
                $count_in_row = 0;
              }
            }
            echo '</tr>';
          }
        ?>
      </table>

      <div class="line" style="position: relative;height: 1px;margin: 25px 0px;background: #e6e6e6;"><div class="red" style="position: relative;width: 60px;height: 1px;background: #fb4400;"></div></div>

      <h2 style="position: relative;margin: 0px;padding: 0px;margin-bottom: 15px;font-size: 18px;font-weight: 300;"><?php _e("Événements enregistrés", "tcn"); ?></h2>
      <table class="events-grid-small" style="position: relative;border-collapse: collapse;border-spacing: 0;margin-bottom: 35px;">
        <?php
          if (count($recent) > 0) {
            $row = 0;
            $count_in_row = 0;
            $per_row = 3;
            for ($n = 0; $n < min(count($recent), 3); $n ++) {
              if ($count_in_row == 0) {
                if ($row > 0) {
                  echo '</tr>';
                }
                echo '<tr style="position: relative;">';
              }
              if ($count_in_row > 0) {
                echo '<td class="sep" style="position: relative;padding: 0;width: 7px;"></td>';
              }
              $event = $recent[$n];
        ?>
              <td style="position: relative;padding: 0;vertical-align: top; font-family: 'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;">
                <a href="<?php echo get_the_permalink($event->ID); ?>" class="picture" style="position: relative;background-color: transparent;text-decoration: none;color: #444;cursor: pointer;outline: none;display: block;margin-bottom: 16px;">
                  <img src="<?php echo get_post_thumbnail($event->ID); ?>" width="164" height="92" style="position: relative;border: 0;display: block;">
                </a>
                <p class="title" style="position: relative;margin: 0px;padding: 0px;font-size: 16px;color: #222;"><a href="<?php echo get_the_permalink($event->ID); ?>" style="position: relative;background-color: transparent;text-decoration: none;color: #222;cursor: pointer;outline: none;"><?php echo $event->post_title; ?></a></p>
              </td>
        <?php
              $count_in_row ++;
              if ($count_in_row == $per_row) {
                $row += 1;
                $count_in_row = 0;
              }
            }
            echo '</tr>';
          }
        ?>
      </table>

      <div class="line" style="position: relative;height: 1px;margin: 25px 0px;background: #e6e6e6;"><div class="red" style="position: relative;width: 60px;height: 1px;background: #fb4400;"></div></div>

      <h2 style="position: relative;margin: 0px;padding: 0px;margin-bottom: 15px;font-size: 18px;font-weight: 300;"><?php _e(" Articles récents", "tcn"); ?></h2>
      <table class="stories-grid" style="position: relative;border-collapse: collapse;border-spacing: 0;">
        <?php
          if (count($stories) > 0) {
            $row = 0;
            $count_in_row = 0;
            $per_row = 2;
            for ($n = 0; $n < min(count($stories), 2); $n ++) {
              if ($count_in_row == 0) {
                if ($row > 0) {
                  echo '</tr>';
                }
                echo '<tr style="position: relative;">';
              }
              if ($count_in_row > 0) {
                echo '<td class="sep" style="position: relative;padding: 0;width: 40px;"></td>';
              }
              $story = $stories[$n];
        ?>
              <td style="position: relative;padding: 0;vertical-align: top; font-family: 'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;">
                <p class="category" style="position: relative;margin: 0px;padding: 0px;margin-bottom: 4px;font-size: 16px;font-weight: bold;color: #222;"><a href="<?php echo get_the_permalink($story->ID ); ?>" style="position: relative;background-color: transparent;text-decoration: none;color: #222;cursor: pointer;outline: none;">
                    <?php if($story->post_author == 1): ?>
                        Actualités
                    <?php else: ?>
                        Blog
                    <?php endif ?>
                    </a></p>
                <p class="title" style="position: relative;margin: 0px;padding: 0px;margin-bottom: 16px;font-size: 16px;font-weight: 300;color: #222;"><a href="<?php echo get_the_permalink($story->ID ); ?>" style="position: relative;background-color: transparent;text-decoration: none;color: #222;cursor: pointer;outline: none;"><?php echo $story->post_title; ?></a></p>
                <div class="text" style="position: relative;font-size: 13px;color: #646568;line-height: 1.5;">
                  <?php generate_excerpt($story->ID ); ?>
                </div>
              </td>
        <?php
              $count_in_row ++;
              if ($count_in_row == $per_row) {
                $row += 1;
                $count_in_row = 0;
              }
            }
            echo '</tr>';
          }
        ?>
      </table>

    </div>
  </div>

  <div id="footer" style="position: relative;height: 66px;background: #262626;text-align: right;">
    <div class="container" style="position: relative;width: 506px;margin: 0 auto;padding: 0px 45px;">
      <div class="social" style="float: right; height: 36px; padding-top: 14px; padding-left: 14px;">
        <a href="https://www.facebook.com/lratcn" style="position: relative;background-color: transparent;text-decoration: none;color: #444;cursor: pointer;outline: none;display: block;width: 39px;height: 36px;float: left;margin-left: 4px;">
          <span style="position: relative;display: inline-block;background: url('<?php echo $image_path; ?>email_facebook.png');height: 36px;width: 39px;"></span>
        </a><a href="https://twitter.com/lratcn" style="position: relative;background-color: transparent;text-decoration: none;color: #444;cursor: pointer;outline: none;display: block;width: 39px;height: 36px;float: left;margin-left: 4px;">
          <span style="position: relative;display: inline-block;background: url('<?php echo $image_path; ?>email_twitter.png');height: 36px;width: 39px;"></span>
        </a>
      </div>
      <a href="http://lereseauaidant.ca/contact/" class="contact" style="float: right; background-color: transparent;text-decoration: none;color: white;cursor: pointer;outline: none;font-size: 13px;line-height: 66px;"><?php _e("Contact", "tcn"); ?></a>
    </div>
  </div>
  
</body>
</html>