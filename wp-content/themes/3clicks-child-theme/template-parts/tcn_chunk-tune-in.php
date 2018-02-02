<?php
if(is_category())
{
    $cat_id = get_query_var('cat');
    $cat = get_category($cat_id);
    $cat_url = $cat->slug . '/';
}
else
{
    $cat_url = '';
}
?>

    <?php $cat = get_queried_object(); ?>
    <div class="g1-isotope-filters tcn-channel-switcher">
        <div>
            <ul class="meta option-set" data-isotope-filter-group="post_tag">
                <?php
                    $all = false;
                    $upcoming = false;
                    $most_viewed = false;
 
                    if(isset($_GET['mode']))
                    {
                        $upcoming = $_GET['mode'] === 'upcoming';
                        $all = $_GET['mode'] === 'all';
                        $most_viewed = $_GET['mode'] === 'most_viewed';
                    }
                    else
                    {
                        $all = true;
                    }

                ?>

                <li class="g1-isotope-filter<?php if($all) echo ' g1-isotope-filter--current' ?>">                    
                        <a href="<?php echo site_url() ?>/channel/<?php echo $cat_url; ?>?mode=all" data-isotope-filter-value="">
                            <?php _e("All Events", "tcn"); ?>
                        </a>
                </li>
            
                <li class="g1-isotope-filter<?php if($upcoming) echo ' g1-isotope-filter--current' ?>">                    
                        <a href="<?php echo site_url() ?>/channel/<?php echo $cat_url; ?>?mode=upcoming" data-isotope-filter-value="">
                            <?php _e("Upcoming", "tcn"); ?>
                        </a>
                </li>
                
                <li class="g1-isotope-filter<?php if($most_viewed) echo ' g1-isotope-filter--current' ?>">
                        <a href="<?php echo site_url() ?>/channel/<?php echo $cat_url; ?>?mode=most_viewed" data-isotope-filter-value="">
                            <?php _e("Most Viewed", "tcn"); ?>
                        </a>
                </li>
                
                <?php if($post->ID != 64): ?>
                    <?php
                        $category_tags = get_category_tags($cat->term_id);
                    ?>
                    
                    <?php foreach($category_tags as $ct): ?>
                        <?php $ct = get_tag($ct); ?>
                        <li class="g1-isotope-filter">
                            <a href="<?php echo get_tag_link($ct); ?>" data-isotope-filter-value="">
                                <?php echo $ct->name ?>
                            </a>
                        </li>
                    <?php endforeach ?>
                <?php endif ?>
            </ul>
        </div>
    </div>