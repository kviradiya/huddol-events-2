
    
    <?php
        $args = array(
            'role' => 'author'
        );

    	$authors = get_users( $args );
    	$post_authors = array();
    	foreach($authors as $author)
    	{
    	    $posts = get_author_posts($author->ID);
    	    if(count($posts))
    	    {
    	        array_push($post_authors, $author);
    	    }
    	}
    
    	?>
   
<section class="widget widget_search g1-widget--cssclass">
    <header class="tcn-sidebar-title">
        <?php _e("Our Blog Authors", "tcn"); ?>
    </header> 	
    <ul class="tcn-sidebar-posts">
    	<?php
    	foreach($post_authors as $author)
    	{
    	    $imgURL = get_cupp_meta( $author->ID, 'thumbnail' ); 
    	    // echo $imgURL;
    	    ?>
            	    <li>
                        <a href="<?php echo get_author_posts_url($author->ID); ?>">
                            <div class="avatar avatar-60 photo" style="background-image: url('<?php echo $imgURL; ?>');"></div>
                            <div class="author-name" style="left: 75px;">
                                <?php echo $author->display_name; ?>
                            </div>
                        </a>
                    </li>
    	    <?php
    	} ?>
    </ul>
    	<?php
    ?>
</section>