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
          <a href="<?php echo site_url(); ?>" class="logo" style="background-color: transparent; text-decoration: none; cursor: pointer; outline: none; display: block;"><img src="<?php echo $image_path; ?>email_logo.png" style="width: 247px; height: 49px;"></img></a>
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

      <h2 style="position: relative;margin: 0px;padding: 0px;margin-bottom: 15px;font-size: 18px;font-weight: 300;color: #ee3a29;">
        <?php _e("Confirming your subscription", "tcn"); ?>
      </h2>
      <h4 style="position: relative;margin: 0px;padding: 0px;margin-bottom: 20px;margin-top: 24px;font-size: 14px;font-weight: normal;line-height: 1.75;">
        <?php _e("This is to confirm that you are now subscribed to The Caregiver Network.", "tcn" ); ?></h4>
      
      <div style="position: relative;margin: 0px;padding: 0px;margin-bottom: 20px;margin-top: 24px;font-size: 18px; color:black;font-weight: bold;line-height: 1.75;"><?php _e("Discover how to register for our learning events.", "tcn" ); ?></div>
      <div style="background-color: #ef3e29;border: 1px solid #000;color: white; width:190px; height:68px;text-align: center;text-decoration: none;border-radius: 12px;"><a href="https://www.youtube.com/watch?v=Qi7LSPJbnFE" style="font-family: 'Open Sans';font-size: 18px;line-height: 65px; color:white; text-decoration:none; font-weight:bold;"><?php _e("Watch Video", "tcn" ); ?></a></div>

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
      <a href="<?php echo site_url(); ?>/contact-us/" class="contact" style="float: right; background-color: transparent;text-decoration: none;color: white;cursor: pointer;outline: none;font-size: 13px;line-height: 66px;"><?php _e("Contact Us", "tcn"); ?></a>
    </div>
  </div>
  
</body>
</html>