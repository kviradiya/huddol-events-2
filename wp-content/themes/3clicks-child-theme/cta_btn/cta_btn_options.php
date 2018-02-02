<?php

function mac_add_general_options_pages(){
	$page_title 	= __('CTA Button', 'Mac Theme');
	$menu_title 	= __('CTA Button', 'Mac Theme'); 
	$capability 	= 'edit_pages';
	$menu_slug 		= 'cta_btn_options.php';
	$function 		= 'cta_btn_options';
	$icon_url 		= 'dashicons-forms';
	$position 		= 61;
	
	$parent_menu_slug = $menu_slug;
	add_menu_page($page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position);
	
}
add_action('admin_menu', 'mac_add_general_options_pages');

function mac_general_options_init() {

	register_setting( 'mac_setup', 'mac_general_options' );

	add_settings_section(
		'mac_setup_section', 
		__( 'Veuillez compléter les champs suivants pour le bouton en français :', 'Mac Theme' ), 
		'mac_general_options_section_callback', 
		'mac_setup'
	);

	add_settings_field( 
		'btn_title', 
		__( 'Titre du bouton français', 'Mac Theme' ), 
		'mac_btn_title_render', 
		'mac_setup', 
		'mac_setup_section' 
	);

	add_settings_field( 
		'btn_text', 
		__( 'Texte du bouton français', 'Mac Theme' ), 
		'mac_btn_text_render', 
		'mac_setup', 
		'mac_setup_section' 
	);

	add_settings_field( 
		'btn_logo', 
		__( 'Logo du bouton français', 'Mac Theme' ), 
		'mac_btn_logo_render', 
		'mac_setup', 
		'mac_setup_section' 
	);

	add_settings_field(
		'wptuts_setting_logo_preview',  
		__( 'Aperçu du logo', 'Mac Theme' ), 
		'wptuts_setting_logo_preview', 
		'mac_setup', 
		'mac_setup_section');

	add_settings_field( 
		'btn_cta_text', 
		__( 'Texte "Call to Action" français', 'Mac Theme' ), 
		'mac_btn_cta_text_render', 
		'mac_setup', 
		'mac_setup_section' 
	);

	add_settings_field( 
		'btn_cta_link', 
		__( 'Lien sur le bouton français', 'Mac Theme' ), 
		'mac_btn_cta_link_render', 
		'mac_setup', 
		'mac_setup_section' 
	);
	
	add_settings_section(
		'mac_setup_section_eng', 
		__( 'Please complete the following fields for the english button', 'Mac Theme' ), 
		'mac_general_options_section_callback', 
		'mac_setup'
	);

	add_settings_field( 
		'btn_title_eng', 
		__( 'Title of CTA', 'Mac Theme' ), 
		'mac_btn_title_eng_render', 
		'mac_setup', 
		'mac_setup_section_eng' 
	);

	add_settings_field( 
		'btn_text_eng', 
		__( 'Text for CTA', 'Mac Theme' ), 
		'mac_btn_text_eng_render', 
		'mac_setup', 
		'mac_setup_section_eng' 
	);

	add_settings_field( 
		'btn_logo_eng', 
		__( 'Logo', 'Mac Theme' ), 
		'mac_btn_logo_eng_render', 
		'mac_setup', 
		'mac_setup_section_eng' 
	);

	add_settings_field(
		'wptuts_setting_logo_eng_preview',  
		__( 'Logo preview', 'Mac Theme' ), 
		'wptuts_setting_logo_eng_preview', 
		'mac_setup', 
		'mac_setup_section_eng');

	add_settings_field( 
		'btn_cta_text_eng', 
		__( 'Text "Call to Action"', 'Mac Theme' ), 
		'mac_btn_cta_text_eng_render', 
		'mac_setup', 
		'mac_setup_section_eng' 
	);

	add_settings_field( 
		'btn_cta_link_eng', 
		__( 'Link', 'Mac Theme' ), 
		'mac_btn_cta_link_eng_render', 
		'mac_setup', 
		'mac_setup_section_eng' 
	);
		
}
add_action( 'admin_init', 'mac_general_options_init' );

/* champ français */
function mac_btn_title_render(  ) {

	$options = get_option( 'mac_general_options' );
	?>
	<input type='text' name='mac_general_options[btn_title]' value='<?php echo ( !empty($options['btn_title']) ? $options['btn_title'] : '') ; ?>'>
	<?php

}

function mac_btn_text_render(  ) {

	$options = get_option( 'mac_general_options' );
	?>
	<input type='text' name='mac_general_options[btn_text]' value='<?php echo ( !empty($options['btn_text']) ? $options['btn_text'] : '') ; ?>'>
	<?php
}

