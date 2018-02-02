<?php
    $posts = get_popular_channel_posts(4);
    // echo count($posts);
    // echo '<pre>';
    // print_r($posts);
    // echo '</pre>';
?>
                    
<div class="g1-isotope-wrapper tcn-home-channels">
    <!-- BEGIN: .g1-collection -->
    <div class="g1-collection g1-collection--grid g1-collection--one-fourth g1-collection--filterable g1-effect-none">
        <ul style="position: relative; overflow: visible" class="isotope">
        <?php
            foreach($posts as $category_id => $post)
            {
                include(locate_template('template-parts/tcn_home-popular-channel.php'));
            }
        ?>
        </ul>
    </div>
    <!-- END: .g1-collection -->
</div>