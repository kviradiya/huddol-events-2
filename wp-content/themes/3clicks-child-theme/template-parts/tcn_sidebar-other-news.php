<?php
    $args = array(
        "posts_per_page" => 5,
        "author" => 1,
        "suppress_filters" => false,
        "orderby" => "post_date",
        "order" => "DESC",
        "exclude" => $post->ID
    );
    $other_news = get_posts($args);
?>

<?php if(count($other_news)): ?>
    <section class="widget widget_search g1-widget--cssclass">
        <header class="tcn-sidebar-title">
            <?php _e("Other News", "tcn"); ?>
        </header>
        
        <div class="g1-links">
            <ul>
                <?php
                    foreach($other_news as $news)
                    { ?>
                        <li><a href="<?php echo get_the_permalink($news); ?>"><?php echo $news->post_title; ?></a></li>
                    <?php
                    }
                ?>
            </ul>
        </div>
    </section>
<?php endif ?>