# PAYPAL

tcnb@jonhill.ca - buyer 1234qwer
tcnm@jonhill.ca - merchant 1234qwer

https://www.sandbox.paypal.com/us/webapps/mpp/standard-integration

## Steps:

* https://www.sandbox.paypal.com/us/webapps/mpp/standard-integration
* click Create Button
* Item name = title of post
* Item ID = id of post
* Price = same as you entered on event
* Currency = CAD
* Select button text = Pay Now
* Set the language as appropriate
* Step 3
    * Add advanced variables
    
        notify_url=http://cvnet.test/ipn.php
            OR
        notify_url=http://cvnet.jonhill.ca/ipn.php