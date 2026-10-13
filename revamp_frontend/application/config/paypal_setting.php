<?php
// Paypal setting, taken from payment.php
// also used in payment-successful.php

global $paypal_email, $base_url;
global $paypal_url, $return_url, $cancel_url, $notify_url;

$config['paypal_email'] = 'cs@cmaphk.com'; // the email for the commercial account
$config['base_url'] = 'http://www.cmaphk.com'; // no trailing slash

$PAYPAL_SERVER = 'www.paypal.com';

$paypal_debug = false;
if ($paypal_debug){
	$base_url = base_url(); // 'http://localhost:8088/revamp/index.php?';
//	$base_url = 'http://219.77.56.146:8088/cmaphk';
	$PAYPAL_SERVER = 'www.sandbox.paypal.com';
	$config['paypal_emali'] = 'fungcl@yahoo.com'; // using sandbox accounts.
}else{
  $base_url = base_url(); //  $config['base_url'];}

$config['return_url'] = $base_url . 'index.php?c=paypal_handler&m=payment_success';
$config['cancel_url'] = $base_url . 'index.php?c=paypal_handler&m=payment_cancel';
$config['notify_url'] = $base_url . 'index.php?c=paypal_handler&mpayment_notify';

$config['paypal_url'] = 'https://' . $PAYPAL_SERVER . '/cgi-bin/webscr'; // no trailing /
?>