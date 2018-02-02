<?php
/*
 * lib/assets.php - Custom functions related to assets and media files
 *
 */

/**
 * Replace absolute URLs with https:// when the parent request is using SSL
 */
function lratcn_https_url($url, $a = null, $b = null, $c = null) {
	// Only modify frontend URLs if request was initiated with https
	// Note: could be more specific by filtering on domains also
	if(is_ssl() && !is_admin()) {
		if(is_array($url)) {
			foreach($url as $key => $value) {
				$url[$key] = str_replace('http://', 'https://', $value);
			}
		} else if(!empty($url)) {
			$url = str_replace('http://', 'https://', $url);
		}
	}
	return $url;
}
add_filter( 'wp_get_attachment_image_src', 'lratcn_https_url', 10, 1); // Author avatar images from CUPP plugin
add_filter( 'wp_get_attachment_url', 'lratcn_https_url', 10, 1 ); // Most image attachments
add_filter( 'upload_dir', 'lratcn_https_url', 10, 1 ); // G1 Dynamic CSS
add_filter( 'theme_root_uri', 'lratcn_https_url', 10, 1 ); // get_stylesheet_directory_uri() and get_template_directory_uri()
add_filter( 'plugins_url', 'lratcn_https_url', 10, 1 );
add_filter( 'clean_url', 'lratcn_https_url', 10, 1 ); // Only way to filter script urls in 4.0. 'script_loader_tag' was added in 4.1
