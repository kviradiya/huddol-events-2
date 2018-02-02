<?php 
    require_once( ABSPATH . 'wp-content/plugins/tcn-network/tcn-network.php' );
    
    $network = new TCNNetwork;
    
    $current_user = wp_get_current_user();
    $cats = $network->get_subscribed_categories($current_user);
        
    $province = get_user_profile_field($current_user->ID, 'tcn_user_meta_province');
    $first_name = get_user_profile_field($current_user->ID, 'first_name');
    $last_name = get_user_profile_field($current_user->ID, 'last_name');
    $user_email = $current_user->user_email;
    $age = get_user_profile_field($current_user->ID, 'tcn_user_meta_age');
    $caregiver_role = get_user_meta($current_user->ID, 'tcn_user_meta_caregiver_role', true);
    $marital_status = get_user_profile_field($current_user->ID, 'tcn_user_meta_marital_status');
    $gender = get_user_profile_field($current_user->ID, 'tcn_user_meta_gender');
    $city = get_user_profile_field($current_user->ID, 'tcn_user_meta_city');
    $phone = get_user_profile_field($current_user->ID, 'tcn_user_meta_phone');
    $image_path = get_stylesheet_directory_uri() .'/images/';
?>
    
<?php if(ICL_LANGUAGE_CODE == 'en' ): ?>
<form action="<?php echo site_url(); ?>/edit_profile/" method="POST" class="dashboard-profile">
<?php else: ?>
<form action="<?php echo site_url(); ?>/edit_profile_fr/" method="POST" class="dashboard-profile">
<?php endif ?>


    <?php if(empty($caregiver_role) || $age == '' || $gender == '' || $marital_status == '' || empty($cats)): ?>
        <div class="incomplete-profile-box">
            <img src="<?php echo $image_path; ?>bell_icon_large.png ?>" />
            <p><?php _e("Incomplete fields", "tcn"); ?></p>
        </div>
    <?php endif ?>
    
    <?php if($province == '' || $first_name == '' || $last_name == '' || $user_email == ''): ?>
        <img class="section-incomplete" src="<?php echo $image_path; ?>bell_icon.png ?>" />    
    <?php endif ?>
    <h3><?php _e("General Info", "tcn"); ?></h3>
    
    <div class="field">
        <div class="caption<?php if($first_name == '') echo ' missing'; ?>"><?php _e("Name", "tcn"); ?></div>
        <input type="text" name="first_name" value="<?php echo $first_name ?>" />
    </div>

    <div class="field">
        <div class="caption<?php if($last_name == '') echo ' missing'; ?>"><?php _e("Family Name", "tcn"); ?></div>
        <input type="text" name="last_name" value="<?php echo $last_name ?>" />
    </div>

    <div class="field">
        <div class="caption<?php if($user_email == '') echo ' missing'; ?>"><?php _e("Email", "tcn"); ?></div>
        <input type="email" name="user_email" value="<?php echo $user_email ?>" />
    </div>
    
    <div class="field">
        <div class="caption<?php if($province == '') echo ' missing'; ?>"><?php _e("Residing In", "tcn"); ?></div>
        <select id="tcn_user_meta_province" name="tcn_user_meta_province">
            <?php for($i = 0; $i < get_province_count(); $i++): ?>
                <option <?php if($province == $i ) echo 'selected="selected"'; ?> value="<?php echo $i; ?>"><?php echo get_province_name($i); ?></option>
            <?php endfor ?>
        </select>
    </div>
    
    <div class="field">
        <div class="caption<?php if($city == '') echo ' missing'; ?>"><?php _e("City", "tcn"); ?></div>
        <input type="text" name="city" value="<?php echo $city ?>" />
    </div>
    
    <div class="field">
        <div class="caption<?php if($phone == '') echo ' missing'; ?>"><?php _e("Phone Number", "tcn"); ?></div>
        <input type="text" name="phone" value="<?php echo $phone ?>" />
    </div>


    <div class="tcn-sep"></div>


    <?php if(empty($caregiver_role) || $age == '' || $gender == '' || $marital_status == ''): ?>
        <img class="section-incomplete" src="<?php echo $image_path; ?>bell_icon.png ?>" />
    <?php endif ?>
    <h3><?php _e("About You", "tcn"); ?></h3>
    <div class="field">
        <div class="caption<?php if(empty($caregiver_role)) echo ' missing'; ?>"><?php _e("I am a: (select all that apply)", "tcn"); ?></div>
        <ul class="options fixed-width">
            <li><label><input <?php if(in_array(0, $caregiver_role)){ echo "checked"; } ?> name="tcn_user_meta_caregiver_role_0" class="tog" type="checkbox"><span><?php echo get_caregiver_role_name(0); ?></span></label></li>
            <li><label><input <?php if(in_array(1, $caregiver_role)){ echo "checked"; } ?> name="tcn_user_meta_caregiver_role_1" value="1" class="tog" type="checkbox"><span><?php echo get_caregiver_role_name(1); ?></span></label></li>
            <li></li> <!-- for layout purposes -->
            <li><label><input <?php if(in_array(2, $caregiver_role)){ echo "checked"; } ?> name="tcn_user_meta_caregiver_role_2" value="2" class="tog" type="checkbox"><span><?php echo get_caregiver_role_name(2); ?></span></label></li>
            <li><label><input <?php if(in_array(3, $caregiver_role)){ echo "checked"; } ?> name="tcn_user_meta_caregiver_role_3" value="3" class="tog" type="checkbox"><span><?php echo get_caregiver_role_name(3); ?></span></label></li>
        </ul>
    </div>

    <div class="field">
        <div class="caption<?php if($age == '') echo ' missing'; ?>"><?php _e("What is your age?", "tcn"); ?></div>
        <ul class="options">
            <li><label><input <?php if($age === '0' ){ echo "checked"; } ?> name="tcn_user_meta_age" value="0" class="tog" type="radio"><span>< 17</span></label></li>
            <li><label><input <?php if($age === '1' ){ echo "checked"; } ?> name="tcn_user_meta_age" value="1" class="tog" type="radio"><span>18-24</span></label></li>
            <li><label><input <?php if($age === '2' ){ echo "checked"; } ?> name="tcn_user_meta_age" value="2" class="tog" type="radio"><span>25-34</span></label></li>
            <li><label><input <?php if($age === '3' ){ echo "checked"; } ?> name="tcn_user_meta_age" value="3" class="tog" type="radio"><span>35-44</span></label></li>
            <li><label><input <?php if($age === '4' ){ echo "checked"; } ?> name="tcn_user_meta_age" value="4" class="tog" type="radio"><span>45-54</span></label></li>
            <li><label><input <?php if($age === '5' ){ echo "checked"; } ?> name="tcn_user_meta_age" value="5" class="tog" type="radio"><span>55-64</span></label></li>
            <li><label><input <?php if($age === '6' ){ echo "checked"; } ?> name="tcn_user_meta_age" value="6" class="tog" type="radio"><span>65-74</span></label></li>
            <li><label><input <?php if($age === '7' ){ echo "checked"; } ?> name="tcn_user_meta_age" value="7" class="tog" type="radio"><span>75+</span></label></li>
        </ul>
    </div>

    <div class="field">
        <div class="caption<?php if($gender == '') echo ' missing'; ?>"><?php _e("What is your gender?", "tcn"); ?></div>
        <ul class="options">
            <li><label><input <?php if($gender === '0' ){ echo "checked"; } ?> name="tcn_user_meta_gender" value="0" checked="checked" class="tog" type="radio"><span><?php _e("Female", "tcn"); ?></span></label></li>
            <li><label><input <?php if($gender === '1' ){ echo "checked"; } ?> name="tcn_user_meta_gender" value="1" class="tog" type="radio"><span><?php _e("Male", "tcn"); ?></span></label></li>
            <li><label><input <?php if($gender === '2' ){ echo "checked"; } ?> name="tcn_user_meta_gender" value="2" class="tog" type="radio"><span><?php _e("Decline to State/Other", "tcn"); ?></span></label></li>
        </ul>
    </div>

    <div class="field">
        <div class="caption<?php if($marital_status == '') echo ' missing'; ?>"> <?php _e("What is your marital status?", "tcn"); ?></div>
        <ul class="options">
            <li><label><input <?php if($marital_status === '0' ){ echo "checked"; } ?> name="tcn_user_meta_marital_status" value="0" class="tog" type="radio"><span><?php _e("Single, never married", "tcn"); ?></span></label>
            <li><label><input <?php if($marital_status === '1' ){ echo "checked"; } ?> name="tcn_user_meta_marital_status" value="1" class="tog" type="radio"><span><?php _e("Married/Domestic Partner", "tcn"); ?></span></label>
            <li><label><input <?php if($marital_status === '2' ){ echo "checked"; } ?> name="tcn_user_meta_marital_status" value="2" class="tog" type="radio"><span><?php _e("Widowed", "tcn"); ?></span></label>
            <li><label><input <?php if($marital_status === '3' ){ echo "checked"; } ?> name="tcn_user_meta_marital_status" value="3" class="tog" type="radio"><span><?php _e("Divorced", "tcn"); ?></span></label>
            <li><label><input <?php if($marital_status === '4' ){ echo "checked"; } ?> name="tcn_user_meta_marital_status" value="4" class="tog" type="radio"><span><?php _e("Separated", "tcn"); ?></span></label>
            <li><label><input <?php if($marital_status === '5' ){ echo "checked"; } ?> name="tcn_user_meta_marital_status" value="5" class="tog" type="radio"><span><?php _e("Decline to State/Other", "tcn"); ?></span></label>
        </ul>
    </div>


    <div class="tcn-sep"></div>
    
    <?php if(! count($cats)): ?>
        <img class="section-incomplete" src="<?php echo $image_path; ?>bell_icon.png ?>" />    
    <?php endif ?>
    
    <h3><?php _e("Interests", "tcn"); ?></h3>
    <h4><?php _e("Subscribe me to the following channels, tags or network partners: (select all that are of interest)", "tcn"); ?></h4>

    <div class="field">
        <div class="caption"><?php _e("Channels", "tcn"); ?></div>
        <ul class="options fixed-width">
            <?php
                $args = array(
                  'orderby' => 'name',
                  'order' => 'ASC',
                  'hide_empty' => 0,
		  'exclude' => "1,2"
                 );
         
                $categories = get_categories($args);
    
                foreach($categories as $category) 
                { 
                    echo '<li><label><input '. $network->is_user_category_subscribed($current_user, $category->term_id ) . ' type="checkbox" name="tcn_network_category_'. $category->term_id . '"/><span>' . $category->name . '</span></label></li>';
                } 
            ?>
        </ul>
    </div>
    <?php if(ICL_LANGUAGE_CODE == 'en'): ?>
        <input type="submit" value="<?php _e("Save", "tcn"); ?>" />
<?php else: ?>
            <input type="submit" value="Sauvegarder" />
<?php endif ?>
    
    <div class="tcn-sep"></div>
</form>
