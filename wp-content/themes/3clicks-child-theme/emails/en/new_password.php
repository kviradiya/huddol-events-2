<?php
    $image_path = get_stylesheet_directory_uri().'/images/';
    $english_url = 'http://thecaregivernetwork.ca/wp-login.php?action=rp&key='. $key. '&login=' . $email_address;
    $french_url = 'http://lereseauaidant.ca/wp-login.php?action=rp&key='. $key. '&login=' . $email_address;
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

    <div id="content" style="min-height: 350px; padding-top: 28px; padding-bottom: 50px">
        <h1 style="margin: 0px; padding: 0px; margin-bottom: 15px; font-size: 24px; font-weight: 300; color: #ee3a29;">*IMPORTANT* (english below)</h2>
        <h2 style="margin: 0px; padding: 0px; margin-bottom: 15px; font-size: 18px; font-weight: 300; color: #ee3a29;">Réinitialiser mot de passe / Resetting your password</h2>
      <h2 style="margin: 0px; padding: 0px; margin-bottom: 15px; font-size: 18px; font-weight: 300;">
        Cher membres,
      </h2>
      <p style="margin: 0px; padding: 0px; font-size: 14px; font-weight: normal; line-height: 1.75;">
        Nous avons récemment lancé notre nouveau site Web.
      </p>
      <p style="margin: 0px; padding: 0px; margin-bottom: 8px; font-size: 14px; font-weight: normal; line-height: 1.75;">
          Afin de maintenir des normes de sécurité élevées et de protéger les informations de 
          nos abonnés, nous vous demandons de réinitialiser votre mot de passe.
      </p>
      <p style="margin: 0px; padding: 0px; margin-bottom: 32px; font-size: 14px; font-weight: bold; line-height: 1.75;">
          Le mot de passe que vous avez utilisé précédemment ne fonctionnera pas sur 
          notre nouveau site Web. Veuillez cliquer sur le bouton ci-dessous et suivre les
          instructions.
      </p>

      <p style="margin: 0px; padding: 0px; margin-bottom: 32px; font-size: 14px; font-weight: normal; line-height: 1.75;">
          Si vous éprouvez des difficultés, <a href="http://lereseauaidant.ca/contact">contactez-nous</a> et nous serons heureux de vous 
          aider.
      </p>
      
      <p style="margin: 0px; padding: 0px; margin-bottom: 32px; font-size: 14px; font-weight: normal; line-height: 1.75;">
          Nous espérons que vous appréciez le nouveau site du Réseau aidant.
      </p>
      
      <a href="<?php echo network_site_url('wp-login.php?action=rp&key='. $key. '&login=' . rawurlencode($user_login), 'login'); ?>">
      
      <img src="<?php echo $image_path ?>reset_password_french.png" />
  </a>
    </div>
  
    <h2 style="margin: 0px; padding: 0px; margin-bottom: 15px; font-size: 18px; font-weight: 300;">
      Dear Valued Member,
    </h2>
    <p style="margin: 0px; padding: 0px; font-size: 14px; font-weight: normal; line-height: 1.75;">
      We recently launched our new website. 
    </p>
    <p style="margin: 0px; padding: 0px; margin-bottom: 8px; font-size: 14px; font-weight: normal; line-height: 1.75;">
        In order to maintain the highest security standards and protect the information of 
        our subscribers, we are asking you to reset your password.
    </p>
    <p style="margin: 0px; padding: 0px; margin-bottom: 32px; font-size: 14px; font-weight: bold; line-height: 1.75;">
        The password you used previously will not work on our new website. Please 
        click the button below and follow the steps provided.
    </p>

    <p style="margin: 0px; padding: 0px; margin-bottom: 32px; font-size: 14px; font-weight: normal; line-height: 1.75;">
        If you experience any difficulties, please <a href="http://thecaregivernetwork.ca/contact-us">contact us</a> and we'll be glad to help. 
    </p>
    
    <p style="margin: 0px; padding: 0px; margin-bottom: 32px; font-size: 14px; font-weight: normal; line-height: 1.75;">
          We hope you enjoy the all new Caregiver Network. 
      </p>
          
      
      <a href="<?php echo 'http://lereseauaidant.ca/wp-login.php?action=rp&key='. $key. '&login=' . rawurlencode($user_login); ?>">
          <img src="<?php echo $image_path ?>reset_password_english.png" style="margin-bottom: 20px"/>
         </a>
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
    <a href="<?php echo site_url(); ?>/contact-us/" class="contact" style="float: right; background-color: transparent;text-decoration: none;color: white;cursor: pointer;outline: none;font-size: 13px;line-height: 66px;"><?php _e("Contact Us", "tcn"); ?></a>
  </div>
  
</body>
</html>