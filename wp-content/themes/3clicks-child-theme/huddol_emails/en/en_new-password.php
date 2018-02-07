<?php include('header.php'); ?>
<h2 style="margin: 0px; padding: 0px; margin-bottom: 15px; font-size: 18px; font-weight: 300; color: #29abe2;">Resetting your password</h2>
<h2 style="margin: 0px; padding: 0px; margin-bottom: 15px; font-size: 18px; font-weight: 300;">
  Dear Valued Member,
</h2>
<p style="margin: 0px; padding: 0px; font-size: 14px; font-weight: normal; line-height: 1.75;">
  We recently launched our new website.
</p>
<p style="margin: 0px; padding: 0px; margin-bottom: 8px; font-size: 14px; font-weight: normal; line-height: 1.75;">
  In order to maintain the highest security standards and protect the information of our subscribers, we are asking you to
  reset your password.
</p>
<p style="margin: 0px; padding: 0px; margin-bottom: 32px; font-size: 14px; font-weight: bold; line-height: 1.75;">
  The password you used previously will not work on our new website. Please click the button below and follow the steps provided.
</p>

<p style="margin: 0px; padding: 0px; margin-bottom: 32px; font-size: 14px; font-weight: normal; line-height: 1.75;">
  If you experience any difficulties, please
  <a href="https://events.huddol.com/contact-us">contact us</a> and we'll be glad to help.
</p>

<p style="margin: 0px; padding: 0px; margin-bottom: 32px; font-size: 14px; font-weight: normal; line-height: 1.75;">
  We hope you enjoy the all new Caregiver Network.
</p>


<a href="<?php echo 'https://events.huddol.com/wp-login.php?action=rp&key='. $key. '&login=' . rawurlencode($user_login); ?>">
  <img src="<?php echo $image_path ?>reset_password_english.png" style="margin-bottom: 20px" />
</a>

<?php include('footer.php'); ?>