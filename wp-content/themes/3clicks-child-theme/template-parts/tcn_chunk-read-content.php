<?php 
	
	$posts_per_page = 5;
	// Use WP automatic offset calculation instead of manual offset
	$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	$args = array('posts_per_page' => $posts_per_page, 'paged' => $paged, 'author' => $author_query, 'suppress_filters' => false);
	// get_posts() doesn't support pagination, using WP_Query directly
	// $custom_posts = get_posts($args);  
	$custom_query = new WP_Query( $args );
	while ( $custom_query->have_posts() ) {
		
		$custom_query->the_post();
		get_template_part( 'template-parts/tcn_entry_two_third');
	}
?>