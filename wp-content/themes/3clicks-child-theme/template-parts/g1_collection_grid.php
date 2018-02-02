<?php
/**
 * The Template for displaying work archive|index.
 *
 * The HTMl markup of our collection is a little weird,
 * but we need it to achieve a grid of inline-block items
 *
 * You can read more about it here:
 * http://blog.mozilla.org/webdev/2009/02/20/cross-browser-inline-block/
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
    global $wp_query;

    // Our tricky way to get variables that were passed to this template part
    $g1_data = g1_part_get_data();
    $g1_query = $g1_data['query'];
    echo '<pre>';
    // print_r($g1_query);
    echo '</pre>';
    $g1_query = $g1_query ? $g1_query : $wp_query;
    $g1_collection = $g1_data['collection'];
    $g1_elems = $g1_data['elems'];
    $g1_options = !empty($g1_data['options']) ? $g1_data['options'] : array();
    $range = 0;
?>

<?php do_action( 'g1_collection_before', $g1_collection, $g1_query); ?>

<?php 
if( is_category())
{   
    get_template_part('template-parts/tcn_chunk', 'tune-in'); 
    $cat_id = get_query_var('cat');
    if(isset($_GET['mode']))
        $mode = $_GET['mode'];
    else
        $mode = '';
    
    if($mode === 'upcoming')
    {
        
        $events = get_upcoming_events_in_category($cat_id);
        $paged = 0;
        if(isset($g1_query->query['paged']))
        {
            $paged = $g1_query->query['paged'] - 1;
        }
        $total_event_count = count($events);
        $events = array_slice($events, $paged * 12, 12);
    }
    else if($mode === 'most_viewed')
    {
        $events = get_most_viewed_events_in_category($cat_id);
        $paged = 0;
        if(isset($g1_query->query['paged']))
        {
            $paged = $g1_query->query['paged'] - 1;
        }
        $total_event_count = count($events);
        $events = array_slice($events, $paged * 12, 12);
    }   
    else
    {
        $events = get_all_events_in_category($cat_id);
        $paged = 0;
        if(isset($g1_query->query['paged']))
        {
            $paged = $g1_query->query['paged'] - 1;
        }
        $total_event_count = count($events);
        $events = array_slice($events, $paged * 12, 12);

    }
    ?>
    
    <?php $no_upcoming = false;
    if($mode === 'upcoming' && count($events) == 0)
    {
        $no_upcoming = true;
    }
    ?>
    
    <!-- BEGIN: .g1-collection -->
    <div class="g1-masonry-wrapper tcn-events-thumbnails tcn-events-thumbnails-three">
        <div class="g1-collection g1-collection--grid g1-collection--one-third g1-collection--masonry g1-effect-none">
            <?php if($no_upcoming): ?>
                <?php _e("There are no upcoming events in this channel.", "tcn"); ?>
            <?php else: ?>
            <ul class="events">
                <?php foreach($events as $post): ?>
                    <li class="g1-collection__item event">
                        <div class="event-inner">
                            <?php
                                // Our tricky way to pass variables to a template part
                                g1_part_set_data( array(
                                    'collection' => $g1_collection,
                                    'elems'  => $g1_elems,
                                    'options' => $g1_options
                                ));
                                get_template_part( 'template-parts/g1_content_grid_item' );
                            ?>
                        </div>
                    </li>
                <?php endforeach ?>
            </ul>
            <?php endif ?>
        </div>
    </div>
    
<?php
}
else
{
    ?>
    <!-- BEGIN: .g1-collection -->
    <div class="g1-masonry-wrapper tcn-events-thumbnails tcn-events-thumbnails-three">
        <div class="g1-collection g1-collection--grid g1-collection--one-third g1-collection--masonry g1-effect-none">
            <ul class="events">
                <?php while ( $g1_query->have_posts() ): $g1_query->the_post(); ?>
                    <?php if(! is_event_private(get_the_ID())): ?>
                    <li class="g1-collection__item event">
                        <div class="event-inner">
                            <?php

                                // Our tricky way to pass variables to a template part
                                g1_part_set_data( array(
                                    'collection' => $g1_collection,
                                    'elems'  => $g1_elems,
                                    'options' => $g1_options
                                ));

                                get_template_part( 'template-parts/g1_content_grid_item' );
                            ?>
                        </div>
                    </li>
                    <?php endif ?>
                <?php endwhile ?>
            </ul>
        </div>
    </div>
    <?php
}
?>


<!-- END: .g1-collection -->
<?php wp_reset_postdata(); ?>

<?php do_action( 'g1_collection_after', $g1_collection, $g1_query ); ?>

<?php

if(!$no_upcoming)
{
// echo 'g1_collection_grid<br/>';
$page = G1_Pagination();
$page->set_range($total_event_count/12);
// echo $page->get_range();
$page->render();         
// echo $total_event_count;       
}
?>
