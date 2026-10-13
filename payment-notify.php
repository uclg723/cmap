<?php
// payment-notify.php
//  It is the notify page, initiated from paypal.com
//  to receive notification from paypal about a transaction and update the database.

// Reference:
// https://developer.paypal.com/docs/classic/ipn/ht_ipn/

// Preparation
include('include/functions.php');
include('config/db_config.php');
require('module/SMTPMailer/SMTPMailer.php');

	connect();
	$callback_time = date("Y-m-d H:i:s");
	$data_post = json_encode($_POST,true);
	$data_get = json_encode($_GET,true);
	$data_session = json_encode($_SESSION,true);
	$sql_log = "INSERT INTO callback_log 
			(callback_time, data_post, data_get, data_session) 
			VALUES 
			('".$callback_time."', '".$data_post."', '".$data_get."', '".$data_session."');";
	$query_log = mysql_query($sql_log);
	echo $sql_log;




if (file_exists('config/paypal_config.php')){
	include('config/paypal_config.php');
}
include('debug_paypal_log_script.php');
debug_paypal_log_script('Calling payment-notification with Request[' . print_r($_REQUEST,true) . ']\n');






// STEP 1: read POST data

// Reading POSTed data directly from $_POST causes serialization issues with array data in the POST.
// Instead, read raw POST data from the input stream.
$raw_post_data = file_get_contents('php://input');
$raw_post_array = explode('&', $raw_post_data);
$myPost = array();
foreach ($raw_post_array as $keyval) {
	$keyval = explode ('=', $keyval);
	if (count($keyval) == 2)
		$myPost[$keyval[0]] = urldecode($keyval[1]);
}
// read the IPN message sent from PayPal and prepend 'cmd=_notify-validate'
$req = 'cmd=_notify-validate';
if(function_exists('get_magic_quotes_gpc')) {
	$get_magic_quotes_exists = true;
}
foreach ($myPost as $key => $value) {
	if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
		$value = urlencode(stripslashes($value));
	} else {
		$value = urlencode($value);
	}
	$req .= "&$key=$value";
}

// Step 2: POST IPN data back to PayPal to validate
global $PAYPAL_SERVER;

$ch = curl_init('https://www.paypal.com/cgi-bin/webscr');
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));

// In wamp-like environments that do not come bundled with root authority certificates,
// please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set
// the directory path of the certificate as shown below:
// curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');
if( !($res = curl_exec($ch)) ) {
	debug_paypal_log_script("Got " . curl_error($ch) . " when processing IPN data");
	curl_close($ch);
	exit;
}
curl_close($ch);

if (strcmp ($res, "VERIFIED") == 0) {
	// The IPN is verified, process it:
	// check whether the payment_status is Completed
	// check that txn_id has not been previously processed
	// check that receiver_email is your Primary PayPal email
	// check that payment_amount/payment_currency are correct
	// process the notification

	// assign posted variables to local variables

	$item_name = $_POST['item_name'];
	$item_number = $_POST['item_number'];
	$payment_status = $_POST['payment_status'];
	$payment_amount = $_POST['mc_gross'];
	$payment_currency = $_POST['mc_currency'];
	$txn_id = $_POST['txn_id'];
	$receiver_email = $_POST['receiver_email'];
	$payer_email = $_POST['payer_email'];
	$custom = $_POST['custom'];

	/* check txn_id */
	$sql = "SELECT * FROM `paypal_booking` WHERE `ID` = '".$item_number."' AND `status` = '2'";
	$query = mysql_query($sql);
	$num = mysql_num_rows($query);
	if($num > 0){
		$row = mysql_fetch_assoc($query);
		$sql2 = "SELECT * FROM `booking` WHERE `txn_id` = '".$item_number."'";
		$query2 = mysql_query($sql2);
		$num2 = mysql_num_rows($query2);
		if($num2 == 0){
			$datediff = strtotime($row['to_date']) - strtotime($row['from_date']);
			$nod = floor($datediff/(60*60*24));
			$sql_insert = "INSERT INTO `booking` (`uid`,`date_in`,`date_out`,`add_info`,`days`,`rid`,`btype`,`status`,`created`,`txn_id`) 
									VALUES (
										'".$row['UID']."',
										'".$row['from_date']."',
										'".$row['to_date']."',
										'Paypal Transaction ID: ".$txn_id."',
										'".$nod."',
										'".$row['room']."',
										'2',
										'2',
										'".date("Y-m-d H:i:s")."',
										'".$item_number."'
										)";
			mysql_query($sql_insert);
			$sql_update = "UPDATE `paypal_booking` SET `status` = '1' WHERE `ID` = '".$item_number."'";
			mysql_query($sql_update);



				$sql_user = "SELECT * FROM `users` WHERE `id` = '".$row['UID']."'";
				
				$query3 = mysql_query($sql_user);
				$row3 = mysql_fetch_assoc($query3);
					 
					
				$sql_room = "SELECT * FROM `rooms` WHERE `id` = '".$row['room']."'";
				
				$query4 = mysql_query($sql_room);
				$row4 = mysql_fetch_assoc($query4);



						$din = date("d-m-Y",strtotime($row['from_date']));
						$dout = date("d-m-Y",strtotime($row['to_date']));



						$smtp = new SMTPMailer();
						$smtp->setEmailTemplateById(1);
						$context = array(
							'{$email}'		=> $row3['email'],
							'{$last_name}'	=> $row3['lastname'],
							'{$first_name}'	=> $row3['firstname'],
							'{$pay_type}'	=> "Paypal ",
							'{$room_name}'	=> $row4['room_name'],
							'{$date_in}' 	=> $din,	 // date in
							'{$date_out}'	=> $dout,	 // date out
							'{$nod}'		=> $nod,
							'{$item_amount}'	=> $row['amt'],
							'{$currency}'		=> $row['currency'],
							'{$transaction_id}' => $row['ID'], // transaction id
						);
						$result = $smtp->send($context);
						
						$sql_log = "INSERT INTO callback_log 
								(callback_time, data_post, data_get, data_session) 
								VALUES 
								('".$callback_time."', '".$result."', '".$data_get."', '".$data_session."');";
						$query_log = mysql_query($sql_log);



		}

	}
	// IPN message values depend upon the type of notification sent.
	// To loop through the &_POST array and print the NV pairs to the screen:
	foreach($_POST as $key => $value) {
		echo $key." = ". $value."<br>";
	}
} else if (strcmp ($res, "INVALID") == 0) {
	// IPN invalid, log for manual investigation
	echo "The response from IPN was: <b>" .$res ."</b>";
}