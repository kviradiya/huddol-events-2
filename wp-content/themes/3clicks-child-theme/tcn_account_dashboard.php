<?php
/**
 * Template Name: TCN: Account Dashboard
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

<?php
    $mode = 'profile';
    if(isset($_GET['mode']))
    {
        $mode = $_GET['mode'];
    }
    
    $image_path = get_stylesheet_directory_uri() .'/images/';
    $current_user = wp_get_current_user();
?>

<?php get_header(); ?>
        <div id="primary">
            <div id="content" role="main" class="tcn-dashboard">
                <h2 class="tcn-page-title"><?php the_title(); ?></h2>
                <?php if(! $current_user->data): ?>
                    <p><?php _e("You aren't logged in and can't view this page.", "tcn"); ?>
                <?php else: ?>
                    <div class="dashboard-container">
                        <div class="dashboard-menu">
                            <ul class="meta option-set" data-isotope-filter-group="post_tag">
                                <li class="g1-isotope-filter<?php if($mode == 'profile') echo ' g1-isotope-filter--current' ?>">
                                    <a href="?mode=profile" data-isotope-filter-value="">
                                        <img style="vertical-align: middle" src="<?php echo "$image_path/head_icon.png" ?>" /><?php _e("My Profile", "tcn"); ?>
                                    </a>
                                </li>

                                <li class="g1-isotope-filter<?php if($mode == 'upcoming') echo ' g1-isotope-filter--current' ?>">
                                    <a href="?mode=upcoming" data-isotope-filter-value="">
                                        <img style="vertical-align: middle" src="<?php echo "$image_path/upcoming_icon.png" ?>" />
                                        <?php _e("My Upcoming Events", "tcn"); ?>
                                    </a>
                                </li>

                                <li class="g1-isotope-filter<?php if($mode == 'recent') echo ' g1-isotope-filter--current' ?>">
                                    <a href="?mode=recent" data-isotope-filter-value="">
                                        <img style="vertical-align: middle" src="<?php echo "$image_path/recent_icon.png" ?>" />
                                        <?php _e("My Recent Events", "tcn"); ?>
                                    </a>
                                </li>

                                <li class="g1-isotope-filter<?php if($mode == 'favorites') echo ' g1-isotope-filter--current' ?>">
                                    <a href="?mode=favorites" data-isotope-filter-value="">
                                        <img style="vertical-align: middle" src="<?php echo "$image_path/favorites_icon.png" ?>" />
                                        <?php _e("My Favorites", "tcn"); ?>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    
                        <div class="dashboard-content">
                            <?php if($mode == 'profile'): ?>
                                <?php get_template_part('template-parts/tcn_account_dashboard', 'profile'); ?>
                            <?php elseif($mode == 'profile_edit'): ?>
                                <?php get_template_part('template-parts/tcn_account_dashboard', 'profile-edit'); ?>
                            <?php elseif($mode == 'upcoming'): ?>
                                <?php get_template_part('template-parts/tcn_account_dashboard', 'upcoming'); ?>
                            <?php elseif($mode == 'recent'): ?>
                                <?php get_template_part('template-parts/tcn_account_dashboard', 'recent'); ?>
                            <?php elseif($mode == 'favorites'): ?>
                                <?php get_template_part('template-parts/tcn_account_dashboard', 'favorites'); ?>
                            <?php else: ?>
                                <p><?php _e("Invalid Page.", "tcn"); ?></p>
                            <?php endif ?>
                        </div>
                    </div>

                <?php endif ?>
            </div><!-- #content -->
        </div><!-- #primary -->
<?php get_footer(); ?>