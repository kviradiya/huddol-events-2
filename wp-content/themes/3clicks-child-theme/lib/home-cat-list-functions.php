<?php

$excluded = [];

function print_home_categories_and_posts() {

	global $post, $excluded, $category_id;

	$home_id    = $post->ID;
	$categories = get_field( 'category_select' );
	$today      = date( "Y-m-d" );

	if ( $categories ) {

		foreach ( $categories as $category_id ) {

			$args = [
				'post_type'   => 'tribe_events',
				'numberposts' => 4,
				'category'    => $category_id,
				'meta_query'  => [
					[ 'key' => 'wpb_post_views_count' ],
					[
						'key'     => '_EventEndDate',
						'value'   => $today,
						'compare' => '<',
						'type'    => 'datetime',
					],
				],
				'orderby'     => 'meta_value_num',
			];

			if ( ! empty( $excluded ) ) {

				$args['exclude'] = $excluded;
			}


			$my_posts = get_posts( $args );

			if ( count( $my_posts > 0 ) ) {

				?>
                <hr/>
                <div class="header">
                    <h2><?php echo get_the_category_by_ID( $category_id ); ?></h2>
                        <a href="<?php
                        $category_link = get_category_link( $category_id );
                        echo esc_url( $category_link ); ?>"
                           title="<?php echo get_the_category_by_ID( $category_id ); ?>">
                            <?php _e( "See all", "tcn" ); ?>
                        </a>
                </div>
                <div class="g1-collection g1-collection--grid g1-collection--one-fourth g1-collection--filterable g1-effect-none">
                    <ul style="position: relative; overflow: visible"
                        class="isotope custom-select">
						<?php

						foreach ( $my_posts as $post ) {

							$excluded[] = $post->ID;
							include( locate_template( 'template-parts/tcn_home-popular-channel.php' ) );

						}
						?>
                    </ul>
                </div>
				<?php
			}
		}
	} else {
		$posts = get_popular_channel_posts( 4 );
		?>
        <!-- BEGIN: .g1-collection -->
        <hr/>
        <h2><?php _e( "Popular Events", "tcn" ); ?></h2>

        <div class="g1-collection g1-collection--grid g1-collection--one-fourth g1-collection--filterable g1-effect-none">
            <ul style="position: relative; overflow: visible" class="isotope">
				<?php
				foreach ( $posts as $category_id => $post ) {
					include( locate_template( 'template-parts/tcn_home-popular-channel.php' ) );
				}
				?>
            </ul>
        </div>
        <!-- END: .g1-collection -->
		<?php

	}
}

?>