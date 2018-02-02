<?php
/**
 * Template Name: TCN [HANDLER]: ipn
 *
 * For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package G1_Framework
 * @subpackage G1_Theme03
 * @since G1_Theme03 1.0.0
 */
 ?>
 
<?php
    $er = new EventRegistration();
    $message = print_r($_POST, true);
    
    $data = array();
    if(isset($_POST['custom']))
    {
        $paypal_ipn_id = $_POST['custom'];
        $data['paypal_ipn_id'] = $paypal_ipn_id;
        $data['success'] = $er->finalize_paypal($paypal_ipn_id);
    }
    
    $data['POST'] = $_POST;
    print mail("jon@jonhill.ca", "IPN Notification - Paypal Transaction Completed", print_r($data, true));
?>