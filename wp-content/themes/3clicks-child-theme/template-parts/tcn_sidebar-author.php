

<?php $author = get_the_author_meta( 'ID' ); ?>
<?php
    $other_author_posts = get_author_posts($author, $post->ID);
?>

<?php if(count($other_author_posts) != 0): ?>
<section class="widget widget_search g1-widget--cssclass">
    <header class="tcn-sidebar-title">
        <?php _e("Other Posts by this Author", "tcn"); ?>
    </header>
    
    
    <div class="g1-links">
    <ul style="margin-bottom: 0px;">
        <?php foreach($other_author_posts as $oap): ?>
            <li>
                <a href="<?php echo get_the_permalink($oap); ?>">
                    <?php echo $oap->post_title; ?> 
                </a>
            </li>
        <?php endforeach ?>
    </ul>
</div>
</section>
<?php endif ?>