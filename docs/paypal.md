# Paypal integration

tcnb@jonhill.ca - buyer 1234qwer
tcnm@jonhill.ca - merchant 1234qwer

https://www.sandbox.paypal.com/us/webapps/mpp/standard-integration

## Steps:

* https://www.paypal.com/us/webapps/mpp/standard-integration
* click Create Button
* Item name = title of this event
* Item ID = id of this event
* Price = same as you entered on event
* Currency = CAD
* Select button text = Pay Now
* Set the language as appropriate
* Step 3
    * Add advanced variables
    
        notify_url=http://cvnet.jonhill.ca/ipn/
            OR
        notify_url=http://cvnet.jonhill.ca/ipn/