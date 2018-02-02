<?php 
    $key = '_partner_in_profile';
    if(ICL_LANGUAGE_CODE != 'en')
    {
        $key = '_partner_in_profile_fr';
    }
    
    $args = array(
        'blog_id'      => $GLOBALS['blog_id'],
    	'role'         => '',
    	'meta_key'     => $key,
    	'meta_value'   => '1',
    	'meta_compare' => '',
    	'meta_query'   => array(),
    	'include'      => array(),
    	'exclude'      => array(),
    	'orderby'      => 'login',
    	'order'        => 'ASC',
    	'offset'       => '',
    	'search'       => '',
    	'number'       => '',
    	'count_total'  => false,
    	'fields'       => 'all',
    	'who'          => ''
     ); 
    
    $users = get_users($args);
    $user = null;
    if(count($users) > 1)
    {
        $user = $users[0];
    }
    else if(count($users) == 1)
    {
        $user = $users[0];
    }
?>

<?php if($user): ?>
<hr />
<h2><?php _e("Partner in Profile", "tcn"); ?></h2>

<?php
include(locate_template('template-parts/tcn_chunk-partner-banner.php'));
?>
<?php endif ?>