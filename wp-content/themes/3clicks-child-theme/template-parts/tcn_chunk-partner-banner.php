<?php if($user): ?>
    <div class="tcn-partner-banner">
        <?php $imgURL = get_cupp_meta( $user->ID, 'large' ); ?>
        <a href="<?php echo get_author_posts_url($user->ID); ?>">
            <?php /*
            <div class="partner-picture" style="background-image: url('<?php echo $imgURL; ?>');"></div>
            */ ?>
            
            <div class="partner-picture" style="margin-bottom: 40px">
                <?php if( is_author()): ?>
                <h2 class="tcn-page-title" style="margin-bottom: 10px"><?php echo get_partner_name($user->ID); ?></h2>
                <?php endif ?>
                <img src="<?php echo $imgURL; ?>" alt="<?php echo get_partner_name($user->ID); ?> logo" />
                <div style="clear: both"></div>
            </div>
        </a>
        
        <div class="partner-info">
            <h2 class="partner-name">
                <?php if( is_author()): ?>
                    <?php $userdata = get_userdata($user->ID); ?>
                    <?php $url = ''; ?>
                    <?php if(ICL_LANGUAGE_CODE=='en')
                    {
                        $url = get_user_meta($user->ID, 'tcn_partner_english_website', true);
                    }
                    else
                    {
                        $url = get_user_meta($user->ID, 'tcn_partner_french_website', true);
                    } ?>
                    
                    <?php if(startsWith($url, 'http://')): ?>
                        <a target="_blank" href="<?php echo $url ?>">
                    <?php else: ?>
                        <a target="_blank" href="http://<?php echo $url; ?>">
                    <?php endif ?>
                    
                        <?php if(startsWith($url, 'http://')): ?>
                            <?php echo substr($url, 7); ?>
                        <?php elseif(startsWith($url, 'https://')): ?>
                            <?php echo substr($url, 8); ?>
                        <?php else: ?>
                            <?php echo $url; ?>
                        <?php endif ?>
                    </a>

                <?php else: ?>
                    <a href="<?php echo get_author_posts_url($user->ID); ?>">
                        <?php echo get_partner_name($user->ID); ?>
                    </a>
                <?php endif ?>
            </h2>
            
            <?php if(ICL_LANGUAGE_CODE=='en')
            {
                $description = get_user_meta($user->ID, 'tcn_partner_english_description', true);
            }
            else
            {
                $description = get_user_meta($user->ID, 'tcn_partner_french_description', true);
            } ?>
            
            <p class="partner-description">
                <?php echo $description; ?>
            </p>
            
            <?php if( is_author()): ?>
                <?php print_r( tcn_capture_author_categories($user)); ?>
            <?php endif ?>
        </div>
    </div>
<?php endif ?>
