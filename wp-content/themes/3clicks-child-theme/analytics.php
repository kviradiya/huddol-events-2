<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-85106907-1', 'auto');
  ga('send', 'pageview');
  
  // Legacy GA accounts
	<?php if(ICL_LANGUAGE_CODE == 'en'): ?>
			ga('create', 'UA-61432101-1', 'auto','lratcn');
			ga('lratcn.send', 'pageview');
	<?php else:?>
			ga('create', 'UA-61431803-1', 'auto','lratcn');
			ga('lratcn.send', 'pageview');
	<?php endif ?>
</script>

<script type="text/javascript">
	jQuery(document).ready(function($) {
		// Track AdobeConnect Clicks
		$('a[href*="adobeconnect.com"]').on('click', function(e) {
			if(ga) {
				ga('send', 'event', { eventCategory: 'AdobeConnect', eventAction: 'Click', eventLabel: $(e.currentTarget).attr('href') + ' ' + document.title});
        ga('lratcn.send', 'event', { eventCategory: 'AdobeConnect', eventAction: 'Click', eventLabel: $(e.currentTarget).attr('href') + ' ' + document.title});
			}
		});

	});
</script>