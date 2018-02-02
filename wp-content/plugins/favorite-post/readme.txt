=== Favorite Post ===
Contributors: tareq1988
Donate Link: http://tareq.wedevs.com/donate/
Tags: favorite, favorite post, bookmark
Requires at least: 3.3
Tested up to: 3.6
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This is a simple yet another favorite post plugin.

== Description ==

This is a simple yet another favorite post plugin for WordPress.

= Features =

1. Gives a button to favorite a post.
2. Works only for logged in users.
3. Has a favorite post widget
4. Custom post type support

= Usage =

1. Insert `<?php if ( function_exists( 'wfp_button' ) ) wfp_button(); ?>` this code in your post page to show a favorite post link.
1. `$favorites = WeDevs_Favorite_Posts::init()->get_favorites();` - get favorite posts. Supports **3** parameters, `post_type`, `limit`, `offset`. The default `post_type` is `all`, for getting all post types.
1. Show favorite posts in a widget.
1. Use the shortcode `[favorite-post-btn]` for inserting the favorite post link. You can also pass a post id as a parameter. `[favorite-post-btn post_id="938"]`.
1. Use the shortcode `[favorite-post]` to display favorited posts. You can also pass these parameters: `user_id`, `count`, `post_type`. e.g. `[favorite-post user_id="1" count="5" post_type="all"]`

= Contribute =
This may have bugs and lack of many features. If you want to contribute on this project, you are more than welcome. Please fork the repository from [Github](https://github.com/tareq1988/wedevs-favorite-post).

= Author =
Brought to you by [Tareq Hasan](http://tareq.wedevs.com) from [weDevs](http://wedevs.com)

= Donate =
Please [donate](http://tareq.wedevs.com/donate/) for this awesome plugin to continue it's development to bring more awesome features.


== Installation ==

Extract the zip file and just drop the contents in the wp-content/plugins/ directory of your WordPress installation and then activate the Plugin from Plugins page.

== Frequently Asked Questions ==

Nothing here


== Changelog ==

= 0.1 =
Initial version released


== Upgrade Notice ==

Nothing here
