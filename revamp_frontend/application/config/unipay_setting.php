<?php
// Paypal setting, taken from payment.php
// also used in payment-successful.php

global $paypal_email, $base_url;
global $paypal_url, $return_url, $cancel_url, $notify_url;

$config['unipay_partner'] = 'MAP'; // the email for the commercial account
$config['md5_key'] = 'MAP001'; // no md5? '1234567890';

$config['base_url'] = 'http://www.cmaphk.com'; // no trailing slash

$UNIPAY_SERVER = 'www.unipaygo.com';

$paypal_debug = false;
if ($paypal_debug){
	$base_url = base_url(); // 'http://localhost:8088/revamp/index.php?';
//	$base_url = 'http://219.77.56.146:8088/cmaphk';
	//$UNIPAY_SERVER = 'localhost:8088/';
	//$config['paypal_emali'] = 'fungcl@yahoo.com'; // using sandbox accounts.
}else{
  $base_url = base_url(); //  $config['base_url'];}

$config['return_url'] = $base_url . 'index.php?c=unipay_handler&m=payment_success';
$config['cancel_url'] = $base_url . 'index.php?c=unipay_handler&m=payment_cancel';
$config['notify_url'] = $base_url . 'index.php?c=unipay_handler&m=payment_notify';

$config['unipay_url'] = 'https://' . $UNIPAY_SERVER . '/index.php/payapi/'; // no trailing /
?>