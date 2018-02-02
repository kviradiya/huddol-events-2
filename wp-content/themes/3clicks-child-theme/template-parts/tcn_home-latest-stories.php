<?php
    $posts = get_latest_stories(4);
?>
                    
<div class="g1-isotope-wrapper tcn-home-stories">
    <!-- BEGIN: .g1-collection -->
    <div class="g1-collection g1-collection--grid g1-collection--one-half g1-collection--filterable g1-effect-none">
        <ul style="position: relative; overflow: visible;" class="isotope"><!-- -->
        <?php
            foreach($posts as $post)
            {
                include(locate_template('template-parts/tcn_home-latest-story.php'));
            }
    ?>
        </ul>
    </div>
    <!-- END: .g1-collection -->
</div>