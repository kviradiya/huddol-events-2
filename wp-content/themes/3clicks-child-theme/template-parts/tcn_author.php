<h2 class="tcn-page-title"><?php _e("Blog", "tcn"); ?> / <span style="color: black; font-size: 24px;"><?php echo $user->display_name; ?></h2>
<div class="tcn-author">
    <?php $imgURL = get_cupp_meta( $user->ID, 'large' ); ?>
    <section itemtype="http://schema.org/UserComments" style="border:0px;">
            <ol class="commentlist">
	            <li id="li-comment-2" class="comment byuser comment-author-admin bypostauthor even thread-even depth-1">
                    <article id="comment-2" itemtype="http://schema.org/Comment" itemprop="comment" itemscope="">
                        <header itemtype="http://schema.org/Person" itemscope="" itemprop="author">
                            <div itemtype="http://schema.org/Person" itemscope="" itemprop="author" style="width: 100%">
                                <img style="height: 60px; width: 60px" class="avatar avatar-60 photo" src="<?php echo $imgURL; ?>" alt="" itemprop="image">                                                 
                                <h3 style="margin-bottom: 0px;">
                                    <?php echo get_partner_name($user->ID); ?><?php if(get_user_meta($user->ID, 'cupp_title', true)): ?>, <span style="font-size: 18px;"><?php echo get_user_meta($user->ID, 'cupp_title', true); ?></span>
                                <?php endif ?>
                                </h3>
                                <?php if( $user->user_url): ?>
                                    <h4 style="margin-bottom: 10px; font-size: 14px;">
                                        <?php $url = $user->user_url; ?>
                                        <?php if(startsWith($url, 'http://')): ?>
                                            <?php $url = substr($url, 7); ?>
                                        <?php elseif(startsWith($url, 'https://')): ?>
                                            <?php $url = substr($url, 8); ?>
                                        <?php else: ?>
                                            
                                        <?php endif ?>
                                        
                                        <a target="_blank" href="<?php echo $user->user_url; ?>"><?php echo $url; ?></a>
                                    </h4>
                                <?php endif ?>
                            </div>
                        </header>

                        <div class="comment-body" itemprop="text">
                            <p>
                                <?php echo $user->description; ?>
                            </p>
                        </div>
                    </article><!-- END: #comment-##  -->
                </li><!-- #comment-## -->
            </ol>
    </section>
    
    <div style="clear:both"></div>
    <div class="g1-isotope-wrapper">
        <?php $posts = get_author_posts($user->ID); ?>
        <!-- BEGIN: .g1-collection -->
        <?php if(count($posts)): ?>
            <div class="g1-collection g1-collection--grid g1-collection--one-half g1-collection--filterable g1-effect-none tcn-events-thumbnails tcn-events-thumbnails-halkf">
                <ul class="isotope events">
                    <?php
                        foreach($posts as $event)
                        {
                            include(locate_template('template-parts/tcn_chunk-one-half-post.php'));
                        }
                    ?>
                </ul>
            </div>
        <?php else: ?>
            <p><?php _e("This author has no posts.", "tcn"); ?></p>
        <?php endif ?>
        <!-- END: .g1-collection -->
    </div>
</div>
