<?php
// Paypal setting, taken from payment.php
// also used in payment-successful.php

global $paypal_email, $base_url;
global $paypal_url, $return_url, $cancel_url, $notify_url;

$paypal_email = 'cs@cmaphk.com'; // the email for the commercial account
$base_url = 'http://www.cmaphk.com'; // no trailing slash

$PAYPAL_SERVER = 'www.paypal.com';

$paypal_debug = false;
if ($paypal_debug){
	$base_url = 'http://fungcl.no-ip.org:8088/cmaphk';
//	$base_url = 'http://219.77.56.146:8088/cmaphk';
	$PAYPAL_SERVER = 'www.sandbox.paypal.com';
}

$return_url = $base_url . '/payment-successful.php';
$cancel_url = $base_url . '/payment-cancelled.php';
$notify_url = $base_url . '/payment-notify.php';

$paypal_url = 'https://' . $PAYPAL_SERVER . '/cgi-bin/webscr'; // no trailing /