<?php
/**
 * Template Name: TCN: Account Verification Success
 *
 * For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package G1_Framework
 * @subpackage G1_Theme03
 * @since G1_Theme03 1.0.0
 */
 
?>

<?php get_header(); ?>
    <!-- TODO BROULEAU -->
    
    <h2><?php _e("Account Validation", "tcn"); ?></h2>
    <p>
        <span><?php _e("Success!"); ?></span><span><?php _e("Your account is now validated.", "tcn"); ?></span><br />
        <span><?php _e("You can", "tcn"); ?> <a href="<?php site_url(); ?>/login/"> <?php _e("log in here", "tcn"); ?></a> <?php _e("or by selecting log in at the top of the page", "tcn"); ?></span>
    </p>

<?php get_footer(); ?>