<!-- Global site tag (gtag.js) - Google Analytics -->
<script async
        src="https://www.googletagmanager.com/gtag/js?id=UA-114911874-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];

  function gtag() {
    dataLayer.push(arguments);
  }

  gtag('js', new Date());

  gtag('config', 'UA-114911874-1');
</script>


<script type="text/javascript">
  jQuery(document).ready(function ($) {
    // Track AdobeConnect Clicks
    $('a[href*="adobeconnect.com"]').on('click', function (e) {
      if (gtag) {
        gtag('event', 'Click', {
          'event_category': 'AdobeConnect',
          'event_label': $(e.currentTarget).attr('href') + ' ' + document.title
        });
      }
    });

  });
</script>