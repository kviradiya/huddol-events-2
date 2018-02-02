<?php 
    require_once( ABSPATH . 'wp-content/plugins/tcn-network/tcn-network.php' );
    
    $network = new TCNNetwork;
    
    $current_user = wp_get_current_user();
    $cats = $network->get_subscribed_categories($current_user);
    
    $province = get_user_profile_field($current_user->ID, 'tcn_user_meta_province');
    $city = get_user_profile_field($current_user->ID, 'tcn_user_meta_city');
    $phone = get_user_profile_field($current_user->ID, 'tcn_user_meta_phone');
    $first_name = get_user_profile_field($current_user->ID, 'first_name');
    $last_name = get_user_profile_field($current_user->ID, 'last_name');
    $user_email = $current_user->user_email;
    $age = get_user_profile_field($current_user->ID, 'tcn_user_meta_age');
    $caregiver_role = get_user_profile_field($current_user->ID, 'tcn_user_meta_caregiver_role');
    $marital_status = get_user_profile_field($current_user->ID, 'tcn_user_meta_marital_status');
    $gender = get_user_profile_field($current_user->ID, 'tcn_user_meta_gender');
    
    $missings_fields = array();

    push_profile_error($missings_fields, $province, __('Residing In', "tcn"));
    push_profile_error($missings_fields, $first_name, __('First Name', "tcn"));
    push_profile_error($missings_fields, $last_name, __('Last Name', "tcn"));
    push_profile_error($missings_fields, $user_email, __('User Email', "tcn"));
    push_profile_error($missings_fields, $age, __('Age', "tcn"));
    push_profile_error($missings_fields, $caregiver_role, __('I Am A', "tcn"));
    push_profile_error($missings_fields, $marital_status, __('Marital Status', "tcn"));
    push_profile_error($missings_fields, $gender, __('Gender', "tcn"));
    push_profile_error($missings_fields, $city, __('City', "tcn"));
    push_profile_error($missings_fields, $phone, __('Phone', "tcn"));
        
    $image_path = get_stylesheet_directory_uri() .'/images/';
?>
    
