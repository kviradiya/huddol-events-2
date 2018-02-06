<?php
/**
 * Template Name: TCN: Home
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
    if(is_user_logged_in())
    {
        $mode = "my_network";
    }
    else
    {
        $mode = "upcoming";
    }
    
    if(isset($_GET["mode"]))
    {
        $mode = $_GET["mode"];
    }
?>

<?php
    $network = new TCNNetwork;
    $network_events = $network->get_upcoming_network_events(wp_get_current_user());
    if(count($network_events) == 0 && $mode === "my_network")
    {
        $mode = "upcoming";
    }
    
    $upcoming_events = array_slice(get_upcoming_events(), 0, 12);
    if(count($upcoming_events) == 0)
    {
        $mode = "most_viewed";
    }
?>

<?php get_header(); ?>
        <div id="primary">
            <div id="content" role="main">
                
                
                <?php 
                    if($mode === "my_network"): ?>
                    <?php get_template_part('template-parts/tcn_home', 'network'); ?>
                    
                <?php
                    elseif( $mode === "upcoming" ): ?>
                    <?php get_template_part('template-parts/tcn_home', 'upcoming'); ?>
                    
                <?php 
                    elseif( $mode === "most_viewed"): ?>
                    <?php get_template_part('template-parts/tcn_home', 'most-viewed'); ?>
                    
                <?php 
                    else: ?>
                    <?php get_template_part('template-parts/tcn_home', 'network'); ?>
                <?php endif ?>
                
                <!--hr style="margin-top:0px"-->
                <?php /* <h2><?php _e("Latest Stories", "tcn"); ?></h2>
                <?php get_template_part('template-parts/tcn_home', 'latest-stories'); */?>
        
                <hr />
                <h2><?php _e("Popular Events", "tcn"); ?></h2>
                <?php get_template_part('template-parts/tcn_home', 'popular-channels'); ?>
        
                <?php get_template_part('template-parts/tcn_home', 'partner'); ?>
            </div><!-- #content -->
        </div><!-- #primary -->

        
<?php get_footer(); ?>
