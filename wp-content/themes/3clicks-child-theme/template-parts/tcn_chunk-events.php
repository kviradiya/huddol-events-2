<?php
    $image_path = get_stylesheet_directory_uri() .'/images/';
    // $events = array_slice($events, 0, 1);
    if (!empty($events)) {
?>

    <div class="tcn-home-slider">
        <?php if( count($events) > 3): ?>
            <div class="slider-arrows">
                <a href="#" class="left"><img src="<?php echo $image_path; ?>arrow_left.png" width="40" height="40" alt="Previous"></a>
                <a href="#" class="right"><img src="<?php echo $image_path; ?>arrow_right.png" width="40" height="40" alt="Next"></a>
            </div>
        <?php endif ?>
        <div class="slider-slides">
            <?php
                $slides = array();
                $slides[] = array();
                $current_slide = 0;
                foreach ($events as $event)
                {
                    if (count($slides[$current_slide]) == 3)
                    {
                        $slides[] = array();
                        $current_slide ++;
                    }
                    $slides[$current_slide][] = $event;
                }

                foreach ($slides as $slide)
                {
            ?><div class="slider-slide">
                    <div class="g1-masonry-wrapper tcn-events-thumbnails tcn-events-thumbnails-three tcn-events-thumbnails-border">
                        <!-- BEGIN: .g1-collection -->
                        <?php if(count($events) == 1): ?>
                            <div class="g1-collection g1-collection--grid g1-collection--max g1-collection--masonry g1-effect-none">
                        <?php elseif(count($events) == 2): ?>
                            <div class="g1-collection g1-collection--grid g1-collection--one-half g1-collection--masonry g1-effect-none">
                        <?php else: ?>
                            <div class="g1-collection g1-collection--grid g1-collection--one-third g1-collection--masonry g1-effect-none">
                        <?php endif ?>
                            <ul class="isotope events" style="margin-bottom:0px">
                    
                                <?php
                                    foreach ($slide as $event) {
                                        include(locate_template('template-parts/tcn_chunk-event.php'));
                                    }
                                ?>
                    
                            </ul>
                        </div>
                        <!-- END: .g1-collection -->
                    </div>
                </div><?php
                }
            ?>
        </div>
    </div>
    
<?php
    }
?>

    <script>

        jQuery(document).ready(function() {

            var total_slides = jQuery(".tcn-home-slider .slider-slides").children().length;
            var current_slide = 0;

            var slide_next = function() {
                current_slide += 1;
                if (current_slide >= total_slides) {
                    current_slide = 0;
                }
                slide_animate();
            };

            var slide_previous = function() {
                current_slide -= 1;
                if (current_slide < 0) {
                    current_slide = total_slides - 1;
                }
                slide_animate();
            };

            var slide_animate = function() {
                var left = (current_slide * -100) + "%";
                jQuery(".tcn-home-slider .slider-slides").animate({left: left}, 600, "swing");
            };

            jQuery(".tcn-home-slider .slider-arrows a.left").click(function(e) {
                e.preventDefault();
                slide_previous();
            });

            jQuery(".tcn-home-slider .slider-arrows a.right").click(function(e) {
                e.preventDefault();
                slide_next();
            });
        });
    </script>
