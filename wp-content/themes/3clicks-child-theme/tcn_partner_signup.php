<?php
/**
 * Template Name: TCN: Partner Signup
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
            <div id="content" role="main" >
                <h2 class="tcn-page-title">
                    <?php _e("Becoming a Partner", "tcn"); ?>
                </h2>
                
                <div class="tcn-partner-signup">
                    <h2 class="tcn-partner-signup-title">
                        <?php _e("What Our Partners Say About Us", "tcn"); ?>
                    </h2>
                
                    <h3 class="tcn-partner-signup-subtitle">
                        <?php _e("How remote learning technology changes the way they connect.", "tcn"); ?>
                    </h3>
                    
                    <div class="tcn-partner-signup-banner">
                        <img src="<?php echo $image_path; ?>/partner_signup_divider.png">
                    </div>
                    
                    <ul class="g1-grid">
                        <li class="g1-column g1-one-half g1-valign-top">
                            <figure id="g1-quote-1" class="g1-quote g1-quote--solid g1-quote--small ">
                                <div class="g1-inner">
                                    <p>
                                        <?php if(ICL_LANGUAGE_CODE == 'en'): ?>
                                            “
                                        <?php else: ?>                                            
                                            &laquo; 
                                        <?php endif ?>
                                            
                                        <?php _e("Remote technology has helped our psychosocial counsellors connect with our members who have ALS and cannot physically attend a conference because of their limited mobility. The technology is very easy to access for our users making the overall experience a positive one.", "tcn"); ?>
                                            
                                        <?php if(ICL_LANGUAGE_CODE == 'en'): ?>
                                            ”
                                        <?php else: ?>                                            
                                            &raquo; 
                                        <?php endif ?>
                                    </p>
                                </div>
                                <figcaption class="g1-meta">
                                    <span class="g1-quote__image"><img src="//events.huddol.com/wp-content/uploads/2015/04/als_logo.jpg"></span><strong>Elizabeth Barbosa</strong><span><?php _e("Support Group Coordinator, ALS Society of Quebec", "tcn"); ?></span>
                                </figcaption>
                            </figure>
                        </li>
                        <li class="g1-column g1-one-half g1-valign-top">
                            <figure id="g1-quote-2" class="g1-quote g1-quote--solid g1-quote--small ">
                                <div class="g1-inner">
                                    <p>
                                        <?php if(ICL_LANGUAGE_CODE == 'en'): ?>
                                            “
                                        <?php else: ?>                                            
                                            &laquo; 
                                        <?php endif ?>

                                        <?php _e("The web conferencing platform has allowed our organization to provide better training and support to our staff and volunteers from across the province. Externally, this has also allowed our target audience to access education and information in a way that accommodates their needs.", "tcn"); ?>
                                        <?php if(ICL_LANGUAGE_CODE == 'en'): ?>
                                            ”
                                        <?php else: ?>                                            
                                            &raquo; 
                                        <?php endif ?>
                                    </p>
                                </div>
                                <figcaption class="g1-meta">
                                    <span class="g1-quote__image"></span><strong>Carrie</strong><span><?php _e("Client Services Coordinator, Multiple Sclerosis Society of Canada", "tcn"); ?>
                                        
                                        <?php if(ICL_LANGUAGE_CODE == 'en'): ?>
                                            <img src="//events.huddol.com/wp-content/uploads/2015/04/ms_logo.jpg"></span>
                                    <?php else: ?>
                                            <img src="//events.huddol.com/wp-content/uploads/2015/04/ms_logo_fr.jpg"></span>
                                    <?php endif ?>
                                </figcaption>
                            </figure>
                        </li>
                    </ul>

                    <h2 style="margin-top: 30px;"><?php _e("The power of remote technologies", "tcn" ); ?></h2>
                    <div class="tcn-partner-signup-grid">
                        <div class="unit">
                            <div class="inner">
                                <img src="<?php echo $image_path .'like.png'; ?>" width="56" height="56">
                                <h3><?php _e("Agile", "tcn"); ?></h3>
                                <p>
                                    <?php _e("More than ever people are using technology to meet their health needs. They’re doing it with greater speed and efficiency than ever before. That’s an opportunity for organizations to create a greater number of touch points using digital mediums that matter to their members.", "tcn" ); ?>
                                </p>
                            </div>
                        </div><div class="unit">
                            <div class="inner">
                                <img src="<?php echo $image_path .'like_2.png'; ?>" width="56" height="56">
                                <h3><?php _e("Access", "tcn"); ?></h3>
                                <p>
                                    <?php _e("Organizations are faced with the dilemma of how to serve people who are disconnected from help for a variety of reasons. These constraints may be related to time, geography, stigma, or mobility. Distance learning helps build bridges to people in need and increase their access to support through adaptive environments.", "tcn" ); ?>
                                </p>
                            </div>
                        </div><div class="unit">
                            <div class="inner">
                                <img src="<?php echo $image_path.'like.png'; ?>" width="56" height="56">
                                <h3><?php _e("Scale", "tcn"); ?></h3>
                                <p>
                                    <?php _e("How do you ramp up services and programs and help more people without straining resources? Remote technologies can scale up an organization’s capacity without the added strain.", "tcn" ); ?>
                                </p>
                            </div>
                        </div><div class="unit">
                            <div class="inner">
                                <img src="<?php echo $image_path.'like_2.png'; ?>" width="56" height="56">
                                <h3><?php _e("Cost", "tcn"); ?></h3>
                                <p>
                                    
                                    <?php _e("Most non profit organizations are working with very limited means. This puts a premium on servicing people while minimizing pressure on human and financial resources. Remote technologies cost very little but do a whole lot to advance an organization’s social mission.", "tcn"); ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="tcn-partner-become-container">
                        <?php if(ICL_LANGUAGE_CODE == 'en'): ?>
                            <a target="_blank" href="<?php echo site_url(); ?>/wp-content/uploads/2015/02/TCN_BecomingAPartner.pdf" class="button-big">
                        <?php else: ?>
                            <a target="_blank" href="<?php echo site_url(); ?>/wp-content/uploads/2015/03/LRA_devenir-un-partenaire-du-re%CC%81seau.pdf" class="button-big">
                        <?php endif ?>
                        <?php _e("Download Our Partner Guide", "tcn"); ?>
                        </a>
                    </div>

                    <div class="tcn-sep"></div>

                    <h2><?php _e("Connect to learn more about the advantages of partnering with us", "tcn"); ?></h2>
                    <?php the_content(); ?>

                </div>
                
            </div><!-- #content -->
        </div><!-- #primary -->

        
<?php get_footer(); ?>