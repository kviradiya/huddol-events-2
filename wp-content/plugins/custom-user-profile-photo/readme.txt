=== Plugin Name ===
Contributors: 3five, VincentListrani
Donate link: 
Tags: custom profile photo, user profile, profile photo, user profile photo
Requires at least: 3.6.1
Tested up to: 3.9
Stable tag: 0.2.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add a customized User Profile photo to a WordPress user profile.

== Description ==

A more flexible way to attach and display a photo for a WordPress user profile.

Some users might not have or want to have a gravatar account or other universal avatar account. They simply may want to use a one-time specified photo to represent them on your WordPress site. This plugin solves that use case.

With the ability to upload a photo to a user profile via the WordPress Media Uploader or by specifying an external URL to an image, your users and/or authors can have a personalized photo specific to your website.*

By utilizing the WordPress Media Uploader, you also have the ability to use and of the predefined images sizes that the media uploader creates and any custom images size specific to your theme.

This plugin will add a custom set of fields to the user profile page which will allow for the use of a custom profile photo.

You can add/change/edit uploaded photos directly from the user profile page. The external option allows you to provide a URL to the external image or remove it.

*Future Updates to this plugin include full WordPress avatar integration (posts and comments), allowing other roles to access this feature, and ajax processing of images that use the WordPress Media Uploader.

To use this plugin, simply go to the users section and select a user. The new fields are added to the bottom of the user profile page. Choose which type of photo you want to use. Upload or add the url, depending on your option. Then press the Update Profile button.

To retrieve the photo on the front-end use the following example on your template page(s):
`<?php
	// Retrieve The Post's Author ID
	$user_id = get_the_author_meta('ID');
	// Set the image size. Accepts all registered images sizes and array(int, int)
	$size = 'thumbnail';

	// Get the image URL using the author ID and image size params
	$imgURL = get_cupp_meta($user_id, $size);

	// Print the image on the page
	echo '<img src="'. $imgURL .'" alt="">';
?>`

== Installation ==

1. Upload `custom-user-profile-photo` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Place `<?php get_cupp_meta($user_id, $size); ?>` in your templates

== Frequently Asked Questions ==

= How do I retrieve the image after I've updated the user profile. =

You can call it in your template page(s) like so: `<?php echo get_cupp_meta($user_ID, $size); ?>`
where the $user_ID is the users ID number and the size is a registered image size like 'thumbnail' or an array like `array(50,50)`

= Who can upload and manage these images. =

Currently, only a user with admin privileges can manage this option.

== Screenshots ==

1. The new fields that are added to the user profile page.

2. After uploading and saving your selected image.

3. On hover, Edit or Remove an uploaded image.

4. On hover, Remove a URL to an external image.

5. An example of getting this new image to display on the front-end.

== Changelog ==

= 0.2.4 =
* Tested with WordPress v3.8
* Updated description text. Better explanation of how to quickly use this plugin.

= 0.2.3 =
* Beta version release.

== Upgrade Notice ==

= 0.2.3 =
Beta Release