<?php

/**
 * Ben Debugging
 */
include('debug_paypal_log_script.php');
$post_string = print_r($_POST,true); // capture in string
debug_paypal_log_script('Calling payment.php with post [' . $post_string . ']\n'); // for writing to the file.


//https://www.paypal.com/cgi-bin/customerprofileweb?cmd=_profile-website-payments
include('include/functions.php');
session_start();
if(!isset($_SESSION['id']))
{
	header('LOCATION:logout.php');
}
if(isset($_SESSION['id']))
{
		$user_login = 1;
		connect();
		$id = sanitize($_SESSION['id']);

		$SQL = "SELECT * FROM users WHERE id = $id";
		
		$query = mysql_query($SQL);
		if($query){
			
			$nor = mysql_num_rows($query);
			if($nor>0){
			$row = mysql_fetch_assoc($query);
			 $fname=$row['firstname'];
			 $lname=$row['lastname'];
			 $cc=$row['country'];
			 $phone=$row['phone'];
			 $email=$row['email'];
			 $passport=$row['passport'];
			 
			}
			
		}else{
			die("Error: ".mysql_error());
		}
		close();
}

if(isset($_SESSION['tid'])){
	debug_paypal_log_script('payment.php: isset session tid, tid =' . $_SESSION['tid']);
	
	$tid = $_SESSION['tid'];
	connect();
	$sql = "SELECT * FROM paypal_booking WHERE ID=".$tid;
	$query = mysql_query($sql);
	if($query){
		$nor = mysql_num_rows($query);
		if($nor==1){
			$booking = mysql_fetch_assoc($query);
		}else{
			header('LOCATION:logout.php');
		}
	}else{
		echo die(mysql_error());
	}
	
	$sql = "SELECT * FROM rooms WHERE ID=".$booking['room'];
	$query = mysql_query($sql);
	if($query){
		$nor = mysql_num_rows($query);
		if($nor==1){
			$room = mysql_fetch_assoc($query);
		}else{
			header('LOCATION:logout.php');
		}
	}else{
		echo die(mysql_error());
	}
	
}else{
	header('LOCATION:logout.php');
}
// PayPal settings (OLD Code form freelancer)
//$paypal_email = 'cs@cmaphk.com';
//$return_url = 'http://www.cmaphk.com/payment-successful.php';
//$cancel_url = 'http://www.cmaphk.com/payment-cancelled.htm';
//$notify_url = 'http://www.cmaphk.com/paypal/payments.php';
//$paypal_url = 'https://www.paypal.com/cgi-bin/webscr'; // no trailing /
// above were default. better load from config/paypal.php
if (file_exists('config/paypal_config.php')){
	include('config/paypal_config.php');
}


$item_name = $room['room_name'];
$item_amount = sprintf('%01.2f', $booking['amt']);
$item_desc   = 'Booking room:' . $room['room_name'] . '\n' . 
				'from ' . $booking['from_date'] . ' to ' . $booking['to_date'] . '\n' .
				' for user with email [' . $email . ']\n';  
// Include Functions
include("payment-functions.php");

//Database Connection

