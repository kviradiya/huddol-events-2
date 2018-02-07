<?php include('header.php'); ?>

<div id="content" style="position: relative;min-height: 350px;padding-top: 20px;padding-bottom: 50px;">

  <h2 style="position: relative;margin: 0px;padding: 0px;margin-bottom: 15px;font-size: 18px;font-weight: 300;color: #29abe2;">
    <?php _e("Resetting your password", "tcn"); ?>
  </h2>
  <h4 style="position: relative;margin: 0px;padding: 0px;margin-bottom: 20px;margin-top: 24px;font-size: 14px;font-weight: normal;line-height: 1.75;">
    <?php _e("Please follow this link to reset your password:", "tcn"); ?>
    <br style="position: relative;">
    <?php $url = network_site_url('https://events.huddol.com/fr/wp-login.php?action=rp&key='. $key. '&login=' . rawurlencode($user_login), 'login'); ?>
    <a href="<?php echo $url; ?>" class="important" style="position: relative;background-color: transparent;text-decoration: none;color: #29abe2;cursor: pointer;outline: none;font-size: 16px;font-weight: bold;">
      <?php echo $url; ?>
    </a>
  </h4>

</div>

<?php include('footer.php'); ?>