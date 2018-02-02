<?php
/**
 * Template Name: TCN: About
 *
 * For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package G1_Framework
 * @subpackage G1_Theme03
 * @since G1_Theme03 1.0.0
 */

// Prevent direct script access
if ( !defined('ABSPATH') )
    die ( 'No direct script access allowed' );
?>

<?php $image_path = get_stylesheet_directory_uri() .'/images/'; ?>

<?php get_header(); ?>
        <div id="primary">
            <div id="content" role="main">
                <div class="tcn-about">
                    <h2 class="tcn-page-title">
                        <?php _e("The Caregiver Network", "tcn"); ?>
                    </h2>
                    <div class="one-third">
                        <div class="left-content">
                            <h2><?php _e("Our Mission", "tcn"); ?></h2>
                            <p>
                            <?php _e("TCN is Canada’s largest online learning network supporting family caregivers, their loved ones and the health care professionals who work on their behalf.", "tcn"); ?>
                            </p>
                            <p>
                                <?php if(ICL_LANGUAGE_CODE == 'en'): ?>
                                    <a class="tcn-about-link" href="<?php echo site_url(); ?>"><?php _e("Check out our upcoming events >", "tcn"); ?></a>
                                <?php else: ?>
                                    <a class="tcn-about-link" href="<?php echo site_url(); ?>"><?php _e("Check out our upcoming events >", "tcn"); ?></a>
                                <?php endif ?>
                            </p>
                        </div>
                        <div class="center-content">
                            <h2><?php _e("What We Do", "tcn"); ?></h2>
                            
                            <p>
                                <?php _e("We host free educational events in partnership with associations across the country. Experts lead our events, sharing up to date information and responding to questions from participants. Our goal is simple:  help you better navigate the care journey.", "tcn" ); ?>
                            </p>
                            <p>
                                <?php if(ICL_LANGUAGE_CODE == 'en'): ?>
                                    <a class="tcn-about-link" href="<?php echo site_url(); ?>/partners/"><?php _e("Meet our partners >", "tcn"); ?></a>
                                <?php else: ?>
                                    <a class="tcn-about-link" href="<?php echo site_url(); ?>/nos-partenaires/"><?php _e("Meet our partners >", "tcn"); ?></a>
                                <?php endif ?>
                            </p>
                        </div>
                        <div class="right-content">
                            <h2><?php _e("How We Do It", "tcn"); ?></h2>
                            <p>
                                <?php _e("Our remote learning events are easy to access from most anywhere and either require an internet or phone connection. All of our event are recorded and archived on our site for anytime online viewing by visitors and subscribers.", "tcn" ); ?>
                            </p>
                            <p>
                                <?php if(ICL_LANGUAGE_CODE == 'en'): ?>
                                    <a class="tcn-about-link" href="<?php echo site_url(); ?>/login/?callout=true"><?php _e("Join our community >", "tcn"); ?></a>
                                <?php else: ?>
                                    <a class="tcn-about-link" href="<?php echo site_url(); ?>/login-fr/?callout=true"><?php _e("Join our community >", "tcn"); ?></a>
                                <?php endif ?>
                            </p>
 
                        </div>
                    </div>
                    
                    <div class="tcn-about-thumbs-up-container">
                        <img src="<?php echo $image_path; ?>/about_thumbs_up_banner.png" />
                    </div>
                    
                    <h2 class="tcn-companies">
                        <?php _e("The companies and organizations that support our work", "tcn"); ?>
                    </h2>
                    
                    <h3 class="tcn-non-profit">
                        <?php _e("We’re a non profit and give away our product for free. We’re grateful for the help.", "tcn"); ?>
                    </h3>
                    
                    <div class="tcn-sponsors-container">
                        <?php the_content(); ?>
                    </div>
                    
                    <div class="tcn-contact-us-button">
                        <?php if(ICL_LANGUAGE_CODE == 'en'): ?>
                            <a href="<?php echo site_url(); ?>/contact-us/" class="button-big">
                        <?php else: ?>
                            <a href="<?php echo site_url(); ?>/contact/" class="button-big">
                        <?php endif ?>
                            <?php _e("Contact Us", "tcn"); ?>
                        </a>
                    </div>
                </div>
            </div><!-- #content -->
        </div><!-- #primary -->

        
<?php get_footer(); ?>