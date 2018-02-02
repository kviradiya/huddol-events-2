<?php
/**
 * Template Name: Page: Right Sidebar
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

$image_path = get_stylesheet_directory_uri() .'/images/';
$event_registration =  new EventRegistration;
$favorites = WeDevs_Favorite_Posts::init();
                        $category = get_event_category( $post->ID );

// Add proper body classes
add_filter( 'body_class', array(G1_Theme(), 'secondary_wide_body_class') );
add_filter( 'body_class', array(G1_Theme(), 'secondary_after_body_class') );
?>
<?php get_header(); ?>
    <div id="primary">
		<div id="content" role="main" class="tcn-event-details">
		    <?php if(isset($_GET['mode']) && $_GET['mode'] === 'paypal_redirect' &&
		             isset($_GET['paypal_ipn_id'])): ?>
		        
		        <?php while ( have_posts() ) : the_post(); ?>
		            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <div class="event-details-main">                        
            		         <?php $post_id = get_the_ID(); ?>
            		         <?php $post = get_post($post_id); ?>
            		         <h2 class="tcn-page-title">Processing Your Payment</h2>
            		         <h3 class="event-details-title">                                
                                 <?php echo $post->post_title; ?>
                             </h3>
                             <p>
                                 Please click on the PayPal button below to register for this event. 
                             </p>
                             <p>
                                 You will be re-directed to the PayPal website to complete the transaction. 
                             </p>
                     
                             <?php echo $event_registration->get_paypal_button($post_id, $_GET['paypal_ipn_id']); ?>
                             
                             <!--
                             <pre>
                                 <?php print_r( $event_registration->get_pending_paypal($_GET['paypal_ipn_id'])); ?>
                             </pre>
                             -->
                        </div>
                    </article>
                 <?php endwhile ?>
                 
	        <?php else: ?>	        
    			<?php while ( have_posts() ) : the_post(); ?>

                    <?php
                        $post_id = get_the_ID();
                        $itemType =  is_event_over($post->ID) ? 'OnDemandEvent' : 'BroadcastEvent';
                        $price = get_post_meta( $post->ID, 'event_registration_price', true);
                    ?>

                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemtype="http://schema.org/<?php echo $itemType; ?>">

                        <?php if($itemType == 'BroadcastEvent'): ?>
                            <meta itemprop="isLiveBroadcast" content="true" />
                        <?php endif; ?>
                        
                        <?php if(empty($price) || preg_match('/free|gratuit/i', $price)): ?>
                            <meta itemprop="free" content="true" />
                            <meta itemprop="isAccessibleForFree" content="true" />
                        <?php endif; ?>

                        <div class="event-details-main">
                            <?php if($category): ?>
                            <h3 class="event-details-category">
                                <a href="<?php echo get_category_link($category); ?>">
                                    <?php echo $category->cat_name; ?>
                                </a>
                            </h3>
                            <?php endif ?>
                        	
							<?php
							if(is_user_logged_in())
							{
								print_category_reminder($category->term_id);
							}
							?>
                            <?php 

                                $imgURL = get_post_thumbnail($post_id, 'full'); 
                                $imgId = get_post_thumbnail_id( $post_id );
                                $imgAlt = get_post_meta( $imgId, '_wp_attachment_image_alt', true );
                            ?>
                            
                            <?php if(!is_event_over($post->ID)): ?>
                                
                                <?php if($imgURL): ?>
                                    <img class="wp-post-image" src="<?php echo $imgURL; ?>" alt="<?php echo $imgAlt; ?>" />
                                <?php else: ?>
                                    <?php // black_square(); ?>
                                <?php endif ?>
                            <?php else: ?>

                                <div style="margin-bottom: 24px;">
                                <?php
                                    $recording = get_post_meta($post_id, 'event_meta_recording', true);
                                    if($recording != '')
                                    {
                                        if(strpos($recording, 'youtube.com') !== FALSE)
                                        {
                                            echo $recording;
                                        }
                                        else if(strpos($recording, 'soundcloud.com') !== FALSE)
                                        {
                                            echo '<iframe width="100%" height="166" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url='. urlencode($recording) . '"></iframe>';
                                        }
                                        else if(strpos($recording, 'adobeconnect') !== FALSE)
                                        {
                                        ?>
                                        <a target="_blank" href="<?php echo $recording ?>">
                                            
                                            <div style="position: relative;">
                                                <img src="<?php echo $imgURL; ?>">
                                                <img src="<?php echo $image_path; ?>adobe_play.png" 
                                                    style=" width: 109px;
                                                            height: 120px;
                                                            position: absolute;
                                                            left: 0px;
                                                            right: 0px;
                                                            top: 0px;
                                                            bottom: 0px;
                                                            margin: auto;">
                                            </div>
                                        
                                        </a>
                                        <?php
                                        }
                                        else
                                        {
                                        }
                                    }
                                    else
                                    {
                                        ?>
                                        <?php $imgURL = get_post_thumbnail($post_id, 'full');  ?>
                                        <?php if($imgURL): ?>
                                            <img class="wp-post-image" src="<?php echo $imgURL; ?>" alt="<?php echo esc_attr(get_post_meta( get_post_thumbnail_id($post_id), '_wp_attachment_image_alt', true )); ?>">
                                        <?php else: ?>
                                            <?php black_square(); ?>
                                        <?php endif ?>
                                        <?php
                                    }
                                    ?>
                                </div>
                            <?php endif ?>
                    
                            <?php if(! is_event_over($post_id)): ?>
                            <h4 class="event-details-channel">
                                <?php if( get_post_meta($post_id, 'event_meta_type', true) == 0): ?>
                                    <?php _e("Webinar", "tcn"); ?> -
                                <?php endif ?>    
                                <?php if( get_post_meta($post_id, 'event_meta_type', true) == 1): ?>
                                    <?php _e("Teleconference", "tcn"); ?> - 
                                <?php endif ?>    

                                     
                                <?php if( get_post_meta($post_id, 'event_meta_language', true) == 0): ?>
                                    <?php _e("English", "tcn"); ?>
                                <?php endif ?>    
                                <?php if( get_post_meta($post_id, 'event_meta_language', true) == 1): ?>
                                    <?php _e("French", "tcn"); ?>
                                <?php endif ?>
                            </h4>
                            <?php endif ?>
                            
                            <h1 class="event-details-title" itemprop="name">                                
                                <?php echo $post->post_title; ?>
                            </h1>
                            
                            <div class="event-details-date">
                                <time datetime="<?php echo get_event_date_iso($post); ?>" itemprop="startDate"><?php echo get_event_date($post); ?></time>
                            </div>
                            
                            <?php if(!is_event_over($post->ID)): ?>
                            <div>
                                <?php do_action( 'tribe_events_single_event_after_the_content' ); ?>
                            </div>
                            <?php endif ?>
                            
                            <div class="access-meta">
                                <?php if($event_registration->is_event_province_restricted($post->ID)): ?> 
                                    <h4 class="event-details-access">
                                        <?php _e("Access for residents of ", "tcn"); ?> <?php echo $event_registration->get_location_restriction_string($post->ID); ?>
                                    </h4>
                                <?php endif ?>
                                
                                <?php if($event_registration->is_event_role_intended($post->ID)): ?>
                                    <h4 class="event-details-access">
                                        <?php _e("This session is intended for ", "tcn"); ?> <?php echo $event_registration->get_role_intention_string($post->ID); ?>
                                    </h4>
                                <?php endif ?>
                                
                                <?php /*
                                <?php if($event_registration->get_max_registrants($post->ID)): ?>                                
                                    <h4 class="event-details-access">
                                        <?php _e("Maximum Registrants: ", "tcn"); ?> <?php echo $event_registration->get_max_registrants($post->ID); ?>
                                    </h4>
                                <?php endif ?>
                                */ ?>
                               
                            </div>
                    
                            <div class="event-details-description" itemprop="description" >
                                <?php the_content(); ?> 
                            </div>
                    
                            <?php echo g1_render_entry_categories(); ?>
                            <?php echo g1_render_entry_tags(); ?>
                    
                            
                            <?php for($i = 0; $i < 3; $i++): ?>
                            <?php $presenter_name = ''; $presenter_image = ''; $presenter_bio = ''; ?>
                            
                            
                            <?php if(count(get_post_meta($post_id, 'event_meta_presenter_name_' . $i))): ?>
                                <?php $presenter_name = get_post_meta($post_id, 'event_meta_presenter_name_' . $i, true); ?>
                            <?php endif ?>
                            
                            <?php if(count(get_post_meta($post_id, 'event_meta_presenter_image_' . $i))): ?>
                                <?php $presenter_image = get_post_meta($post_id, 'event_meta_presenter_image_' . $i, true); ?>
                            <?php endif ?>
                            
                            <?php if(count(get_post_meta($post_id, 'event_meta_presenter_bio_' . $i))): ?>
                                <?php $presenter_bio = get_post_meta($post_id, 'event_meta_presenter_bio_' . $i, true); ?>
                            <?php endif ?>
                            
                            <?php if($presenter_name != '' && $presenter_image != '' && $presenter_bio != ''): ?>    
                            <section itemtype="http://schema.org/UserComments" itemscope="" id="comments">
                                <div class="g1-replies g1-replies--comments">
                                    <ol class="commentlist">
                        	            <li id="li-comment-2" class="comment byuser comment-author-admin bypostauthor even thread-even depth-1">
        		                            <article id="comment-2" itemtype="http://schema.org/Comment" itemprop="comment" itemscope="">
                                                <header itemtype="http://schema.org/Person" itemscope="" itemprop="author">
                                                    <div itemtype="http://schema.org/Person" itemscope="" itemprop="author">
                                                        <?php $image_post = wp_get_attachment_image_src($presenter_image, 'thumbnail') ?>
                                                        <img style="height: 60px; width: 60px" class="avatar avatar-60 photo" src="<?php echo $image_post[0]; ?>" alt="" itemprop="image">                                       
                                                        <cite><span itemprop="name"><?php _e("Presenter", "tcn"); ?></span></cite><br/>                
                                                        <h3>
                                                            <?php echo $presenter_name ?>
                                                        </h3>              
                                                    </div>
                                                </header>
    
                                                <div class="comment-body" itemprop="text">
                                                    <p>
                                                        <?php echo $presenter_bio; ?>
                                                    </p>
                                                </div>
                                            </article><!-- END: #comment-##  -->
                                        </li><!-- #comment-## -->
                                    </ol>
                                </div>
                            </section>
                            
                            <?php endif ?>
                            <?php endfor; ?>
                    
                            <?php comments_template( '', true ); ?>
                        </div>
                    </article><!-- #post-<?php the_ID(); ?> -->
    			<?php endwhile; ?>
            <?php endif ?>
    	</div><!-- #content -->
    </div><!-- #primary -->
	
		    
    <div role="complementary" class="g1-sidebar widget-area event-details-sidebar" id="secondary">
	    <div class="g1-inner">
	        <aside class="widget widget_search g1-widget--cssclass">
	            <?php include(locate_template('template-parts/tcn_event_sidebar.php')); ?>
		    </aside>

	        <aside class="widget widget_search g1-widget--cssclass event-details-author">
		        <header>
		            <?php _e("Hosted By", "tcn"); ?>
		        </header>
		        <?php $author = get_the_author(); ?>
                <?php $imgURL = get_cupp_meta( get_the_author_meta('ID'), 'small' ); ?>       
                <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>">
                    <div class="author-name"><?php echo get_partner_name(get_the_author_meta('ID')); ?></div>
                    <?php /*<div class="tcn-image" style="height: 165px; background-image: url('<?php echo $imgURL; ?>');"></div>*/ ?>
                    <div>
                        <img src="<?php echo $imgURL; ?>" alt="<?php echo $author; ?>" />
                    </div>
                </a>
            </aside>
        
            <?php $captioned = false; ?>
            <?php for($i = 0; $i < 2; $i++): ?>
            <?php if(count(get_post_meta($post_id, 'event_meta_partnership_' . $i))): ?>
                <?php $partnership_id = get_post_meta($post_id, 'event_meta_partnership_' . $i, true); ?>
                
                <?php $url = ''; ?>
                <?php if(ICL_LANGUAGE_CODE=='en')
                {
                    $url = get_user_meta($partnership_id, 'tcn_partner_english_website', true);
                }
                else
                {
                    $url = get_user_meta($partnership_id, 'tcn_partner_french_website', true);
                } ?>
                                    
                <?php if($partnership_id != -1 && $partnership_id != ''): ?>
                    <?php $partnership = get_userdata($partnership_id); ?>
        		    <section class="widget widget_search g1-widget--cssclass event-details-partnership">
                
                        <?php $imgURL = get_cupp_meta( $partnership->ID, 'small' ); ?>
                        <?php if($captioned == false ): ?>
            		        <header>
            		            <?php _e("With the Support Of", "tcn"); ?>
            		        </header>
            		        <?php $captioned = true; ?>
        		        <?php endif ?>
        		        
        		        <?php if(startsWith($url, 'http://')): ?>
            		        <a target="_blank" href="<?php echo $url; ?>">
                        <?php else: ?>
            		        <a target="_blank" href="http://<?php echo $url; ?>">
                        <?php endif ?>
                        
        		            <div class="partnership-name"><?php echo get_partner_name($partnership->ID);  ?></div>
        		            <div class="tcn-image" style="height: 165px; background-image: url('<?php echo $imgURL; ?>');"></div>
    		            </a>
        		    </section>
        		<?php endif ?>
		    <?php endif ?>
	        <?php endfor ?>
		    
	        <?php
		        include(locate_template('template-parts/tcn_sidebar-related-events.php'));
		    ?>
		    
		    <?php
		        include(locate_template('template-parts/tcn_sidebar-related-posts.php'));
		    ?>
		</div> <!-- #g1-inner -->
	</div> <!-- END #secondary -->
	
<script type="text/javascript">
    jQuery(document).ready(function()
    {
        jQuery("button.register.logged-out").click(function(e) {
            e.preventDefault();
            $message = jQuery(this).parent().find(".italic-message");
            $message.addClass("flash");
            setTimeout(function() {
                $message.addClass("fade-out");
                setTimeout(function() {
                    $message.removeClass("flash fade-out");
                }, 1000);
            }, 500);
        });
        
        //console.log(jQuery(".favorites-logged-out"));
        jQuery(".favorites-logged-out").click(function(e) {
            //console.log("okay");
            e.preventDefault();
            $message = jQuery(".italic-message");
            //console.log($message);
            $message.addClass("flash");
            setTimeout(function() {
                $message.addClass("fade-out");
                setTimeout(function() {
                    $message.removeClass("flash fade-out");
                }, 1000);
            }, 500);
        });
        
        jQuery("button.register.no-access").click(function(e) {
            //console.log(jQuery("#access-errors"));
            jQuery("#access-errors").show(1000);
        });
    });
</script>

<?php get_footer(); ?>