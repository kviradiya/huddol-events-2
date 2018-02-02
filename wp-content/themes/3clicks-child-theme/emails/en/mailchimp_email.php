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
    
    <div id="header" style="padding-top: 34px; text-align: center;">
      <a href="<?php echo site_url(); ?>">
        <img src="<?php echo $image_path; ?>email_logo_square.png" width="196" height="196">
      </a>
    </div>

    <div id="content" style="min-height: 350px; padding-top: 28px; padding-bottom: 50px; text-align: center;">

      <h2 style="margin: 0px; padding: 0px; margin-bottom: 15px; font-size: 18px; font-weight: 300; color: #ee3a29;">
        <?php _e("Mise à jour de vos préférences", "tcn"); ?>
      </h2>
      <p style="margin: 0px; padding: 0px; font-size: 14px; font-weight: normal; line-height: 1.75;">
        <?php _e("Nous mettons à jour notre liste de courriel afin de mieux répondre à vos besoins.", "tcn"); ?>
      </p>
      <p style="margin: 0px; padding: 0px; margin-bottom: 8px; font-size: 14px; font-weight: normal; line-height: 1.75;">
        <?php _e("Veuillez indiquer comment vous désirez recevoir nos annonces à l'avenir.", "tcn"); ?>
      </p>
       <?php /*
      <p style="margin: 0px; padding: 0px; margin-bottom: 32px; font-size: 14px; font-weight: bold; line-height: 1.75;">
        (<span style="color: #ee3a29;">Remarque</span> : si vous souhaitez <span style="color: #ee3a29;">TOUT</span> connaître de nos télé-événements à venir, blog et nouvelles, veuillez sélectionner les deux options ci-dessous.)
      </p>
      */ ?>

      <h2 style="margin: 0px; padding: 0px; margin-bottom: 15px; font-size: 18px; font-weight: 300; color: #ee3a29;">
        <?php _e("Updating Your Preferences", "tcn"); ?>
      </h2>
      <p style="margin: 0px; padding: 0px; font-size: 14px; font-weight: normal; line-height: 1.75;">
        <?php _e("We’re updating our email list to better meet your needs.", "tcn"); ?>
      </p>
      <p style="margin: 0px; padding: 0px; margin-bottom: 8px; font-size: 14px; font-weight: normal; line-height: 1.75;">
        <?php _e("Please let us know how you want to receive information from us in the future."); ?>
      </p>
      <?php /*
      <p style="margin: 0px; padding: 0px; margin-bottom: 32px; font-size: 14px; font-weight: bold; line-height: 1.75;">
        (<span style="color: #ee3a29;">Note</span> : if you would like to know about <span style="color: #ee3a29;">ALL</span> of our upcoming tele-events, blog posts and news, please select both language options below.)
      </p>
      */ ?>
      
       <p>
        <a href="http://lereseauaidant.ca/mailchimp-2/" style="margin: 0px; padding: 0px; margin-bottom: 15px; font-size: 18px; font-weight: 300; color: #ee3a29;">      <img src="<?php echo $image_path ?>mailchimp_fr.PNG" />
               </a>
    </p>
    
      <p>
          <a href="http://thecaregivernetwork.ca/mailchimp/" style="margin: 0px; padding: 0px; margin-bottom: 15px; font-size: 18px; font-weight: 300; color: #ee3a29;">
              <img src="<?php echo $image_path ?>mailchimp_en.PNG" />
             </a>
            </p>
            
           
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
    </div>
  </div>
  
</body>
</html>