function mac_btn_logo_render(){
	$options = get_option( 'mac_general_options' );
	wp_enqueue_media();
	
	if(!empty($options['image_attachment_id'])){
		
	?>
	<div class='image-preview-wrapper'>
		<img id='image-preview' src='<?php echo $options['image_attachment_id'];?>' width='100' style='max-height: 100px; width: 100px;'>
	</div>
	<?php
	}
	?>
	<input id="upload_image_button" type="button" class="button" value="<?php _e( 'Charger une image', 'Mac Theme' ); ?>" />
	<input type='hidden' name='mac_general_options[image_attachment_id]' id='image_attachment_id' value='<?php echo $options['image_attachment_id'];?>'>
	<?php
}

function mac_btn_cta_text_render(  ) {
	$options = get_option( 'mac_general_options' );
	?>
	<input type='text' name='mac_general_options[btn_cta_text]' value='<?php echo ( !empty($options['btn_cta_text']) ? $options['btn_cta_text'] : '') ; ?>'>
	<?php

}

function mac_btn_cta_link_render(  ) {
	$options = get_option( 'mac_general_options' );
	?>
	<input type='text' name='mac_general_options[btn_cta_link]' value='<?php echo ( !empty($options['btn_cta_link']) ? $options['btn_cta_link'] : '') ; ?>'>
	<?php

}

/* champs anglais */
function mac_btn_title_eng_render(  ) {
	$options = get_option( 'mac_general_options' );
	?>
	<input type='text' name='mac_general_options[btn_title_eng]' value='<?php echo ( !empty($options['btn_title_eng']) ? $options['btn_title_eng'] : '') ; ?>'>
	<?php

}

function mac_btn_text_eng_render(  ) {
	$options = get_option( 'mac_general_options' );
	?>
	<input type='text' name='mac_general_options[btn_text_eng]' value='<?php echo ( !empty($options['btn_text_eng']) ? $options['btn_text_eng'] : ''); ?>'>
	<?php
}

function mac_btn_logo_eng_render(){
	$options = get_option( 'mac_general_options' );
	wp_enqueue_media();
	
	if(!empty($options['image_attachment_id_eng'])){
		
	?>
	<div class='image-preview-wrapper'>
		<img id='image-preview_eng' src='<?php echo $options['image_attachment_id_eng'];?>' width='100' style='max-height: 100px; width: 100px;'>
	</div>
	<?php
	}
	?>
	<input id="upload_image_button_eng" type="button" class="button" value="<?php _e( 'Charger une image', 'Mac Theme' ); ?>" />
	<input type='hidden' name='mac_general_options[image_attachment_id_eng]' id='image_attachment_id_eng' value='<?php echo $options['image_attachment_id_eng'];?>'>
	<?php
}

function mac_btn_cta_text_eng_render(  ) {

	$options = get_option( 'mac_general_options' );
	?>
	<input type='text' name='mac_general_options[btn_cta_text_eng]' value='<?php echo ( !empty($options['btn_cta_text_eng']) ? $options['btn_cta_text_eng'] : '' ) ; ?>'>
	<?php

}

function mac_btn_cta_link_eng_render(  ) {

	$options = get_option( 'mac_general_options' );
	?>
	<input type='text' name='mac_general_options[btn_cta_link_eng]' value='<?php echo ( !empty($options['btn_cta_link_eng']) ? $options['btn_cta_link_eng'] : '' ) ; ?>'>
	<?php

}

/**/
function mac_general_options_section_callback(  ) {
	echo __( 'The information entered here will be used to make certain data dynamic.', 'Mac Theme' );
}

function cta_btn_options(  ) {
	
	$my_theme = get_current_theme_info();
	?>
	<div class="section panel">
		<h1><?php echo __('CTA Options', 'Mac Theme')?></h1>
		<form action='options.php' method='post'>
			<?php
			settings_fields( 'mac_setup' );
			do_settings_sections( 'mac_setup' );
			submit_button();
			?>
	
		</form>
		<p><?php echo __($my_theme['desc'], 'Mac Theme')?></p>
	</div>
	<?php
}

function print_theme_option($option, $action = 'print'){

	$options = get_option('mac_general_options');


	$str = '';

	if(is_array($option)){

		foreach($option as $key => $this_opt){			
			
			$str.= '<span class="'.$this_opt.'">'.print_theme_option_cases($key, $this_opt).'</span>'."\r\n";
		}

	}
	else{

		$str.= '<span class="'.$option.'">'.print_theme_option_cases($option, $options[$option]).'</span>'."\r\n";
	}


	if($action == 'print'){
		echo $str;
	}
	else{
		return $str;
	}

}

