<?php
/**
 * The Template Part for displaying the Prefooter Theme Area.
 *
 * The prefooter is a widget-ready theme area below the content and above the footer.
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

<!-- BEGIN #g1-prefooter -->
<aside id="g1-prefooter" class="g1-prefooter">
    <?php if ( is_front_page() ): ?>
        <div class="tcn-footer-about">
            <div class="tcn-footer-about-inner">
                <h2><?php _e("About The Caregiver Network", "tcn"); ?></h2>
            
                <h4>
                    <?php if(ICL_LANGUAGE_CODE == 'en'): ?>
                    The Caregiver Network broadcasts programming for the benefit of caregiving Canadians. Started in 2004 as a pilot project, TCN is now Canada's largest tele-learning network for family caregivers offering content on a broad range of health and wellness issues. We strive to share ideas, information, and resources that affect meaningful change.
                <?php else: ?>
                    Ce qui a d'abord été un projet pilote en 2004 est aujourd'hui le plus grand réseau de téléapprentissage au Canada pour les proches aidants. Le Réseau aidant offre une variété d’ateliers portant sur la santé et le bien-être. Nous nous efforçons de partager des idées, des informations et des ressources qui pourront, peut-être, apporter un changement significatif dans la vie des proches aidants.
                <?php endif ?>
                </h4>
        
                <!-- TODO JHILL: FRENCH -->
                
                <?php if(ICL_LANGUAGE_CODE == 'en'): ?>
                    <a href="<?php echo site_url(); ?>/about/" class="button-big">
                <?php else: ?>
                    <a href="/apropos/" class="button-big">
                <?php endif ?>
                    <?php _e("Learn more about TCN", "tcn"); ?>
                </a>
            </div>
        </div>
    <?php endif ?>
</aside>
<!-- END #g1-prefooter -->