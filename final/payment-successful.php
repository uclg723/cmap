<?php
include('include/functions.php');
//http://www.phpbuilder.com/articles/application-architecture/shopping-carts/setting-up-paypal-ipn-in-php.html
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
			$uid=  sanitize($_SESSION['id']);
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
		echo die(mysql_query());
	}
	
}else{
	header('LOCATION:logout.php');
}
// PayPal settings
$paypal_email = 'am.arun90@gmail.com';
$return_url = 'http://www.cmaphk.com/payment-successful.php';
$cancel_url = 'http://www.cmaphk.com/payment-cancelled.htm';
$notify_url = 'http://www.cmaphk.com/paypal/payments.php';

$currency_code = $booking['currency'];
$item_name = $room['room_name'];
$item_amount = sprintf('%01.2f', $booking['amt']);

// Include Functions
include("payment-functions.php");

//Database Connection

$querystring= "";
// Check if paypal request or response
if (!isset($_POST["txn_id"]) && !isset($_POST["txn_type"])){

	// Firstly Append paypal account to querystring
	$querystring .= "?business=".urlencode($paypal_email)."&";	
	
	// Append amount& currency (£) to quersytring so it cannot be edited in html
	
	//The item name and amount can be brought in dynamically by querying the $_POST['item_number'] variable.
	$querystring .= "item_name=".urlencode($item_name)."&";
	$querystring .= "amount=".urlencode($item_amount)."&";
	
	$querystring .= "cmd=".urlencode('_xclick')."&";
	$querystring .= "no_note=".urlencode('1')."&";
	$querystring .= "lc=".urlencode('USA')."&";
	$querystring .= "currency_code=".urlencode($booking['currency'])."&";
	$querystring .= "bn=".urlencode('PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest')."&";
	$querystring .= "first_name=".urlencode($fname)."&";
	$querystring .= "last_name=".urlencode($lname)."&";
	$querystring .= "item_number=".urlencode($tid)."&";
	
	// Append paypal return addresses
	$querystring .= "return=".urlencode(stripslashes($return_url))."&";
	$querystring .= "cancel_return=".urlencode(stripslashes($cancel_url))."&";
	$querystring .= "notify_url=".urlencode($notify_url);
	
	// Append querystring with custom field
	//$querystring .= "&custom=".$_SESSION['id'];
	
	// Redirect to paypal IPN	
	header('location:https://www.sandbox.paypal.com/cgi-bin/webscr'.$querystring);
	exit();

}else{
	
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
	//$data['custom'] 			= $_POST['custom'];
			
	// post back to PayPal system to validate
	$header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
	$header .= "Host: www.sandbox.paypal.com\r\n";
	$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
	$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
	
	  // Open a socket for the acknowledgement request
	  $fp = fsockopen('ssl://www.sandbox.paypal.com', 443, $errno, $errstr, 30);
	  	
	  // Send the HTTP POST request back to PayPal for validation
	  fputs($fp, $header . $req);
  	
	if (!$fp) {
		echo "HTTP ERROR";
	} else {			
		fputs ($fp, $header . $req);		
		while (!feof($fp)) {
			$res = fgets ($fp, 1024);
			if (strcmp($res, "VERIFIED") == 0) {							
				// Check payment status 				
				if ($_POST['payment_status'] != 'Completed') { 
					unset($_SESSION['tid']);
					$_SESSION['booking'] = "Payment not completed";
					header("Location: booking.php?id=".$room['id']);
				}else if ($_POST['mc_currency'] != $currency_code)  {
					unset($_SESSION['tid']);
					$_SESSION['booking'] = "Incorrect currency code";
					header("Location: booking.php?id=".$room['id']);
				}else if ($_POST['mc_gross'] != $item_amount) {
					unset($_SESSION['tid']);
					$_SESSION['booking'] = "Incorrect product price";
					header("Location: booking.php?id=".$room['id']);						
				}else if ($_POST['custom'] !=$uid ) {
					unset($_SESSION['tid']);
					$_SESSION['booking'] = "Invalid User";
					header("Location: booking.php?id=".$room['id']);						
				}else{
					$txn_id = mysql_real_escape_string($_POST['txn_id']);
 				
					$sql = "SELECT COUNT(*) AS count FROM booking WHERE txn_id = '$txn_id'";
					$q = mysql_query($sql);
					$f = mysql_fetch_array($q);
 
					if($f['count'] > 0) {
						$errors[] = "Transaction already processed";
					} else {
						$datediff = strtotime($booking['to_date']) - strtotime($booking['from_date']);
					    $nod = floor($datediff/(60*60*24));
						$sql = "INSERT INTO booking (uid,txn_id,date_in,date_out,add_info,days,rid,status,btype) VALUES('$uid','$txn_id','".$booking['from_date']."','".$booking['to_date']."','','$nod','".$booking['room']."','2','2')";
						$query = mysql_query($sql);
						if(!$query){
							echo mysql_error();
						}
						
						$sql = "UPDATE paypal_booking SET status=1 WHERE id=".$tid;
						$query = mysql_query($sql);
						
						unset($_SESSION['tid']);
						$_SESSION['booking'] = "Completed";
						header("Location: booking.php?id=".$room['id']);
					}
				}
			
			}else if (strcmp ($res, "INVALID") == 0) {			
				$_SESSION['booking'] = "Cannot Verify Payment";
				header("Location: booking.php?id=".$room['id']);
			}		
		}		
	fclose ($fp);
	}	
}
?>