function print_theme_option_cases($key, $value){
		
	$str = '';

	switch($key){
		case 'toll_free':
		case 'btn_cta_text':
			$str.= '<a href="tel:'. str_replace( '-','', str_replace(' ', '', $value) ).'">'.$value.'</a>';
		break;
		
		case 'courriel':
			$str.= '<a href="mailto:'.$value.'">'.$value.'</a>';
		break;
		
		default:
			$str = $value;
	}
	
	return $str;
}

/* Repéchange des informations du thème */
function get_current_theme_info(){
		
		$theme = wp_get_theme();
		$my_theme['Name']		= $theme->get('Name');
		$my_theme['AuthorURI']	= $theme->get('AuthorURI');
		$my_theme['TextDomain']	= $theme->get('TextDomain');
		$my_theme['desc']		= str_replace($my_theme['TextDomain'], '<a href="'.$my_theme['AuthorURI'].'" target="_blank">'.$my_theme['TextDomain'].'</a>', $theme->get('Description'));
	
		return $my_theme;
	
}

function wptuts_options_enqueue_scripts() {
    wp_register_script( 'cta_btn', get_stylesheet_directory_uri() .'/cta_btn/cta_btn.js', array('jquery','media-upload','thickbox'), '1.0', false );
 		
    if ( 'toplevel_page_cta_btn_options' == get_current_screen()->id ) {
        wp_enqueue_script('jquery');
 
        wp_enqueue_script('thickbox');
        wp_enqueue_style('thickbox');
 
        wp_enqueue_script('media-upload');
        wp_enqueue_script('cta_btn');
    }
 
}
add_action('admin_enqueue_scripts', 'wptuts_options_enqueue_scripts');

function wptuts_front_end_options_enqueue_scripts() {
	wp_register_style( 'cta_css', get_stylesheet_directory_uri() .'/cta_btn/cta_btn.css');
	wp_register_style( 'cta_fonts', get_stylesheet_directory_uri() .'/cta_btn/fonts/fonts.css');
	wp_enqueue_style( 'cta_css' );
	wp_enqueue_style( 'cta_fonts' );
}
add_action('wp_enqueue_scripts', 'wptuts_front_end_options_enqueue_scripts');

function wptuts_setting_logo_preview() {
    $wptuts_options = get_option( 'theme_wptuts_options' );  ?>
    <div id="upload_logo_preview" style="min-width: 100px;">
        <img style="max-width:100%;" src="<?php echo esc_url( $wptuts_options['logo'] ); ?>" />
    </div>
    <?php
}

function wptuts_setting_logo_eng_preview() {
    $wptuts_options = get_option( 'theme_wptuts_options' );  ?>
    <div id="upload_logo_eng_preview" style="min-width: 100px;">
        <img style="max-width:100%;" src="<?php echo esc_url( $wptuts_options['logo'] ); ?>" />
    </div>
    <?php
}

function print_cta(){
	
	$fields = get_fields();	
	//print_array($fields);
	
	$is_full = false;
	
	$str = '';
	$str.= '<div id="btn_cta">';
	
	if(!empty($fields) && is_array($fields)){
		
		$is_full = true;
		foreach($fields as $field){
			
			if(empty($field) ){
				
				$is_full = false;
				break;
			}
		}
		
	}
	
	if(!empty($fields) && $is_full){
		
		$str.= '<img class="cta_logo" src="'.$fields['logo_du_bouton'].'">';
		$str.= '<span class="cta_title">'.$fields['titre_du_bouton'].'</span>';
		$str.= '<p class="cta_parag">'.$fields['texte_du_bouton'].'</p>';
		$str.= '<a href="'.$fields['lien_sur_le_bouton'].'" target="_blank" class="cta_link">'.$fields['texte_call_to_action'].'</a>';
	}
	else{
		$lang = ( ICL_LANGUAGE_CODE == 'en' ? '_eng' : false );
		$options = get_option('mac_general_options');

		$str.= '<img class="cta_logo" src="'.$options['image_attachment_id'.$lang].'">';
		$str.= '<span class="cta_title">'.$options['btn_title'.$lang].'</span>';
		$str.= '<p class="cta_parag">'.$options['btn_text'.$lang].'</p>';
		$str.= '<a href="'.$options['btn_cta_link'.$lang].'" target="_blank" class="cta_link">'.$options['btn_cta_text'.$lang].'</a>';
	}
	
	$str.= '</div>';
	echo $str;
	return;
}

?>