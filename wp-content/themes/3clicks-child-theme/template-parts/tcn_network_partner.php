<div class="tcn-author">
    <div class="author-top">
        <?php
            $user = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));
            include(locate_template('template-parts/tcn_chunk-partner-banner.php'));
        ?>
    </div>
    
    <?php
        $upcoming_events = get_network_partner_upcoming_events($user->ID, 'upcoming');
        $recorded_events = get_network_partner_recorded_events($user->ID, 'recorded');
    ?>

    <div class="author-upcoming">
        <h3><?php _e("Upcoming Events", "tcn"); ?></h3>
        <div class="g1-isotope-wrapper">
            <!-- BEGIN: .g1-collection -->
            <?php if(count($upcoming_events)): ?>
                <div class="g1-collection g1-collection--grid g1-collection--one-fourth g1-collection--filterable g1-effect-none tcn-events-thumbnails tcn-events-thumbnails-four">
                    <ul class="isotope events">
                        <?php
                            foreach($upcoming_events as $event)
                            {
                                include(locate_template('template-parts/tcn_chunk-one-fourth-event.php'));
                            }
                        ?>
                    </ul>
                </div>
            <?php else: ?>
                <p><?php _e("This network partner has no upcoming events.", "tcn"); ?></p>
            <?php endif ?>
            <!-- END: .g1-collection -->
        </div>
    </div>
    
    <div class="author-recorded">
        <h3><?php _e("Recorded Events", "tcn"); ?></h3>
        <div class="g1-isotope-wrapper">
            <!-- BEGIN: .g1-collection -->
            <?php if(count($recorded_events)): ?>
                <div class="g1-collection g1-collection--grid g1-collection--one-fourth g1-collection--filterable g1-effect-none tcn-events-thumbnails tcn-events-thumbnails-four">
                    <ul class="isotope events">
                        <?php
                            foreach($recorded_events as $event)
                            {
                                include(locate_template('template-parts/tcn_chunk-one-fourth-event.php'));
                            }
                        ?>
                    </ul>
                </div>
            <?php else: ?>
                <p><?php _e("This network partner has no recorded events.", "tcn"); ?></p>
            <?php endif ?>

            <!-- END: .g1-collection -->
        </div>
    </div>
</div>