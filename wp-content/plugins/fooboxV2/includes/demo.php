<?php
/**
 * Demo on Foobox Settings Page
 *
 * @author 	Brad Vincent
 * @package 	foobox/includes
 * @version     1.0
 */

$size = 60;

?>
<a href="http://graphicriver.net/item/urban-backgrounds-pack-9/2441331" />Images by brandtz</a>
<div style="clear:both"></div>
<div class="demo-gallery">
  <a title="You can now have a nice image description" href="http://getfoobox.com/wp-content/uploads/2012/07/foobox-demo-pic-1.jpg"><img width="<?php echo $size; ?>" height="<?php echo $size; ?>" src="http://getfoobox.com/wp-content/uploads/2012/07/foobox-demo-pic-1-150x150.jpg" title="Jail Courtyard" alt="Jail Courtyard Alt" /></a>
  <a title="HTML is also <a href='#'>allowed</a> in your descriptions!" href="http://getfoobox.com/wp-content/uploads/2012/07/foobox-demo-pic-2.jpg"><img width="<?php echo $size; ?>" height="<?php echo $size; ?>" src="http://getfoobox.com/wp-content/uploads/2012/07/foobox-demo-pic-2-150x150.jpg" title="Grafitti Wall" /></a>
  <a href="/wp-content/uploads/2014/07/Graffiti-Wall.jpg"><img width="<?php echo $size; ?>" height="<?php echo $size; ?>" src="http://getfoobox.com/wp-content/uploads/2012/07/foobox-demo-pic-3-150x150.jpg" title="An Old Abandoned Building Covered In Grafitti" /></a>
  <a href="http://getfoobox.com/wp-content/uploads/2013/01/foobox-branded-demo-pic-1.jpg"><img width="<?php echo $size; ?>" height="<?php echo $size; ?>" src="http://getfoobox.com/wp-content/uploads/2012/07/foobox-demo-pic-4-150x150.jpg" title="Barbedwire Fence" /></a>
  <a href="http://getfoobox.com/wp-content/uploads/2012/07/foobox-demo-pic-5.jpg"><img width="<?php echo $size; ?>" height="<?php echo $size; ?>" src="http://getfoobox.com/wp-content/uploads/2012/07/foobox-demo-pic-5-150x150.jpg" title="A Dark Place" /></a>
  <a href="http://getfoobox.com/wp-content/uploads/2013/01/foobox-branded-demo-pic-3.jpg"><img width="<?php echo $size; ?>" height="<?php echo $size; ?>" src="http://getfoobox.com/wp-content/uploads/2012/07/foobox-demo-pic-6-150x150.jpg" /></a>
  <a title="this-description-will-be-prettified-if-caption-prettification-is-enabled-0203" href="http://getfoobox.com/wp-content/uploads/2012/07/foobox-demo-pic-7.jpg"><img width="<?php echo $size; ?>" height="<?php echo $size; ?>" src="http://getfoobox.com/wp-content/uploads/2012/07/foobox-demo-pic-7-150x150.jpg" title="Factories" /></a>
  <a href="http://getfoobox.com/wp-content/uploads/2012/07/foobox-demo-pic-8.jpg"><img width="<?php echo $size; ?>" height="<?php echo $size; ?>" src="http://getfoobox.com/wp-content/uploads/2012/07/foobox-demo-pic-8-150x150.jpg" title="Gas Tank Behind House" /></a>
  <a href="http://getfoobox.com/wp-content/uploads/2012/07/foobox-demo-pic-9.jpg"><img width="<?php echo $size; ?>" height="<?php echo $size; ?>" src="http://getfoobox.com/wp-content/uploads/2012/07/foobox-demo-pic-9-150x150.jpg" title="Porcelain Telephone" /></a>
  <a href="http://getfoobox.com/wp-content/uploads/2013/01/foobox-branded-demo-pic-2.jpg"><img width="<?php echo $size; ?>" height="<?php echo $size; ?>" src="http://getfoobox.com/wp-content/uploads/2012/07/foobox-demo-pic-10-150x150.jpg" title="Cityscape Lake" /></a>
</div>
<div style="clear:both"></div>
<div class="demo-gallery demo-gallery-advanced">
	<a title="Test a 404 error image" href="http://getfoobox.com/wp-content/uploads/2012/07/unknown_pic.jpg"><img src="<?php echo FOOBOX_PLUGIN_URL; ?>img/404.png" /></a>
	<a data-caption-title="Video captions are awesome!" data-caption-desc="Set a video caption title by adding a <code>data-caption-title</code> attribute to your link.<br />You can also set a caption description by adding a <code>data-caption-desc</code> attribute to your link." title="YouTube Video" href="http://youtu.be/mO0g5wWVSVk" data-image="http://img.youtube.com/vi/mO0g5wWVSVk/0.jpg"><img src="<?php echo FOOBOX_PLUGIN_URL; ?>img/youtube.png" /></a>
	<a data-caption-title="Captions for Vimeo vids are cool!" data-caption-desc="Did you know that you can also include <strong>HTML</strong> in your captions!" title="Vimeo Video" href="http://vimeo.com/68546202" data-image="http://b.vimeocdn.com/ts/440/907/440907223_640.jpg"><img src="<?php echo FOOBOX_PLUGIN_URL; ?>img/vimeo.png" /></a>
	<a data-caption-title="I am an iFrame caption" data-caption-desc="This is an iFrame caption description." title="iFrame" href="http://fooplugins.com" target="foobox"><img src="<?php echo FOOBOX_PLUGIN_URL; ?>img/iframe.png" /></a>
	<a data-caption-title="I am an HTML caption" data-caption-desc="This is an HTML caption description." title="Inline Element" href="#foobox-inline" data-width="600px" data-height="420px" target="foobox"><img src="<?php echo FOOBOX_PLUGIN_URL; ?>img/inline.png" /></a>
</div>

<div id="foobox-inline" style="display: none;">
	<img style="float: left" src="<?php echo FOOBOX_PLUGIN_URL; ?>/img/foobot.png" />
	<div class="demo_inline">
		<h1>Join Our Newsletter!</h1>
		<input type="text" placeholder="Name" /><br />
		<input type="text" placeholder="Email address" /><br />
		<a class="demo_subscribe" href="#" onclick="alert('This is only a demo to show off an inline HTML FooBox');">Subscribe!</a>
		<p><strong>Some reasons why you should stay in the loop:</strong></p>
		<ol>
			<li>Stay informed about new releases</li>
			<li>Make sure your site is bug free and secure</li>
			<li>New themes and features released all the time</li>
			<li>Get special offers on other plugins</li>
		</ol>
	</div>
</div>

<div style="clear:both"></div>