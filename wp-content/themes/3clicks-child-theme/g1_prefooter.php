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
                <h2><?php _e("About Huddol", "tcn"); ?></h2>
            
                <h4>
                    <?php _e("We created Huddol because we believe that caring better means caring together. The Huddol community helps you build your own dedicated support team. Connect with others like you and our network of helping professionals.", "tcn"); ?>
                </h4>
        
                <?php if(ICL_LANGUAGE_CODE == 'en'): ?>
                    <a href="https://www.huddol.com" class="button-big" target="_blank">
                <?php else: ?>
                    <a href="https://www.huddol.com/fr" class="button-big" target="_blank">
                <?php endif ?>
                    <?php _e("Join the Huddol Community", "tcn"); ?>
                </a>
            </div>
        </div>
    <?php endif ?>
</aside>
<!-- END #g1-prefooter -->