<?php include('header.php'); ?>
  <h2 style="margin: 0px; padding: 0px; margin-bottom: 15px; font-size: 18px; font-weight: 300; color: #29abe2;">Réinitialiser mot de passe </h2>

  <h2 style="margin: 0px; padding: 0px; margin-bottom: 15px; font-size: 18px; font-weight: 300;">
    Cher membres,
  </h2>

  <p style="margin: 0px; padding: 0px; font-size: 14px; font-weight: normal; line-height: 1.75;">
    Nous avons récemment lancé notre nouveau site Web.
  </p>
  <p style="margin: 0px; padding: 0px; margin-bottom: 8px; font-size: 14px; font-weight: normal; line-height: 1.75;">
    Afin de maintenir des normes de sécurité élevées et de protéger les informations de nos abonnés, nous vous demandons de réinitialiser
    votre mot de passe.
  </p>
  <p style="margin: 0px; padding: 0px; margin-bottom: 32px; font-size: 14px; font-weight: bold; line-height: 1.75;">
    Le mot de passe que vous avez utilisé précédemment ne fonctionnera pas sur notre nouveau site Web. Veuillez cliquer sur le
    bouton ci-dessous et suivre les instructions.
  </p>

  <p style="margin: 0px; padding: 0px; margin-bottom: 32px; font-size: 14px; font-weight: normal; line-height: 1.75;">
    Si vous éprouvez des difficultés,
    <a href="https://events.huddol.com/contact">contactez-nous</a> et nous serons heureux de vous aider.
  </p>

  <p style="margin: 0px; padding: 0px; margin-bottom: 32px; font-size: 14px; font-weight: normal; line-height: 1.75;">
    Nous espérons que vous appréciez le nouveau site du Réseau aidant.
  </p>

  <a href="<?php echo network_site_url('fr/wp-login.php?action=rp&key='. $key. '&login=' . rawurlencode($user_login), 'login'); ?>">
    <img src="<?php echo $image_path ?>reset_password_french.png" />
  </a>

<?php include('footer.php'); ?>