<div class="dashboard-profile">
    <div class="edit-top">
        <a href="<?php echo get_the_permalink() ?>?mode=profile_edit"?><button><?php _e("Edit my profile", "tcn"); ?></button></a>
    </div>
    <?php if(count($missings_fields) || empty($cats)) { ?>
        
        <div class="incomplete-profile-box">
            <img src="<?php echo $image_path; ?>bell_icon_large.png" />
            <p>
                <?php _e("Looks like your profile is incomplete.", "tcn"); ?>
            </p>
            <p>
                <?php _e("Click the edit button to update your profile. It will help us create a better experience for you on our website.", "tcn"); ?>
            </p>
        </div>
    <?php } ?>
    
    <?php if($province == '' || $first_name == '' || $last_name == '' || $user_email == '' || $city == '' || $phone == ''): ?>
        <img class="section-incomplete" src="<?php echo $image_path; ?>bell_icon.png" />    
    <?php endif ?>
    <h3><?php _e("General Info", "tcn"); ?></h3>
    
    <div class="field">
        <div class="caption"><?php _e("Name", "tcn"); ?></div>
        <div class="value"><?php echo $first_name ? $first_name : '-'; ?></div>
    </div>

    <div class="field">
        <div class="caption"><?php _e("Family Name", "tcn"); ?></div>
        <div class="value"><?php echo $last_name ? $last_name : '-'; ?></div>
    </div>

    <div class="field">
        <div class="caption"><?php _e("Email", "tcn"); ?></div>
        <div class="value"><?php echo $user_email ? $user_email : '-'; ?></div>
    </div>

    <div class="field">
        <div class="caption"><?php _e("Password", "tcn"); ?></div>
        <div class="value">&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;</div>
    </div>
   
    <div class="field">
        <div class="caption"><?php _e("Residing In", "tcn"); ?></div>
        <div class="value">
            <?php for($i = 0; $i < get_province_count(); $i++): ?>
                <?php if($province == $i ) echo get_province_name($i); ?>
            <?php endfor ?>
        </div>
    </div>
    
    <div class="field">
        <div class="caption"><?php _e("City", "tcn"); ?></div>
        <?php if($city === ''): ?>
            <div class="value"><a href="<?php echo get_account_dashboard_url(); ?>?mode=profile_edit"><?php _e("Update your profile", "tcn"); ?></a></div>
        <?php else: ?>
            <div class="value"><?php echo $city; ?></div>
        <?php endif ?>
    </div>
    
    <div class="field">
        <div class="caption"><?php _e("Phone Number", "tcn"); ?></div>
        <?php if($phone === ''): ?>
            <div class="value"><a href="<?php echo get_account_dashboard_url(); ?>?mode=profile_edit"><?php _e("Update your profile", "tcn"); ?></a></div>
        <?php else: ?>
            <div class="value"><?php echo $phone; ?></div>
        <?php endif ?>
    </div>

    
    <div class="tcn-sep"></div>
    
    <?php if(empty($caregiver_role) || $age == '' || $gender == '' || $marital_status == ''): ?>
        <img class="section-incomplete" src="<?php echo $image_path; ?>bell_icon.png" />
    <?php endif ?>
    <h3><?php _e("About You", "tcn"); ?></h3>

    <div class="field">
        <div class="caption"><?php _e("I am a", "tcn"); ?></div>
        <div class="value">
            <?php if(!empty($caregiver_role)): ?>
            <?php $role = ''; ?>
            <?php for($i = 0; $i < get_num_caregiver_roles(); $i++ ): ?>
                <?php if(is_user_caregiver_role($current_user->ID, $i)): ?>
                    <?php $role .= get_caregiver_role_name($i) . ', ' ?>
                <?php endif ?>
            <?php endfor ?>
            <?php echo substr($role, 0, strlen($role)-2); ?>
            
            <?php endif ?>
            <?php if(empty($caregiver_role)): ?>
                <a href="<?php echo get_account_dashboard_url(); ?>?mode=profile_edit"><?php _e("Update your profile", "tcn"); ?></a>
            <?php endif ?>
        </div>
    </div>

    <div class="field">
        <div class="caption"><?php _e("My age", "tcn"); ?></div>
        <div class="value">
            <?php if($age === '0' ){ echo "<17"; } ?>
            <?php if($age === '1' ){ echo "18-24"; } ?>
            <?php if($age === '2' ){ echo "25-34"; } ?>
            <?php if($age === '3' ){ echo "35-44"; } ?>
            <?php if($age === '4' ){ echo "45-54"; } ?>
            <?php if($age === '5' ){ echo "55-64"; } ?>
            <?php if($age === '6' ){ echo "65-74"; } ?>
            <?php if($age === '7' ){ echo "75+"; } ?>
            <?php if($age == null): ?>
                <a href="<?php echo get_account_dashboard_url(); ?>?mode=profile_edit"><?php _e("Update your profile", "tcn"); ?></a>
            <?php endif ?>
        </div>
    </div>

    <div class="field">
        <div class="caption"><?php _e("My gender", "tcn"); ?></div>
        <div class="value">
            <?php if($gender === '0' ){ echo __("Female", "tcn"); } ?>
            <?php if($gender === '1' ){ echo __("Male", "tcn"); } ?>
            <?php if($gender === '2' ){ echo __("Decline to State/Other", "tcn" ); } ?>
            <?php if($gender == null): ?>
                <a href="<?php echo get_account_dashboard_url(); ?>?mode=profile_edit"><?php _e("Update your profile", "tcn"); ?></a>
            <?php endif ?>
        </div>
    </div>

    <div class="field">
        <div class="caption"><?php _e("My marital status", "tcn"); ?></div>
        <div class="value">
            <?php if($marital_status === '0' ){ echo __("Single, never married", "tcn" ); } ?>
            <?php if($marital_status === '1' ){ echo __("Married/Domestic Partner", "tcn" ); } ?>
            <?php if($marital_status === '2' ){ echo __("Widowed", "tcn" ); } ?>
            <?php if($marital_status === '3' ){ echo __("Divorced", "tcn" ); } ?>
            <?php if($marital_status === '4' ){ echo __("Separated", "tcn" ); } ?>
            <?php if($marital_status === '5' ){ echo __("Decline to State/Other", "tcn" ); } ?>
            <?php if($marital_status == null): ?>
                <a href="<?php echo get_account_dashboard_url(); ?>?mode=profile_edit"><?php _e("Update your profile", "tcn"); ?></a>
            <?php endif ?>
        </div>
    </div>
    
    <div class="tcn-sep"></div>
    
    <?php if(count($cats) == 0): ?>
        <img class="section-incomplete" src="<?php echo $image_path; ?>bell_icon.png" />    
    <?php endif ?>
    <h3><?php _e("Interests", "tcn"); ?></h3>
        
    <div class="field">
        <div class="caption"><?php _e("Channels", "tcn"); ?></div>
        <?php if(count($cats)): ?>
            <p><?php _e("I am subscribed to these channels:", "tcn"); ?></p>
            <ul>
                <?php
        
                    foreach($cats as $cat)
                    {
                        echo '<li><a href="'. get_category_link($cat) . '">' . $cat->name . "</a></li>";
                    }
                ?>
            </ul>
        <?php else: ?>
            <a href="<?php echo get_account_dashboard_url(); ?>?mode=profile_edit"><?php _e("Update your profile", "tcn"); ?></a> <?php _e("to subscribe to channels.", "tcn"); ?>
        <?php endif ?>
    </div>
</div>