$querystring= "";
// Check if paypal request or response
if (!isset($_POST["txn_id"]) && !isset($_POST["txn_type"])){

	// Reference of all the HTML variables:
	// https://developer.paypal.com/docs/classic/button-manager/integration-guide/ButtonManagerHTMLVariables/
	
	// Firstly Append paypal account to querystring
	$querystring .= "?business=".urlencode($paypal_email)."&";	
	
	// Append amount& currency (£) to quersytring so it cannot be edited in html
	
	//The item name and amount can be brought in dynamically by querying the $_POST['item_number'] variable.
	$querystring .= "item_name=".urlencode($item_name)."&";
	$querystring .= "item_desc=" . urlencode($item_desc) . "&";
	$querystring .= "amount=".urlencode($item_amount)."&";
	
	$querystring .= "cmd=".urlencode('_xclick')."&";
	$querystring .= "no_note=".urlencode('1')."&";
	$querystring .= "lc=".urlencode('USA')."&";
	$querystring .= "currency_code=".urlencode($booking['currency'])."&";
	$querystring .= "bn=".urlencode('PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest')."&";
	$querystring .= "first_name=".urlencode($fname)."&";
	$querystring .= "last_name=".urlencode($lname)."&";
	$querystring .= "item_number=".urlencode($tid)."&";
	
	$querystring .= "option_index=4&";
	
	$querystring .= "on0=Description&";
	$querystring .= "os0=&"; // not to use description, rather by individual items.
	$querystring .= "on1=Room&";
	$querystring .= "os1=" . urlencode($room['room_name']) . "&";
	$querystring .= "on2=FromDate&";
	$querystring .= "os2=" . urlencode($booking['from_date']) . "&";
	$querystring .= "on3=ToDate&";
	$querystring .= "os3=" . urlencode($booking['to_date']). "&";
	$querystring .= "on4=Email&";
	$querystring .= "os4=" . urlencode($email). "&";
	
	
	//loop for posted values and append to querystring
	/*foreach($_POST as $key => $value){
		$value = urlencode(stripslashes($value));
		$querystring .= "$key=$value&";
	}*/
	
	// Append paypal return addresses
	$querystring .= "return=".urlencode(stripslashes($return_url))."&";
	$querystring .= "rm=0&"; // return method, for return url, 0=GET, 1 = GET with not data, 2 = POST
	$querystring .= "cancel_return=".urlencode(stripslashes($cancel_url))."&";
	$querystring .= "notify_url=".urlencode($notify_url);
	
	// Append querystring with custom field
	$querystring .= "&custom=".$_SESSION['id'];
	
	debug_paypal_log_script('payment.php: redirecting to paypal [' . $paypal_url . $querystring . ']');
	
	// Redirect to paypal IPN	
	header('location:'.$paypal_url.$querystring);
	exit();

}else{
	
	// Debug
	debug_paypal_log_script('payment.php: Get response from paypal with POST[' . print_r($_POST,true) . ']');
	
	// Response from Paypal

	// read the post from PayPal system and add 'cmd'
	$req = 'cmd=_notify-validate';
	foreach ($_POST as $key => $value) {
		$value = urlencode(stripslashes($value));
		$value = preg_replace('/(.*[^%^0^D])(%0A)(.*)/i','${1}%0D%0A${3}',$value);// IPN fix
		$req .= "&$key=$value";
	}
	
	// assign posted variables to local variables
	$data['item_name']			= $_POST['item_name'];
	$data['item_number'] 		= $_POST['item_number'];
	$data['payment_status'] 	= $_POST['payment_status'];
	$data['payment_amount'] 	= $_POST['mc_gross'];
	$data['payment_currency']	= $_POST['mc_currency'];
	$data['txn_id']				= $_POST['txn_id'];
	$data['receiver_email'] 	= $_POST['receiver_email'];
	$data['payer_email'] 		= $_POST['payer_email'];
	$data['custom'] 			= $_POST['custom'];
		
	// post back to PayPal system to validate
	$header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
	$header .= "Host: www.paypal.com\r\n";
	$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
	$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";

	$fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);	
	
	if (!$fp) {
		// HTTP ERROR
	} else {	

		fputs ($fp, $header . $req);
		while (!feof($fp)) {
			$res = fgets ($fp, 1024);
			if (strcmp($res, "VERIFIED") == 0) {
			
				// Used for debugging
				//@mail("you@youremail.com", "PAYPAL DEBUGGING", "Verified Response<br />data = <pre>".print_r($post, true)."</pre>");
						
				// Validate payment (Check unique txnid & correct price)
				$valid_txnid = check_txnid($data['txn_id']);
				$valid_price = check_price($data['payment_amount'], $data['item_number']);
				// PAYMENT VALIDATED & VERIFIED!
				if($valid_txnid && $valid_price){				
					$orderid = updatePayments($data);		
					if($orderid){					
						// Payment has been made & successfully inserted into the Database								
					}else{								
						// Error inserting into DB
						// E-mail admin or alert user
					}
				}else{					
					// Payment made but data has been changed
					// E-mail admin or alert user
				}						
			
			}else if (strcmp ($res, "INVALID") == 0) {
			
				// PAYMENT INVALID & INVESTIGATE MANUALY! 
				// E-mail admin or alert user
				
				// Used for debugging
				//@mail("you@youremail.com", "PAYPAL DEBUGGING", "Invalid Response<br />data = <pre>".print_r($post, true)."</pre>");
			}		
		}		
	fclose ($fp);
	}	
}
?>