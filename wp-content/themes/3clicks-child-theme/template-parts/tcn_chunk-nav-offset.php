<nav class="g1-nav-single tcn-nav-single">
	<ol>
		<li class="g1-nav-single__prev">
			<?php next_posts_link( __('Older Entries', 'tcn'), $custom_query->max_num_pages ); ?>
			&nbsp;   
		</li>
		<li class="g1-nav-single__next">
			<?php previous_posts_link( __('Newer Entries', 'tcn'), $custom_query->max_num_pages ); ?>
		</li>
	</ol>
</nav>