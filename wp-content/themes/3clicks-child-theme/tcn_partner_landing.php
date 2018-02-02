<?php
/**
 * Template Name: TCN: Partner Landing
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
                <h2 class="tcn-page-title"><?php echo the_title(); ?></h2>
                <div class="tcn-partner-banner-container">
                    
                    <img src="<?php echo $image_path.'partner_landing_logo.png'; ?>" width="47" height="47">
                    
                    <h2 class="tcn-partner-banner-title">
                        <?php _e("We’re all about partnerships", "tcn"); ?>
                    </h2>
                    
                    <p class="tcn-parter-banner-text">
                        <?php if(ICL_LANGUAGE_CODE == 'en'): ?>
                        Our Network Partners are organizations throughout Canada working to support caregivers, patients, and professionals 
                        across many health and social service areas.  They’re experts in their respective fields and share TCN’s social mission. 
                        Together, we work hard to create a diverse programming line up intended to empower our caregiving communities.  
                        <?php else: ?>
                            Notre réseau est composé d’organismes communautaires présents à travers tout le Canada qui soutiennent les proches aidants, les malades et les professionnels de la santé dans le secteur médical et social. Ils sont experts dans leurs domaines et partagent les valeurs sociales du LRA. Ensemble, nous travaillons fort pour créer une programmation diversifiée afin de renforcer l’autonomie de notre communauté de proches aidants.
                        <?php endif ?>
                    </p>
                    
                    
                </div>
                <h3 style="margin-top: 21px; margin-bottom: 21px;"><?php _e("Click to expand", "tcn"); ?></h3>
                <div class="tcn-partner-content">
                <?php
                $index = 20;
                $args = array(
                  'orderby' => 'name',
                  'parent' => 0,
                  'hide_empty' => 1,
                  'pad_counts' => 1
                );

                
                $cats = array();
                $categories = get_categories( $args );
                foreach($categories as $cat)
                {
                    if($cat->name !== 'Uncategorized' && $cat->name !== 'Non classifié(e)')
                        array_push($cats, $cat);
                }
                $chunks = array_chunk($cats, 3);
                
                foreach($chunks as $chunk)
                {
                    ?>
                    <ul class="g1-grid">
                    <?php
                    foreach($chunk as $cat)
                    {
                            
                        ?>
                        <li class="g1-column g1-one-third g1-valign-top"><br>
                        <div class="g1-toggle g1-toggle--off g1-toggle--solid g1-toggle--noicon" id="g1-toggle-counter-<?php echo $index; ?>">
                            <div class="g1-toggle__title">
                                <span class="g1-toggle__switch"></span>
                                <?php echo $cat->name; ?>
                            </div>
                            <div class="g1-toggle__content" style="display: none;"><div class="g1-block"><br>
                                <?php 
                                    $authors = get_category_authors($cat->term_id);
                                    foreach($authors as $author)
                                    {
                                    ?>
                                    <?php if(is_network_partner($author)): ?>
                                        <a href="<?php echo get_author_posts_url($author->ID); ?>">
                                            <?php // echo $author->display_name; ?>
                                            <?php echo get_partner_name($author->ID); ?></a>
                                        <p></p>
                                        <hr/>
                                    <?php endif ?>
                                    <?php    
                                    }
                                ?>
                            
                                <?php /*
                            <a href="/author/alzheimer-society-of-british-columbia/">Alzheimer Society of British Columbia</a><p></p>
                            <hr>
                            <p><a href="/author/british-columbia-psychogeriatric-association/">British Columbia Psychogeriatric Association</a></p>
                            <hr>
                            <p><a href="http://cvnet.test/author/societe-alzheimer-de-montreal/" title="Alzheimer Society of Montreal">Alzheimer Society of Montreal</a></p>
                            <hr>
                            <p><a href="/author/vha-home-healthcare/">VHA Home HealthCare</a></p>
                                */ ?>
                            </div></div>
                        
                        </div>
                    
                    
                    
                    
                    
                        </li>
                    
                        <?php
                        $index++;
                    }
                    ?>
                    </ul>
                <?php
                }
                ?>
            </div>
                
                <?php // the_content(); ?>
                
                
                
                <div class="tcn-partner-become-container">
                    <?php if(ICL_LANGUAGE_CODE == 'en'): ?>
                    <a href="<?php echo site_url(); ?>/becoming-a-partner/" class="button-big">
                <?php else: ?>
                    <a href="<?php echo site_url(); ?>/devenir-partenaire/" class="button-big">
                <?php endif ?>
                        <?php _e("Become A Partner", "tcn"); ?>
                    </a>
                </div>
            </div><!-- #content -->
        </div><!-- #primary -->

        
<?php get_footer(); ?>
